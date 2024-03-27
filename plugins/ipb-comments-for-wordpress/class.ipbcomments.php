<?php
/**
 * IP.Board Comments for WordPress
 * Using IP.Board for WordPress comments, and forum cross-posting.
 */

class WP_IPBComments {

	public $crosspost_edits = false;      // don't change this yet

	function __construct() {

		$this->options = get_option('ipb_comments_options');

		add_action( 'wp_head', array($this, 'register_comments') );
		add_action( 'admin_menu', array($this, 'register_comments_admin') );
		
		wp_register_style( 'ipbcomments_stylesheet', plugins_url( 'ipb-comments-for-wordpress.css' , __FILE__ ) );
	}

	/**
	 * setup hooks and filters for the Admin 
	 */
	function register_comments_admin() {

		// below triggers the cross-posting on edits/updates too
		if ($this->crosspost_edits) {
			add_action('publish_post', array($this, 'create_topic'));
		}
		
		// use post publish status transitions to ensure this only posts in case of a new file
		// and not whenever a post is edited or updated
		add_action('new_to_publish', array($this, 'create_topic'));
		add_action('future_to_publish', array($this, 'create_topic'));
		add_action('draft_to_publish', array($this, 'create_topic'));

		// setup the admin menus
		add_action('admin_init', array($this,'settings_init') );

		/**
		 * Create the IPB Comments menu under Dashboard Settings
		 * http://codex.wordpress.org/Function_Reference/add_options_page
		 */
		// create the settings menu
		add_options_page( 'IPB Comments Options',     // page title tag
			'IPB Comments',                           // menu text
			'manage_options',                         // capability required to use this menu option
			'ipb-comments',                           // slug to refer to this menu item
			array($this,'show_options_page')          // optional callback function
			);

		add_action( 'admin_print_styles', array($this,'stylesheets') );
	}

	/**
	 * setup hooks and filters for the posts
	 */
	function register_comments() {

		// where to put the link + link text
		switch ( $this->options['ipb_custom_link_filter'] ) {

			case 'before_comments':
				add_filter( 'comments_array', array($this,'show_link_text') );
				break;

			case 'after_comments':
				add_action( 'comment_form_before', array($this,'show_link_text') );
				break;

			case 'before_content':
			case 'after_content':
				add_filter( 'the_content', array($this,'show_link_text') );
				break;
		}

		// add action wp_print_styles isn't being called here, so workaround
		wp_enqueue_style('ipbcomments_stylesheet' );
		wp_print_styles();
	}


	/**
	 * Create a new IP.Board topic when a new post is created in a matching category
	 */
	function create_topic( $post_ID ) {

		/**
		 * checking for required option values before continuing
		 */
		if ( ! (isset($this->options['ipb_field_path']) OR file_exists($this->options['ipb_field_path'])) ) {
			// the required path to the initdata.php folder is missing
			return FALSE;
		}

		if ( ! isset($this->options['ipb_field_member_id']) ) {
			// the required member id to post from is missing
			return FALSE;
		}

		// http://codex.wordpress.org/Function_Reference/get_post
		$wp = get_post($post_ID);

		foreach ( get_the_category($wp->ID) as $cat ) {
			if ( ! empty( $this->options['categories'][$cat->slug] ) ) {
				$forumID = intval( $this->options['categories'][$cat->slug] );
				break;
			}
		}

		// if we haven't found a matching category, do nothing
		if ( ! isset( $forumID ) ) {
			return FALSE;
		}

		/**
		 * Invision Power Board 
		 * Add a new topic
		 *
		 * Source: http://community.invisionpower.com/resources/documentation/index.html/_/developer-resources/
		 */

		// Keep the board from redirecting
		// http://community.invisionpower.com/tracker/issue-26224-issues-with-ssiphp/
		define('CCS_GATEWAY_CALLED',FALSE);

		require_once( $this->options['ipb_field_path'] .'/initdata.php' );

		require_once( IPS_ROOT_PATH .'sources/base/ipsController.php' );
		require_once( IPS_ROOT_PATH .'sources/base/ipsRegistry.php' );

		$registry = ipsRegistry::instance();
		$registry->init();

		require_once( IPSLib::getAppDir('forums') .'/sources/classes/post/classPost.php' );
		$postClass = new classPost( $registry );

		$postClass->setForumID( $forumID );
		$postClass->setForumData( $registry->class_forums->allForums[$forumID] );

		$postClass->setAuthor( $this->options['ipb_field_member_id'] );
		$postClass->setTopicTitle( $wp->post_title );

		// option to use excerpt or content should go here
		$content = nl2br( $wp->post_content )
			.'<br><br><a href="'.get_permalink( $wp->ID ).'">'
			.$this->options['ipb_field_link_text_ipb']
			.'</a></p>';

		$postClass->setPostContentPreFormatted( $content );

		$postClass->setIsPreview( false );
		$postClass->setTopicState( 'open' );
		$postClass->setPublished( true );

		try {

			if ( $postClass->addTopic() ) {

				// get topic data
				$topicData = $postClass->getTopicData();

				// build the topic url using the IPB output class
				if ( ipsRegistry::$settings['use_friendly_urls'] AND ipsRegistry::$settings['seo_r_on'] ) {
					$topicUrl = $registry->getClass( 'output' )->buildSEOUrl( 'showtopic=' .$topicData['tid'], 'public', $topicData['title_seo'], 'showtopic' );
				} else {
					$topicUrl = $this->options['ipb_field_url'].'/index.php?showtopic='.intval($topicData['tid']);
				}

				// add custom field 'forum_topic_url' to our post
				update_post_meta( $wp->ID, 'forum_topic_url', htmlentities($topicUrl));

				// add custom field 'forum_topic_meta' to our post
				update_post_meta( $wp->ID, 'forum_topic_meta', 
					array(
						'topic_id' => intval($topicData['tid']),    // forum topic id 
						'replies' => array(),                       // last X replies in topic
						'timestamp' => time()                       // current timestamp of this update
						) );

			} else {
				//var_dump($postClass->_postErrors);
			}
		}
		catch ( Exception $error ) {
			print $error->getMessage();
		}

	 }


	/**
	 * get the last X replies for IPB topic_id
	 */
	function get_replies( $topic_id ) {

		// check if the required path to the initdata.php folder is missing
		if ( ! (isset($this->options['ipb_field_path']) OR file_exists($this->options['ipb_field_path'])) ) {
			return FALSE;
		}

		/**
		 * Invision Power Board 
		 * get last X replies in a topic
		 */

		/**
		 * Note: E_STRICT errors in WP code
		 * does IPS turn on display errors?
		 * turn them off
		 */
		ini_set( 'display_errors', 0 );

		// Keep the board from redirecting
		// http://community.invisionpower.com/tracker/issue-26224-issues-with-ssiphp/
		define('CCS_GATEWAY_CALLED',FALSE);

		require_once( $this->options['ipb_field_path'] .'/initdata.php' );

		require_once( IPS_ROOT_PATH .'sources/base/ipsController.php' );
		require_once( IPS_ROOT_PATH .'sources/base/ipsRegistry.php' );

		$registry = ipsRegistry::instance();
		$registry->init();

		// build the select statement, skip the original post, we just want the replies
		$registry->DB()->build( array( 'select' => 'author_name,post_date,post', 
							'from'  => 'posts',
							'where' => 'topic_id = '.intval($topic_id),
							'order' => 'post_date',
							'limit' => array(1,$this->options['ipb_field_show_comments'])
							) 
						);

		$result = $registry->DB()->execute();

		$replies = array();

		if ( $result ) {

			while ( $row = $registry->DB()->fetch() ) {
				// author_name, post_date, post
				extract($row);

				$post = wp_trim_excerpt( 
						wp_strip_all_tags( 
						preg_replace('/<br(\s+)?\/?>/i', "\n", str_replace(array('[',']'), array('<','>'), $post) )
						) );

				$replies[] = array( 'author' => $author_name, 'date' => $post_date, 'comment' => $post );
			}
		}

		// return the comments in reverse chronological order
		return array_reverse($replies);
	}

	
	// ========================================
	// == IPBComments Settings Menu ===========
	// ========================================

	/**
	 * Dashboard Admin Menu for IPB Comments
	 * Settings > IPB Comments
	 * 
	 * Settings API
	 * http://codex.wordpress.org/Settings_API
	 * http://ottopress.com/2009/wordpress-settings-api-tutorial/
	 */
	
	/**
	 * Callback function to display main options page
	 */
	function show_options_page() {
	
		if ( ! current_user_can('manage_options') ) {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		?>
		<div class="wrap" >
		<h2>IPB Comments</h2>
		<form method="post" action="options.php">
		<?php settings_fields('ipb_comments_options'); ?>
		<?php do_settings_sections('ipb_comments'); ?>
		<p><input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" /></p>
		</form>
		</div>
		<?php
	}
	
	
	/**
	 * Settings sections and fields for Admin IPB Comments Page
	 * http://codex.wordpress.org/Function_Reference/add_settings_section
	 */
	function settings_init() {

		#add_action( 'wp_print_styles', array($this,'ipb_stylesheets') );

		/**
		 * IPB Comments main settings section and fields
		 */
		add_settings_section( 'section_main',           // string used for 'id' attribute
			'Main Forum Settings',                      // title of the section
			array($this,'section_main'),                // callback function
			'ipb_comments'                              // settings page type (general, reading, media, etc..)
			);
		add_settings_field( 'ipb_field_member_id',      // string used for 'id' attribute
			'IPB Member ID',                            // title of the field
			'ipb_setting_member_id',                    // callback function
			'ipb_comments'                              // settings page type
			);
		add_settings_field('ipb_field_path', 'IPB Base Path', 'ipb_setting_path', 'ipb_comments');
		add_settings_field('ipb_field_url', 'IPB Base Url', 'ipb_setting_url', 'ipb_comments');

		/**
		 * IPB Comments custom settings section and fields
		 */
		add_settings_section('section_custom', 'Custom Settings', array($this,'section_custom'), 'ipb_comments');
		add_settings_field('ipb_field_custom', 'IPB Custom Options', 'ipb_setting_custom', 'ipb_comments');

		/**
		 * IPB Comments category settings section and fields
		 */
		add_settings_section('section_categories', 'Category Settings', array($this,'section_categories'), 'ipb_comments');
		add_settings_field('ipb_field_categories', 'IPB Category Options', 'ipb_setting_categories', 'ipb_comments');

		/**
		 * Register the settings
		 */
		register_setting( 'ipb_comments_options',       // option group
			'ipb_comments_options'                      // option name
			);
	}
	
	/**
	 * Main Settings callback function
	 */
	function section_main() { 

		// defaults
		if ( empty( $this->options['ipb_field_member_id'] ) ) {
			$this->options['ipb_field_member_id'] = 1;
		}
		if ( empty( $this->options['ipb_field_show_comments'] ) ) {
			$this->options['ipb_field_show_comments'] = 0;
		}
		// errors
		$error_ipb_field_path = '';
		if ( ! file_exists( $this->options['ipb_field_path'].'/initdata.php' ) ) {
			$error_ipb_field_path = ' style="color:red;"';
		}
		?>
		<ul class="forum_settings">
			<li>
			<label for="base_url">Base Url:</label>
			<input type="text" size="60" name="ipb_comments_options[ipb_field_url]" 
				value="<?php echo $this->options['ipb_field_url']; ?>" />
				<em>base url to your forum. ex. http://yourforum.com</em>
			</li>
	
			<li>
			<label for="base_path">Base Path:</label>
			<input type="text" size="60" name="ipb_comments_options[ipb_field_path]" 
				value="<?php echo $this->options['ipb_field_path']; ?>" />
				<em<?php echo $error_ipb_field_path; ?>>full path to your forum where initdata.php is located. ex. /var/www/forum</em>
			</li>
	
			<li>
			<label for="ttl">Cache TTL:</label>
			<input type="text" size="2" name="ipb_comments_options[ipb_field_ttl]" 
				value="<?php echo $this->options['ipb_field_ttl']; ?>" />
				<em>180 (seconds to cache the replies from the forum)</em>
			</li>

			<li>
			<label for="member_id">Member ID:</label>
			<input type="text" size="5" name="ipb_comments_options[ipb_field_member_id]" 
				value="<?php echo $this->options['ipb_field_member_id']; ?>" />
				<em>forum member ID who will create the new topics. ex. 1</em>
			</li>
			
			<li>
			<label for="show_comments">Comments:</label>
			<input type="text" size="5" name="ipb_comments_options[ipb_field_show_comments]" 
				value="<?php echo $this->options['ipb_field_show_comments']; ?>" />
				<em>how many recent comments to display, 0 to disable</em>
			</li>
		</ul>
		<br style="clear:both;" />
	<?php
	}
	
	/**
	 * Category Settings callback function
	 */
	function section_categories() { 
		?>
		<p>To the left of each WordPress category below, enter the IPB forum # to use when making a new topic.</p>
		<ul class="category_settings">
		<?php
		$categories = get_categories(array('hide_empty'=>0));
	
		foreach( $categories as $cat ) {
			echo sprintf('<li><input type="text" size="2" name="ipb_comments_options[categories][%s]" value="%s" />%s</li>',
				$cat->slug,
				$this->options['categories'][$cat->slug],
				$cat->name);
		}
		?>
		</ul>
	<?php
	}
	
	/**
	 * Custom Settings callback function
	 */
	function section_custom() {

		// default settings
		if ( empty( $this->options['ipb_field_link_text_wp'] ) ) {
			$this->options['ipb_field_link_text_wp'] = 'Follow the discussion in progress';
		}

		if ( empty( $this->options['ipb_field_link_text_ipb'] ) ) {
			$this->options['ipb_field_link_text_ipb'] = 'Read the full story here';
		}

		$radio = array('before_comments'=>'','after_comments'=>'','before_content'=>'','after_content'=>'');
		extract($radio);
		$key = $this->options['ipb_custom_link_filter'];
		if ( empty($key) ) $key = 'before_comments';
		$$key = 'checked';
		?>
		<ul class="custom_settings">

			<li>
			<label for="link_text_wp">WordPress Link Text</label>
			<input type="text" size="60" name="ipb_comments_options[ipb_field_link_text_wp]" 
				value="<?php echo $this->options['ipb_field_link_text_wp']; ?>" />
				<em>ex. Follow the discussion in progress</em>
			</li>

			<li>
			<label for="link_text_ipb">IP.Board Link Text</label>
			<input type="text" size="60" name="ipb_comments_options[ipb_field_link_text_ipb]" 
				value="<?php echo $this->options['ipb_field_link_text_ipb']; ?>" />
				<em>ex. Read the full story here</em>
			</li>

			<li>
			<label for="link_text_location">Link text location:</label>
			<input type="radio" size="2" name="ipb_comments_options[ipb_custom_link_filter]" value="before_comments" 
				<?php echo $before_comments; ?> /> Before Comments 
			<input type="radio" size="2" name="ipb_comments_options[ipb_custom_link_filter]" value="after_comments"  
				<?php echo $after_comments; ?> /> After Comments
			  <br />
			<input type="radio" size="2" name="ipb_comments_options[ipb_custom_link_filter]" value="before_content" 
				<?php echo $before_content; ?> /> Before Content 
			<input type="radio" size="2" name="ipb_comments_options[ipb_custom_link_filter]" value="after_content"  
				<?php echo $after_content; ?> /> After Content
			</li>

		</ul>
		<br style="clear:both;" />
		<?php
	}

	/**
	 * show link text
	 */
	function show_link_text ( $content ) {

		// assemble the link
		$meta = get_post_meta(get_the_ID(),'forum_topic_url');
		if ( empty( $meta ) ) return $content;

		$topic_link = current($meta);
		$topic_link_text = $this->options['ipb_field_link_text_wp'];

		$link_text = sprintf( '<p class="ipb_discussion"><a href="%s">%s</a></p>', $topic_link, $topic_link_text );

		// add it to the content/comments
		switch ( $this->options['ipb_custom_link_filter'] ) {

			case 'before_comments':
			case 'after_comments':
				echo $this->get_forum_comments();
				echo $link_text;
				return $content;

			case 'before_content':
				return $this->get_forum_comments() . $link_text . $content;

			case 'after_content':
				return $content . $this->get_forum_comments() . $link_text;

			default: 
				return $content;
		}

	}

	// ========================================
	// == IPBComments Show Forum Comments =====
	// ========================================
	
	/**
	 * get last X topic replies to display as comments
	 */
	
	function get_forum_comments () {

		if ( ! is_single() ) return $comments;
		
		if ( empty($this->options['ipb_field_show_comments']) ) return $comments;
	
		$post_ID = get_the_ID();

		/**
		 * check for a topic url assigned to this post
		 */
		$meta = get_post_meta($post_ID,'forum_topic_url');
		if ( empty($meta) ) return $comments;

		$topic_link = current($meta);

		/**
		 * check for forum topic values assigned to this post
		 * provides: int topic_id, int ttl, int timestamp, array replies
		 */
		$meta = get_post_meta($post_ID,'forum_topic_meta');
		extract($meta[0]);

		/**
		 * check if cache has expired using timestamp and ttl against current time()
		 * if cache has expired, get new replies and update all custom fields or post meta values
		 */
		$t = time();

		$update_cache = (empty($meta) OR empty($replies) OR ($t - $timestamp > intval($this->options['ipb_field_ttl'])));

		if ( $update_cache ) {
	
			$replies = $this->get_replies($topic_id);
	
			// if replies are empty, set a default value
			if ( empty($replies) ) {
				$replies = array('No comments.');
			}
	
			// update the post meta with new values
			update_post_meta ( $post_ID, 'forum_topic_meta', 
				array(
					'topic_id'  => intval($topic_id),
					'replies'   => $replies,
					'ttl'       => intval($ttl),
					'timestamp' => intval($t)
					) );
		}

		/**
		 * format any valid forum replies
		 */
		$reply_content = '';
		foreach ( $replies as $reply ) {
			if (! is_array($reply)) continue;
			extract($reply);

			if ( ! $comment ) continue;

			$reply_content .= 
				sprintf('<li>%s<br /><span class="ipb_comment_meta">posted by %s on %s</span></li>',
					nl2br($comment), $author, date( get_option('date_format'), $date ) );
		}

		if ( empty( $reply_content ) ) return $comments;

		/**
		 * Show our forum comments just above our post comments
		 */
		ob_start();
		?>
		<div id="ipb_comments">
		<p class="ipb_discussion"><a href="<?php echo $topic_link; ?>"><?php echo $this->topic_text; ?></a></p>
		<h4>Most recent forum comments:</h4>
			<ul>
				<?php echo $reply_content; ?>
			</ul>
		</div>
		<?php

		return ob_get_clean();
	}

	
	/**
	 * add styles for plugin on single post pages
	 */
	function stylesheets() {

		// add our stylesheet on admin or single post pages
		if ( is_admin() || is_single() ) {
			wp_enqueue_style('ipbcomments_stylesheet' );
		}

	}
	

}

