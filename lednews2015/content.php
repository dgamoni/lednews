		<!-- Begin post -->
		<div class="one-third column posthome">
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
               <?php } else {?>
	               <div id="" class="effects clearfix">
	                    <div class="img nothumbnail">
	                        <img src="<?php echo get_template_directory_uri(); ?>/images/blank.jpg" width="100%" />
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
               <?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>'); ?>
               
               <div class="clear"></div>


               <div class="entry-meta">
					<span class="entry-date"><abbr class="published" title="<?php the_time('Y-M-D\TH:i:sO'); ?>"><?php the_time('d F в g:i'); ?></abbr></span>
					<span class="meta-sep">&#149;</span>
					<span class="meta-autor"><?php the_author(); ?></span>
                </div>


               <div class="entry-content">
				    <p><?php echo codium_now_cleanCut(get_the_excerpt(), 180); ?></p>
					
				<div class="post-meta">
					<?php if ( $decollete_post_views = get_post_meta( $post_id, "decollete_post_views", true ) ) { ?>

						<span class="post-views">
							<?php echo $decollete_post_views; ?>
						</span>

					<?php } ?>

						<span class="post-comments"><?php $comments = get_comment_count( get_the_ID() );
							echo $comments['approved']; ?>
						</span>
				</div>

                </div> <!-- end entry-content   -->

		  </div>
        </div>    
		<!-- End post -->