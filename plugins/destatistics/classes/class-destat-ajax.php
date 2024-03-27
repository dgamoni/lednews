<?php

/*
 * class DESTAT_for work ajax requests
 */

class DESTAT_Ajax {

	public function __construct() {

		add_action( 'wp_ajax_destat_show_table_stat', array( &$this, 'showTableStatistics' ) );
		add_action( 'wp_ajax_destat_get_filters_data', array( &$this, 'getFiltersData' ) );
		add_action( 'wp_ajax_get_destats_data_for_table_list', array( &$this, 'getDestatsDataForTableList' ) );
		add_action( 'wp_ajax_destat_delete_ga_access', array( &$this, 'deleteGAAccess' ) );
		add_action( 'wp_ajax_destat_update_single', array( &$this, 'statisticsUpdateSingle' ) );

	}


	public function showTableStatistics() {

		global $wpdb;

		//			$sMonthAgoTimestamp = time() - ( 60 * 60 * 24 * 30 );
		//echo date('Y-m-d H:i:s', $sMonthAgoTimestamp);
		//		$aFilter['DateFrom'] = date( 'Y-m-d H:i:s', $sMonthAgoTimestamp );
		//		$aFilter['DateFor']  = date( 'Y-m-d H:i:s', time() );
		//			$DESTAT_args['DateFrom'] = date( 'Y-m-d H:i:s', $sMonthAgoTimestamp );
		//			$DESTAT_args['DateTo']   = date( 'Y-m-d H:i:s', time() );

		$posts_per_page = intval( $_POST['posts_per_page'] );
		$page           = intval( $_POST['page'] );
		$category       = intval( $_POST['category'] );
		$date_from      = $_POST['date_from'];
		$date_to        = $_POST['date_to'];
		$author         = intval( $_POST['author'] );
		$post_type      = $_POST['post_type'];
		$orderby        = $_POST['orderby'];
		$search         = $_POST['search'];
		$nums           = $_POST['i'];
		$orderby        = $_POST['orderby'];
		$order          = $_POST['order'];


		if ( empty( $post_type ) ) {
			$post_type = 'post';
		}

		$paged = $posts_per_page * ( $page - 1 );

		$blog_id = get_current_blog_id();


		$query = " SELECT {$wpdb->de_statistics}.*, {$wpdb->posts}.* FROM {$wpdb->de_statistics} ";

		$query .= " INNER JOIN {$wpdb->posts} ON ( {$wpdb->de_statistics}.post_id = {$wpdb->posts}.ID ) ";

		if ( $category ) {
			$query .= "INNER JOIN {$wpdb->term_relationships} ON ( {$wpdb->term_relationships}.object_id = {$wpdb->de_statistics}.post_id )";
		}

		$where = " WHERE 1=1 ";
		$where .= " AND {$wpdb->de_statistics}.blog_id = $blog_id ";
		$where .= " AND {$wpdb->posts}.post_type = '$post_type' ";
		$where .= " AND {$wpdb->posts}.post_status = 'publish' ";

		if ( $author ) {
			$where .= " AND {$wpdb->posts}.post_author = " . $author;
		}

		if ( $search ) {
			$where .= " AND {$wpdb->posts}.post_title LIKE '%" . $search . "%'";
		}

		if ( $category ) {
			$where .= " AND {$wpdb->term_relationships}.term_taxonomy_id = " . $category;
		}


		$query .= $where;


		if ( $orderby = 'post_date' ) {
			$orderby = "{$wpdb->de_statistics}.views_counts";
		}

		$destatistics = $wpdb->get_results( $query . " ORDER BY $orderby $order LIMIT $paged, $posts_per_page" );

		$max_pages = $wpdb->get_results( "SELECT COUNT(*) as count FROM {$wpdb->de_statistics} INNER JOIN {$wpdb->posts} ON ( {$wpdb->de_statistics}.post_id = {$wpdb->posts}.ID ) $where" );

		if ( $max_pages[0]->count > $posts_per_page ) {
			$maximum_pages = ceil( $max_pages[0]->count / $posts_per_page );
		}


		ob_start();
		$i = 0;
		if ( $page ) {
			$i = ( $page - 1 ) * $posts_per_page;
		}

		foreach ( $destatistics as $item ) {
			$i ++;
			$cat             = get_the_category( $item->ID );
			$user            = get_user_meta( $item->post_author );
			$class           = 'alternate ';
			$item->cat_name  = $cat['0']->name;
			$item->user_name = $user['first_name']['0'] . ' ' . $user['last_name']['0'];
			$item->i         = $i;
			?>
			<tr class="<?php echo ( $i % 2 != 0 ) ? $class . 'postview_' . $item->ID : 'postview_' . $item->ID; ?>">
				<?php $this->dataRowStatisticTable( $item ); ?>
			</tr>
		<?php

		}

		$res['html'] = ob_get_contents();
		ob_end_clean();


		//			$maximum_pages = 2;
		// NAvigation
		if ( $maximum_pages > 1 ) {

			$first_page = 1;
			$prev_page  = $page - 1;
			$next_page  = $page + 1;
			$last_page  = $maximum_pages;


			$disabled_first_page_button = '';
			$disabled_prev_page_button  = '';
			if ( $page == 1 ) {
				$disabled_first_page_button = ' disabled';
				$disabled_prev_page_button  = ' disabled';
			}

			$disabled_next_page_button = '';
			$disabled_last_page_button = '';
			if ( $page == $maximum_pages ) {
				$disabled_next_page_button = ' disabled';
				$disabled_last_page_button = ' disabled';
			}

			ob_start();
			?>

			<div class="tablenav-pages">
				<span class="displaying-num">
					<?php echo $i; ?>
					<?php _e( 'items', DESTAT_TEXTDOMAIN ); ?>
				</span>
				<span class="pagination-links">
						<a class="first-page<?php echo $disabled_first_page_button; ?>" title="Go to the first page" href="#" <?php if ( ! $disabled_first_page_button ) { ?> onclick="Destatistics.page(this, 1, <?php echo $i; ?>); return false;" <?php } ?>>«</a>

				<a class="prev-page<?php echo $disabled_prev_page_button; ?>" title="Go to the previous page" href="#" <?php if ( ! $disabled_prev_page_button) { ?>onclick="Destatistics.page(this, 1, <?php echo $i; ?>); return false;"<?php } ?>>‹</a>

					<?php echo $page; ?> of
					<span class="total-pages">
						<?php echo $maximum_pages; ?></span></span>
				<a class="next-page<?php echo $disabled_next_page_button; ?>" title="Go to the next page" href="#" onclick="Destatistics.page(this, <?php echo $next_page; ?>, <?php echo $i; ?>); return false;">›</a>
				<a class="last-page<?php echo $disabled_last_page_button; ?>" title="Go to the last page" href="#" onclick="Destatistics.page(this, <?php echo $maximum_pages; ?>, <?php echo $i; ?>); return false; ">»</a></span>
			</div>

			<br class="clear">

			<?php
			$res['pagination_top'] = ob_get_contents();
			ob_end_clean();
		} else {
			$res['pagination_top'] = '';
		}

		$res['blog_id'] = $wpdb->de_statistics;
		echo json_encode( $res );
		exit;
	}

	public function dataRowStatisticTable( $item ) {

		?>
		<td class="manage-column column-num">
			<?php echo $item->i; ?>
		</td>
		<td class="manage-column column-title" style="word-wrap:initial;">
			<a href="<?php echo get_permalink( $item->ID ); ?>" title="<?php $title = get_the_title( $item->ID );
			echo $title; ?>"><?php echo $title; ?></a>
		</td>

		<td class="manage-column column-post-type" style="text-align: center;">
			<?php echo $item->post_type; ?>
		</td>

		<td class="manage-column column-views" style="text-align: center;">
			<?php echo $item->views_counts ? $item->views_counts : ''; ?>
		</td>
		<td class="manage-column column-comments" style="text-align: center;">
			<?php echo $item->comments_counts ? $item->comments_counts : ''; ?>
		</td>
		<td class="manage-column column-likes" style="text-align: center;">
			<?php echo $item->votes_sum ? $item->votes_sum : ''; ?>
		</td>

		<?php if ( get_option( "de_fb_enable" ) ) { ?>
			<td class="manage-column column-facebook" style="text-align: center;">
				<?php echo $item->fb_counts ? $item->fb_counts : ''; ?>
			</td>
		<?php } ?>

		<?php if ( get_option( "de_twi_enable" ) ) { ?>
			<td class="manage-column column-twitter" style="text-align: center;">
				<?php echo $item->tw_counts ? $item->tw_counts : ''; ?>
			</td>
		<?php } ?>

		<?php if ( get_option( "de_vk_enable" ) ) { ?>
			<td class="manage-column column-vk" style="text-align: center;">
				<?php echo $item->vk_counts ? $item->vk_counts : ''; ?>
			</td>
		<?php } ?>

		<?php if ( get_option( "de_gplus_enable" ) ) { ?>
			<td class="manage-column column-google-plus" style="text-align: center;">
				<?php echo $item->gplus_counts ? $item->gplus_counts : ''; ?>
			</td>
		<?php } ?>

		<?php if ( get_option( "de_pocket_enable" ) ) { ?>
			<td class="manage-column column-pocket" style="text-align: center;">
				<?php echo $item->pocket_counts ? $item->pocket_counts : ''; ?>
			</td>
		<?php } ?>

		<?php if ( get_option( "de_li_enable" ) ) { ?>
			<td class="manage-column column-pocket" style="text-align: center;">
				<?php echo $item->ln_counts ? $item->ln_counts : ''; ?>
			</td>
		<?php } ?>

		<?php if ( get_option( "de_ok_enable" ) ) { ?>
			<td class="manage-column column-pocket" style="text-align: center;">
				<?php echo $item->ok_counts ? $item->ok_counts : ''; ?>
			</td>
		<?php } ?>


		<td class="manage-column column-category" style="word-wrap:initial;">
			<?php echo $item->cat_name; ?>
		</td>
		<td class="manage-column column-author" style="word-wrap:initial;">
			<?php echo $item->user_name; ?>
		</td>
		<td class="manage-column column-post-created" style="word-wrap:initial;">
			<?php echo $item->post_date; ?>
		</td>
		<td class="manage-column column-post-updated" style="word-wrap:initial;">
			<?php echo ( $item->updated_time != '0000-00-00 00:00:00' ) ? $item->updated_time : ''; ?>
		</td>
		<td>
			<a href="#" onclick="Destatistics.update_single(this, <?php echo get_current_blog_id(); ?>, <?php echo $item->ID; ?>, <?php echo $item->i; ?>); return false;"><?php _e( 'Update data', DESTAT_TEXTDOMAIN ); ?></a>
		</td>

	<?php
	}

	public function deleteGAAccess() {

		$network = intval( $_POST['network'] );

		if ( $network ) {
			$functions = new DESTAT_Functions();
			$functions->deleteOption( "GAaccess_token" );
			$functions->deleteTransient( "GAaccess_token" );
			$functions->deleteOption( "GArefresh_token" );
			$functions->deleteOption( "GAUserProfile" );
			$functions->deleteOption( "GAAuthCode" );
			$res['is_network'] = '1';
		} else {
			delete_option( "GAaccess_token" );
			delete_transient( "GAaccess_token" );
			delete_option( "GArefresh_token" );
			delete_option( "GAUserProfile" );
			delete_option( "GAAuthCode" );
		}
		$res['single'] = '1';
		echo json_encode( $res );
		exit;
	}

	public function statisticsUpdateSingle() {

		global $wpdb;

		$post_id = $_POST['post_id'];

		$blog_id = $_POST['blog_id'];
		$i       = $_POST['i'];

		$stats = new DESTAT_Stats();
		ob_start();
		$res_sing_update = $stats->singleUpdateStatsPost( $post_id, $blog_id );
		$error           = ob_get_contents();
		ob_end_clean();

		$res = array();

		$query = " SELECT {$wpdb->de_statistics}.*, {$wpdb->posts}.* FROM {$wpdb->de_statistics} ";

		$query .= " INNER JOIN {$wpdb->posts} ON ( {$wpdb->de_statistics}.post_id = {$wpdb->posts}.ID ) ";

		$where = " WHERE 1=1 ";
		$where .= " AND {$wpdb->de_statistics}.blog_id = $blog_id ";
		$where .= " AND {$wpdb->de_statistics}.post_id = $post_id ";

		$query .= $where;

		$item = $wpdb->get_row( $query );

		$cat             = get_the_category( $item->ID );
		$user            = get_user_meta( $item->post_author );
		$item->i         = $i;
		$item->cat_name  = $cat['0']->name;
		$item->user_name = $user['first_name']['0'] . ' ' . $user['last_name']['0'];

		$res['posts'] = array();
		ob_start();
		$this->dataRowStatisticTable( $item );
		$res['posts'] = array(
			'post_id' => $post_id,
			'html'    => ob_get_contents(),
			'info'    => $error,
			'res'     => $res_sing_update
		);
		ob_end_clean();

		echo json_encode( $res );
		exit;
	}


	public function getDestatsDataForTableList() {


		$page      = $_REQUEST['page'];
		$count     = $_REQUEST['count'];
		$filter    = $_REQUEST['filter'];
		$sorting   = $_REQUEST['sorting'];
		$date_from = $_REQUEST['date_from'];
		$date_to   = $_REQUEST['date_to'];
		$author    = urldecode( $_REQUEST['author'] );
		$category  = urldecode( $_REQUEST['category'] );

		$offset = ( $page - 1 ) * $count;

		$blogid = get_current_blog_id();

		global $wpdb;

		$sql = "SELECT
					{$wpdb->posts}.post_title as post_title,
					{$wpdb->posts}.post_author as post_author,
					{$wpdb->users}.display_name as author,
					{$wpdb->de_statistics}.post_id,
					{$wpdb->de_statistics}.views_counts,
					{$wpdb->de_statistics}.comments_counts,
					{$wpdb->de_statistics}.votes_sum,
					{$wpdb->de_statistics}.fb_counts,
					{$wpdb->de_statistics}.tw_counts,
					{$wpdb->de_statistics}.vk_counts,
					{$wpdb->de_statistics}.ln_counts,
					{$wpdb->de_statistics}.gplus_counts,
					{$wpdb->de_statistics}.ok_counts,
					{$wpdb->de_statistics}.pocket_counts,
					{$wpdb->posts}.post_modified as created_time,
					{$wpdb->de_statistics}.updated_time,
					{$wpdb->terms}.name as category
					FROM $wpdb->de_statistics INNER JOIN $wpdb->posts
                        ON {$wpdb->de_statistics}.post_id = {$wpdb->posts}.ID
                        INNER JOIN $wpdb->users
                        ON {$wpdb->posts}.post_author = {$wpdb->users}.ID ";

		$sql .= "       INNER JOIN $wpdb->term_relationships
								ON {$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id ";

		$sql .= "       INNER JOIN $wpdb->term_taxonomy
								ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id";

		$sql .= "       INNER JOIN $wpdb->terms
								ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id ";


		if ( count( $filter ) > 0 ) {
			foreach ( $filter as $filter_name => $filter_value ) {
				$filter_value = urldecode( $filter_value );
				if ( $filter_name == 'author' ) {
					$filters .= " AND {$wpdb->users}.display_name LIKE '%$filter_value%'";
				} else {
					$filters .= " AND $filter_name LIKE '%$filter_value%'";
				}
			}
		}


		if ( $author ) {
			$filters .= " AND {$wpdb->users}.display_name = '$author'";
		}

		if ( $category ) {
			$filters .= " AND {$wpdb->terms}.name = '$category'";
		}

		if ( $date_from && $date_from != '1970-01-01' ) {
//			$filters .= " AND {$wpdb->posts}.post_modified >= '$date_from 00:00:00'";
			$filters .= " AND {$wpdb->de_statistics}.created_time >= '$date_from 00:00:00'";
		}

		if ( $date_to && $date_to != '1970-01-01' ) {
			$filters .= " AND {$wpdb->de_statistics}.created_time <= '$date_to 23:59:59'";
//			$filters .= " AND {$wpdb->posts}.post_modified <= '$date_to 23:59:59'";
		}


		if ( count( $sorting ) > 0 ) {
			$order = 'ORDER BY';
			foreach ( $sorting as $sorting_name => $sorting_value ) {
				$order .= ' ' . $sorting_name . ' ' . $sorting_value;
			}
		} else {
			$order = 'ORDER BY views_counts DESC';
		}

		$sql .= " WHERE 1=1 AND {$wpdb->term_taxonomy}.taxonomy = 'category' " . $filters;

		$sql .= " GROUP BY {$wpdb->de_statistics}.post_id $order LIMIT $offset, $count ";

		$destats_data = $wpdb->get_results( $sql );

		$sql1 = "SELECT {$wpdb->de_statistics}.post_id,
					{$wpdb->de_statistics}.views_counts,
					{$wpdb->de_statistics}.comments_counts,
					{$wpdb->de_statistics}.votes_sum,
					{$wpdb->de_statistics}.fb_counts,
					{$wpdb->de_statistics}.tw_counts,
					{$wpdb->de_statistics}.vk_counts,
					{$wpdb->de_statistics}.ln_counts,
					{$wpdb->de_statistics}.gplus_counts,
					{$wpdb->de_statistics}.ok_counts,
					{$wpdb->de_statistics}.pocket_counts
					FROM $wpdb->de_statistics INNER JOIN $wpdb->posts
                        ON {$wpdb->de_statistics}.post_id = {$wpdb->posts}.ID
                        INNER JOIN $wpdb->users
                        ON {$wpdb->posts}.post_author = {$wpdb->users}.ID
						INNER JOIN $wpdb->term_relationships
						ON {$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id
						INNER JOIN $wpdb->term_taxonomy
						ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
						INNER JOIN $wpdb->terms
						ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id
                        ";

		$sql1 .= " WHERE 1=1 " . $filters . " GROUP BY {$wpdb->de_statistics}.post_id";

		$count_rows = $wpdb->get_results( $sql1 );

		if ( $destats_data ) {

			$result = array();

			foreach ( $destats_data as $post ) {
				$result[] = array(
					'ID'              => $post->post_id,
					'href'            => get_permalink( $post->post_id ),
					'blogname'        => '',
					'title'           => $post->post_title,
					'post_type'       => $post->post_type,
					'views_counts'    => $post->views_counts ? $post->views_counts : ' ',
					'comments_counts' => $post->comments_counts ? $post->comments_counts : ' ',
					'votes_sum'       => $post->votes_sum ? $post->votes_sum : ' ',
					'fb_counts'       => $post->fb_counts ? $post->fb_counts : ' ',
					'tw_counts'       => $post->tw_counts ? $post->tw_counts : ' ',
					'vk_counts'       => $post->vk_counts ? $post->vk_counts : ' ',
					'ln_counts'       => $post->ln_counts ? $post->ln_counts : ' ',
					'gplus_counts'    => $post->gplus_counts ? $post->gplus_counts : ' ',
					'ok_counts'       => $post->ok_counts ? $post->ok_counts : ' ',
					'pocket_counts'   => $post->pocket_counts ? $post->pocket_counts : ' ',
					'category'        => $post->category,
					'author'          => $post->author,
					'created_time'    => $post->created_time,
					'updated_time'    => ( $post->updated_time == '0000-00-00 00:00:00' ) ? ' ' : $post->updated_time,
				);

			}
			$res = array(
				'result'        => $result,
				'total'         => count( $count_rows ),
				'total_records' => $count_rows,
				'get'           => $_GET,
				'$archive'      => $destats_data,
				'$sql'          => $sql,
				'$sql1'         => $sql1,
				'$sorting'      => $sorting,
				'$date_from'    => $date_from,
			);

		} else {
			$res = array(
				'result'        => '',
				'total'         => count( $count_rows ),
				'total_records' => $count_rows,
				'$sql'          => $sql,
				'$sorting'      => $sorting,
			);


		}


		echo json_encode( $res );
		die();
	}

	public function getFiltersData() {

		global $wpdb;

		$sql = "SELECT
					{$wpdb->users}.display_name as author,
					{$wpdb->terms}.name as category
					FROM $wpdb->de_statistics INNER JOIN $wpdb->posts
                        ON {$wpdb->de_statistics}.post_id = {$wpdb->posts}.ID
                        INNER JOIN $wpdb->users
                        ON {$wpdb->posts}.post_author = {$wpdb->users}.ID ";

		$sql .= "       INNER JOIN $wpdb->term_relationships
								ON {$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id ";

		$sql .= "       INNER JOIN $wpdb->term_taxonomy
								ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id";

		$sql .= "       INNER JOIN $wpdb->terms
								ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id ";

		$sql .= " WHERE {$wpdb->term_taxonomy}.taxonomy = 'category' ";

		$destats_data = $wpdb->get_results( $sql );

		if ( $destats_data ) {

			$result = array();

			foreach ( $destats_data as $post ) {
				$result[] = array(
					'category' => $post->category,
					'author'   => $post->author
				);

			}

		}
		$res = array(
			'result'     => $result,
			'get'        => $_GET,
			'$archive'   => $destats_data,
			'$sql'       => $sql,
			'$sql1'      => $sql1,
			'$sorting'   => $sorting,
			'$date_from' => $date_from,
		);


		echo json_encode( $res );
		die();
	}

}