		<!-- Begin post -->
		<div class="one-third column posthome">
	       <div id="post-<?php the_ID() ?>" <?php post_class('dp100') ?>>
               

               <span class="entry-cat">
	               <?php 
	               the_category(', ');
					// $category = get_the_category();
					// echo $category[0]->cat_name;
	                ?>
                </span>
               <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>'); ?>
               
               <div class="clear"></div>


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


               <div class="entry-content">
				    <p><?php //echo codium_now_cleanCut(get_the_excerpt(), 180); ?></p>
					
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