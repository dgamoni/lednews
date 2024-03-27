<!-- mfunc --><!--dynamic-cached-content--><?php
	// load data
	$stats			= $GLOBALS['ipbwi']->stats->board();
	$birthday		= $GLOBALS['ipbwi']->stats->birthdayMembers();
	
	$last_mem_info	= $GLOBALS['ipbwi']->member->info($stats['last_mem_id']);
	
	$statsData		= '';
	
	$statsData	.= '<ul class="ipbwi_widget_stats">
						<li class="ipbwi_widget_stats_total_topics"><div class="ipbwi_widget_stats_total_topics"><span class="desc">'.(($instance['total_topics'] != '') ? $instance['total_topics'] : 'Total Topics').':</span> <span class="value">'.$stats['total_topics'].'</span></div></li>
						<li class="ipbwi_widget_stats_total_replies"><div class="ipbwi_widget_stats_total_replies"><span class="desc">'.(($instance['total_replies'] != '') ? $instance['total_replies'] : 'Total Posts').':</span> <span class="value">'.$stats['total_replies'].'</span></div></li>
						<li class="ipbwi_widget_stats_member_count"><div class="ipbwi_widget_stats_member_count"><span class="desc">'.(($instance['member_count'] != '') ? $instance['member_count'] : 'Total Members').':</span> <span class="value">'.$stats['mem_count'].'</span></div></li>
';
	if($GLOBALS['ipbwi']->getBoardVar('htaccess_mod_rewrite') == 1){
		$statsData	.= '<li class="ipbwi_widget_stats_last_member"><div class="ipbwi_widget_stats_last_member"><span class="desc">'.(($instance['last_member'] != '') ? $instance['last_member'] : 'Newest Member').':</span> <span class="value"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'user/'.$stats['last_mem_id'].'-'.$last_mem_info['members_seo_name'].'/">'.$stats['last_mem_name'].'</a></span></div></li>';
	}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'query_string'){
		$statsData	.= '<li class="ipbwi_widget_stats_last_member"><div class="ipbwi_widget_stats_last_member"><span class="desc">'.(($instance['last_member'] != '') ? $instance['last_member'] : 'Newest Member').':</span> <span class="value"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?/user/'.$stats['last_mem_id'].'-'.$last_mem_info['members_seo_name'].'/">'.$stats['last_mem_name'].'</a></span></div></li>';
	}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'path_info'){
		$statsData	.= '<li class="ipbwi_widget_stats_last_member"><div class="ipbwi_widget_stats_last_member"><span class="desc">'.(($instance['last_member'] != '') ? $instance['last_member'] : 'Newest Member').':</span> <span class="value"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php/user/'.$stats['last_mem_id'].'-'.$last_mem_info['members_seo_name'].'/">'.$stats['last_mem_name'].'</a></span></div></li>';
	}

	$statsData	.= '<li class="ipbwi_widget_stats_most_count"><div class="ipbwi_widget_stats_most_count"><span class="desc">'.(($instance['most_count'] != '') ? $instance['most_count'] : 'Online At Once Record').':</span> <span class="value">'.$stats['most_count'].' @ '.$GLOBALS['ipbwi']->date($stats['most_date'],'%d.%m.%Y').'</span></div></li>';
	
	if(count($birthday) > 0){
		$statsData .= '<li class="ipbwi_widget_stats_birthday"><div class="desc">'.(($instance['birthday'] != '') ? $instance['birthday'] : 'Members celebrating their birthdays: ').'</div>';
		foreach($birthday as $member){
			if($GLOBALS['ipbwi']->getBoardVar('htaccess_mod_rewrite') == 1){
				$statsData .= '<div class="value"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'user/'.$member['member_id'].'-'.$member['members_seo_name'].'/">'.$member['members_display_name'].'</a></div>';
			}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'query_string'){
				$statsData .= '<div class="value"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?/user/'.$member['member_id'].'-'.$member['members_seo_name'].'/">'.$member['members_display_name'].'</a></div>';
			}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'path_info'){
				$statsData .= '<div class="value"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php/user/'.$member['member_id'].'-'.$member['members_seo_name'].'/">'.$member['members_display_name'].'</a></div>';
			}
		}
		$statsData .= '</li>';
	}
	
	$statsData .= '</ul>';
	
	echo
	$before_widget.
	$before_title.
	(($instance['title'] != '') ? $instance['title'] : 'Statistics').
	$after_title.
	$statsData.
	$after_widget;
?><!--/dynamic-cached-content--><!-- /mfunc -->