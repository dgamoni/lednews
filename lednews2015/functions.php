<?php


require_once __DIR__ . '/deco-framework/load.php';



// map catogory status 
function led_map_category_status() {
	$target_category = 'map';
	$change_status_from = 'publish';
	$change_status_to = 'pending';
	$update_query = new WP_Query(array('post_status'=>$change_status_from, 'category_name'=>$target_category, 'posts_per_page'=>-1));

	if($update_query->have_posts()){
	    while($update_query->have_posts()){
	    	if ( ! wp_is_post_revision( $post->ID ) ){

	    		remove_action('acf/save_post', 'led_map_category_status');

		        $update_query->the_post();
		        wp_update_post(array('ID'=>$post->ID, 'post_status'=>$change_status_to));

		        add_action('acf/save_post', 'led_map_category_status');
		        
		     }
	    }
	}

}//end map

//add_action('acf/save_post', 'led_map_category_status', 20);


// Highlight WordPress Search Terms with jQuery
function hls_set_query() {
  $query  = attribute_escape(get_search_query());
 
  if(strlen($query) > 0){
    echo '
      <script type="text/javascript">
        var hls_query  = "'.$query.'";
      </script>
    ';
  }
}
add_action('wp_print_scripts', 'hls_set_query');

//Page Slug Body Class
function add_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
	$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
	}
add_filter( 'body_class', 'add_slug_body_class' );


function get_map_post() {
	    $argsss = array(
        //'post__in'     => $map_post,
        //'post__not_in' => $map_post,
        'category_name'    => 'map',
        'posts_per_page' => 1,
        'post_status'   => 'publish',
        'category'    => '36',
        'order'               => 'DESC',
        'orderby'             => 'date',
        //'orderby' => 'rand',
        //'ignore_sticky_posts' => false
        'fields' => 'ids'
    );

	$map_query = new WP_Query( $argsss );
	wp_reset_query();
	$map_last = $map_query->posts;

	    $argsss2 = array(
	        //'post__in'     => $map_post,
	        'post__not_in' => $map_last,
	        'category_name'    => 'map',
	        'posts_per_page' => 1,
	        'post_status'   => 'publish',
	        'category'    => '36',
	        'order'               => 'DESC',
	        //'orderby'             => 'date',
	        'orderby' => 'rand',
	        //'ignore_sticky_posts' => false
	        'fields' => 'ids'
	    );

	$map_query2 = new WP_Query( $argsss2 );
	wp_reset_query();
	$map_rand = $map_query2->posts;
	$map_def = array_merge($map_rand, $map_last);
	
	return $map_def;
}

function wpse120407_pre_get_posts( $query ) {

    if ( is_home() && $query->is_main_query() ) {


		$map_post = get_field('led-blockmap-fix', 'option' , false);

		if ($map_post) {
		    $argsss = array(
		        //'post__in'     => $map_post,
		        'post__not_in' => $map_post,
		        'posts_per_page' => -1,
		        'post_status'   => 'publish',
		        'category_name'    => 'map',
		        'order'               => 'DESC',
		        'orderby'             => 'date',
		        //'ignore_sticky_posts' => false
		        'fields' => 'ids'
		    );

			$map_query = new WP_Query( $argsss );
			$map_ids = $map_query->posts;
			wp_reset_query();

			$query->set( 'post__not_in' , $map_ids );

		} else {
		    $argsss2 = array(
		        //'post__in'     => $map_post,
		        'post__not_in' => get_map_post(),
		        'posts_per_page' => -1,
		        'post_status'   => 'publish',
		        'category_name'    => 'map',
		        'order'               => 'DESC',
		        'orderby'             => 'date',
		        //'ignore_sticky_posts' => false
		        'fields' => 'ids'
		    );

			$map_query2 = new WP_Query( $argsss2 );
			$map_ids_last = $map_query2->posts;
			wp_reset_query();

			$query->set( 'post__not_in' , $map_ids_last );


		}

        //$query->set( 'category__not_in', array( 36 ) );
        // $query->set( 'post__not_in' , $map_ids );


    }
}
add_action( 'pre_get_posts', 'wpse120407_pre_get_posts' );



function short_title_widget($after = null, $length, $title) {
	$mytitle = $title;
	//$mytitle = wp_trim_words( get_the_title(), 6 , ''); 
	$size = strlen($mytitle);
	if($size>$length) {
		$mytitle = mb_substr($mytitle, 0, $length);
		$mytitle = explode(' ',$mytitle);
		array_pop($mytitle);
		// $mytitle = implode(" ",$mytitle).$after;
		$mytitle = implode(" ",$mytitle);
		$mytitle = wp_trim_words( $mytitle, 6);
		$mytitle = $mytitle.$after;
	} 
	// else {
	// 	 $mytitle = wp_trim_words( $mytitle, 6 );
	// }
	return $mytitle;
}

function short_title($after = null, $length) {
	$mytitle = get_the_title();
	//$mytitle = wp_trim_words( get_the_title(), 6 , ''); 
	$size = strlen($mytitle);
	if($size>$length) {
		$mytitle = mb_substr($mytitle, 0, $length);
		$mytitle = explode(' ',$mytitle);
		array_pop($mytitle);
		// $mytitle = implode(" ",$mytitle).$after;
		$mytitle = implode(" ",$mytitle);
		$mytitle = wp_trim_words( $mytitle, 6);
		$mytitle = $mytitle.$after;
	} 
	// else {
	// 	 $mytitle = wp_trim_words( $mytitle, 6 );
	// }
	return $mytitle;
}

 	function month_full_name_ru( $m ) {

		$month = array(
			"1"  => "Января",
			"2"  => "Февраля",
			"3"  => "Марта",
			"4"  => "апреля",
			"5"  => "Мая",
			"6"  => "Июня",
			"7"  => "Июля",
			"8"  => "Августа",
			"9"  => "Сентября",
			"10" => "Октября",
			"11" => "Ноября",
			"12" => "Декабря"
		);

		return strtr( $m, $month );
	}

 

function deco_get_post_counts( $post_id = 0 ) {
	global $wpdb;

	if ( empty( $post_id ) ) {
		global $post;
		$post_id = $post->ID;
	}

	$current_blog_id = get_current_blog_id();
	$stats           = $wpdb->get_row( "SELECT * FROM $wpdb->de_statistics WHERE post_id = $post_id and blog_id = $current_blog_id" );
	$result          = array(
		'views' => $stats->views_counts,
		'likes' => $stats->votes_sum,
		'fb'    => $stats->fb_counts,
		'vk'    => $stats->vk_counts,
		'tw'    => $stats->tw_counts,
		'ln'    => $stats->ln_counts,
	);

	return $result;
}



// add tumb for gallery
// add_action( 'init', 'create_post_pub' );
// function create_post_pub()
// {
//     add_theme_support( 'post-thumbnails', array( 'ledgallery', 'publicidade' ) );
//     add_theme_support( 'thumbnail', array( 'ledgallery', 'publicidade' ) );
// }

	/*
	This file is part of codium_now. Hack and customize by henri labarre and based on the marvelous sandbox theme	codium_now is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.
	
	*/
	
	if ( ! function_exists( 'codium_now_setup' ) ) :
	function codium_now_setup() {
	    
	    // Make theme available for translation
	    // Translations can be filed in the /languages/ directory
	    //load_theme_textdomain( 'codium-now', get_template_directory() . '/languages' );
	
	    // add_editor_style support
	    //add_editor_style( 'editor-style.css' );
	   
	    //feed
		//add_theme_support('automatic-feed-links');
		
		// add_theme_support( 'html5', array(
		// 	'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
		// ) );
	    
		register_nav_menus( array(
			'primary'   => __( 'Top primary menu', 'codium-now' ),
		) );
		register_nav_menus( array(
			'primary-mobil'   => __( 'Mobil primary menu', 'codium-now' ),
		) );
		register_nav_menus( array(
			'footr'   => __( 'Footer menu', 'codium-now' ),
		) );
	
	    // Post thumbnails support for post
		add_theme_support( 'post-thumbnails', array( 'post', 'ledgallery' ) ); // Add it for posts
		set_post_thumbnail_size( 300, 200, true ); // Normal post thumbnails
	
	    // This theme allows users to set a custom background
		//add_theme_support( 'custom-background' );
	    
	    // This theme allows users to set a custom header image
	    //custom header for 3.4 and compatability for prior version
	
		// $args = array(
		// 	'width'               => 1280,
		// 	'height'              => 250,
		// 	'default-image'       => '',
		// 	'default-text-color'  => '444',
		// 	'wp-head-callback'    => 'codium_now_header_style',
		// 	'admin-head-callback' => 'codium_now_admin_header_style',
	        
		// );
	
		// $args = apply_filters( 'codium_now_custom_header', $args );
	
	 //    add_theme_support( 'custom-header', $args );
	
	
	}
	endif; // codium_now_setup
	add_action( 'after_setup_theme', 'codium_now_setup' );
	
	if ( ! function_exists( 'codium_now_scripts_styles' ) ) :
	function codium_now_scripts_styles() {
	
	    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
	
		// Loads our main stylesheet.
		wp_enqueue_style( 'codium_now-style', get_stylesheet_uri(), array(), '2014-11-22' );

		

	    wp_enqueue_script( 'codium_now-script', get_template_directory_uri() . '/js/menu.js', array( 'jquery' ), '20140630', true );
	
		wp_enqueue_style( 'slick-css', get_template_directory_uri() . '/js/slick.css' );
		wp_enqueue_style( 'slick-theme-css', get_template_directory_uri() . '/js/slick-theme.css' );
		 wp_enqueue_script( 'slick-min-js', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ), '20140630', true );

		wp_enqueue_script( 'orphus', get_template_directory_uri() .'/js/orphus.js', array(), false, true );

		wp_enqueue_style( 'bootstrap-colorpicke-css', get_template_directory_uri() . '/js/colpick.css' );
		wp_enqueue_script( 'bootstrap-colorpicker', get_template_directory_uri() .'/js/colpick.js');

		wp_enqueue_script( 'highlight', get_template_directory_uri() .'/js/jquery.highlight.js');

		

		
		
	}
	endif; // codium_now_scripts_styles
	add_action( 'wp_enqueue_scripts', 'codium_now_scripts_styles' );
	
function led_brending() {
	//$color = get_theme_mod( 'my-custom-color' ); // #FF0000
	// $custom_css = "
	// 	.mycolor{
	// 		background: {$color};
	// 	}";

	$led_brend_on = get_field('led_brend_on', 'option');
	$led_brend_img= get_field('led_brend_img', 'option');
	$led_brend_count_home = get_field('led_brend_count_home', 'option');
	$led_brend_count_page = get_field('led_brend_count_page', 'option');
	$led_brend_color = get_field('led_brend_color', 'option');

	if ($led_brend_on) 	{

		$custom_css = "
			.customize-support{
				background: ".$led_brend_color." url(".$led_brend_img.") 50% 62px no-repeat;
			}
			.ledbanner {
			    height: 325px;
			    visibility: hidden;
			}
			#wrapper, #wrapper2 {
			    background-color: rgb(255, 255, 255);
			}
			#primary > .side-banner{
				padding-top: 30px;
			}
			.slick-slider {
			    margin-bottom: 0px !important; 
			}
			.wrapper-down {
				margin-top: 0px !important;
			    margin-bottom: 0px !important;
				padding-bottom: 20px;
			}
			.sidebar-down {
				padding-top: 30px;
			}
			.led-gallery.container-head {
				max-width: 100% !important;
			}";

		wp_add_inline_style( 'codium_now-style', $custom_css );
	} //end if
}
add_action( 'wp_enqueue_scripts', 'led_brending' );	

function led_brending_add_link() {
	$led_brend_on = get_field('led_brend_on', 'option');
	$led_brend_link = get_field('led_brend_link', 'option');
	if ($led_brend_on) 	{
		echo '<a href="'.$led_brend_link.'" id="bglink" target="_blank">brending link</a>';
	}
}

	
	
	// Set the content width based on the theme's design and stylesheet.
		if ( ! isset( $content_width ) )
		$content_width = 800;

	if ( ! function_exists( 'codium_now_cats_meow' ) ) :
	// For category lists on category archives: Returns other categories except the current one (redundant)
	function codium_now_cats_meow($glue) {
		$current_cat = single_cat_title( '', false );
		$separator = "\n";
		$cats = explode( $separator, get_the_category_list($separator) );
		foreach ( $cats as $i => $str ) {
			if ( strstr( $str, ">$current_cat<" ) ) {
				unset($cats[$i]);
				break;
			}
		}
		if ( empty($cats) )
			return false;
	
		return trim(join( $glue, $cats ));
	}
	endif; // codium_now_cats_meow
		
	if ( ! function_exists( 'codium_now_posted_on' ) ) :
	// data before post
	function codium_now_posted_on() {
		
		printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s.', 'codium-now' ),
			'meta-prep meta-prep-author',
			sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
				get_permalink(),
				esc_attr( get_the_time() ),
				get_the_date()
			),
			sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
				get_author_posts_url( get_the_author_meta( 'ID' ) ),
				sprintf( esc_attr__( 'View all posts by %s', 'codium-now' ), get_the_author() ),
				get_the_author()
			)
		);
	}
	endif;
	
	if ( ! function_exists( 'codium_now_posted_in' ) ) :
	// data after post
	function codium_now_posted_in() {
		// Retrieves tag list of current post, separated by commas.
		$tag_list = get_the_tag_list( '', ', ' );
		if ( $tag_list ) {
			$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'codium-now' );
		} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
			$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'codium-now' );
		} else {
			$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'codium-now' );
		}
		// Prints the string, replacing the placeholders.
		printf(
			$posted_in,
			get_the_category_list( ', ' ),
			$tag_list,
			get_permalink(),
			the_title_attribute( 'echo=0' )
		);
	}
	endif;
	
// register widget
	add_action( 'widgets_init', 'deco_sidebar' );
		function deco_sidebar() {
			
			register_sidebar(array(
				'name' => __( 'Main Right Sidebar верхний'),
				'description' => __( 'Right Sidebar верхний' ),
				'id'            => 'sidebarright',
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '',
				'after_title'   => ''
			));

			 register_sidebar(array(
				'name' => __( 'Main Right Sidebar нижний' ),
				'description' => __( 'Right Sidebar нижний' ),
				'id'            => 'sidebarright-down',
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '',
				'after_title'   => ''
			));

			register_sidebar(
				array(
					'id'            => 'banner_sidebar_center',
					'name'          => __( 'Home Центральный банер' ),
					'description'   => __( 'Виджет для центрального банера' ),
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '',
					'after_title'   => ''
				)
			);
			register_sidebar(
				array(
					'id'            => 'banner_sidebar_up',
					'name'          => __( 'Header Верхний банер' ),
					'description'   => __( 'Виджет для верхнего банера' ),
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '',
					'after_title'   => ''
				)
			);
			register_sidebar(
				array(
					'id'            => 'banner_map_single',
					'name'          => __( 'Single Виджет под постом' ),
					'description'   => __( 'Виджет под постом' ),
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '',
					'after_title'   => ''
				)
			);

		}
// end widget 	


	if ( ! function_exists( 'codium_now_excerpt_more' ) ) :
	// Changes default [...] in excerpt to a real link
	function codium_now_excerpt_more($more) {
	       global $post;
	       $readmore = __(' read more <span class="meta-nav"></span>', 'codium-now' );
		return ' <a href="'. get_permalink($post->ID) . '">' . $readmore . '</a>';
	}
	endif;
	add_filter('excerpt_more', 'codium_now_excerpt_more');
	
	
	// Adds filters for the description/meta content in archives.php
	add_filter( 'archive_meta', 'wptexturize' );
	add_filter( 'archive_meta', 'convert_smilies' );
	add_filter( 'archive_meta', 'convert_chars' );
	add_filter( 'archive_meta', 'wpautop' );
	
	// Remember: the codium_now is for play.
	
	// footer link 

	
if ( ! function_exists( 'codium_now_cleanCut' ) ) :
//clean cut paragraph
function codium_now_cleanCut($string,$length,$cutString = '...')
{
if(strlen($string) <= $length)
{
return $string;
}
$str = substr($string,0,$length-strlen($cutString)+1);
return substr($str,0,strrpos($str,' ')).$cutString;
}
endif;
if ( ! function_exists( 'codium_now_wp_title' ) ) :

//wp title
function codium_now_wp_title( $title, $sep ) {
if ( is_feed() ) {
return $title;
}

global $page, $paged;
// Add the blog name
$title .= get_bloginfo( 'name', 'display' );
// Add the blog description for the home/front page.
$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) ) {
$title .= " $sep $site_description";
}
// Add a page number if necessary:
if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
$title .= " $sep " . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );
}
return $title;
}
endif;
add_filter( 'wp_title', 'codium_now_wp_title', 10, 2 );

//exclude twitter for iframe responsive code
add_filter( 'embed_oembed_html', 'codium_now_tdd_oembed_filter', 10, 4 ) ;

function codium_now_tdd_oembed_filter($html, $url, $attr, $post_ID) {
    if (preg_match_all("/twitter/", $url, $matches, PREG_SET_ORDER)) {
        return $html;
    } else {
        $return = '<div class="video-container '.$url.'">'.$html.'</div>';
        return $return;
    }
}




?>