<?php

/*
 * @package (Pro) WordPress IPSConnect
 * @brief DEEEESTROY!!!
 * @version 1.1.3
 * @author Provisionists (based on Marcher Technologies WP IPS Connect)
 * @link https://provisionists.com
 * @copyright Copyright (c) 2014, Provisionists LLC All Rights Reserved
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}
//some init...
$options = array('wpIpsConnect_options', 'ipsConnectStats', 'ipsConnectTopics', 'ipsConnectForums');
global $wpdb;
$mytables = array(
    $wpdb->usermeta => array('wpIpsConnectID', 'wpIpsConnectAvatar'),
    $wpdb->postmeta => array(
		'ipb_tid','ipb_fid','ipb_replies','ipb_synch','ipb_link', 'ipb_fid_custom',
		'ipb_replies_custom','ipb_synch_custom','ipb_link_custom',
	'ipb_link_only', 'ipb_link_only_custom', ),
    $wpdb->commentmeta => array('ipb_posted','ipb_pid'),
);
//DOOOOOO IT!!!
foreach ($options as $o) {
    delete_option($o);
}
foreach ($mytables as $t => $v) {
    $wpdb->query('DELETE FROM ' . $t . ' WHERE meta_key IN (\''.trim(implode("','", $v), ',').'\')');
}
