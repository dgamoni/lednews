<?php 
	/**
	 * The Template for displaying page
	 *
	 */
	
get_header(); 
?>

<?php the_post(); ?>


	<div id="content" class="ten columns">
		
		<?php the_title( '<h1 class="entry-title center">', '</h1>' ); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="dp100">

				<div class="clear"></div>
				<div class="entry-content">
					<div class="dp100 ">

						<?php the_content(); ?>
												
						<div class="clear"></div>

						<div class="bottom-text center">
							<p class="text-strong">У Вас есть к нам вопросы или предложения?</p>
							<p>Пишите нам по адресу <a href="mailto:news@lednews.ru" target="_top">news@lednews.ru</a></p>
						</div>

					</div>
				</div>
			</div>
		</div>


	</div> <!-- end content -->


<?php get_footer(); ?>