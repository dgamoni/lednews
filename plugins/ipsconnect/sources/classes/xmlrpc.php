<?php

/*
 * @package (Pro) WordPress IPSConnect
 * @brief API Abstraction
 * @version 1.1.2
 * @author Provisionists (based on Marcher Technologies WP IPS Connect)
 * @link https://provisionists.com
 * @copyright Copyright (c) 2014, Provisionists LLC All Rights Reserved
 */

class wpIpbApi {

	static public $settings;

	static public function getResponse($method = '', array $params = array(), $base = '') {
		if (!$method) {
			return FALSE;
		}
		if (!in_array($method, array_keys(self::methods()))) {
			return FALSE;
		}

		if (!is_array(self::$settings)) {
			$settingsClass = ipsConnectSettings::instance();
			self::$settings = & $settingsClass->fetchSettings();
		}
		
		$base = $base ? $base : str_replace('interface/ipsconnect/ipsconnect.php', 'interface/board/json.php', self::$settings['masterUrl']);
		$params['api_key'] = !isset($params['api_key']) ? self::$settings['masterApiKey'] : $params['api_key'];
		$params['api_module'] = !isset($params['api_module']) ? 'ipb' : $params['api_module'];
		$request = @json_encode(array('methodCall' => array('methodName' => $method, 'params' => $params)));
		$context = array(
			'method' => "POST",
			'headers' => array("Content-Type" => "application/json", 'encoding' => 'UTF-8'),
			'body' => $request
		);
		$file = wp_remote_post($base, $context);
		if (is_wp_error($file)) {
			return FALSE;
		}
		$response = @json_decode($file['body'], TRUE);
		if (isset($response['methodResponse']['fault']) && is_array($response['methodResponse']['fault'])) {
			return FALSE;
		}
		return $response['methodResponse'];
	}

	/*
	 * XMLRPC methods
	 * @return array
	 * @access public
	 */

	static public function methods() {
		$methods = array();
		$methods['fetchMember'] = array(
			'used_for' => __('Display Name for Login, Exclude Blog Owner'),
			'defaults' => array(
				'api_module' => 'ipb'
			)
		);
		$methods['fetchAvatar'] = array(
			'used_for' => __('Avatars'),
			'defaults' => array(
				'api_module' => 'mt',
				'unknowable' => true,
			)
		);
		$methods['fetchStats'] = array(
			'used_for' => __('Stats Widget'),
			'defaults' => array(
				'api_module' => 'ipb'
			)
		);
		$methods['fetchForumsOptionList'] = array(
			'used_for' => __('Topics Widget, Forums Widget, Settings Page'),
			'defaults' => array(
				'api_module' => 'ipb'
			)
		);
		$methods['fetchForums'] = array(
			'used_for' => __('Forums Widget'),
			'defaults' => array(
				'forums' => '*',
				'api_module' => 'ipb'
			)
		);

		$methods['fetchGroups'] = array(
			'used_for' => __('Settings Page'),
			'defaults' => array(
				'api_module' => 'mt',
			)
		);
		$methods['fetchTopics'] = array(
			'used_for' => __('Topics Widget'),
			'defaults' => array(
				'order_field' => 'start_date',
				'offset' => '0',
				'limit' => '1',
				'forum_ids' => '*',
				'api_module' => 'mt'
			)
		);
		$methods['fetchPosts'] = array(
			'used_for' => __('Pulling IPB Posts for displaying as WP Comments'),
			'defaults' => array(
				'api_module' => 'mt',
				'unknowable' => true,
			)
		);
		$methods['postTopic'] = array(
			'used_for' => __('Posting WP Posts as IPB Topics'),
			'defaults' => array(
				'api_module' => 'mt',
				'unknowable' => true,
			)
		);
		$methods['postReply'] = array(
			'used_for' => __('Posting WP Comments as IPB Posts'),
			'defaults' => array(
				'api_module' => 'mt',
				'unknowable' => true,
			)
		);
		return apply_filters('wpIpbApiMethods', $methods);
	}

}