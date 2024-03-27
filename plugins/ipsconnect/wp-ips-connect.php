<?php

/*
  Plugin Name: (Pro) WordPress IPSConnect
  Description: IPS Connect WordPress SSO
  Version: 1.1.3
  Author: Provisionists
  Author URI: http://www.provisionists.com/
 * @package (Pro) WordPress IPSConnect
 * @version 1.1.3
 * @author Provisionists
 * @link http://www.provisionists.com
 * @copyright Copyright (c) 2013, Provisionists LLC All Rights Reserved
 */
require_once 'wp-ips-connect-core.php';
$ipsConnect = wpIpsConnect::instance(); //request the instance.
/*
 * sighs, this is NOT how we roll in IPB
 * mutters, overload wp_logout
 * so I have the user ips connect_id if we livey.
 */
if ($ipsConnect->isEnabled()) {
    function wp_logout() {
	$id = get_current_user_id();
	wp_clear_auth_cookie();
	do_action('wp_logout', $id);
    }
}
