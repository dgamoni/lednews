<?php

/*
  * @package (Pro) WordPress IPSConnect
 * @brief Settings Topics Abstraction
 * @author Provisionists (based on Marcher Technologies WP IPS Connect)
 * @link https://provisionists.com
 * @copyright Copyright (c) 2014, Provisionists LLC All Rights Reserved
 */

class ipsConnectSettingsTopics extends ipsConnectSettingsXmlRpc {

	public function adminInit() {
		parent::adminInit();
		if (!$this->settings['xmlrpc_enabled'] || !$this->settings['masterApiKey'] || !$this->settings['ipsConnectOn']) {
			return;
		}
		add_settings_section(
				'wpIpsConnect_post', __('API Posting Defaults'), array(&$this, 'sectionPost'), 'wpIpsConnect');
		add_settings_field(
				'slavePostForum', __('Post to Forum'), array(&$this, 'postForumDefault'), 'wpIpsConnect', 'wpIpsConnect_post');
		add_settings_field(
				'slavePostTopic', __('Post to Topic'), array(&$this, 'postTopicDefault'), 'wpIpsConnect', 'wpIpsConnect_post');
		add_settings_field(
				'slaveSynchTopic', __('Synch Topic'), array(&$this, 'synchTopicDefault'), 'wpIpsConnect', 'wpIpsConnect_post');
		add_settings_field(
				'slaveLinkText', __('Post Link'), array(&$this, 'linkTopicDefault'), 'wpIpsConnect', 'wpIpsConnect_post');
		add_settings_field(
				'slaveLinkOnly', __('Only Link'), array(&$this, 'linkOnlyDefault'), 'wpIpsConnect', 'wpIpsConnect_post');
		add_settings_field(
				'slaveForceDefaults', __('Force Defaults'), array(&$this, 'forceDefaults'), 'wpIpsConnect', 'wpIpsConnect_post');
		add_settings_field(
				'slavePostAsMember', __('Topic Starter'), array(&$this, 'postAsMember'), 'wpIpsConnect', 'wpIpsConnect_post');
		add_settings_field(
				'slavePostAsGuest', __('Guest Commenter'), array(&$this, 'postAsGuest'), 'wpIpsConnect', 'wpIpsConnect_post');
	}

	public function postForumDefault() {
		$forum = intval($this->settings['slavePostForum']);
		$formable = '';
		$stats = wpIpsConnect::instance()->getXmlRpc('fetchForumsOptionList');
		if (isset($stats['forumList'])) {
			$formable .= '<select id="slavePostForum" name="wpIpsConnect_options[slavePostForum]">';
			$formable .= "<option value='0'>--" . __('No Default Forum') . "--</option>";
			$selectBox = $stats['forumList'];
			if ($forum) {
				$selectBox = str_replace('value="' . $forum, 'selected="selected" value="' . $forum, $selectBox);
			}
			$formable .= $selectBox;
			$formable .= '</select><br />';
			$formable .= __('Post an IPB topic for every WP post made in the selected forum by default.');
			$formable .= '<br />';
			$formable .= __('Can be overriden by Post.');
			echo $formable;
		}
	}

	/*
	 * Master XML-RPC Posting Defaults Header
	 * @return void echos to screen.
	 * @access public
	 */

	public function sectionPost() {
		echo '<p>' . __('Configure Defaults for the Master XML-RPC Posting') . '</p>';
	}

	public function postTopicDefault() {
		$default = $this->settings['slavePostTopic'] ? " checked='checked'" : '';
		echo "<input id='slavePostTopic' name='wpIpsConnect_options[slavePostTopic]' type='checkbox' value='1'{$default} />&nbsp;&nbsp;";
		_e('Post Comments as Replies to the Topic by default?');
	}

	public function synchTopicDefault() {
		$default = $this->settings['slaveSynchTopic'] ? " checked='checked'" : '';
		echo "<input id='slaveSynchTopic' name='wpIpsConnect_options[slaveSynchTopic]' type='checkbox' value='1'{$default} />&nbsp;&nbsp;";
		_e('Synch Post to Topic by default?');
	}

	public function linkTopicDefault() {
		$link = $this->settings['slaveLinkText'] ? $this->settings['slaveLinkText'] : '';
		if ($link) {
			$link = esc_textarea($link);
		}
		echo <<<WPHTML
WPHTML;
		_e("Default Link to post format, if given, must include {url} which will be replaced with the post url, the result will be appended to the post body.");
		echo <<<WPHTML
			    <br />
<textarea class="wp-editor-area" name="wpIpsConnect_options[slaveLinkText]" id="wpIpsConnect_options[slaveLinkText]" cols="100" style="height: 100px; resize: none;">{$link}</textarea>
<br />
WPHTML;
		_e('HTML Allowed.');
		echo <<<WPHTML
</p>
WPHTML;
	}

	public function linkOnlyDefault() {
		$default = $this->settings['slaveLinkOnly'] ? " checked='checked'" : '';
		echo "<input id='slaveLinkOnly' name='wpIpsConnect_options[slaveLinkOnly]' type='checkbox' value='1'{$default} />&nbsp;&nbsp;";
		_e('If linking to the post in the topic, ONLY provide link by default?');
		echo '<br />';
		_e('Has No effect if no link format given.');
	}

	public function postAsMember() {
		echo "<input id='slavePostAsMember' name='wpIpsConnect_options[slavePostAsMember]' size='20' type='text' value='{$this->settings['slavePostAsMember']}' /><br />";
		_e('If Given, ALL IPB topics Posted from this installation will be started by the member with the given IPB member_id.');
	}

	public function postAsGuest() {
		echo "<input id='slavePostAsGuest' name='wpIpsConnect_options[slavePostAsGuest]' size='20' type='text' value='{$this->settings['slavePostAsGuest']}' /><br />";
		_e('If Given, ALL Visitor/Guest posts Posted from this installation to IPB will be posted by the member with the given IPB member_id. Useful for allowing guest posting in wordpress.');
	}

	public function forceDefaults() {
		$default = $this->settings['slaveForceDefaults'] ? " checked='checked'" : '';
		echo "<input id='slaveForceDefaults' name='wpIpsConnect_options[slaveForceDefaults]' type='checkbox' value='1'{$default} />&nbsp;&nbsp;";
		_e('Disallow by-post modification of the above?');
	}

	/*
	 * Validate Plugin settings input
	 * @param array $input Dirty input.
	 * @return array $newinput Cleaned input.
	 * @access public
	 */

	public function validate($input) {
		$newinput = parent::validate($input);
		$newinput['slavePostForum'] = absint($input['slavePostForum']);
		$newinput['slavePostTopic'] = absint($input['slavePostTopic']);
		$newinput['slaveSynchTopic'] = absint($input['slaveSynchTopic']);
		$newinput['slaveLinkOnly'] = absint($input['slaveLinkOnly']);
		$newinput['slaveForceDefaults'] = absint($input['slaveForceDefaults']);
		$newinput['slavePostAsMember'] = absint($input['slavePostAsMember']);
		$newinput['slavePostAsGuest'] = absint($input['slavePostAsGuest']);
		$newinput['slaveLinkText'] = trim($input['slaveLinkText']);
		return $newinput;
	}

}
