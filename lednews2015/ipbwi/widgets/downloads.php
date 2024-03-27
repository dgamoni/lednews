<!-- mfunc --><!--dynamic-cached-content--><?php

		if(is_array($downloads) && count($downloads) > 0){

		$downloadsData = '<ul class="ipbwi_widget_downloads">';
		foreach($downloads as $download){
			$downloadsData .= '<li class="ipbwi_widget_downloads_entry">';
			
			if($GLOBALS['ipbwi']->getBoardVar('htaccess_mod_rewrite') == 1){
				$downloadsData .= '<div class="ipbwi_widget_downloads_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'files/file/'.$download['file_id'].'-'.$download['file_name_furl'].'/" title="'.$download['file_name'].' '.$download['file_version'].'">'.$download['file_name'].'</a></div>';
			}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'query_string'){
				$downloadsData .= '<div class="ipbwi_widget_downloads_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?files/file/'.$download['file_id'].'-'.$download['file_name_furl'].'/" title="'.$download['file_name'].' '.$download['file_version'].'">'.$download['file_name'].'</a></div>';
			}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'path_info'){
				$downloadsData .= '<div class="ipbwi_widget_downloads_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php/files/file/'.$download['file_id'].'-'.$download['file_name_furl'].'/" title="'.$download['file_name'].' '.$download['file_version'].'">'.$download['file_name'].'</a></div>';
			}
			
			$downloadsData .= '</li>';
		}
		$downloadsData .= '</ul>';

		echo
		$before_widget.
		$before_title.
		(($instance['title'] != '') ? $instance['title'] : '').
		$after_title.
		$downloadsData.
		$after_widget;

	}
?><!--/dynamic-cached-content--><!-- /mfunc -->