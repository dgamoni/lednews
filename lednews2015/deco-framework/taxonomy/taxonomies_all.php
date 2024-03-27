<?php
/*
 * Регистрируем кастомную таксономию 
 * 
 */

$args = array(

	array(
		'slug'       => 'gallery_cat',
		'post_types' => array(
			'ledgallery'
		),
		'args'       => array(
			'hierarchical'      => true, // false if use tag style
			'show_ui'           => true,
			'labels'            => array(
				'name'          => __( 'Рубрики', 'joinup' ),
				'singular_name' => __( 'Рубрики', 'joinup' ),
				'search_items'  => __( 'Найти', 'joinup' ),
				'edit_item'     => __( 'Редактировать', 'joinup' ),
				'update_item'   => __( 'Обновить', 'joinup' ),
				'add_new_item'  => __( 'Добавить', 'joinup' ),
				'new_item_name' => __( 'Рубрики', 'joinup' ),
				'menu_name'     => __( 'Рубрики', 'joinup' ),
			),
			'query_var'         => true,
			'rewrite'           => true,
			'show_in_nav_menus' => true,
			'show_admin_column' => false,
		)
	)

);

$taxonomies = new Deco_Taxonomies( $args );

/**
 * Класс для работы со всеми кастомными пост тайпами темы
 *
 *
 */
class Deco_Taxonomies {

	protected $taxonomies;

	function __construct( $args ) {

		$this->taxonomies = $args;

		$this->init();

	}

	public function init() {

		$taxonomies = $this->taxonomies;

		if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {

			foreach ( $taxonomies as $taxonomy ) {

				$this->register( $taxonomy['slug'], $taxonomy['post_types'], $taxonomy['args'] );

			}

		}

	}

	public function register(
		$slug,
		$post_types,
		$args
	) {

		register_taxonomy( $slug, $post_types, $args );

	}

}

?>