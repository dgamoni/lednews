<?php

/* ----------- Пример ---------------

	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => 10,
		'de_statistics'  => array(
			'fields' => array( 'facebook', 'twitter', 'gplus', 'vkontakte', 'odnoklassniki', 'pocket' )
		)
	);

	$posts = new WP_Query( $args );

-------------- Описание -------------

de_statistics   — обязательно должен быть массивом, и содержать в себе параметр fields
fields          - может быть как массивом, так и строкой (с одним лишь значением)

Если:

fields => array(
			'facebook',
			'twitter',
			'gplus',
			'vkontakte',
			'odnoklassniki',
			'pocket'
		)

то это будет тоже самое, что указать fields => 'total_votes', потому как нет смысла суммировать значения, для этого есть отдельный столбик для сортировки в таблице БД (votes_sum)

Если:

fields => array(
			'facebook',
			'twitter',
			'gplus'
		)

то три значения кадого поста будут сумироваться, ну и далее уже будет применятся DESC или ASC к результату, это позволит делать кастомыне выборки, например только учитывая Твиттер и Facebook, например

Если:

fields => array(
			'total_views'
		)

то выборка будет сортироваться по общему кол-во просмотров поста, которые мы собираем через Google Analytics

Если:

fields => array(
			'total_votes'
		)

то выборка будет сортироваться по общему кол-во шейров поста

Если параметр fields содержит в массиве total_votes или total_views + другие (описаные выше), то будут учитываться лишь один из total_ параметров, тот, который первый в массиве

------------------------------------- */

/**
 * Класс для работы с WP_Query, ну и в частности,
 * для обработки параметра de_statistics, передаваемого в том же WP_Query
 */
class DESTAT_Queries {

	/**
	 * Переменные
	 */
	private $_db;
	/**
	 * @var string
	 */
	private $_table;
	/**
	 * @var string
	 */
	private $_field_prefix = 'destat_';
	/**
	 * @var
	 */
	private $_destat_query;
	/**
	 * @var string
	 */
	private $_destat_query_args = 'de_statistics';
	/**
	 * @var array
	 */
	private $_orderby_fields = array(
		'total_votes',
		'total_views',
		'twitter',
		'facebook',
		'gplus',
		'vkontakte',
		'pocket'
	);

	/**
	 * Construc method
	 */
	public function __construct() {

		global $wpdb;

		$this->_db    = $wpdb;
		$this->_table = $wpdb->de_statistics;

		add_action( 'parse_query', array( $this, 'add_filters' ), 1 );
		add_action( 'posts_selection', array( $this, 'remove_filters' ), 1 );


	}

	/**
	 * Adding custom join, fields and orderby filter to the WP_Query,
	 * but only if current query contains de_statistics argument
	 *
	 * @param $query
	 *
	 * @return mixed
	 */
	public function add_filters( $query ) {

		if ( isset( $query->query_vars[ $this->_destat_query_args ] ) && ! empty( $query->query_vars[ $this->_destat_query_args ] ) && ! empty( $query->query_vars[ $this->_destat_query_args ]['fields'] ) ) {

			$this->_destat_query = $query->query_vars[ $this->_destat_query_args ];

			add_filter( 'posts_join', array( $this, 'join' ), 999 );
			add_filter( 'posts_fields', array( $this, 'fields' ), 999 );
			add_filter( 'posts_orderby', array( $this, 'orderby' ), 999 );

		}

		return $query;

	}

	/**
	 * Removing all our custom filters after the posts were obtained
	 */
	public function remove_filters() {

		remove_filter( 'posts_join', array( $this, 'join' ), 999 );
		remove_filter( 'posts_fields', array( $this, 'fields' ), 999 );
		remove_filter( 'posts_orderby', array( $this, 'orderby' ), 999 );

	}

	/**
	 * Join de_statistics table rows to current sql query by post_id
	 *
	 * @param $join
	 *
	 * @return string
	 */
	public function join( $join ) {

		$join .= "LEFT JOIN {$this->_table} ON {$this->_db->posts}.ID = {$this->_table}.post_id";

		return $join;
	}

	/**
	 * Adding custom variables to db query using JOIN
	 * with our db table
	 *
	 * @param $fields
	 *
	 * @return string
	 */
	public function fields( $fields ) {

		$prefix     = $this->_field_prefix;
		$filtered   = $this->_filter_fields();
		$sum_fields = $filtered['sum_fields'];

		$fields .= ", {$this->_table}.votes_sum as {$prefix}total_votes,
						  {$this->_table}.views_counts as {$prefix}total_views,
						  {$this->_table}.fb_counts as {$prefix}facebook,
						  {$this->_table}.tw_counts as {$prefix}twitter,
						  {$this->_table}.vk_counts as {$prefix}vkontakte,
						  {$this->_table}.gplus_counts as {$prefix}gplus,
						  {$this->_table}.ok_counts as {$prefix}odnoklassniki,
						  {$this->_table}.pocket_counts as {$prefix}pocket
						  {$sum_fields}";

		return $fields;
	}

	/**
	 * Adding custom orderby row name from
	 * previously added variables in sql query
	 *
	 * @param $orderby
	 *
	 * @return string
	 */
	public function orderby( $orderby ) {

		$filtered       = $this->_filter_fields();
		$destat_orderby = $filtered['orderby'];

		$orderby = "$destat_orderby, " . $orderby;

		return $orderby;
	}

	/**
	 * Filtering de_statistics arguments sended in WP_Query
	 *
	 * @return array
	 */
	private function _filter_fields() {

		$prefix   = $this->_field_prefix;
		$fields   = $this->_destat_query['fields'];
		$filtered = array(
			'orderby'    => $prefix . 'total_votes',
			'sum_fields' => ''
		);

		if ( is_array( $fields ) ) {

			$fields       = array_unique( $fields );
			$equal_arrays = $this->is_equal_fields( $fields );

			if ( count( $fields ) > 0 && ( ! in_array( 'total_views', $fields ) && ! in_array( 'total_votes', $fields ) ) && ! $equal_arrays ) {

				$sum_field_names_arr = array();

				foreach ( $fields as $field ) {

					if ( in_array( $field, $this->_orderby_fields ) ) {
						$sum_field_names_arr[] = $prefix . $field;
					}

				}

				$sum_field_names        = implode( ' + ', $sum_field_names_arr );
				$sum_as_name            = $prefix . implode( '_', $fields );
				$filtered['orderby']    = $sum_as_name;
				$filtered['sum_fields'] = "( {$sum_field_names} ) as {$sum_as_name}";

			} else if ( in_array( 'total_views', $fields ) ) {
				$filtered['orderby'] = $prefix . 'total_views';
			}

		} else if ( is_string( $fields ) && in_array( $fields, $this->_orderby_fields ) ) {
			$filtered['orderby'] = $prefix . $fields;
		}

		return $filtered;

	}

	/**
	 * Check if all fields values are equal the
	 * twitter + facebook + gplus + vkontakte + pocket
	 *
	 * @param $a
	 *
	 * @return bool
	 */
	public function is_equal_fields( $a ) {

		$b = $this->_orderby_fields;
		unset( $b[ array_search( 'total_votes', $b ) ] );
		unset( $b[ array_search( 'total_views', $b ) ] );

		$equal = ( is_array( $a ) && is_array( $b ) && array_diff( $a, $b ) === array_diff( $b, $a ) );

		return $equal;

	}

}
