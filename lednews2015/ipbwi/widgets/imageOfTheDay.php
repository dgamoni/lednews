<!-- mfunc --><!--dynamic-cached-content--><?php
	echo
	$before_widget.
	$before_title.
	(($instance['title'] != '') ? $instance['title'] : 'Image of the Day').
	$after_title;
	
	if($GLOBALS['ipbwi']->getBoardVar('htaccess_mod_rewrite') == 1){
		echo '<div class="ipbwi_widget_imageOfTheDay"><div class="ipbwi_widget_imageOfTheDay_image"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'gallery/image/'.$img['id'].'-'.$img['caption_seo'].'/"><img src="'.$imgURL.'" alt="'.strip_tags($img['description']).'" title="'.strip_tags($img['description']).'" /></a></div>';
	}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'query_string'){
		echo '<div class="ipbwi_widget_imageOfTheDay"><div class="ipbwi_widget_imageOfTheDay_image"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?/gallery/image/'.$img['id'].'-'.$img['caption_seo'].'/"><img src="'.$imgURL.'" alt="'.strip_tags($img['description']).'" title="'.strip_tags($img['description']).'" /></a></div>';
	}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'path_info'){
		echo '<div class="ipbwi_widget_imageOfTheDay"><div class="ipbwi_widget_imageOfTheDay_image"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php/gallery/image/'.$img['id'].'-'.$img['caption_seo'].'/"><img src="'.$imgURL.'" alt="'.strip_tags($img['description']).'" title="'.strip_tags($img['description']).'" /></a></div>';
	}
	
	echo
	'<div class="ipbwi_widget_imageOfTheDay_desc">'.$img['img_desc'].'</div></div>'.
	$after_widget;
?><!--/dynamic-cached-content--><!-- /mfunc -->