<?php

class Deco_Destatistics_Add_Select {


	private $field = '';
	private $table = '';
	private $blogid = 1;
	private $wpdb;
	private $request = array();
	private $custom_name_field;

	public function __construct( $field = 'votes_sum', $custom_name_field = 'deco_votes_sum' ) {
		global $wpdb;
		$this->table             = $wpdb->de_statistics;
		$this->field             = $field;
		$this->blogid            = get_current_blog_id();
		$this->wpdb              = $wpdb;
		$this->custom_name_field = $custom_name_field;
	}

	/**
	 * Join de_statistics table rows to current sql query by post_id
	 *
	 * @param $join
	 *
	 * @return string
	 */
	public function join( $join ) {
		$join .= " INNER JOIN {$this->table} ON {$this->wpdb->posts}.ID = {$this->table}.post_id";

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

		$fields .= ", {$this->table}.{$this->field} as {$this->custom_name_field} ";

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

		$order   = 'DESC';
		$orderby = "{$this->custom_name_field} $order, " . $orderby;

		return $orderby;
	}

	public function where( $where ) {
		$where .= " AND {$this->table}.blog_id = {$this->blogid} AND {$this->custom_name_field} > 0 ";

//		return $where;
	}

	public function removeFilter() {

		remove_filter( 'posts_join', array( $this, 'join' ), 999 );
		remove_filter( 'posts_fields', array( $this, 'fields' ), 999 );
		remove_filter( 'posts_orderby', array( $this, 'orderby' ), 999 );
		remove_filter( 'posts_where', array( $this, 'where' ), 999 );

	}


	public function addFilter() {

		add_action( 'parse_query', array( $this, 'useFilter' ), 1 );
	}

	public function useFilter( $query ) {
		if ( isset( $query->query_vars['orderby'] ) && $query->query_vars['orderby'] == 'destatistics' ) {

			$this->request = $query->query_vars;

			add_filter( 'posts_join', array( $this, 'join' ), 999 );
			add_filter( 'posts_fields', array( $this, 'fields' ), 999 );
			add_filter( 'posts_orderby', array( $this, 'orderby' ), 999 );
			add_filter( 'posts_where', array( $this, 'where' ), 999 );

		}
	}


}