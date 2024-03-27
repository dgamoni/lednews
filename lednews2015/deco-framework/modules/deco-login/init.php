<?php
define( 'DECO_LOGIN_STYLE_SCRIPT_VER', '0.3' );
define( 'DECO_LOGIN_PATH', dirname( __FILE__ ) . '/' );
define( 'DECO_LOGIN_URL', str_replace( ABSPATH, site_url() . '/', dirname( __FILE__ ) ) . '/' );

require_once DECO_LOGIN_PATH . 'popups.php';
require_once DECO_LOGIN_PATH . 'deco-login.php';