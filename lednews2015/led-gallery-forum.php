<?php
/*
 * gallery
 * 
 */
?>
	
	<div id="wrapper2" class="container">
		<h6 class="main-title">Новое в галерее</h6>
	</div>

	<div id="lasttopics_gallery" class="led-gallery container-head variable-width">

		<?php $count_loop = get_field('led-gallery-count', 'option'); ?>
		<?php echo do_shortcode('[lasttopics_gallery count="'.$count_loop.'"]'); ?>
		
	</div>

