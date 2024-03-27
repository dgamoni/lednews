<?php 
	/**
	 * The Template for displaying page
	 *
	 */
	
get_header(); 
?>

<?php the_post(); ?>


	<div id="content" class="ten columns">
		
		

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="dp100">

				<div class="clear"></div>
				<div class="entry-content">
					<div class="dp100 ">

						<div class="error-ico"></div>												
						<h2 class="center">Ошибка 404</h2>

						<div class="bottom-text center">
							<p class="text-strong">Страница перемещена, удалена или никогда не существовала...</p>
							<p><a href="mailto:news@lednews.ru" target="_top">Напишите</a> нам об этом или перейдите на <a href="<?php echo esc_url(home_url( '/' )); ?>">главную.</a></p>
						</div>

					</div>
				</div>
			</div>
		</div>


	</div> <!-- end content -->


<?php get_footer(); ?>