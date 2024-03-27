<!-- mfunc --><!--dynamic-cached-content--><?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback to twentyten_comment which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
if(isset($GLOBALS['ipbwi'])){
?>
			<div id="comments">
<?php
	// You can start editing here -- including this comment!
	if(get_option('ipbwi_settings_forumComments_merge') == 1 && have_comments()){
?>
			<h3 id="comments-title"><?php
			printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'twentyten' ),
			number_format_i18n(get_comments_number()), '<em>' . get_the_title() . '</em>' );
			?></h3>
<?php if(get_comment_pages_count() > 1 && get_option('page_comments')){ // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'twentyten' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
			</div> <!-- .navigation -->
<?php } // check for comment navigation ?>
			<ol class="commentlist">
				<?php
					/* Loop through and list the comments. Tell wp_list_comments()
					 * to use twentyten_comment() to format the comments.
					 * If you want to overload this in a child theme then you can
					 * define twentyten_comment() and that will be used instead.
					 * See twentyten_comment() in twentyten/functions.php for more.
					 */
					wp_list_comments( array( 'callback' => 'ipbwi_forumComments_listWPComments' ) );
				?>
			</ol>
<?php if(get_comment_pages_count() > 1 && get_option( 'page_comments')){ // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'twentyten' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
			</div><!-- .navigation -->
<?php } // check for comment navigation
} // end have_comments()

// check, if comments forum is postable for member and comments are open
if(comments_open() && $GLOBALS['ipbwi']->member->isLoggedIn() && $GLOBALS['ipbwi']->forum->isPostable(get_option('ipbwi_settings_forumComments_category'))){
	$loggedIn	= true;
	$showForm	= true;
// check, if comments forum is postable for guest and comments are open
}elseif(comments_open() && !$GLOBALS['ipbwi']->member->isLoggedIn() && $GLOBALS['ipbwi']->forum->isPostable(get_option('ipbwi_settings_forumComments_category'))){
	$loggedIn	= false;
	$showForm	= true;
// if user is a guest, recommend logging in to post a comment
}elseif(comments_open() && !$GLOBALS['ipbwi']->member->isLoggedIn() && !$GLOBALS['ipbwi']->forum->isPostable(get_option('ipbwi_settings_forumComments_category'))){
	$loggedIn	= false;
	$showForm	= false;
}else{
	$showForm	= false;
}
 ?>
	<div class="forum_discussions">
		<h3>Forum Discussion about this article</h3>
<?php

	// is are discussion topic already created?
	if(!ipbwi_forumComments_get_entry(get_the_ID()) === true){
		echo '<p>No Forum Discussion started yet.</p>';
	}else{
		$topic	= $GLOBALS['ipbwi']->topic->info(ipbwi_forumComments_get_entry(get_the_ID()));
		echo '<h4><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'topic/'.ipbwi_forumComments_get_entry(get_the_ID()).'-'.$topic['title_seo'].'/">Participate on the Discussion now!</a></h4>';
	}
	
	// do we know the visitor?
	if($GLOBALS['ipbwi']->member->isLoggedIn()){
		echo '<p>You are logged in as <strong>'.$GLOBALS['ipbwi']->member->myInfo['members_display_name'].'</strong></p>';
	}else{
		echo '<p>You are a guest</p>';
	}

	// show form if possible
	if($showForm == true){
		echo '<form action="#" enctype="multipart/form-data" method="post">';
		if($loggedIn == false){
			echo 'Your name: <input type="text" name="guestname" value="'.(isset($_POST['guestname']) ? $_POST['guestname'] : '').'" /><br />';
		}
		echo ipbwi_forumComments_form();
		// check if captcha is typed in correctly
		if(get_option('ipbwi_settings_forumComments_captcha') && isset($_POST['post_content']) && $GLOBALS['ipbwiForumCommentsStatus']['captcha'] == '0'){
			echo '<p><strong>The text you\'ve typed did not match the Captcha-Code.</strong></p>';
		}
		
		echo '
			<p><input type="submit" /></p>
			<input type="hidden" name="wpid" value="'.get_the_ID().'" />
		</form>';
	// cannot write comments
	}else{
		// comments are closed through wordpress settings
		if(!comments_open()){
			echo '<p class="nocomments">'; _e( 'Comments are closed.', 'twentyten' ); echo '</p>';
		// not enough rights
		}elseif($loggedIn == false){
			echo '<h4>Insufficient rights</h4>';
			echo '<p class="nocomments">Hint: <a href="'.wp_login_url('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']).'"><strong>Login or create a member account</strong></a> to improve your posting status.</p>';
		}
	}
?>
		<ol class="commentlist"><?php ipbwi_forumComments_listIPComments(get_the_ID()); ?></ol>
	</div>
</div><!-- #comments -->
<!--/dynamic-cached-content--><!-- /mfunc -->
<?php
}
?>