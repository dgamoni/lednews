<!-- mfunc --><!--dynamic-cached-content--><?php
	// load data
	$active			= $GLOBALS['ipbwi']->stats->activeCount();
	$onlineMembers	= $GLOBALS['ipbwi']->member->listOnlineMembers(true,'avatars',false,'running_time','DESC','');
	
	$usageData	= '<ul class="ipbwi_widget_usage">
				<li class="ipbwi_widget_usage_usage_activity">
					<div class="ipbwi_widget_usage_activity_list">
						<span class="total">'.$active['total'].'</span> <span class="desc total">'.(($instance['total'] != '') ? $instance['total'] : 'total').':</span>
						<span class="guests">'.$active['guests'].'</span> <span class="desc guests">'.(($instance['guests'] != '') ? $instance['guests'] : 'guests').',</span>
						<span class="members">'.$active['members'].'</span> <span class="desc members">'.(($instance['members'] != '') ? $instance['members'] : 'members').',</span>
						<span class="anon">'.$active['anon'].'</span> <span class="desc anon">'.(($instance['anon'] != '') ? $instance['anon'] : 'anonymous').'</span>
					</div>
				</li>
				<li class="ipbwi_widget_usage_online">
					<div class="desc">'.(($instance['online'] != '') ? $instance['online'] : 'Members Online:').'</div>
					<div class="online_members">'.$onlineMembers.'</div>
				</li>
				</ul>';
	
	echo
	$before_widget.
	$before_title.
	(($instance['title'] != '') ? $instance['title'] : 'Active Users').
	$after_title.
	$usageData.
	$after_widget;
?><!--/dynamic-cached-content--><!-- /mfunc -->