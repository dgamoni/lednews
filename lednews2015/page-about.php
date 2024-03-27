<?php 
	/**
	 * The Template for displaying page
	 *
	 */
	
get_header(); 
?>

<?php the_post(); ?>


	<div id="content" class="ten columns">
		
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="dp100">

				<div class="clear"></div>
				<div class="entry-content">
					<div class="dp100 about-content">

						<?php the_content(); ?>

						<!-- about block -->
						<div class="one-third column posthome center">
							<h3>Руководитель проекта</h3>
							<?php 
							$led_about_foto1 = get_field('led_about_foto1');
							$led_about_text1 = get_field('led_about_text1');
							$params = array( 'height' => 300 ); 
							?>
							<img src="<?php echo $led_about_foto1['url']; ?>"/>
							<p><?php echo $led_about_text1; ?></p>
						</div>
						<div class="one-third column posthome center">
							<h3>Главный редактор</h3>
							<?php 
							$led_about_foto2 = get_field('led_about_foto2');
							$led_about_text2 = get_field('led_about_text2');
							$params = array( 'height' => 300 ); 
							?>
							<img src="<?php echo $led_about_foto2['url']; ?>"/>
							<p><?php echo $led_about_text2; ?></p>
						</div>
						
						<div class="clear"></div>
						
						<h3 class="center">Профессиональные журналисты</h3>
						
						<?php
						if( have_rows('led_about_loop') ):
						    while ( have_rows('led_about_loop') ) : the_row();
								$led_about_foto_loop = get_sub_field('led_about_loop_foto');
								$led_about_text_loop = get_sub_field('led_about_loop_text');
								?>
								<div class="one-third column posthome center">
									<img src="<?php echo $led_about_foto_loop['url']; ?>"/>
									<p><?php echo $led_about_text_loop; ?></p>
								</div>
								<?php
						    endwhile;
						endif;
						?>
						<!-- end about -->
												
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