<?php
/*
* Template Name: Код кнопки
*/
?>
	
<?php get_header(); ?>

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
				<div class="ten columns center">
					<script type="text/javascript"> var widget_embed = 'button'; var size_button = 999; </script>
					<script src="http://test.lednews.ru/widget/wp-widget-button.js" type="text/javascript"></script>
					<div id="embed-widget-container2"></div>
				</div>

				<!-- informer code -->
				
				<div class="entry-content ten columns">
					<h6 class="main-title">Персональный код информера</h6>
					<pre>
					    <xmp><!-- код информера lednews.ru --><script type="text/javascript"> var widget_embed = 'button';var size_button = 1;</script><script src="http://test.lednews.ru/widget/wp-widget-button.js" type="text/javascript"></script><div id="embed-widget-container2"></div><!-- end код информера lednews.ru --></xmp>
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