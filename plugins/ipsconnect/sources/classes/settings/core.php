<?php

/*
 * @package (MT) WordPress IPSConnect
 * @brief Settings CORE Abstraction
 * @version 1.1.1
 * @author Robert Marcher
 * @link http://www.marchertech.com/ Marcher Technologies
 * @copyright Copyright (c) 2013, Marcher Technologies All Rights Reserved
 */

class ipsConnectSettingsCore extends ipsConnectSettings {

	public function init() {
		$this->adminInit();
	}

	/*
	 * Admin Initialization
	 * Add settings
	 * @return void
	 * @access public
	 */

	public function adminInit() {
		register_setting(
				'wpIpsConnect_options', 'wpIpsConnect_options', array(&$this, 'validate')
		);
		add_settings_section(
				'wpIpsConnect_master', __('Master'), array(&$this, 'sectionMaster'), 'wpIpsConnect');
		add_settings_section(
				'wpIpsConnect_slave', __('Slave'), array(&$this, 'sectionSlave'), 'wpIpsConnect');
		add_settings_field(
				'masterUrl', __('Master URL'), array(&$this, 'masterUrl'), 'wpIpsConnect', 'wpIpsConnect_master');
		add_settings_field(
				'masterKey', __('Master Key'), array(&$this, 'masterKey'), 'wpIpsConnect', 'wpIpsConnect_master');
		add_settings_field(
				'masterType', __('Login Type'), array(&$this, 'masterType'), 'wpIpsConnect', 'wpIpsConnect_master');
		add_settings_field(
				'returnUrl', __('Return Url Post-Logout'), array(&$this, 'redirectUrl'), 'wpIpsConnect', 'wpIpsConnect_slave');
		if (!$this->settings['isMultiSite']) {
			add_settings_field(
					'hideAdmin', __('Hide Admin'), array(&$this, 'hideAdmin'), 'wpIpsConnect', 'wpIpsConnect_slave');
		}
		add_settings_field(
				'noRole', __('Force No Role'), array(&$this, 'noRole'), 'wpIpsConnect', 'wpIpsConnect_slave');
		if (!$this->settings['isMultiSite']) {
			add_settings_field(
					'swapUCP', __('Swap UCP'), array(&$this, 'swapUCP'), 'wpIpsConnect', 'wpIpsConnect_slave');
			add_settings_field(
					'userUrl', __('UCP URL'), array(&$this, 'userUrl'), 'wpIpsConnect', 'wpIpsConnect_slave');
		}
	}

	/*
	 * Master Settings Header
	 * @return void echos to screen.
	 * @access public
	 */

	public function sectionMaster() {
		echo '<p>' . __('Configure Details for the Master Application') . '</p>';
	}

	/*
	 * Slave Settings Header
	 * @return void echos to screen.
	 * @access public
	 */

	public function sectionSlave() {
		echo '<p>' . __('Configure Details for the Slave Application') . '</p>';
	}

	/*
	 * Master URL Setting
	 * @return void echos to screen.
	 * @access public
	 */

	public function masterUrl() {
		echo "<input id='masterUrl' name='wpIpsConnect_options[masterUrl]' size='80' type='text' value='{$this->settings['masterUrl']}' /><br />";
		_e('This Is the IPSConnect Master\'s URL gathered from the Master Login Management Page');
	}

	/*
	 * Master Key Setting
	 * @return void echos to screen.
	 * @access public
	 */

	public function masterKey() {
		echo "<input id='masterKey' name='wpIpsConnect_options[masterKey]' size='80' type='password' value='{$this->settings['masterKey']}' /><br />";
		_e('This Is the IPSConnect Master\'s Key gathered from the Master Login Management Page');
	}

	public function masterType() {
		$setting = $this->settings['masterType'];
		$opts = array(
			'username' => __('User Name'),
			'email' => __('E-Mail'),
		);
		if ($this->settings['masterApiKey']) {
			$opts['display_name'] = __('Display Name');
		}
		$out = '';
		$out .= '<select name="wpIpsConnect_options[masterType]">';
		foreach ($opts as $key => $val) {
			$default = ($setting && $setting == $key) ? ' selected="selected"' : '';
			$out .= '<option value="' . $key . '"' . $default . '>' . $val . '</option>';
		}
		$out .= '</select><br />';
		$out .= __('Select the User login type');
		echo $out;
	}

	/*
	 * Edit Profile URL Setting
	 * @return void echos to screen.
	 * @access public
	 */

	public function userUrl() {
		echo "<input id='userUrl' name='wpIpsConnect_options[userUrl]' size='80' type='text' value='{$this->settings['userUrl']}' /><br />";
		_e('This Is the Master User Control Panel URL, can usually be left blank');
		if ($this->settings['masterUrl']) {
			echo '<br />';
			_e('We have Detected your Master User Control Panel URL is');
			echo ' : <strong>' . $this->parseUrl(FALSE, 'ipsConnectProfile') . 'app=core&module=usercp</strong>';
		}
	}

	/*
	 * Logout Redirect URI Setting
	 * @return void echos to screen.
	 * @access public
	 */

	public function redirectUrl() {
		echo "<input id='returnUrl' name='wpIpsConnect_options[returnUrl]' size='80' type='text' value='{$this->settings['returnUrl']}' /><br />";
		_e('This Is the URL The User Will Be returned to after logout');
	}

	/*
	 * Force Role to No Role Setting
	 * @return void echos to screen.
	 * @access public
	 */

	public function noRole() {
		$default = $this->settings['noRole'] ? " checked='checked'" : '';
		echo "<input id='noRole' name='wpIpsConnect_options[noRole]' type='checkbox' value='1'{$default} />&nbsp;&nbsp;";
		_e('New WP users will have \'No role for this site\'');
	}

	/*
	 * Hide Admin bar for external Users Setting.
	 * @return void echos to screen.
	 * @access public
	 */

	public function hideAdmin() {
		$default = $this->settings['hideAdmin'] ? " checked='checked'" : '';
		echo "<input id='hideAdmin' name='wpIpsConnect_options[hideAdmin]' type='checkbox' value='1'{$default} />&nbsp;&nbsp;";
		_e('Hide Admin Links');
		echo '<br />';
		_e('Will Hide all Links to the WP Admin on the frontend if enabled.');
	}

	/*
	 * Swap UCP Url's Setting.
	 * @return void echos to screen.
	 * @access public
	 */

	public function swapUCP() {
		$default = $this->settings['swapUCP'] ? " checked='checked'" : '';
		echo "<input id='swapUCP' name='wpIpsConnect_options[swapUCP]' type='checkbox' value='1'{$default} />&nbsp;&nbsp;";
		_e('Replace WordPress \'My Profile\' Links');
		echo '<br />';
		_e('Will Replace \'My Profile\' links with the Master User Control Panel Link if the User is in the Master');
	}

	/*
	 * Validate Plugin settings input
	 * @param array $input Dirty input.
	 * @return array $newinput Cleaned input.
	 * @access public
	 */

	public function validate($input) {
		$newinput = array();
		$newinput['masterKey'] = trim($input['masterKey']);
		$newinput['masterUrl'] = esc_url_raw(trim($input['masterUrl']), array('http', 'https'));
		$newinput['returnUrl'] = esc_url_raw(trim($input['returnUrl']), array('http', 'https'));
		$newinput['userUrl'] = esc_url_raw(trim($input['userUrl']), array('http', 'https'));
		$newinput['hideAdmin'] = absint($input['hideAdmin']);
		$newinput['noRole'] = absint($input['noRole']);
		$newinput['swapUCP'] = absint($input['swapUCP']);
		$newinput['masterType'] = trim($input['masterType']);
		if ($newinput['masterKey']) {
			if (!preg_match('/^[a-z0-9]{32}$/i', $newinput['masterKey'])) {
				$newinput['masterKey'] = '';
			}
		}
		if (!$newinput['masterUrl']) {
			$newinput['userUrl'] = $newinput['returnUrl'] = '';
		}
		if ($newinput['masterType']) {
			if (!in_array($newinput['masterType'], array('username', 'email', 'display_name'))) {
				$newinput['masterType'] = 'user_name';
			}
			if ($newinput['masterType'] == 'display_name' && !$input['masterApiKey']) {
				$newinput['masterType'] = 'username';
			}
		} else {
			$newinput['masterType'] = 'user_name';
		}
		if ($newinput['hideAdmin'] && ( $newinput['hideAdmin'] > 1 || $newinput['hideAdmin'] < 1 )) {
			$newinput['hideAdmin'] = 0;
		}
		if ($newinput['noRole'] && ( $newinput['noRole'] > 1 || $newinput['noRole'] < 1 )) {
			$newinput['noRole'] = 0;
		}
		if ($newinput['swapUCP'] && ( $newinput['swapUCP'] > 1 || $newinput['swapUCP'] < 1 )) {
			$newinput['swapUCP'] = 0;
		}

		return $newinput;
	}

}
