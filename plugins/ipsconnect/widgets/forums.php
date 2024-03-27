<?php

/*
 * @package (Pro) WordPress IPSConnect
 * @brief Ips_Connect_Forums_Widget widget
 * @version 1.1.2
 * @author Provisionists (based on Marcher Technologies WP IPS Connect)
 * @link https://provisionists.com
 * @copyright Copyright (c) 2014, Provisionists LLC All Rights Reserved
 */

class Ips_Connect_Forums_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
				'ips_connect_forums_widget', // Base ID
				__('Forums'), // Name
				array('description' => __('Forums Jump List/Menu')) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance) {
		extract($args);
		$_block = '';
		$options = array();
		$return = array();
		$_stats = array();
		$_cache = array();
		$fields = $this->fields();
		foreach ($fields as $key) {
			$options[$key] = apply_filters('widget_' . $key, isset($instance[$key]) ? $instance[$key] : $this->_defaultize($key));
		}
		$stKey = md5(json_encode($options));
		if ($options['cache_period']) {
			$cacheTime = intval($options['cache_period'] * 60);
			$_cache = get_option('ipsConnectForums');
			$last = (is_array($_cache) && isset($_cache[$stKey]) && isset($_cache[$stKey]['cache_last'])) ? $_cache[$stKey]['cache_last'] : 0;
			$_NOWJIM = time();
			$updateCache = intval($cacheTime - intval($_NOWJIM - intval($last)));
		}
		$forums = $options['forums'] ? $options['forums'] : '';
		if (!$options['cache_period'] || $updateCache <= 0 && $forums) {
			$stats = wpIpsConnect::instance()->getXmlRpc('fetchForums', array(
						'forum_ids' => $forums,
						'view_as_guest' => (bool) $options['cache_period']
			));
			if (is_array($stats) && count($stats)) {
				foreach ($stats as $f) {
					$_stats[$stKey]['forums'][] = $f;
				}
			}
		}

		if (is_array($_stats) && isset($_stats[$stKey])) {
			$_cache = array();
			$_cache[$stKey] = array();
			$_cache[$stKey]['forums'] = $_stats[$stKey]['forums'];
			$_cache[$stKey]['cache_last'] = $_stats[$stKey]['cache_last'];
		}
		if (is_array($_cache) && isset($_cache[$stKey]) && $options['cache_period'] && $updateCache <= 0) {
			$_cache[$stKey]['cache_last'] = $_NOWJIM;
			update_option('ipsConnectForums', $_cache);
		}
		if (isset($_cache[$stKey]['forums'])) {
			$return = $_cache[$stKey]['forums'];
		}
		echo $before_widget;
		if (!empty($options['title'])) {
			echo $before_title . $options['title'] . $after_title;
		}
		if (count($return)) {
			$_block .= '<div>';
			$base = str_replace('ipsconnect.php', 'index.php', str_replace('interface/ipsconnect/', '', wpIpsConnect::instance()->settings['masterUrl']));
			switch ($options['navigation_mode']) {
				case TRUE;
					foreach ($return as $forum) {
						$_block .= '<a href="' . $base . '?app=forums&amp;module=forums&amp;section=forums&amp;f=' . $forum['id'] . '" style="margin-bottom:1px;display:block;clear:both;">' . $forum['name'] . '</a>';
					}
					break;
				case FALSE;
					$_block .= '<form action="' . $base . '" style="text-align:center;">';
					$_block .= '<input type="hidden" name="app" value="forums" />';
					$_block .= '<input type="hidden" name="module" value="forums" />';
					$_block .= '<input type="hidden" name="section" value="forums" />';
					$_block .= '<select  id="';
					$_block .= $this->get_field_id('f') . '" name="f">';
					foreach ($return as $forum) {
						$_block .= '<option value="' . $forum['id'] . '">' . $forum['name'] . '</option>';
					}
					$_block .= '</select>&nbsp;&nbsp;';
					$_block .= '<input type="submit" value="' . __('Go') . '" />';
					$_block .= '</form>';
					break;
			}
			$_block .= '</div><hr />';
			echo $_block;
		}
		unset($fields, $options, $key, $_cache, $_stats, $stats, $stKey, $_block);
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance) {
		$instance = array();
		foreach ($this->fields() as $key) {
			$string = '';
			switch ($key) {
				case 'cache_period':
					$string = intval($new_instance[$key]);
					break;
				case 'forums':
					$string = count($new_instance[$key]) ? implode(',', $new_instance[$key]) : '';
					break;
				case 'navigation_mode':
					$string = ($new_instance[$key] == 'on') ? 1 : 0;
					break;
				default:
					$string = strip_tags($new_instance[$key]);
					break;
			}
			$instance[$key] = $string;
		}
		return $instance;
	}

	protected function _defaultize($key) {
		$string = '';
		switch ($key) {
			case 'title':
				$string = 'Forums';
				break;
			case 'cache_period':
				$string = '5';
				break;
			case 'navigation_mode':
				$string = '0';
				break;
			default:
				break;
		}
		return $string;
	}

	public function fields() {
		return array('title', 'cache_period', 'navigation_mode', 'forums');
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance) {
		$options = array();
		$fields = $this->fields();
		$formable = '';
		foreach ($fields as $key) {
			$options[$key] = (isset($instance[$key]) && $instance[$key] !== '') ? $instance[$key] : __($this->_defaultize($key));
		}
		reset($fields);
		unset($key);
		$formable .= '<p>';
		foreach ($fields as $key) {
			switch ($key) {
				default:
					$formable .= '<label for="' . $this->get_field_id($key) . '">' . __(ucwords(str_replace('_', ' ', $key)) . ':') . '</label>';
					$formable .= '<input class="widefat" id="' .
							$this->get_field_id($key) . '" name="' .
							$this->get_field_name($key) . '" type="text" value="' .
							esc_attr($options[$key]) . '"' .
							' /><br />';
					break;
				case 'navigation_mode':
					$formable .= '<label for="' . $this->get_field_id($key) . '">' . __(ucwords(str_replace('_', ' ', $key)) . ':') . '</label>';
					$formable .= '<input class="widefat" id="';
					$formable .= $this->get_field_id($key) . '" name="';
					$formable .= $this->get_field_name($key) . '" type="checkbox"';
					$formable .= ($options[$key]) ? ' checked="checked"' : '';
					$formable .= ' /><br />';
					break;
				case 'forums':
					$forums = isset($options[$key]) ? explode(',', $options[$key]) : array();
					$stats = wpIpsConnect::instance()->getXmlRpc('fetchForumsOptionList');
					if (isset($stats['forumList'])) {
						$formable .= '<label for="' . $this->get_field_id($key) . '">' . __(ucwords(str_replace('_', ' ', $key)) . ':') . '</label>';
						$formable .= '<br /><select  id="' . $this->get_field_id($key) . '" name="' .
								$this->get_field_name($key) . '[]" multiple="multiple">';
						$selectBox = $stats['forumList'];
						if (count($forums)) {
							foreach ($forums as $id) {
								$selectBox = str_replace('value="' . $id, 'selected="selected" value="' . $id, $selectBox);
							}
						}
						$formable .= $selectBox;
						$formable .= '</select>';
					}
					break;
			}
		}
		$formable .= '</p>';
		echo $formable;
		unset($formable, $options, $fields, $key);
	}

}