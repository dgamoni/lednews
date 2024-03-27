<?php

/*
* @package (Pro) WordPress IPSConnect
 * @brief Ips_Connect_Topics_Widget widget
 * @version 1.1.3
 * @author Provisionists (based on Marcher Technologies WP IPS Connect)
 * @link https://provisionists.com
 * @copyright Copyright (c) 2014, Provisionists LLC All Rights Reserved
 */

class Ips_Connect_Topics_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
				'ips_connect_topics_widget', // Base ID
				__('Topics'), // Name
				array('description' => __('Topics List')) // Args
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
		$topics = array();
		$return = array();
		$_stats = array();
		static $_cache = array();
		$fields = $this->fields();
		foreach ($fields as $key) {
			$options[$key] = apply_filters('widget_' . $key, isset($instance[$key]) ? $instance[$key] : $this->_defaultize($key));
		}
		$stKey = md5(json_encode($options));
		$_f = $options['forums'] ? $options['forums'] : '*';
		$options['cache_period'] = isset($options['cache_period']) ? $options['cache_period'] : 0;
		$limit = intval($options['topics']);
		if ($options['cache_period']) {
			$cacheTime = intval($options['cache_period'] * 60);
			$_stats = get_option('ipsConnectTopics');
			$last = (is_array($_stats) && isset($_stats[$stKey]) && isset($_stats[$stKey]['cache_last'])) ? $_stats[$stKey]['cache_last'] : 0;
			$_NOWJIM = time();
			$updateCache = intval($cacheTime - intval($_NOWJIM - intval($last)));
		}
		if (!isset($_cache[$stKey])) {
			if (isset($_stats[$stKey])) {
				$_cache[$stKey] = $_stats[$stKey];
			}
			if ($limit) {
				if (!$options['cache_period'] || $updateCache <= 0) {
					$topics = wpIpsConnect::instance()->getXmlRpc('fetchTopics', array(
						'order_field' => $options['sort_field'] ? $options['sort_field'] : 'start_date',
						'forum_ids' => $_f,
						'offset' => 0,
						'limit' => $limit,
						'view_as_guest' => (bool) $options['cache_period'],
						'api_module' => 'mt',
							)
					);
				}
				if (is_array($topics) && count($topics)) {
					$_cache[$stKey] = array();
					foreach ($topics as $k => $t) {
						$row = array();
						foreach (array_keys($t) as $key) {
							$row[$key] = apply_filters('widget_topic_' . $key, $t[$key]);
						}
						$_cache[$stKey]['topics'][$k] = $row;
					}
				}
			}
		}
		if ($options['cache_period'] && $updateCache <= 0) {
			$_cache[$stKey]['cache_last'] = $_NOWJIM;
			update_option('ipsConnectTopics', $_cache);
		}
		if (isset($_cache[$stKey]) && isset($_cache[$stKey]['topics'])) {
			$return = $_cache[$stKey]['topics'];
		}
		echo $before_widget;
		if (!empty($options['title'])) {
			echo $before_title . $options['title'] . $after_title;
		}
		if (is_array($return) && count($return)) {
			$_block = '';
			foreach ($return as $_t) {
				$_block .= '<h4><a href="' . $_t['link-topic'] . '">' . $_t['title'] . '</a></h4>';
				if ($options['show_start_date']) {
					$_block .= __('started') . '&nbsp;' .
							date_i18n(get_option('date_format'), $_t['start_date']) . '&nbsp;' .
							date_i18n(get_option('time_format'), $_t['start_date']);
					$_block .=!$options['show_post'] ? '<br />' : '&nbsp;';
				}
				if ($options['show_author']) {
					$_block .= __('by') . '&nbsp;<a href="' . $_t['link-profile'] . '">' . $_t['starter_name'] . '</a>';
					$_block .=!$options['show_post'] ? '<br />' : '&nbsp;';
				}
				$_block .= $options['show_forum'] ? __('in') . '&nbsp;<a href="' . $_t['link-forum'] . '">' . $_t['forum_name'] . '</a>' : '';
				$_block .= $options['show_post'] ? '<br /><br />' . $_t['post'] . '<hr />' : '';
			}
			echo $_block;
		}

		unset($fields, $options, $key, $topics);
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
			switch ($key) {
				case 'topics':
				case 'cache_period':
					$instance[$key] = intval($new_instance[$key]);
					break;
				case 'show_post':
				case 'show_author':
				case 'show_start_date':
				case 'show_forum':
					$instance[$key] = ($new_instance[$key] == 'on') ? 1 : 0;
					break;
				case 'sort_field':
					$instance[$key] = in_array($new_instance[$key], $this->sorts()) ? $new_instance[$key] : 'start_date';
					break;
				case 'forums':
					$instance[$key] = count($new_instance[$key]) ? implode(',', $new_instance[$key]) : '*';
					break;
				default:
					$instance[$key] = strip_tags($new_instance[$key]);
					break;
			}
		}
		return $instance;
	}

	protected function _defaultize($key) {
		$string = '';
		switch ($key) {
			case 'title':
				$string = 'Recent Topics';
				break;
			case 'topics':
			case 'cache_period':
				$string = '5';
				break;
			case 'show_post':
				$string = '0';
				break;
			case 'show_author':
			case 'show_start_date':
			case 'show_forum':
				$string = '1';
				break;
			case 'sort_field':
				$string = 'start_date';
				break;
			default;
				break;
		}
		return $string;
	}

	public function fields() {
		return array('title', 'cache_period', 'show_post', 'show_author', 'show_start_date', 'show_forum', 'topics', 'sort_field', 'forums');
	}

	public function sorts() {
		return array('last_post', 'start_date', 'tid', 'title', 'views', 'posts');
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
		static $forums = array();
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
					$formable .= '<label for="' . $this->get_field_id($key) .
							'">' . __(ucwords(str_replace('_', ' ', $key)) . ':') .
							'</label>';
					$formable .= '<input class="widefat" id="' .
							$this->get_field_id($key) . '" name="' .
							$this->get_field_name($key) . '" type="text" value="' . esc_attr($options[$key]) . '" /><br />';
					break;
				case 'show_post':
				case 'show_author':
				case 'show_start_date':
				case 'show_forum':
					$formable .= '<label for="' . $this->get_field_id($key) .
							'">' . __(ucwords(str_replace('_', ' ', $key)) . ':') .
							'</label>';
					$selected = ($options[$key]) ? ' checked="checked"' : '';
					$formable .= '<input class="widefat" id="' .
							$this->get_field_id($key) . '" name="' .
							$this->get_field_name($key) . '" type="checkbox"' . $selected . '" /><br />';
					break;
				case 'sort_field':
					$formable .= '<label for="' . $this->get_field_id($key) .
							'">' . __(ucwords(str_replace('_', ' ', $key)) . ':') .
							'</label>';
					$formable .= '<br /><select class="widefat" id="' .
							$this->get_field_id($key) . '" name="' .
							$this->get_field_name($key) . '">';
					foreach ($this->sorts() as $sort) {
						$default = ($options[$key] == $sort) ? ' selected="selected"' : '';
						$formable .= '<option value="' . $sort . '"' . $default . '>' . __(ucwords(str_replace('_', ' ', $sort))) . '</option>';
					}
					$formable .= '</select>';
					break;
				case 'forums':
					$forums = isset($options[$key]) ? explode(',', $options[$key]) : array();
					$stats =  wpIpsConnect::instance()->getXmlRpc('fetchForumsOptionList');
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
		echo $formable;
		echo '</p>';
		unset($formable, $options, $fields, $key, $stats, $forums, $_forums, $id, $selectBox, $sort, $default);
	}

}
