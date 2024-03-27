<?php
global $wpdb;

if ( ! $posts = $wpdb->get_results( "SELECT * FROM $wpdb->de_statistics WHERE flag_update = 0 ORDER BY post_id DESC" ) ) {

	// Обнуляем флаг для нового обновления если все обновилось
	// за указанный период,и начинаем заново обновлять
	$wpdb->get_results( "UPDATE $wpdb->de_statistics SET flag_update = 0 WHERE flag_update = 1 " );

	// Repeat the process
	$posts = $wpdb->get_results( "SELECT * FROM $wpdb->de_statistics WHERE flag_update = 0 ORDER BY post_id DESC" );
}
foreach ( $posts as $post ) {
	$import_ids[] = $post->post_id;
}
//echo count( $import_ids );
//print_r( $import_ids );

?>
<script type="text/javascript">
	jQuery(document).ready(function ($) {

		var num = 0;
		var pause_start = 0;
		var limit = 0;
		var offset = 0;
		var ids = '';
		var max_records_imported = 0;


		$('#pause_start').click(function () {
			var obj = $(this);
			if (pause_start == 1) {
				obj.html('Pause');
				pause_start = 0;
				doAjaxImport(limit, offset, ids);
			} else {
				pause_start = 1;
				obj.html('Start');
			}
		});

		$("#show_debug").click(function () {
			$("#debug").show();
			$(this).hide();
		});

		doAjaxImport(1, 0, JSON.stringify(<?php echo json_encode($import_ids); ?>));

		function doAjaxImport(limit, offset, ids) {
			var data = {
				"action": "destatistics-records-updater-ajax",
				"limit" : limit,
				"offset": offset,
				"ids"   : ids
			};

			//ajaxurl is defined by WordPress
			$.post(ajaxurl, data, ajaxImportCallback);
		}

		function ajaxImportCallback(response_text) {

			$("#debug").append($(document.createElement("p")).text(response_text));

			var response = jQuery.parseJSON(response_text);


			console.log(response);

			$("#insert_count").text(response.insert_count + " (" + response.insert_percent + "%)");
			$("#remaining_count").text(response.remaining_count);
			$("#row_count").text(response.row_count);

			//show inserted rows
			for (var row_num in response.inserted_rows) {
				var tr = $(document.createElement("tr"));

				if (response.inserted_rows[row_num]['success'] == true) {
					if (response.inserted_rows[row_num]['has_errors'] == true) {
						tr.addClass("error");
					} else {
						tr.addClass("success");
					}
				} else {
					tr.addClass("fail");
				}

				var post_link = $(document.createElement("a"));
				post_link.attr("target", "_blank");
				post_link.attr("href", "<?php echo get_admin_url(); ?>post.php?post=" + response.inserted_rows[row_num]['post_id'] + "&action=edit");
				post_link.text(response.inserted_rows[row_num]['post_id']);

				tr.append($(document.createElement("td")).append($(document.createElement("span")).addClass("icon")));
				num++;
				tr.append($(document.createElement("td")).text(num));
				tr.append($(document.createElement("td")).append(post_link));
				tr.append($(document.createElement("td")).text(response.inserted_rows[row_num]['name']));

				var result_messages = "";
				if (response.inserted_rows[row_num]['has_messages'] == true) {
					result_messages += response.inserted_rows[row_num]['messages'].join("\n") + "\n";
				}
				if (response.inserted_rows[row_num]['has_errors'] == true) {
					result_messages += response.inserted_rows[row_num]['errors'].join("\n") + "\n";
				} else {
					result_messages += "Updated";
				}
				tr.append($(document.createElement("td")).text(result_messages));

				tr.appendTo("#inserted_rows tbody");
			}

			//show error messages
			for (var message in response.error_messages) {
				$(document.createElement("li")).text(response.error_messages[message]).appendTo(".import_error_messages");
			}

			limit = response.limit;
			offset = response.new_offset;
			ids = JSON.stringify(response.ids);
			//move on to the next set!
			if (parseInt(response.remaining_count) > 0 && pause_start == 0) {
				if (max_records_imported > 0 && parseInt(response.remaining_count) >= max_records_imported) {
					return false;
				}
				doAjaxImport(response.limit, response.new_offset, JSON.stringify(response.ids));
			} else if (pause_start == 0) {
				$("#import_status").addClass("complete");
			}
		}
	});
</script>

<div class="deco__importer_wrapper wrap">
	<div id="icon-tools" class="icon32"><br /></div>
	<h2><?php _e( 'Update records &raquo; Results' ); ?></h2>

	<ul class="import_error_messages">
	</ul>

	<div id="import_status">
		<div id="import_in_progress">
			<img src="<?php echo DESTAT_COMPONENTS_URL; ?>/update-records/img/ajax-loader.gif"
			     alt="<?php _e( 'Updating. Please do not close this window or click your browser\'s stop button.' ); ?>"
			     title="<?php _e( 'Updating. Please do not close this window or click your browser\'s stop button.' ); ?>">

			<strong><?php _e( 'Updating. Please do not close this window or click your browser\'s stop button.' ); ?></strong>
		</div>
		<div id="import_complete">
			<img src="<?php echo DESTAT_COMPONENTS_URL; ?>/update-records/img/complete.png"
			     alt="<?php _e( 'Update complete!' ); ?>"
			     title="<?php _e( 'Update complete!' ); ?>">
			<strong><?php _e( 'Update Complete! Results below.' ); ?></strong>
		</div>


		<table>
			<tbody>
			<tr>
				<th><?php _e( 'Processed' ); ?></th>
				<td id="insert_count">0</td>
			</tr>
			<tr>
				<th><?php _e( 'Remainin' ); ?>g</th>
				<td id="remaining_count"><?php echo $post_data['row_count']; ?></td>
			</tr>
			<tr>
				<th><?php _e( 'Total' ); ?></th>
				<td id="row_count"><?php echo $post_data['row_count']; ?></td>
			</tr>
			</tbody>
		</table>
	</div>

	<button id="pause_start" data-status="0" class="button button-primary">Pause</button>
	<br>
	<table id="inserted_rows" class="wp-list-table widefat fixed pages" cellspacing="0">
		<thead>
		<tr>
			<th style="width: 30px;"></th>
			<th style="width: 80px;"><?php _e( 'Num' ); ?></th>
			<th style="width: 80px;"><?php _e( 'Post ID' ); ?></th>
			<th><?php _e( 'Name' ); ?></th>
			<th><?php _e( 'Result' ); ?></th>
		</tr>
		</thead>
		<tbody><!-- rows inserted via AJAX --></tbody>
	</table>

	<p><a id="show_debug" href="#" class="button"><?php _e( 'Show Raw AJAX Responses' ); ?></a>
	</p>

	<div id="debug"><!-- server responses get logged here --></div>

</div>