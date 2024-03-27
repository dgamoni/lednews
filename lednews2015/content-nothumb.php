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

							<span class="post-share"><span class="post-share-text">Поделиться</span></span>
							<!-- uptolike -->
							<!-- <div data-background-alpha="0.0" data-buttons-color="#FFFFFF" data-counter-background-color="#ffffff" data-share-counter-size="12" data-top-button="false" data-share-counter-type="disable" data-share-style="1" data-mode="share" data-like-text-enable="false" data-mobile-view="true" data-icon-color="#ffffff" data-orientation="horizontal" data-text-color="#000000" data-share-shape="rectangle" data-sn-ids="fb.vk.tw.ok." data-share-size="20" data-background-color="#ffffff" data-preview-mobile="false" data-mobile-sn-ids="fb.vk.tw.wh.ok.gp." data-pid="1392766" data-counter-background-alpha="1.0" data-following-enable="false" data-exclude-show-more="true" data-selection-enable="false" class="uptolike-buttons uptolike-buttons-content" ></div> -->

					</div>


                </div> <!-- end entry-content   -->

		  </div>
        </div>    
		<!-- End post -->