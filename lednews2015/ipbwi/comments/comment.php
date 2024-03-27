<!-- mfunc --><!--dynamic-cached-content-->
	<li>
		<div class="comment">
			<div class="comment-author vcard">
				<?php
					if(get_option('ipbwi_settings_forumComments_avatar') && $user['member_id'] > 0 && $GLOBALS['ipbwi']->member->avatar($user['member_id']) != ''){
						echo '<a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'user/'.$user['member_id'].'-'.$user['members_seo_name'].'/'.'">'.$GLOBALS['ipbwi']->member->avatar($user['member_id']).'</a>';
					}else{
						
					}
				?>
			</div>
			<div class="comment-meta commentmetadata">
			<?php
				if(intval($post['author_id']) > 0){
					echo '<a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'user/'.$user['member_id'].'-'.$user['members_seo_name'].'/"><strong>'.$user['members_display_name'].'</strong></a>';
				}elseif($wp_comments['comment_author'] != ''){
					echo $wp_comments['comment_author'];
				}else{
					echo 'anonymous';
				}
				if($comment->ipbwi_comment_date){
					echo ' on '.$GLOBALS['ipbwi']->date($comment->ipbwi_comment_date);
				}else{
					echo ' on '.mysql2date(get_option('date_format'), $comment->comment_date).' @ '.mysql2date(get_option('time_format'), $comment->comment_date,true);
				}
			?>
			</div>
			<div class="comment-body"><?php echo $comment->comment_content; ?></div>
			<div class="comment-footer">
			<?php
			
			if(get_option('ipbwi_settings_forumComments_signature') == 1){
				echo $user['signature'];
			}
			
			?>
			</div>
		</div><!-- #comment-##  -->
	<?php echo $trailing_li; ?>
<!--/dynamic-cached-content--><!-- /mfunc -->