<?php

add_action( 'tagtheme_edit_form_fields', 'deco_tagtheme_fields_edit', 1, 2 );
function deco_tagtheme_fields_edit( $tag, $taxonomy ) {
	$term_id    = $tag->term_id;
	$show_terms = get_option( 'deco_show_terms' );
	if ( isset( $show_terms[ $term_id ] ) && $show_terms[ $term_id ] == 1 ) {
		$checked = 1;
	}
	?>

	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="tagtheme_show_on_page_diyalnist_block_filter_theme">Показати у списку фiльтрiв "Дiяльнiсть по темам" для роздiлу "Дiяльнiсть"</label>
		</th>
		<td>
			<input type="checkbox" name="tagtheme_show_on_page_diyalnist_block_filter_theme" id="tagtheme_show_on_page_diyalnist_block_filter_theme" value="1" <?php checked( $checked, 1 ); ?>>
		</td>
	</tr>
	<?php
}


add_action( 'tagtheme_add_form_fields', 'deco_tagtheme_fields_add', 10, 2 );
function deco_tagtheme_fields_add( $taxonomy ) {
	?>

	<div class="form-field">
		<label for="tagtheme_show_on_page_diyalnist_block_filter_theme">Показати у списку фiльтрiв "Дiяльнiсть по темам" для роздiлу "Дiяльнiсть"</label>
		<input type="checkbox" name="tagtheme_show_on_page_diyalnist_block_filter_theme" id="tagtheme_show_on_page_diyalnist_block_filter_theme" value="1">
	</div>

	<?php
}


add_filter( 'manage_edit-tagtheme_columns', 'deco_tagtheme_columns', 999 );
function deco_tagtheme_columns( $columns ) {
	$new_columns                = array();
	$new_columns['cb']          = $columns['cb'];
	$new_columns['thumbnail']   = 'Мiнiатюра';
	$new_columns['name']        = 'Iм`я';
	$new_columns['show_term']   = 'Вiдображене у роздiлi "Дiяльнiсть"';
	$new_columns['description'] = 'Опис';
	$new_columns['slug']        = 'Скорочення';
	$new_columns['posts']       = 'Кiлькiсть';


//	unset( $columns['cb'] );

//	$columns                       = array_merge( $new_columns, $columns );


	return $new_columns;
}

add_filter( 'manage_tagtheme_custom_column', 'deco_tagtheme_column', 10, 3 );
function deco_tagtheme_column( $columns, $column, $id ) {

	if ( 'show_term' == $column ) {

		$show_terms = get_option( 'deco_show_terms' );
		if ( isset( $show_terms[ $id ] ) && $show_terms[ $id ] == 1 ) {
			echo 'Так';
		}
	}

	return $columns;
}


add_action( 'delete_term', 'deco_tagtheme_delete_term', 5 );
function deco_tagtheme_delete_term( $term_id ) {

	$term_id    = (int) $term_id;
	$show_terms = get_option( 'deco_show_terms' );
	if ( isset( $show_terms[ $term_id ] ) ) {
		unset( $show_terms[ $term_id ] );
		update_option( 'deco_show_terms', $show_terms );
	}
}


add_action( 'edited_tagtheme', 'save_tagtheme_taxonomy_custom_meta', 10, 2 );
add_action( 'create_tagtheme', 'save_tagtheme_taxonomy_custom_meta', 10, 2 );
function save_tagtheme_taxonomy_custom_meta( $term_id ) {
	$checked = 0;
	if ( isset( $_POST['tagtheme_show_on_page_diyalnist_block_filter_theme'] ) ) {
		$checked = 1;
	}
	$show_terms             = get_option( 'deco_show_terms' );
	$show_terms[ $term_id ] = $checked;
	update_option( 'deco_show_terms', $show_terms );
}
