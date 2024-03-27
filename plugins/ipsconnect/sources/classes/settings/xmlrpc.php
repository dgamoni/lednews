<?php

/*
 * @package (MT) WordPress IPSConnect
 * @brief Settings API Abstraction
 * @version 1.1.1
 * @author Robert Marcher
 * @link http://www.marchertech.com/ Marcher Technologies
 * @copyright Copyright (c) 2013, Marcher Technologies All Rights Reserved
 */

class ipsConnectSettingsXmlRpc extends ipsConnectSettingsCore {

	protected function _init() {
		parent::_init();
		$this->settings['ipsconnect_widgets'] = $this->widgets();
		$this->settings['xmlrpc_enabled'] = TRUE;//deprecated in favor of json
	}

	public function adminInit() {
		parent::adminInit();
		if (!$this->settings['xmlrpc_enabled']) {
			return;
		}

		add_settings_section(
				'wpIpsConnect_xmlRpc', __('API'), array(&$this, 'sectionXmlRpc'), 'wpIpsConnect');
		add_settings_field(
				'masterApiKey', __('Master API Key'), array(&$this, 'apiKey'), 'wpIpsConnect', 'wpIpsConnect_xmlRpc');
		add_settings_field(
				'masterCachePeriod', __('Minimum Widget Cache Period'), array(&$this, 'cachePeriod'), 'wpIpsConnect', 'wpIpsConnect_xmlRpc');
		add_settings_field(
				'masterDisableWidgets', __('Disabled Widgets'), array(&$this, 'disableWidgets'), 'wpIpsConnect', 'wpIpsConnect_xmlRpc');
		if ($this->settings['masterApiKey'] && $this->settings['ipsConnectOn']) {
			add_settings_field(
					'slaveDisableGroups', __('SSO/Login Guest Groups'), array(&$this, 'guestGroups'), 'wpIpsConnect', 'wpIpsConnect_xmlRpc');
			add_settings_field(
					'slaveAlwaysSsoOwner', __('Exclude Blog Owner'), array(&$this, 'ssoOwner'), 'wpIpsConnect', 'wpIpsConnect_xmlRpc');
		}
	}

	/*
	 * Slave SSO/Login guest groups Setting
	 * @return void echos to screen.
	 * @access public
	 */

	public function guestGroups() {
		$groups = wpIpsConnect::instance()->getXmlRpc('fetchGroups', array('api_module' => 'mt'));
		if (is_array($groups) && count($groups)) {
			$_g = $this->settings['slaveDisableGroups'] ? explode(',', $this->settings['slaveDisableGroups']) : array();
			$html = '';
			$html .= "<select id='slaveDisableGroups' name='wpIpsConnect_options[slaveDisableGroups][]' multiple='multiple'>";
			foreach ($groups as $g) {
				if ($g['g_access_cp'] || $g['g_is_guest_group']) {
					continue;
				}
				$default = in_array($g['g_id'], $_g) ? " selected='selected'" : '';
				$html .= "<option value='{$g['g_id']}'{$default}>{$g['g_title']}</option>";
			}
			$html .= "</select><br />";
			echo $html;
			_e('Will force WP to treat the selected groups as guests');
		}
	}

	/*
	 * Master XML-RPC Header
	 * @return void echos to screen.
	 * @access public
	 */

	public function sectionXmlRpc() {
		echo '<p>' . __('Configure Details for the Master API User') . '</p>';
	}

	/*
	 * Master XML-RPC API Key Setting
	 * @return void echos to screen.
	 * @access public
	 */

	public function apiKey() {
		echo "<input id='masterApiKey' name='wpIpsConnect_options[masterApiKey]' size='80' type='password' value='{$this->settings['masterApiKey']}' /><br />";
		_e('This Is the Master\'s API User Key gathered from the IPB Master Admin Panel > System > Tools & Settings  >  API Users Page');
	}

	/*
	 * Slave Allowed SSO/login groups Setting
	 * @return void echos to screen.
	 * @access public
	 */

	public function disableWidgets() {
		$widgets = $this->settings['masterDisableWidgets'];
		$widgets = $widgets ? explode(',', $widgets) : array();
		$html = '';
		$html .= "<select id='masterDisableWidgets' name='wpIpsConnect_options[masterDisableWidgets][]' multiple='multiple'>";
		foreach ($this->widgets() as $widget) {
			$default = ($widgets && in_array($widget, $widgets)) ? " selected='selected'" : '';
			$html .= "<option value='{$widget}'{$default}>" . ucfirst($widget) . "</option>";
		}
		$html .= "</select>";
		echo $html;
	}

	/*
	 * Widgets
	 * @return array
	 * @access public
	 */

	public function widgets() {
		return apply_filters('ipsConnectWidgets', array('stats', 'topics', 'forums'));
	}

	public function ssoOwner() {
		$default = $this->settings['slaveAlwaysSsoOwner'] ? " checked='checked'" : '';
		echo "<input id='slaveAlwaysSsoOwner' name='wpIpsConnect_options[slaveAlwaysSsoOwner]' type='checkbox' value='1'{$default} />&nbsp;&nbsp;";
		_e('Always SSO/Login Blog Owner regardless of SSO/Login Guest Groups selected above?');
	}

	/*
	 * Master Widget Cache Period Minimum Setting
	 * @return void echos to screen.
	 * @access public
	 */

	public function cachePeriod() {
		echo "<input id='masterCachePeriod' name='wpIpsConnect_options[masterCachePeriod]' size='80' type='text' value='{$this->settings['masterCachePeriod']}' /><br />";
		_e('Minimum Cache Period For All Widgets');
	}

	/*
	 * Validate Plugin settings input
	 * @param array $input Dirty input.
	 * @return array $newinput Cleaned input.
	 * @access public
	 */

	public function validate($input) {
		$newinput = parent::validate($input);
		$newinput['masterApiKey'] = trim($input['masterApiKey']);
		$newinput['masterCachePeriod'] = absint($input['masterCachePeriod']);
		$newinput['masterDisableWidgets'] = is_array($input['masterDisableWidgets']) ? implode(',', $input['masterDisableWidgets']) : '';
		$newinput['slaveDisableGroups'] = is_array($input['slaveDisableGroups']) ? implode(',', $input['slaveDisableGroups']) : '';
		$newinput['slaveAlwaysSsoOwner'] = absint($input['slaveAlwaysSsoOwner']);
		if ($newinput['masterApiKey']) {
			if (!preg_match('/^[a-z0-9]{32}$/i', $newinput['masterApiKey'])) {
				$newinput['masterApiKey'] = '';
			}
		}
		return $newinput;
	}

	/*
	 * Options page
	 * @return void echos to screen.
	 * @access public
	 */

	public function showSettings() {
		if ($this->settings['masterApiKey'] && $this->settings['ipsConnectOn'] && isset($_REQUEST['do']) && $_REQUEST ['do'] == 'debug') {
			$html = '';
			$html .= __('Debug WordPress IPSConnect API Methods');
			$html .= '<table class="wp-list-table widefat plugins">';
			$html .= '<thead><tr>';
			$html .= '<th class="manage-column column-name" scope="col">';
			$html .= __('Method');
			$html .= '</th>';
			$html .= '<th class="manage-column column-name" scope="col">';
			$html .= __('API Module');
			$html .= '</th>';
			$html .= '<th class="manage-column column-description" scope="col">';
			$html .= __('Used for');
			$html .= '</th>';
			$html .= '<th class="manage-column column-description" scope="col">';
			$html .= __('Debug Status');
			$html .= '</th>';
			$html .= '</tr></thead>';
			foreach (wpIpsConnect::instance()->getXmlRpc('methods') as $key => $value) {
				$unknowable = isset($value['defaults']['unknowable']) ? TRUE : FALSE;
				$toTest = $unknowable ? TRUE : wpIpsConnect::instance()->getXmlRpc($key, $value['defaults']);
				$html .= '<tr' . ($toTest ? ' class="active"' : '') . '>';
				$html .= '<td class="plugin-title">';
				$html .= $key;
				$html .= '</td>';
				$html .= '<td class="plugin-title">';
				$html .= $value['defaults']['api_module'];
				$html .= '</td>';
				$html .= '<td class="plugin-title">';
				$html .= $value['used_for'];
				$html .= '</td>';
				$html .= '<td class="plugin-title"><strong>';
				$html .= $toTest ? ($unknowable ? '<span style="color:grey;">' . __('UNKNOWN') . '</span>' : '<span style="color:green;">' . __('OK') . '</span>' ) : '<span style="color:red;">' . __('FAIL') . '</span>';
				$html .= '</strong></td></tr>';
			}
			$html .= '</table><br />';
			echo $html;
		} else {
			parent::showSettings();
		}
	}

}

