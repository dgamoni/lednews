<!-- mfunc --><!--dynamic-cached-content--><?php
	$imgData = '<ul class="ipbwi_widget_latestImages">';
	foreach($gallery as $img){
		$user		= $GLOBALS['ipbwi']->member->info($img['image_member_id']);
		
		$imgData .= '
			<li class="ipbwi_widget_latestImages_entry">
		';
		
		if($GLOBALS['ipbwi']->getBoardVar('htaccess_mod_rewrite') == 1){
			$imgData .= '
			<div class="ipbwi_widget_latestImages_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'gallery/image/'.$img['image_id'].'-'.$img['image_caption_seo'].'/"><img src="'.$GLOBALS['ipbwi']->gallery->url.$img['image_directory'].'/tn_'.$img['image_masked_file_name'].'" alt="'.strip_tags($img['image_description']).'" title="'.strip_tags($img['image_description']).'" /></a></div>
			<div class="ipbwi_widget_latestImages_author"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'user/'.$user['member_id'].'-'.$user['members_seo_name'].'/">'.$user['members_display_name'].'</a></div>
			';
		}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'query_string'){
			$imgData .= '
			<div class="ipbwi_widget_latestImages_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?/gallery/image/'.$img['image_id'].'-'.$img['image_caption_seo'].'/"><img src="'.$GLOBALS['ipbwi']->gallery->url.$img['image_directory'].'/tn_'.$img['image_masked_file_name'].'" alt="'.strip_tags($img['image_description']).'" title="'.strip_tags($img['image_description']).'" /></a></div>
			<div class="ipbwi_widget_latestImages_author"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?/user/'.$user['member_id'].'-'.$user['members_seo_name'].'/">'.$user['members_display_name'].'</a></div>
			';
		}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'path_info'){
			$imgData .= '
			<div class="ipbwi_widget_latestImages_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php/gallery/image/'.$img['image_id'].'-'.$img['image_caption_seo'].'/"><img src="'.$GLOBALS['ipbwi']->gallery->url.$img['image_directory'].'/tn_'.$img['image_masked_file_name'].'" alt="'.strip_tags($img['image_description']).'" title="'.strip_tags($img['image_description']).'" /></a></div>
			<div class="ipbwi_widget_latestImages_author"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php/user/'.$user['member_id'].'-'.$user['members_seo_name'].'/">'.$user['members_display_name'].'</a></div>
			';
		}

		$imgData .= '
				<div class="ipbwi_widget_latestImages_date" title="'.$GLOBALS['ipbwi']->date($img['image_idate']).'">'.$GLOBALS['ipbwi']->date($img['image_idate'],'%d.%m.%Y').'</div>
			</li>
		';
	}
	$imgData .= '</ul>';
	echo
	$before_widget.
	$before_title.
	(($instance['title'] != '') ? $instance['title'] : 'Latest gallery images').
	$after_title.
	$imgData.
	'<div class="ipbwi_widget_latestImages_more"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?autocom=gallery&req=stats&op=newest"><strong>'.(($instance['viewAll'] != '') ? $instance['viewAll'] : 'View all new images!').'</strong></a></div>'.
	$after_widget;
?><!--/dynamic-cached-content--><!-- /mfunc -->