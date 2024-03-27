<?php
define( 'DECO_SOC_SHARING_PATH', dirname( __FILE__ ) . '/' );
define( 'DECO_SOC_SHARING_URL', str_replace( ABSPATH, site_url() . '/', DECO_SOC_SHARING_PATH ) );
if ( ! is_admin() ) {
	wp_register_script( 'deco-soc-sharing', DECO_SOC_SHARING_URL . 'assets/js/deco-soc-sharing.js', array(), false, true );

	wp_enqueue_script( 'deco-soc-sharing' );
}

require_once DECO_SOC_SHARING_PATH . 'deco-soc-sharing.php';