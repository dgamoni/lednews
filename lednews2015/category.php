<?php get_header(); ?>

    <div id="container">
    	<div id="content" class="nine columns noajax">
        
        <h6 class="main-title"><?php the_category(); ?></h6>
        
        <?php
        global $num_posts; 
        $num_posts = 0;

        if (have_posts()) : 
    		while (have_posts()) : the_post();
    		$num_posts++;

    		if ( $num_posts == 7) {
            	//get_template_part('banner-center');
                dynamic_sidebar( 'banner_sidebar_center' );
            } 
            // else {
                if(has_post_thumbnail()){
            	   get_template_part('content');
                } else {
                    if (in_category('map' )) {
                        get_template_part('content');
                    } else {
                        get_template_part('content-nothumb');
                    }
                }
            // }
            
    	endwhile; endif; ?>

        <div class="clear"></div>

    	</div>
    	<!-- #content -->
    </div>
    <!-- #container -->

    <!-- правый верхний сайдебар -->
    <?php get_sidebar(); ?>

    <div class="clear"></div>

    <!-- ajax content -->
    <div id="container">
        <div id="content" class="ajax-content" >
        </div>
    </div>
    <div class="clear"></div>
    <!-- end ajax content -->

    <!-- paginate -->
    <?php 
    ajax_pagination($custom_query = false, $inner_class = '.ajax-content', $posts_per_page = 9, $ajax_action = 'ain_ajax_pagination', $post_type = 'post', null );
    ?>

    <div class="fresh_navigation center">
        <?php
        global $wp_query;
        $big = 999999999; // need an unlikely integer
        echo paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $wp_query->max_num_pages,
            'prev_text' => __(''),
            'next_text' => __(''),
            
        ));
        ?>
    </div>
    <!-- end paginate -->


	<!-- slider -->
	<?php get_template_part('led-slider');  ?>



<?php get_footer() ?>