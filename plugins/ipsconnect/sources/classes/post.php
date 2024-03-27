<?php

/*
 * @package (Pro) WordPress IPSConnect
 * @brief Post Class Abstraction
 * @version 1.1.2
 * @author Provisionists (based on Marcher Technologies WP IPS Connect)
 * @link https://provisionists.com
 * @copyright Copyright (c) 2014, Provisionists LLC All Rights Reserved
 */

class ipsConnectPostClass {

	protected $settings;
	protected $settingsClass;
	protected $DB;
	public static $member;
	public static $connectIds = array();
	protected static $NOW;

	public function __construct() {
		$this->settingsClass = ipsConnectSettings::instance();
		$this->settings = & $this->settingsClass->fetchSettings();
		$this->DB = $this->settingsClass->DB();
	}

	/*
	 * Post Topic yay
	 * @return void
	 * @access public
	 */

	public function postTopic($post) {
		$post = !is_object($post) ? get_post($post) : $post;
		if (!is_wp_error($post) && in_array($post->post_type, $this->postTypes()) && get_post_status($post->ID) === 'publish' && $post->post_title) {
			$content = !empty($post->post_excerpt) ? $post->post_excerpt : $post->post_content;
			$opts = get_post_meta($post->ID);
			$tid = (isset($opts['ipb_tid'][0]) && $opts['ipb_tid'][0]) ? intval($opts['ipb_tid'][0]) : 0;
			$fid = (isset($opts['ipb_fid'][0]) && $opts['ipb_fid'][0]) ? intval($opts['ipb_fid'][0]) : 0;
			$addLink = (isset($opts['ipb_link'][0]) && $opts['ipb_link'][0]) ? trim($opts['ipb_link'][0]) : '';
			$linkOnly = (isset($opts['ipb_link_only'][0]) && $opts['ipb_link_only'][0]) ? 1 : 0;
			if ($this->settings['slaveForceDefaults']) {
				$fid = intval($this->settings['slavePostForum']);
				$addLink = $this->settings['slaveLinkText'];
				$linkOnly = intval($this->settings['slaveLinkOnly']);
			}
			if ($content && $fid && !$tid) {
				$type = 'id';
				$mid = intval($this->settings['slavePostAsMember']);
				if (!$mid) {
					if (!isset(self::$connectIds[$post->post_author])) {
						self::$connectIds[$post->post_author] = get_user_meta($post->post_author, 'wpIpsConnectID', TRUE);
					}
					if (isset(self::$connectIds[$post->post_author])) {
						$mid = self::$connectIds[$post->post_author];
					}
				}
				if (!$mid) {
					$user = get_userdata($post->post_author);
					switch ($this->settings['masterType']) {
						case 'username':
							$type = 'username';
							$mid = wpIpsConnect::instance()->encodePassForIPB($user->user_login);
							break;
						case 'email':
							$type = 'email';
							$mid = $user->user_email;
							break;
						case 'display_name':
							$type = 'displayname';
							$mid = wpIpsConnect::instance()->encodePassForIPB($user->display_name);
							break;
					}
				}
				if ($mid) {
					if ($addLink) {
						if ($linkOnly) {
							$postContent = str_replace('{url}', get_permalink($post), $addLink);
						} else {
							$postContent = $content . '<br /><br />' . str_replace('{url}', get_permalink($post), $addLink);
						}
					} else {
						$postContent = $content;
					}
					$postContent = str_replace("\n", '<br />', $postContent);
					$postIt = wpIpsConnect::instance()->getXmlRpc('postTopic', array(
						'member_key' => $mid,
						'member_field' => $type,
						'forum_id' => $fid,
						'topic_title' => base64_encode($post->post_title),
						'post_content' => base64_encode($postContent),
						'api_module' => 'mt',
					));
					if ($postIt && isset($postIt['topic_id'])) {
						update_post_meta($post->ID, 'ipb_tid', intval($postIt['topic_id']));
						return $postIt['topic_id'];
					}
				}
			}
		}
	}

	public function postReply($id, $comment) {
		if (wp_get_comment_status($id) === 'approved' || $comment === 'approved') {
			$comment = is_object($comment) ? $comment : get_comment($id);
			$meta = get_post_meta($comment->comment_post_ID);
			$isPosted = get_comment_meta($id, 'ipb_pid', true);
			$doReply = intval($meta['ipb_replies'][0]);
			$tid = intval($meta['ipb_tid'][0]);
			if ($this->settings['slaveForceDefaults']) {
				$doReply = intval($this->settings['slavePostTopic']);
			}
			$post = get_post($comment->comment_post_ID);
			if (!is_wp_error($post) && in_array($post->post_type, $this->postTypes())) {
				if (!$tid) {
					$tid = $this->postTopic($post);
				}
				if ($tid && $doReply && !$isPosted) {
					$type = 'id';
					if ($comment->user_id) {
						if (!isset(self::$connectIds[$comment->user_id])) {
							self::$connectIds[$comment->user_id] = get_user_meta($comment->user_id, 'wpIpsConnectID', TRUE);
						}
						if (isset(self::$connectIds[$comment->user_id])) {
							$mid = self::$connectIds[$comment->user_id];
						}
					} elseif ($this->settings['slavePostAsGuest']) {
						$mid = intval($this->settings['slavePostAsGuest']);
					}
					if (!$mid) {
						switch ($this->settings['masterType']) {
							case 'username':
								$user = get_userdata($comment->user_id);
								$type = 'username';
								$mid = wpIpsConnect::instance()->encodePassForIPB($user->user_login);
								break;
							case 'email':
								$type = 'email';
								$mid = $comment->comment_author_email;
								break;
							case 'display_name':
								$type = 'displayname';
								$mid = wpIpsConnect::instance()->encodePassForIPB($comment->comment_author);
								break;
						}
					}
					if ($mid) {
						$content = $comment->comment_content;
						$content = str_replace("\n", '<br />', $content);
						$postIt = wpIpsConnect::instance()->getXmlRpc('postReply', array(
							'member_key' => $mid,
							'member_field' => $type,
							'topic_id' => $tid,
							'post_content' => base64_encode($content),
							'api_module' => 'mt',
						));

						if ($postIt && isset($postIt['pid'])) {
							update_comment_meta($id, 'ipb_pid', $postIt['pid']);
						}
					}
				}
			}
		}
	}

	public function postReplies($comments, $pid) {
		$this->postTopic($pid);
		$synchIt = get_post_meta($pid, 'ipb_synch', true);
		$doReply = get_post_meta($pid, 'ipb_replies', true);
		if ($this->settings['slaveForceDefaults']) {
			$synchIt = intval($this->settings['slaveSynchTopic']);
			$doReply = intval($this->settings['slavePostTopic']);
		}
		if ($doReply && is_array($comments) && count($comments)) {
			foreach ($comments as $pos => $c) {
				$this->postReply($c->comment_ID, $c);
				if ($c->user_id && !$c->comment_author_url) {

					if (!isset(self::$connectIds[$c->user_id])) {
						self::$connectIds[$c->user_id] = get_user_meta($c->user_id, 'wpIpsConnectID', TRUE);
					}
					if (isset(self::$connectIds[$c->user_id])) {
						$mid = self::$connectIds[$c->user_id];
					}
					if ($mid) {
						$comments[$pos]->comment_author_url = $this->settingsClass->parseUrl($mid, 'ipsConnectUser');
					}
				}
			}
		}
		if ($synchIt) {
			$comments = $this->getComments($pid, $comments);
		}
		return $comments;
	}

	public function getComments($pid, $comments) {
		$tid = get_post_meta($pid, 'ipb_tid', TRUE);
		if ($tid) {
			$st = 0;
			$limit = 0;
			if (get_comment_pages_count() > 1) {
				$limit = comments_per_page();
				global $cpage;
				if ($cpage > 1) {
					$st = ceil(get_comments_number($pid) % $cpage);
				}
			}
			if (!$limit) {
				$limit = apply_filters('ipb_synch_limit', 1000);
			}
			$posts = wpIpsConnect::instance()->getXmlRpc('fetchPosts', array(
				'api_module' => 'mt',
				'topic_ids' => $tid,
				'order_field' => 'pid',
				'order_by' => 'asc',
				'offset' => $st,
				'limit' => $limit,
					)
			);
			if ($posts && is_array($posts) && count($posts)) {
				$pids = trim(implode(',', array_keys($posts)), ',');
				$_posts = array();
				$loc = $this->DB->get_results('SELECT comment_id, meta_value FROM ' . $this->DB->commentmeta . " WHERE meta_key='ipb_pid' AND meta_value IN({$pids})", ARRAY_A);
				if (!is_wp_error($loc)) {
					foreach ($loc as $row) {
						$_posts[intval($row['meta_value'])] = intval($row['comment_id']);
					}
				}
				remove_action('wp_insert_comment', array(&$this, 'postReply'), 0, 2);
				$tzCurr = @date_default_timezone_get();
				$tzOpt = get_option('timezone_string');
				$tzChanged = FALSE;
				if ($tzOpt && $tzOpt != $tzCurr) {
					$tzChanged = TRUE;
				}
				foreach ($posts as $p) {
					$uid = 0;
					if (isset($_posts[$p['pid']]) || $p['pid'] === $p['topic_firstpost']) {
						continue;
					}
					if ($tzChanged) {
						@date_default_timezone_set($tzOpt);
					}
					if ($p['member_id']) {
						$user = $this->DB->get_row($this->DB->prepare('SELECT user_id FROM ' . $this->DB->usermeta . " WHERE meta_key='wpIpsConnectID' AND meta_value=%d", intval($p['member_id'])));
						if (!is_wp_error($user) && $user->user_id) {
							$uid = $user->user_id;
						}
					}
					$p['post'] = preg_replace_callback("!<a(?:.*?)href=['\"](.+?)[\"'](?:[^>]*?)>(.+?)</a>!is", array(&$this, 'cleanUrls'), $p['post']);
					$p['post'] = preg_replace_callback('!<\/p>(.*?)?<p>!s', array(&$this, 'cleanParagraphs'), $p['post']);
					$data = array(
						'comment_post_ID' => intval($pid),
						'comment_author' => $p['author_name'],
						'comment_author_email' => $p['email'],
						'comment_author_url' => $this->settingsClass->parseUrl(intval($p['member_id']), 'ipsConnectUser'),
						'comment_content' => $p['post'],
						'comment_type' => '',
						'comment_parent' => 0,
						'user_id' => intval($uid),
						'comment_author_IP' => $p['ip_address'],
						'comment_date' => date('Y-m-d H:i:s', intval($p['post_date'])),
						'comment_agent' => $_SERVER['HTTP_USER_AGENT'],
						'comment_approved' => 1,
					);
					$_pid = wp_insert_comment($data);
					if (!is_wp_error($_pid) && $_pid) {
						update_comment_meta($_pid, 'ipb_pid', intval($p['pid']));
						$comments[] = get_comment($_pid);
					}
				}
				if ($tzChanged) {
					@date_default_timezone_set($tzCurr);
				}
				add_action('wp_insert_comment', array(&$this, 'postReply'), 0, 2);
				unset($pids, $posts, $_posts, $tzChanged, $tzCurr, $tzOpt);
			}
		}
		return $comments;
	}

	public function cleanUrls($matches) {
		return '<a href="' . $matches[1] . '" title="' . $matches[2] . '">' . $matches[2] . '</a>';
	}

	public function cleanParagraphs($matches) {
		return '<br />' . $matches[1];
	}

	/**
	 * Add post fields
	 * @return void
	 * @access public
	 */
	public function addTopicMeta() {
		add_action('add_meta_boxes', array(&$this, 'addMeta'), 0);
		/* Save post meta on the 'save_post' hook. */
		add_action('save_post', array(&$this, 'saveMeta'), 10, 2);
	}

	/**
	 * actually Add post fields
	 * @return void
	 * @access public
	 */
	public function addMeta() {
		foreach ($this->postTypes() as $key) {
			if (!$this->settings['slaveForceDefaults']) {
				add_meta_box(
						'ipsconnect-post-fid', // Unique ID
						esc_html__('Topic Forum'), // Title
						array(&$this, 'metaFid'), // Callback function
						$key, // Admin page (or post type)
						'side', // Context
						'default'  // Priority
				);
				add_meta_box(
						'ipsconnect-post-replies', // Unique ID
						esc_html__('Topic Replies'), // Title
						array(&$this, 'metaReplies'), // Callback function
						$key, // Admin page (or post type)
						'side', // Context
						'default'  // Priority
				);
				add_meta_box(
						'ipsconnect-post-synch', // Unique ID
						esc_html__('Synch Topic Replies'), // Title
						array(&$this, 'metaSynch'), // Callback function
						$key, // Admin page (or post type)
						'side', // Context
						'default'  // Priority
				);
			}
			add_meta_box(
					'ipsconnect-post-tid', // Unique ID
					esc_html__('Topic tid'), // Title
					array(&$this, 'metaTid'), // Callback function
					$key, // Admin page (or post type)
					'side', // Context
					'default'  // Priority
			);
			if (!$this->settings['slaveForceDefaults']) {
				add_meta_box(
						'ipsconnect-post-link', // Unique ID
						esc_html__('Link Post'), // Title
						array(&$this, 'metaLink'), // Callback function
						$key, // Admin page (or post type)
						'side', // Context
						'default'  // Priority
				);
				add_meta_box(
						'ipsconnect-post-link-only', // Unique ID
						esc_html__('Only Link?'), // Title
						array(&$this, 'metaLinkOnly'), // Callback function
						$key, // Admin page (or post type)
						'side', // Context
						'default'  // Priority
				);
			}
		}
	}

	public function postTypes() {
		$types = get_post_types('', 'names');
		foreach ($types as $i => $type) {
			if ($this->postTypeSupported($type) !== TRUE) {
				unset($types[$i]);
			}
		}
		return apply_filters('ipb_post_types', $types);
	}

	public function postTypeSupported($type) {
		if (post_type_supports($type, 'title') !== TRUE || post_type_supports($type, 'custom-fields') !== TRUE || post_type_supports($type, 'comments') !== TRUE) {
			return FALSE;
		}
		if (post_type_supports($type, 'editor') !== TRUE && post_type_supports($type, 'excerpt') !== TRUE) {
			return FALSE;
		}
		return TRUE;
	}

	/* Display the post ipb forum select box. */

	public function metaFid($object, $box) {
		wp_nonce_field(basename(__FILE__), 'ipsconnect_post_class_nonce');
		$stats = wpIpsConnect::instance()->getXmlRpc('fetchForumsOptionList');
		if (isset($stats['forumList'])) {
			$fid = get_post_meta($object->ID, 'ipb_fid', TRUE);
			$fid = ($fid > 0) ? intval($fid) : 0;
			if (!$fid && !get_post_meta($object->ID, 'ipb_fid_custom', TRUE)) {
				$fid = intval($this->settings['slavePostForum']);
			}
			echo <<<WPHTML
	<p>
		<label for="ipsconnect-post-fid">
WPHTML;
			_e("Post Topic");
			echo <<<WPHTML
		 </label>
<input name="ipsconnect-post-fid-custom" id="ipsconnect-post-fid-custom" type='hidden' value='1' checked="checked" />
			    <br />
<select id="ipsconnect-post-fid" name="ipsconnect-post-fid">
"<option value='0'>--
WPHTML;
			_e('No Topic');
			echo '--</option>';
			$selectBox = $stats['forumList'];
			if ($fid) {
				$selectBox = str_replace('value="' . $fid, 'selected="selected" value="' . $fid, $selectBox);
			}
			echo $selectBox;
			echo '</select></p>';
		}
	}

	/* Display the post ipb tid box. */

	public function metaTid($object, $box) {
		wp_nonce_field(basename(__FILE__), 'ipsconnect_post_class_nonce');
		$tid = get_post_meta($object->ID, 'ipb_tid', TRUE);
		$tid = ($tid > 0) ? intval($tid) : 0;
		echo <<<WPHTML
	<p>
		<label for="ipsconnect-post-tid">
WPHTML;
		_e("Topic Id");
		echo <<<WPHTML
		 </label>
			    <br />
		<input class="widefat" type="text" name="ipsconnect-post-tid" id="ipsconnect-post-tid" value="{$tid}" size="30" />
	</p>
WPHTML;
	}

	public function metaReplies($object, $box) {
		wp_nonce_field(basename(__FILE__), 'ipsconnect_post_class_nonce');
		$reply = get_post_meta($object->ID, 'ipb_replies', TRUE);
		if (!$reply && !get_post_meta($object->ID, 'ipb_replies_custom', TRUE)) {
			$reply = intval($this->settings['slavePostTopic']);
		}
		$default = $reply ? " checked='checked'" : '';
		echo <<<WPHTML
	<p>
	<input name="ipsconnect-post-replies-custom" id="ipsconnect-post-replies-custom" type='hidden' value='1' checked="checked" />
	<input name="ipsconnect-post-replies" id="ipsconnect-post-replies" type='checkbox' value='1'{$default} />
&nbsp;&nbsp;	<label for="ipsconnect-post-replies">
WPHTML;
		_e("Post Comments as replies to the topic");
		echo <<<WPHTML
		 </label>
	</p>
WPHTML;
	}

	public function metaSynch($object, $box) {
		wp_nonce_field(basename(__FILE__), 'ipsconnect_post_class_nonce');
		$reply = get_post_meta($object->ID, 'ipb_synch', TRUE);
		if (!$reply && !get_post_meta($object->ID, 'ipb_synch_custom', TRUE)) {
			$reply = intval($this->settings['slaveSynchTopic']);
		}
		$default = $reply ? " checked='checked'" : '';
		echo <<<WPHTML
	<p>
			<input name="ipsconnect-post-synch-custom" id="ipsconnect-post-synch-custom" type='hidden' value='1' checked="checked" />
				<input name="ipsconnect-post-synch" id="ipsconnect-post-synch" type='checkbox' value='1'{$default}>
&nbsp;&nbsp;	<label for="ipsconnect-post-synch">
WPHTML;
		_e("Synch Topic With Post");
		echo <<<WPHTML
		 </label>
	</p>
WPHTML;
	}

	public function metaLink($object, $box) {
		wp_nonce_field(basename(__FILE__), 'ipsconnect_post_class_nonce');
		$link = get_post_meta($object->ID, 'ipb_link', TRUE);
		if (!$link && !get_post_meta($object->ID, 'ipb_link_custom', TRUE)) {
			$link = $this->settings['slaveLinkText'];
		}
		if ($link) {
			$link = esc_textarea($link);
		}
		echo <<<WPHTML
	<p>
		<label for="ipsconnect-post-link">
WPHTML;
		_e("Link to post format, if given, must include {url} which will be replaced with the post url, the result will be appended to the post body.");
		echo <<<WPHTML
		 </label>
			<input name="ipsconnect-post-link-custom" id="ipsconnect-post-link-custom" type='hidden' value='1' checked="checked" />
			    <br />
<textarea class="wp-editor-area" name="ipsconnect-post-link" id="ipsconnect-post-link" cols="30" style="height: 100px; resize: none;">{$link}</textarea>
WPHTML;
		_e('HTML Allowed.');
		echo <<<WPHTML
</p>
WPHTML;
	}

	public function metaLinkOnly($object, $box) {
		wp_nonce_field(basename(__FILE__), 'ipsconnect_post_class_nonce');
		$reply = get_post_meta($object->ID, 'ipb_link_only', TRUE);
		if (!$reply && !get_post_meta($object->ID, 'ipb_link_only_custom', TRUE)) {
			$reply = intval($this->settings['slaveLinkOnly']);
		}
		$default = $reply ? " checked='checked'" : '';
		echo <<<WPHTML
	<p>
			<input name="ipsconnect-post-link-only-custom" id="ipsconnect-post-link-only-custom" type='hidden' value='1' checked="checked" />
				<input name="ipsconnect-post-link-only" id="ipsconnect-post-link-only" type='checkbox' value='1'{$default}>
&nbsp;&nbsp;	<label for="ipsconnect-post-synch">
WPHTML;
		_e('If linking to the post in the topic, ONLY provide link?');
		echo '<br />';
		_e('Has No effect if no link format given.');
		echo <<<WPHTML
		 </label>
	</p>
WPHTML;
	}

	/* Save the meta box's post metadata. */

	public function saveMeta($post_id, $post) {

		/* Verify the nonce before proceeding. */
		if (!isset($_POST['ipsconnect_post_class_nonce']) || !wp_verify_nonce($_POST['ipsconnect_post_class_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		/* Get the post type object. */
		$post_type = get_post_type_object($post->post_type);

		/* Check if the current user has permission to edit the post. */
		if (!current_user_can($post_type->cap->edit_post, $post_id)) {
			return $post_id;
		}


		/* Get the meta keys. */
		$meta_keys = array(
			'ipb_tid' => 'ipsconnect-post-tid',
		);
		if (!$this->settings['slaveForceDefaults']) {
			$meta_keys = array_merge($meta_keys, array(
				'ipb_fid' => 'ipsconnect-post-fid',
				'ipb_replies' => 'ipsconnect-post-replies',
				'ipb_synch' => 'ipsconnect-post-synch',
				'ipb_link' => 'ipsconnect-post-link',
				'ipb_link_only' => 'ipsconnect-post-link-only',
				'ipb_fid_custom' => 'ipsconnect-post-fid-custom',
				'ipb_replies_custom' => 'ipsconnect-post-replies-custom',
				'ipb_synch_custom' => 'ipsconnect-post-synch-custom',
				'ipb_link_custom' => 'ipsconnect-post-link-custom',
				'ipb_link_only_custom' => 'ipsconnect-post-link-only-custom',
			));
		}

		/* Get the meta values of the custom field keys. */
		$meta = get_post_meta($post_id);
		foreach ($meta_keys as $meta_key => $request_key) {
			/* Get the posted data and sanitize it */
			$new_meta_value = isset($_POST[$request_key]) ? $_POST[$request_key] : '';
			$meta_value = isset($meta[$meta_key][0]) ? $meta[$meta_key][0] : '';
			switch ($meta_key) {
				default:
					$new_meta_value = absint($new_meta_value);
					$meta_value = absint($meta_value);
					break;
				case 'ipb_link':
					$new_meta_value = trim($new_meta_value);
					$meta_value = trim($meta_value);
					break;
			}
			/* If a new meta value was added and there was no previous value, add it. */
			if ($new_meta_value && '' == $meta_value) {
				add_post_meta($post_id, $meta_key, $new_meta_value, true);
			} elseif ($new_meta_value && $new_meta_value != $meta_value) {
				update_post_meta($post_id, $meta_key, $new_meta_value);
			} elseif ('' == $new_meta_value && $meta_value) {
				delete_post_meta($post_id, $meta_key, $meta_value);
			}
			if ($meta_key == 'ipb_tid' && !$new_meta_value) {
				$this->postTopic($post);
				$_posts = array();
				$loc = $this->DB->get_results($this->DB->prepare('SELECT comment_ID FROM ' . $this->DB->comments . " WHERE comment_post_ID=%d", $post_id), ARRAY_A);
				if (!is_wp_error($loc)) {
					foreach ($loc as $row) {
						$_posts[intval($row['comment_ID'])] = intval($row['comment_ID']);
					}
				}
				if (count($_posts)) {
					$this->DB->query('DELETE FROM ' . $this->DB->commentmeta . " WHERE meta_key IN('ipb_posted','ipb_pid') AND comment_id IN(" . trim(implode(',', $_posts), ',') . ')');
				}
			}
		}
		return $post_id;
	}

	public function getAvatar($avatar, $id_or_email, $size, $default, $alt) {
		$ipsId = 0;
		$id = 0;
		$out = '';
		if (is_numeric($id_or_email)) {
			$id = (int) $id_or_email;
			if (!isset(self::$connectIds[$id])) {
				self::$connectIds[$id] = get_user_meta($id, 'wpIpsConnectID', TRUE);
			}
			if (isset(self::$connectIds[$id])) {
				$ipsId = self::$connectIds[$id];
			}
		} elseif (is_object($id_or_email)) {
			// No avatar for pingbacks or trackbacks
			$allowed_comment_types = apply_filters('get_avatar_comment_types', array('comment'));
			if (!empty($id_or_email->comment_type) && !in_array($id_or_email->comment_type, (array) $allowed_comment_types)) {
				return false;
			}
			if (!empty($id_or_email->user_id)) {
				$id = (int) $id_or_email->user_id;
				if (!isset(self::$connectIds[$id])) {
					self::$connectIds[$id] = get_user_meta($id, 'wpIpsConnectID', TRUE);
				}
				if (isset(self::$connectIds[$id])) {
					$ipsId = self::$connectIds[$id];
				}
			}
		}
		if ($id && $ipsId) {
			$alt = $alt ? esc_attr($alt) : '';
			$cache = NULL;
			if (is_null(self::$NOW)) {
				self::$NOW = time();
			}
			$cacheRaw = get_user_meta($id, 'wpIpsConnectAvatar', TRUE);
			if ($cacheRaw && !is_wp_error($cacheRaw)) {
				$cache = json_decode($cacheRaw);
			}
			if ((!is_null($cache) && $cache->last_update > ( self::$NOW - ( 60 * 60 * 24 ) ) ) || is_null($cache)) {
				$ipsPhotoData = wpIpsConnect::instance()->getXmlRpc('fetchAvatar', array(
					'api_module' => 'mt',
					'member_id' => $ipsId,
				));
				$ipsPhotoData['last_update'] = self::$NOW;
				$ipsPhotoData = json_encode($ipsPhotoData);
				update_user_meta($id, 'wpIpsConnectAvatar', $ipsPhotoData);
				$cache = json_decode($ipsPhotoData);
			}
			if (property_exists($cache, 'thumb_photo') && $cache->thumb_width <= $size && $cache->thumb_height <= $size) {
				$out = $cache->thumb_photo;
			} elseif (property_exists($cache, 'small_photo') && $cache->small_width <= $size && $cache->small_height <= $size) {
				$out = $cache->small_photo;
			} elseif (property_exists($cache, 'mini_photo') && $cache->mini_width <= $size && $cache->mini_height <= $size) {
				$out = $cache->small_photo;
			} elseif (property_exists($cache, 'main_photo')) {
				$out = $cache->main_photo;
			}
			if ($out) {
				$avatar = "<img alt='{$alt}' src='{$out}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
			}
		}
		return $avatar;
	}

}