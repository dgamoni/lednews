<?php
define( 'DECO_BRANDING_PATH', dirname( __FILE__ ) . '/' );
define( 'DECO_BRANDING_URL', str_replace( ABSPATH, site_url() . '/', dirname( __FILE__ ) ) . '/' );

require_once DECO_BRANDING_PATH . 'deco-branding.php';

