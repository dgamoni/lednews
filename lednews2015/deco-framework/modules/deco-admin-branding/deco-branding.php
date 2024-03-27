<?php

// Прячем все сообщения обновлений
add_action( 'admin_menu', 'deco_branding_hide_admin_notices' );
function deco_branding_hide_admin_notices() {

	remove_action( 'admin_notices', 'update_nag', 3 );
}

// Удаляе "Обновление" меню с админки
add_action( 'admin_menu', 'deco_branding_remove_menus', 102 );
function deco_branding_remove_menus() {

	global $submenu;
	remove_submenu_page( 'index.php', 'update-core.php' );
}


// Отлючаем обновление тем
remove_action( 'load-update-core.php', 'wp_update_themes' );
add_filter( 'pre_site_transient_update_themes', create_function( '$a', "return null;" ) );

// Отключаем обновление плагинов
//remove_action( 'load-update-core.php', 'wp_update_plugins' );
//add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );

// Скрываем версию
//remove_action( 'wp_head', 'wp_generator' );

// WordPress вставляет в исходный код сообщений в RSS
// Запретим ему это делать
//function deco_remove_version_info() {
//
//	return '';
//}

add_filter( 'the_generator', 'deco_remove_version_info' );


function deco_remove_wp_ver_css_js( $src ) {

	if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) ) {
		$src = remove_query_arg( 'ver', $src );
	}

	return $src;
}

add_filter( 'style_loader_src', 'deco_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'deco_remove_wp_ver_css_js', 9999 );

function deco_remove_wp_version_strings( $src ) {

	global $wp_version;
	parse_str( parse_url( $src, PHP_URL_QUERY ), $query );
	if ( ! empty( $query['ver'] ) && $query['ver'] === $wp_version ) {
		$src = remove_query_arg( 'ver', $src );
	}

	return $src;
}

add_filter( 'script_loader_src', 'deco_remove_wp_version_strings' );
add_filter( 'style_loader_src', 'deco_remove_wp_version_strings' );

function deco_remove_dashboard_widgets() {

	global $wp_meta_boxes;

	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] ); // Прямо сейчас
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] ); // Плагины
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] ); // Входящие ссылки
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] ); // Свежие комментарии
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'] ); // Активность

	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] ); // Быстрая публикация
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'] ); // Свежие черновики
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] ); // Блог WordPress
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] ); // Другие новости WordPress
}

add_action( 'wp_dashboard_setup', 'deco_remove_dashboard_widgets' );

function deco_remove_admin_version_message() {

	echo '<script type="text/javascript">';
	echo ';(function($){ $("#wp-version-message, #dashboard_right_now .table_discussion").hide(); })(jQuery);';
	echo '</script>';
}

add_action( 'admin_footer', 'deco_remove_admin_version_message' );


function deco_replace_footer_version() {

	return 'Работает на <a target="_blank" href="http://wordpres.org/">WordPress</a>';
}

//add_filter( 'update_footer', 'deco_replace_footer_version', '1234' );


function deco_remove_footer_admin() {

	echo "Работает на <a href='http://wordpress.org'>WordPress</a>&nbsp;&nbsp;|&nbsp;&nbsp;Разработка сайта&nbsp;—&nbsp;<a href='https://monothemes.com/'>Monothemes</a>";
}

add_filter( 'admin_footer_text', 'deco_remove_footer_admin' );


remove_action( 'wp_head', 'wp_generator' );
//remove_action( 'wp_head', 'feed_links_extra', 3 );
//remove_action( 'wp_head', 'feed_links', 2 );
//remove_action( 'wp_head', 'rsd_link' );
//remove_action( 'wp_head', 'wlwmanifest_link' );
//remove_action( 'wp_head', 'index_rel_link' );
//remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
//remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
//remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
//global $woocommerce;
//remove_action( 'wp_head', 'woocommerce_generator');


function deco_remove_admin_bar_links() {

	global $wp_admin_bar;

	$wp_admin_bar->remove_menu( 'wp-logo' ); // Remove the WordPress logo
	$wp_admin_bar->remove_menu( 'about' ); // Remove the about WordPress link
	$wp_admin_bar->remove_menu( 'wporg' ); // Remove the WordPress.org link
	$wp_admin_bar->remove_menu( 'documentation' ); // Remove the WordPress documentation link
	$wp_admin_bar->remove_menu( 'support-forums' ); // Remove the support forums link
	$wp_admin_bar->remove_menu( 'feedback' ); // Remove the feedback link
	// $wp_admin_bar->remove_menu('site-name');        // Remove the site name menu
//	$wp_admin_bar->remove_menu( 'view-site' ); // Remove the view site link
	//$wp_admin_bar->remove_menu('updates'); // Remove the updates link
//	$wp_admin_bar->remove_menu( 'comments' ); // Remove the comments link
//	$wp_admin_bar->remove_menu( 'new-content' ); // Remove the content link
	//    $wp_admin_bar->remove_menu('w3tc');             // If you use w3 total cache remove the performance link
	//$wp_admin_bar->remove_menu('my-account');       // Remove the user details tab


}

add_action( 'wp_before_admin_bar_render', 'deco_remove_admin_bar_links' );


function deco_unregister_default_wp_widgets() {

	unregister_widget( 'WP_Widget_Pages' );

	unregister_widget('WP_Widget_Calendar');

	unregister_widget( 'WP_Widget_Archives' );

	unregister_widget( 'WP_Widget_Links' );

	unregister_widget( 'WP_Widget_Meta' );

	unregister_widget( 'WP_Widget_Search' );

	//            unregister_widget( 'WP_Widget_Text' );

	unregister_widget( 'WP_Widget_Categories' );

	unregister_widget( 'WP_Widget_Recent_Posts' );

	unregister_widget( 'WP_Widget_Recent_Comments' );

	unregister_widget( 'WP_Widget_RSS' );

	unregister_widget( 'WP_Widget_Tag_Cloud' );
}

add_action( 'widgets_init', 'deco_unregister_default_wp_widgets', 999 );


add_filter( 'login_headerurl', 'deco_login_logo_url' );
function deco_login_logo_url() {

	return get_bloginfo( 'url' );
}

add_filter( 'login_headertitle', 'deco_login_logo_url_title' );
function deco_login_logo_url_title() {

	return home_url();
}


function deco_login_stylesheet() {

	?>
	<link rel="stylesheet" id="custom_wp_admin_css"
	      href="<?php echo DECO_BRANDING_URL . 'assets/css/deco-branding.css'; ?>" type="text/css"
	      media="all" />
<?php
}

//add_action( 'login_enqueue_scripts', 'deco_login_stylesheet' );

