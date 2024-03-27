<!-- mfunc --><!--dynamic-cached-content--><?php
		if(is_array($calendar) && count($calendar) > 0){

		$calendarData = '<ul class="ipbwi_widget_calendar">';
		foreach($calendar as $event){
			$calendarData .= '<li class="ipbwi_widget_calendar_entry">';
			
			if($GLOBALS['ipbwi']->getBoardVar('htaccess_mod_rewrite') == 1){
				$calendarData .= '<div class="ipbwi_widget_calendar_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'calendar/event/'.$event['event_id'].'-'.$event['event_title_seo'].'/" title="'.$GLOBALS['ipbwi']->date(strtotime($event['event_start_date'])).' '.$event['event_title'].'">'.$event['event_title'].'</a></div>';
			}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'query_string'){
				$calendarData .= '<div class="ipbwi_widget_calendar_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?calendar/event/'.$event['event_id'].'-'.$event['event_title_seo'].'/" title="'.$GLOBALS['ipbwi']->date(strtotime($event['event_start_date'])).' '.$event['event_title'].'">'.$event['event_title'].'</a></div>';
			}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'path_info'){
				$calendarData .= '<div class="ipbwi_widget_calendar_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php/calendar/event/'.$event['event_id'].'-'.$event['event_title_seo'].'/" title="'.$GLOBALS['ipbwi']->date(strtotime($event['event_start_date'])).' '.$event['event_title'].'">'.$event['event_title'].'</a></div>';
			}
			
			$calendarData .= '</li>';
		}
		$calendarData .= '</ul>';

		echo
		$before_widget.
		$before_title.
		(($instance['title'] != '') ? $instance['title'] : '').
		$after_title.
		$calendarData.
		$after_widget;

	}
?><!--/dynamic-cached-content--><!-- /mfunc -->