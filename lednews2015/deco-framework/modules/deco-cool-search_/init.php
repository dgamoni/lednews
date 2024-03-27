<?php
define( 'DECO_COOL_SEARCH_DIR', dirname( __FILE__ ) . '/' );
define( 'DECO_COOL_SEARCH_URL', str_replace( ABSPATH, site_url() . '/', dirname( __FILE__ ) ) . '/' );


require_once DECO_COOL_SEARCH_DIR . 'register-styles-scripts.php';
require_once DECO_COOL_SEARCH_DIR . 'functions.php';
require_once DECO_COOL_SEARCH_DIR . 'ajax.php';