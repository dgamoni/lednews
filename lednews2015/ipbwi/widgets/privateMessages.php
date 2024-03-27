<!-- mfunc --><!--dynamic-cached-content--><?php
	if($instance['unreadOnly'] == 1){
		$folder = 'new';
	}else{
		$folder = 'myconvo';
	}
	$topics		= $GLOBALS['ipbwi']->pm->getList($folder,array('limit' => $instance['limit']));
	$topicData	= '<ul class="ipbwi_widget_privateMessages">';
	if(is_array($topics) && count($topics) > 0){
		foreach($topics as $topic){
			if($instance['substr'] && intval($instance['substr']) > 0){
				$pmTitle = ((strlen($topic['mt_title']) > $instance['substr']) ? substr(strip_tags($topic['mt_title']), 0, $instance['substr']).'...' : $topic['mt_title']);
			}else{
				$pmTitle = $topic['mt_title'];
			}
			// retrieve participient name
			if($topic['mt_starter_id'] != $GLOBALS['ipbwi']->member->myInfo['member_id']){
				$participientID = $topic['mt_starter_id'];
			}else{
				$participientID = $topic['mt_to_member_id'];
			}
			$user	= $GLOBALS['ipbwi']->member->info($participientID);
			
			$topicData .= '
					<li class="ipbwi_widget_privateMessages_entry">
						<div class="ipbwi_widget_privateMessages_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?app=members&module=messaging&section=view&do=showConversation&topicID='.$topic['mt_id'].'" title="'.$topic['mt_title'].'">'.$pmTitle.'</a></div>
			';
			
			if($GLOBALS['ipbwi']->getBoardVar('htaccess_mod_rewrite') == 1){
				$topicData .= '
						<div class="ipbwi_widget_privateMessages_author"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'user/'.$user['member_id'].'-'.$user['members_seo_name'].'/">'.$user['members_display_name'].'</a></div>
				';
			}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'query_string'){
				$topicData .= '
						<div class="ipbwi_widget_privateMessages_author"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?/user/'.$user['member_id'].'-'.$user['members_seo_name'].'/">'.$user['members_display_name'].'</a></div>
				';
			}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'path_info'){
				$topicData .= '
						<div class="ipbwi_widget_privateMessages_author"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php/user/'.$user['member_id'].'-'.$user['members_seo_name'].'/">'.$user['members_display_name'].'</a></div>
				';
			}
			$topicData .= '
						<div class="ipbwi_widget_privateMessages_date" title="'.$GLOBALS['ipbwi']->date($topic['map_last_topic_reply']).'">'.$GLOBALS['ipbwi']->date($topic['map_last_topic_reply'],'%d.%m.%Y').'</div>
					</li>
			';
		}
	}
	$topicData .= '</ul>';
	echo
	$before_widget.
	$before_title.
	(($instance['title'] != '') ? $instance['title'] : 'Latest private messages').
	$after_title.
	$topicData.
	(($GLOBALS['ipbwi']->member->isLoggedIn()) ? '<div class="ipbwi_widget_privateMessages_more"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?app=members&module=messaging"><strong>'.(($instance['viewAll'] != '') ? $instance['viewAll'] : 'View all of your private messages!').'</strong></a></div>' : '').
	$after_widget;
?><!--/dynamic-cached-content--><!-- /mfunc -->