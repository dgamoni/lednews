<?php 
	/**
	 * The Template for displaying informer page
	 *
	 */
	
get_header(); 
?>

<?php the_post(); ?>


	<div id="content" class="fourteen columns">
		
		<?php the_title( '<h1 class="entry-title center">', '</h1>' ); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			

			
				<div class="entry-content ten columns">
					<div class="dp100 ">
						<?php the_content(); ?>
					</div>
				</div>							
						

				<!-- informer view -->
				<div class="center">
					<script type="text/javascript"> var widget_embed = 'posts';</script>
					<script src="http://test.lednews.ru/widget/wp-widget.js" type="text/javascript"></script>
					<div id="embed-widget-container"></div>
				</div>

				<!-- informer code -->
				
				<div class="entry-content ten columns">
					<h6 class="main-title">Персональный код информера</h6>
					<pre>
					    <xmp><script type="text/javascript"> var widget_embed = 'posts';</script><script src="http://test.lednews.ru/widget/wp-widget.js" type="text/javascript"></script><div id="embed-widget-container"></div></xmp>
					 </pre>
				</div>


				<div class="entry-content">
					<div class="dp100 ">
						<div class="bottom-text center">
							<p class="text-strong">У Вас есть к нам вопросы или предложения?</p>
							<p>Пишите нам по адресу <a href="mailto:news@lednews.ru" target="_top">news@lednews.ru</a></p>
						</div>

					</div>
				</div>
			
		</div>


	</div> <!-- end content -->


<?php get_footer(); ?>