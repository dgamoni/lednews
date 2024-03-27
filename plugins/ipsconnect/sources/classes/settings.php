<?php

/*
 * @package (Pro) WordPress IPSConnect
 * @brief Settings Abstraction
 * @version 1.1.2
 * @author Provisionists (based on Marcher Technologies WP IPS Connect)
 * @link https://provisionists.com
 * @copyright Copyright (c) 2014, Provisionists LLC All Rights Reserved
 */

class ipsConnectSettings {

	protected $settings = array();
	static protected $ipsConnectVersion = '1.1.1';
	static private $instance;

	final public function &fetchSettings() {
		return $this->settings;
	}

	final protected function __construct() {
		
	}

	final protected function __clone() {
		
	}

	final static public function instance() {
		if (self::$instance === NULL) {
			$basePath = wpIpsConnect::instance()->basePath . 'sources/classes/settings/';
			require_once $basePath . 'core.php';
			require_once $basePath . 'xmlrpc.php';
			require_once $basePath . 'topics.php';
			self::$instance = new ipsConnectSettingsTopics();
			self::$instance->_init();
		}
		return self::$instance;
	}

	protected function _init() {
		$this->settings['ipsConnectOn'] = FALSE;
		$this->settings['isMultiSite'] = is_multisite();
		if ($this->settings['isMultiSite']) {
			switch_to_blog(1); //force master MU.
		}
		$options = get_option('wpIpsConnect_options');
		if (is_array($options)) {
			$this->settings = array_merge($this->settings, $options);
		}
		if (!$this->settings['noRole']) {
			$this->settings['ipsConnectRole'] = get_option('default_role');
		}
		if ($this->settings['isMultiSite']) {
			restore_current_blog(); //force it back.
			$this->settings['swapUCP'] = $this->settings['hideAdmin'] = 0; //die!
		}
	}

	public function init() {
		return;
	}

	public function validate($input) {
		return $input;
	}

	public function DB() {
		global $wpdb;
		return $wpdb;
	}

	/*
	 * Add settings Page
	 * @return void
	 * @access public
	 */

	public function addSettings() {
		add_options_page(
				__('(MT) WordPress IPSConnect'), __('(MT) WP IPSConnect'), 'manage_options', 'wpIpsConnect', array(&$this, 'display')
		);
	}

	/*
	 * Options page
	 * @return void echos to screen.
	 * @access public
	 */

	public function display() {
		$myPage = '';
		$myPage .= '<div class="wrap"><div class="icon32" id="icon-plugins"><br /></div><h2>';
		$myPage .= $this->_copyText();
		$myPage .= ($this->settings['ipsConnectOn'] ? '<span class="add-new-h2" style="color:green;">' . __('ENABLED') : '<span class="add-new-h2" style="color:red;">' . __('DISABLED')) . '</span>';
		$myPage .= ($this->settings['ipsConnectOn'] && $this->settings['masterApiKey']) ? '<a href="' . ((isset($_REQUEST['do']) && $_REQUEST['do'] == 'debug') ? $this->parseUrl('/wp-admin/options-general.php?page=wpIpsConnect') : $this->parseUrl('/wp-admin/options-general.php?page=wpIpsConnect&do=debug')) . '" class="add-new-h2" style="color:green;">' . __('API ENABLED') . '</a>' : '<span class="add-new-h2" style="color:red;">' . __('API DISABLED') . '</span>';
		$myPage .= '</h2>';
		echo $myPage;
		$this->showSettings();
		echo $this->_copyText() . '<br />';
		echo 'Copyright &copy; 2013 <a href="http://www.marchertech.com" target="_blank">Marcher Technologies</a></div>';
	}

	/*
	 * .....
	 * @return string
	 * @access private
	 */

	private function _copyText() {
		return __('(MT) WordPress IPSConnect') . ' ' . self::$ipsConnectVersion;
	}

	public function showSettings() {
		_e('Configure Details for WordPress IPSConnect');
		echo '<form action="options.php" method="post">';
		settings_fields('wpIpsConnect_options');
		do_settings_sections('wpIpsConnect');
		echo '<br /><input name="Submit" type="submit" value="';
		_e('Save Changes');
		echo '" /></form><br />';
	}

	/*
	 * Magicky parse url method
	 */

	public function parseUrl() {
		$url = '';
		$method = '';
		$args = func_get_args();
		$multi = $this->settings['isMultiSite'];
		if (isset($args[1]) && $args[1]) {
			switch ($args[1]) {
				case 'admin':
				case 'profile':
					$method = 'admin_url';
					break;
				case 'logout':
					$method = 'wp_logout_url';
					break;
				case 'ipsConnectProfile';
					return ( isset($args[2]) && $args[2] && $this->settings['userUrl']) ? $this->settings['userUrl'] : str_replace('ipsconnect.php', 'index.php?', str_replace('interface/ipsconnect/', '', $this->settings['masterUrl'])) . 'app=core&module=usercp';
					break;
				case 'ipsConnectUser';
					return str_replace('ipsconnect.php', 'index.php?', str_replace('interface/ipsconnect/', '', $this->settings['masterUrl'])) . 'showuser=' . intval($args[0]);
					break;
				case 'ipsConnectUpload':
					return (strpos($args[0], 'http') === 0) ? $args[0] : ( ( isset($args[2]) && $args[2] && $this->settings['uploadsUrl']) ? $this->settings['uploadsUrl'] . $args[0] : str_replace('ipsconnect.php', 'uploads/', str_replace('interface/ipsconnect/', '', $this->settings['masterUrl'])) . $args[0]);
					break;
				case 'ipsConnectRegister';
					return str_replace('interface/ipsconnect/ipsconnect.php', 'index.php?', $this->settings['masterUrl']) . 'app=core&module=global&section=register';
					break;
				case 'ipsConnectPass';
					return str_replace('interface/ipsconnect/ipsconnect.php', 'index.php?', $this->settings['masterUrl']) . 'app=core&module=global&section=lostpass';
					break;
			}
		} else {
			if ($multi) {
				$method = 'network_site_url';
			} else {
				$method = 'site_url';
			}
		}
		if (function_exists($method)) {
			if (isset($args[0]) && $args[0]) {
				$url = $method($args[0]);
			} else {
				$url = $method();
			}
		}
		return $url;
	}

}