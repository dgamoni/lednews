<!-- mfunc --><!--dynamic-cached-content--><?php
	$topicData = '<ul class="ipbwi_widget_latestTopics">';
	if(is_array($topics) && count($topics) > 0){
		foreach($topics as $topic){
			$user	= $GLOBALS['ipbwi']->member->info($topic['member_id']);
			if($instance['substr'] && intval($instance['substr']) > 0){
				$title = ((strlen($topic['title']) > $instance['substr']) ? substr(strip_tags($topic['title']), 0, $instance['substr']).'...' : $topic['title']);
			}else{
				$title = $topic['title'];
			}
			
			if($GLOBALS['ipbwi']->getBoardVar('htaccess_mod_rewrite') == 1){
				$topicData .= '
					<li class="ipbwi_widget_latestTopics_entry">
						<div class="ipbwi_widget_latestTopics_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'topic/'.$topic['tid'].'-'.$topic['title_seo'].'/" title="'.$topic['title'].'">'.$title.'</a></div>
						<div class="ipbwi_widget_latestTopics_author"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'user/'.$user['member_id'].'-'.$user['members_seo_name'].'/">'.$topic['starter_name'].'</a></div>
						<div class="ipbwi_widget_latestTopics_date" title="'.$GLOBALS['ipbwi']->date($topic['start_date']).'">'.$GLOBALS['ipbwi']->date($topic['start_date'],'%d.%m.%Y').'</div>
					</li>
				';
			}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'query_string'){
				$topicData .= '
					<li class="ipbwi_widget_latestTopics_entry">
						<div class="ipbwi_widget_latestTopics_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?/topic/'.$topic['tid'].'-'.$topic['title_seo'].'/" title="'.$topic['title'].'">'.$title.'</a></div>
						<div class="ipbwi_widget_latestTopics_author"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?/user/'.$user['member_id'].'-'.$user['members_seo_name'].'/">'.$topic['starter_name'].'</a></div>
						<div class="ipbwi_widget_latestTopics_date" title="'.$GLOBALS['ipbwi']->date($topic['start_date']).'">'.$GLOBALS['ipbwi']->date($topic['start_date'],'%d.%m.%Y').'</div>
					</li>
				';
			}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'path_info'){
				$topicData .= '
					<li class="ipbwi_widget_latestTopics_entry">
						<div class="ipbwi_widget_latestTopics_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php/topic/'.$topic['tid'].'-'.$topic['title_seo'].'/" title="'.$topic['title'].'">'.$title.'</a></div>
						<div class="ipbwi_widget_latestTopics_author"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php/user/'.$user['member_id'].'-'.$user['members_seo_name'].'/">'.$topic['starter_name'].'</a></div>
						<div class="ipbwi_widget_latestTopics_date" title="'.$GLOBALS['ipbwi']->date($topic['start_date']).'">'.$GLOBALS['ipbwi']->date($topic['start_date'],'%d.%m.%Y').'</div>
					</li>
				';
			}

		}
	}
	$topicData .= '</ul>';
	echo
	$before_widget.
	$before_title.
	(($instance['title'] != '') ? $instance['title'] : 'Latest forum topics').
	$after_title.
	$topicData.
	(($GLOBALS['ipbwi']->member->isLoggedIn()) ? '<div class="ipbwi_widget_latestTopics_more"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?app=core&module=search&do=new_posts&search_app=forums"><strong>'.(($instance['viewAll'] != '') ? $instance['viewAll'] : 'View all new posts since your last visit!').'</strong></a></div>' : '').
	$after_widget;
?><!--/dynamic-cached-content--><!-- /mfunc -->