<!-- mfunc --><!--dynamic-cached-content--><?php
	$postData = '<ul class="ipbwi_widget_latestPosts">';
	if(is_array($posts) && count($posts) > 0){
		foreach($posts as $post){
			$user	= $GLOBALS['ipbwi']->member->info($post['member_id']);
			$topic	= $GLOBALS['ipbwi']->topic->info($post['topic_id']);
			if($instance['substr'] && intval($instance['substr']) > 0){
				// allow images (buggy, if substr cuts <img code)
				//$title = ((strlen($post['post']) > $instance['substr']) ? substr(strip_tags($post['post'],'<img>'), 0, $instance['substr']).'...' : $post['post']);
				
				// disallow images
				$title = ((strlen($post['post']) > $instance['substr']) ? substr(strip_tags($post['post']), 0, $instance['substr']).'...' : $post['post']);
			}else{
				$title = $post['post'];
			}
			
			if($GLOBALS['ipbwi']->getBoardVar('htaccess_mod_rewrite') == 1){
				$postData .= '
					<li class="ipbwi_widget_latestPosts_entry">
						<div class="ipbwi_widget_latestPosts_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'topic/'.$post['topic_id'].'-'.$topic['title_seo'].'/?p='.$post['pid'].'" title="'.$topic['title'].'">'.$title.'</a></div>
						<div class="ipbwi_widget_latestPosts_author"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'user/'.$user['member_id'].'-'.$user['members_seo_name'].'/">'.$post['members_display_name'].'</a></div>
						<div class="ipbwi_widget_latestPosts_date" title="'.$GLOBALS['ipbwi']->date($post['start_date']).'">'.$GLOBALS['ipbwi']->timeAgo($GLOBALS['ipbwi']->date($post['post_date'],0)).'</div>
					</li>
				';
			}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'query_string'){
				$postData .= '
					<li class="ipbwi_widget_latestPosts_entry">
						<div class="ipbwi_widget_latestPosts_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?/topic/'.$post['topic_id'].'-'.$topic['title_seo'].'/?p='.$post['pid'].'" title="'.$topic['title'].'">'.$title.'</a></div>
						<div class="ipbwi_widget_latestPosts_author"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?/user/'.$user['member_id'].'-'.$user['members_seo_name'].'/">'.$post['members_display_name'].'</a></div>
						<div class="ipbwi_widget_latestPosts_date" title="'.$GLOBALS['ipbwi']->date($post['start_date']).'">'.$GLOBALS['ipbwi']->timeAgo($GLOBALS['ipbwi']->date($post['post_date'],0)).'</div>
					</li>
				';
			}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'path_info'){
				$postData .= '
					<li class="ipbwi_widget_latestPosts_entry">
						<div class="ipbwi_widget_latestPosts_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php/topic/'.$post['topic_id'].'-'.$topic['title_seo'].'/?p='.$post['pid'].'" title="'.$topic['title'].'">'.$title.'</a></div>
						<div class="ipbwi_widget_latestPosts_author"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php/user/'.$user['member_id'].'-'.$user['members_seo_name'].'/">'.$post['members_display_name'].'</a></div>
						<div class="ipbwi_widget_latestPosts_date" title="'.$GLOBALS['ipbwi']->date($post['start_date']).'">'.$GLOBALS['ipbwi']->timeAgo($GLOBALS['ipbwi']->date($post['post_date'],0)).'</div>
					</li>
				';
			}

		}
	}
	$postData .= '</ul>';
	echo
	$before_widget.
	$before_title.
	(($instance['title'] != '') ? $instance['title'] : 'Latest forum posts').
	$after_title.
	$postData.
	$after_widget;
?><!--/dynamic-cached-content--><!-- /mfunc -->