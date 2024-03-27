<?php

/*
 * @package (Pro) WordPress IPSConnect
 * @brief Ips_Connect_Stats_Widget widget
 * @version 1.1.3
 * @author Provisionists (based on Marcher Technologies WP IPS Connect)
 * @link https://provisionists.com
 * @copyright Copyright (c) 2014, Provisionists LLC All Rights Reserved
 */

class Ips_Connect_Stats_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
				'ips_connect_stats_widget', // Base ID
				__('Statistics'), // Name
				array('description' => __('Forum Statistics')) // Args
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
		$options = array();
		$fields = $this->fields();
		foreach ($fields as $key) {
			$options[$key] = apply_filters('widget_' . $key, isset($instance[$key]) ? $instance[$key] : $this->_defaultize($key));
		}
		reset($fields);
		unset($key);
		array_shift($fields);
		array_shift($fields);
		echo $before_widget;
		if (!empty($options['title'])) {
			echo $before_title . $options['title'] . $after_title;
		}
		$cacheTime = intval($options['cache_period'] * 60);
		$_stats = get_option('ipsConnectStats');
		$last = (is_array($_stats) && isset($_stats['cache_last'])) ? $_stats['cache_last'] : 0;
		$_NOWJIM = time();
		$updateCache = intval($cacheTime - intval($_NOWJIM - intval($last)));
		if (!is_array($_stats) || !$_stats['cache_last'] || !$options['cache_period'] || !is_array($_stats['stats']) || $updateCache <= 0) {
			$_stats = array();
			$_stats['stats'] = wpIpsConnect::instance()->getXmlRpc('fetchStats');
			if ($_stats['stats'] && $options['cache_period']) {
				$_stats['cache_last'] = $_NOWJIM;
				update_option('ipsConnectStats', $_stats);
			}
		}
		if (is_array($_stats['stats'])) {
			$stats = $_stats['stats'];
			unset($_stats['stats']);
			foreach ($fields as $key) {
				if (isset($stats[$key])) {
					echo $options[$key] . ': &nbsp;&nbsp;' .
					( ($key == 'users_most_data_unix') ? date_i18n(get_option('date_format'), $stats[$key]) . '&nbsp;' .
							date_i18n(get_option('time_format'), $stats[$key]) : number_format($stats[$key]) ) .
					'<br />';
				}
			}
			echo '<hr />';
		}
		unset($fields, $options, $key, $stats, $_stats);
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
		$fields = $this->fields();
		foreach ($fields as $key) {
			switch ($key) {
				case 'cache_period':
					$new_instance[$key] = apply_filters('widget_' . $key, $new_instance[$key]);
					break;
				default:
					$instance[$key] = apply_filters('widget_' . $key, strip_tags($new_instance[$key]));
					break;
			}
		}
		unset($fields, $key);
		return $instance;
	}

	protected function _defaultize($key) {
		switch ($key) {
			case 'title':
				return 'Statistics';
				break;
			case 'cache_period':
				return 5;
				break;
			case 'users_most_online':
				return 'Most Users Online';
				break;
			case 'users_most_data_unix':
				return 'On';
				break;
			case 'total_posts':
				return 'Total Posts';
				break;
			case 'total_members';
				return 'Total Members';
				break;
			default:
				return '';
				break;
		}
	}

	public function fields() {
		return array('title', 'cache_period', 'users_most_online', 'users_most_data_unix', 'total_posts', 'total_members');
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
			$options[$key] = (isset($instance[$key]) && $instance[$key]) ? $instance[$key] : __($this->_defaultize($key));
		}
		reset($fields);
		unset($key);
		$formable .= '<p>';
		foreach ($fields as $key) {
			$formable .= '<label for="' . $this->get_field_id($key) .
					'">' .
					__(ucwords(str_replace('_', ' ', str_replace('data_unix', 'date', $key))) . ( (!in_array($key, array('title', 'cache_period')) ? ' Title' : '')) . ':') .
					'</label>' .
					'<input class="widefat" id="' .
					$this->get_field_id($key) . '" name="' .
					$this->get_field_name($key) . '" type="text" value="' .
					(!in_array($key, array('cache_period')) ? esc_attr($options[$key]) : intval($options[$key])) .
					'" />';
		}
		$formable .= '</p>';
		echo $formable;
		unset($formable, $options, $fields, $key);
	}

}
