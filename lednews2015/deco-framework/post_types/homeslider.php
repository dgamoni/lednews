<?php



	function register_ledgallery_post_type() {

		$labels = array(
			'name'               => _x( 'Галерея', 'ledgallery' ),
			'singular_name'      => _x( 'Галерея', 'ledgallery' ),
			'add_new'            => 'Добавить',
			'add_new_item'       => __( 'Добавить' ),
			'edit_item'          => __( 'Редактировать' ),
			'new_item'           => __( 'Добавить' ),
			'view_item'          => __( 'Просмотр' ),
			'search_items'       => __( 'Поиск' ),
			'not_found'          => __( 'Не найдено' ),
			'not_found_in_trash' => __( 'Не найдено в корзине' ),
			// 'parent_item_colon' => 'Parent Post:',
		 //    'menu_name' => 'Posts'
		);



		register_post_type( 'ledgallery', array(
			'labels'          => $labels,
			'public'          => true,
			'show_ui'         => true,
			'capability_type' => 'page',
			'menu_icon'       => 'dashicons-images-alt2',
						'has_archive'     => true,
			'hierarchical'    => false,
			//'supports'        => array( 'title', 'thumbnail','page-attributes' ),
			'supports' => array( 'title', 'thumbnail' ,'page-attributes'),
			'menu_position' => 8,
		) );
	}


	function manage_ledgallery_columns( $column_name, $id ) {

		global $wpdb, $pageURLs;

		$diplom = get_post( $id );
		$user   = get_userdata( $diplom->post_author );

		switch ( $column_name ) {
			case 'id':
				echo $id;
				break;

			case 'ledgallery_thumb':
				if ( has_post_thumbnail( $id ) ):
					echo '<a href="' . admin_url( 'post.php?post=' . $id . '&action=edit' ) . '">';
					echo '<div class="rc-admin-thumb">';
					$url_arr = wp_get_attachment_image_src( get_post_thumbnail_id( $id ) );
					echo '<img width="100" src="' . $url_arr[0] . '" alt="" />';
					echo '</div>';
					echo '</a>';
				endif;
				break;
			default:
				break;
		} // end switch
	}

	function add_ledgallery_columns( $columns ) {

		global $pageURLs;
		$new_columns['cb']               = '<input type="checkbox" />';
		$new_columns['title']            = _x( 'Заголовок', 'column name' );
		$new_columns['date']             = _x( 'Дата', 'column name' );
		$new_columns['ledgallery_thumb'] = _x( 'Миниатюра', 'column name' );

		return $new_columns;
	}


	add_action( 'init', 'register_ledgallery_post_type' );
	add_filter( 'manage_edit-ledgallery_columns', 'add_ledgallery_columns' );
	add_action( 'manage_posts_custom_column', 'manage_ledgallery_columns', 10, 2 );


// place
	function register_place_post_type() {

		$labels = array(
			'name'               => _x( 'Place', 'place' ),
			'singular_name'      => _x( 'Place', 'place' ),
			'add_new'            => 'Добавить',
			'add_new_item'       => __( 'Добавить' ),
			'edit_item'          => __( 'Редактировать' ),
			'new_item'           => __( 'Добавить' ),
			'view_item'          => __( 'Просмотр' ),
			'search_items'       => __( 'Поиск' ),
			'not_found'          => __( 'Не найдено' ),
			'not_found_in_trash' => __( 'Не найдено в корзине' ),
			// 'parent_item_colon' => 'Parent Post:',
		 //    'menu_name' => 'Posts'
		);



		register_post_type( 'place', array(
			'labels'          => $labels,
			'public'          => true,
			'show_ui'         => true,
			'capability_type' => 'page',
			'menu_icon'       => 'dashicons-images-alt2',
						'has_archive'     => true,
			'hierarchical'    => false,
			//'supports'        => array( 'title', 'thumbnail','page-attributes' ),
			'supports' => array( 'title','editor', 'thumbnail' ,'page-attributes','custom-fields'),
			'menu_position' => 8,
		) );
	}


	function manage_place_columns( $column_name, $id ) {

		global $wpdb, $pageURLs;

		$diplom = get_post( $id );
		$user   = get_userdata( $diplom->post_author );

		switch ( $column_name ) {
			case 'id':
				echo $id;
				break;

			case 'place_thumb':
				if ( has_post_thumbnail( $id ) ):
					echo '<a href="' . admin_url( 'post.php?post=' . $id . '&action=edit' ) . '">';
					echo '<div class="rc-admin-thumb">';
					$url_arr = wp_get_attachment_image_src( get_post_thumbnail_id( $id ) );
					echo '<img width="100" src="' . $url_arr[0] . '" alt="" />';
					echo '</div>';
					echo '</a>';
				endif;
				break;
			default:
				break;
		} // end switch
	}

	function add_place_columns( $columns ) {

		global $pageURLs;
		$new_columns['cb']               = '<input type="checkbox" />';
		$new_columns['title']            = _x( 'Заголовок', 'column name' );
		$new_columns['date']             = _x( 'Дата', 'column name' );
		$new_columns['place_thumb'] = _x( 'Миниатюра', 'column name' );

		return $new_columns;
	}


	add_action( 'init', 'register_place_post_type' );
	add_filter( 'manage_edit-place_columns', 'add_place_columns' );
	add_action( 'manage_posts_custom_column', 'manage_place_columns', 10, 2 );