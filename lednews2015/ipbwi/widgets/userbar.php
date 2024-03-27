<!-- mfunc --><!--dynamic-cached-content--><?php
if($GLOBALS['ipbwi']->member->isLoggedIn()){
?>
<div id="ipbwi_userbar_loggedIn">
	<div class="ipbwi_avatar">
		<div><?php echo $GLOBALS['ipbwi']->member->avatar(); ?></div>
		<div class="change"><a href="<?php echo $GLOBALS['ipbwi']->getBoardVar('url'); ?>index.php?app=members&module=profile&section=photo">Change image?</a></div>
	</div>
	<div class="ipbwi_welcome">Welcome, <?php echo $GLOBALS['ipbwi']->member->myInfo['members_display_name']; ?>!</div>
	<div class="ipbwi_links_left">
		<p><a href="<?php echo $GLOBALS['ipbwi']->getBoardVar('url'); ?>">Forums</a></p>
	</div>
	<div class="ipbwi_links_right">
		<p><a href="<?php echo $GLOBALS['ipbwi']->getBoardVar('url'); ?>index.php?app=core&module=usercp">My Settings</a></p>
		<p><a href="<?php echo $GLOBALS['ipbwi']->getBoardVar('url'); ?>index.php?app=members&module=messaging">Messages</a></p>
		<?php
		if($GLOBALS['ipbwi']->getBoardVar('htaccess_mod_rewrite') == 1){
		?>
		<p><a href="<?php echo $GLOBALS['ipbwi']->getBoardVar('url'); ?>user/<?php echo $GLOBALS['ipbwi']->member->myInfo['member_id']; ?>-<?php echo $GLOBALS['ipbwi']->member->myInfo['members_seo_name']; ?>/">My Profile</a></p>
		<?php
		}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'query_string'){
		?>
		<p><a href="<?php echo $GLOBALS['ipbwi']->getBoardVar('url'); ?>index.php?/user/<?php echo $GLOBALS['ipbwi']->member->myInfo['member_id']; ?>-<?php echo $GLOBALS['ipbwi']->member->myInfo['members_seo_name']; ?>/">My Profile</a></p>
		<?php
		}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'path_info'){
		?>
		<p><a href="<?php echo $GLOBALS['ipbwi']->getBoardVar('url'); ?>index.php/user/<?php echo $GLOBALS['ipbwi']->member->myInfo['member_id']; ?>-<?php echo $GLOBALS['ipbwi']->member->myInfo['members_seo_name']; ?>/">My Profile</a></p>
		<?php
		}
		?>
	</div>
	<div class="logout"><?php wp_register('', ''); ?></div>
	<div class="logout"><a href="<?php echo wp_logout_url( $_SERVER['REQUEST_URI'] ); ?>">Logout</a></div>
</div>
<?php
}else{
?>
<div id="ipbwi_userbar_loggedOut">
	<div class="ipbwi_createAccount"><?php wp_register('', ''); ?></div>
	<div class="ipbwi_signInIPB"><a href="<?php echo $GLOBALS['ipbwi']->getBoardVar('url'); ?>index.php?app=core&module=global&section=login">Sign In via Forum</a></div>
	<div class="ipbwi_signInWP"><a href="<?php echo wp_login_url(); ?>">Sign In via WordPress</a></div>
	<div class="ipbwi_signInFB"><a href="<?php echo $GLOBALS['ipbwi']->getBoardVar('url'); ?>index.php?app=core&module=global&section=login&serviceClick=facebook">
		<img alt="Log in with Facebook" src="<?php echo $GLOBALS['ipbwi']->getBoardVar('url'); ?>public/style_images/master/loginmethods/facebook.png">
	</a>
	</div>
</div>
<?php
}
?><!--/dynamic-cached-content--><!-- /mfunc -->