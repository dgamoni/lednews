<?php 
	/**
	 * The Template for displaying all single posts
	 *
	 */
	
get_header(); 
?>

<?php the_post(); ?>

<div id="container">
	<div id="content" class="nine columns">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        <?php
			$categories = get_the_category($post->ID);
			$cat_link = get_category_link($categories[0]->cat_ID);
			// $link_forum = get_field('led_news_link');
			$led_news_tid = get_field('led_news_tid');
			$led_news_tid_link = do_shortcode('[lasttopics_single id="'.$led_news_tid.'" link="1" count_comments="0" ]');
			$led_news_tid_count = do_shortcode('[lasttopics_single id="'.$led_news_tid.'" link="0" count_comments="1" ]');
		?>

        <div class="entry-meta">
        	<a class="entry-cat" href="<?php echo $cat_link; ?>">все <?php echo $categories[0]->cat_name; ?></a>
			<span class="entry-date"><abbr class="published" title="<?php the_time('Y-M-D\TH:i:sO'); ?>"><?php the_time('d F в g:i'); ?></abbr></span>
			<span class="meta-sep">&#149;</span>
			<span class="meta-autor"><?php the_author(); ?></span>
        </div>

		<div class="led-social">
			<div data-background-alpha="0.0" data-buttons-color="#ffffff" data-counter-background-color="#ffffff" data-share-counter-size="12" data-top-button="false" data-share-counter-type="separate" data-share-style="10" data-mode="share" data-like-text-enable="false" data-mobile-view="true" data-icon-color="#ffffff" data-orientation="horizontal" data-text-color="#ffffff" data-share-shape="rectangle" data-sn-ids="fb.vk.tw.gp.ps." data-share-size="30" data-background-color="#ffffff" data-preview-mobile="false" data-mobile-sn-ids="fb.vk.tw.wh.ok.gp." data-pid="1349111" data-counter-background-alpha="1.0" data-following-enable="false" data-exclude-show-more="true" data-selection-enable="false" class="uptolike-buttons uptolike-buttons-single" ></div>
			<div class="counter"><?php echo $led_news_tid_count; ?></div>
			<div class="forum_button">
				<a href="<?php echo $led_news_tid_link; ?>" target="_blank">Обсудить на форуме</a>
			</div>
			
			<div class="clear"></div>
		</div>


	</div>
</div>


<div id="container">
	<div id="content" class="nine columns">
		
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="dp100">

				<div class="clear"></div>
				<div class="entry-content">
					<div class="dp100 postlink">



						<?php the_content(); ?>
												
						<div class="clear"></div>
						<!-- <div class="hr"></div> -->

						<div class="led-social">
							<div data-background-alpha="0.0" data-buttons-color="#ffffff" data-counter-background-color="#ffffff" data-share-counter-size="12" data-top-button="false" data-share-counter-type="separate" data-share-style="10" data-mode="share" data-like-text-enable="false" data-mobile-view="true" data-icon-color="#ffffff" data-orientation="horizontal" data-text-color="#ffffff" data-share-shape="rectangle" data-sn-ids="fb.vk.tw.gp.ps." data-share-size="30" data-background-color="#ffffff" data-preview-mobile="false" data-mobile-sn-ids="fb.vk.tw.wh.ok.gp." data-pid="1349111" data-counter-background-alpha="1.0" data-following-enable="false" data-exclude-show-more="true" data-selection-enable="false" class="uptolike-buttons" ></div>
							
							<div class="counter"><?php echo $led_news_tid_count; ?></div>
							<div class="forum_button">
								<a href="<?php echo $led_news_tid_link; ?>" target="_blank">Обсудить на форуме</a>
							</div>
							
							<div class="clear"></div>
						</div>

					</div>
				</div>
			</div>
		</div>

	<!--  banner -->
	<?php dynamic_sidebar( 'banner_map_single' );   ?>

<!-- 	<div class="hr-center"></div>
	<div class="hr"></div> -->
	


	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>