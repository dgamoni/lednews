		<?php global $num_posts; ?>

		<!-- Begin post -->
		<div class="one-third column posthome num_posts-<?php echo $num_posts; ?>">
	       <div id="post-<?php the_ID() ?>" <?php post_class('dp100') ?>>
               

               <?php if(has_post_thumbnail()){ ?> 
	               <div id="" class="effects clearfix">
		               
		                    <div class="img">
		                        <a href="<?php the_permalink(); ?>" class="led_post_thumbnail"><?php echo get_the_post_thumbnail($post->ID); ?></a>
		                        <div class="overlay">
		                            <a href="<?php the_permalink(); ?>" class="expand"></a>
		                        </div>
		                    </div>

	                </div>
               <?php } else { ?>
	               <div id="" class="effects clearfix">
	                    
	                    <div class="img nothumbnail">
	                    	<?php if (in_category('map' )) { ?>
	                    		<?php $rssmi_url = get_field('rssmi_url', $post->ID); ?>
	                    		<a href="<?php echo $rssmi_url; ?>" class="led_post_thumbnail" target="_blank">
	                        		<img src="<?php echo get_template_directory_uri(); ?>/images/pre_map.png" width="100%" />
	                        	</a>
	                        <?php } else { ?>
	                        	<img src="<?php echo get_template_directory_uri(); ?>/images/blank.jpg" width="100%" />
	                        <?php } ?>	
	                    </div>

	                </div>
               <?php } ?>

               <span class="entry-cat">
	               <?php 
	               the_category(', ');
					// $category = get_the_category();
					// echo $category[0]->cat_name;
	                ?>
                </span>
               <?php //the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>'); ?>
               <h3 class="entry-title">
               		<?php if (in_category('map' )) { ?>
               			<?php $rssmi_url = get_field('rssmi_url', $post->ID); ?>
               			<a href="<?php echo $rssmi_url; ?>" target="_blank"><?php echo short_title('...',110); //echo wp_trim_words( get_the_title(), 6 ); ?></a>
               		<?php } else { ?>
               			<a href="<?php the_permalink(); ?>"><?php echo short_title('...',110); //echo wp_trim_words( get_the_title(), 6 ); ?></a>
               		<?php } ?>
               	</h3>
               <div class="clear"></div>

               <?php $led_map_adress = get_field('led_map_adress', $post->ID); ?>

               <?php if ( (in_category('map' )) && $led_map_adress ) { ?>
               		
               		<div class="entry-meta">
               			<?php echo $led_map_adress; ?>
	                </div>
               <?php } else { ?>
	               <div class="entry-meta">
						<span class="entry-date">
							<abbr class="published" title="<?php the_time('Y-M-D\TH:i:sO'); ?>">
								<?php //the_time('d F в g:i'); ?>
								<?php the_time( 'd ' ); ?>
								<?php echo month_full_name_ru( get_the_time( 'n' ) ); ?>
								<?php  the_time(' в g:i'); ?>
							</abbr>
						</span>
						<span class="meta-sep">&#149;</span>
						<span class="meta-autor"><?php the_author(); ?></span>
	                </div>
                <?php } ?>


               <div class="entry-content">
				    <p><?php echo codium_now_cleanCut(get_the_excerpt(), 180); ?></p>
					
					<div class="post-meta">
						<?php global $post;
						if (
						 //$decollete_post_views = get_post_meta( $post->ID, "decollete_post_views", true ) 
							$decollete_post_views = get_field( "decollete_post_views", get_the_ID() ) 
						) { ?>
						<?php } else $decollete_post_views = 0; ?>

							<span class="post-views">
								<?php echo $decollete_post_views; ?>
							</span>

							<span class="post-comments">
								<?php $comments = get_comment_count( get_the_ID() );
								//echo $comments['approved'];
								// update 6-07-15
								$led_news_tid = get_field('led_news_tid', get_the_ID() );
								$led_news_tid_count = do_shortcode('[lasttopics_single id="'.$led_news_tid.'" link="0" count_comments="1" ]');
								if ($led_news_tid) {
									echo $led_news_tid_count;
								} else {
									echo  $comments['approved'];
								}//end update
								?>
							</span>

							<!-- <span class="post-share"><span class="post-share-text">Поделиться</span></span> -->
							
							<span class="post-share">
								<!-- <a class="post-share-text" href="http://vkontakte.ru/share.php?url=<?php the_permalink(); ?>" target="_blank">Поделиться</a> -->
								<a href="#" class="post-share-text" onclick="deco_soc_sharing_window('http://vkontakte.ru/share.php?url=<?php the_permalink(); ?>','Поделиться'); return false;">Поделиться</a>
							</span>

							
							

					</div>




                </div> <!-- end entry-content   -->

		  </div>
        </div>    
		<!-- End post -->