var posts_per_page = 20,
	page = 1,
	category,
	date_from,
	date_to,
	author,
	post_type,
	search_text,
	orderby = 'post_date',
	order = 'DESC',
	loading_proccess = false;


var Destatistics = {
	filter       : function () {
		category = jQuery('#post_cat :selected').val();
//		date_from = jQuery('#post_author :selected').val();
//		date_to = jQuery('').val();
		author = jQuery('#post_author :selected').val();
		post_type = jQuery('#post_type :selected').val();
		this.load();

	},
	search       : function () {
		search_text = jQuery('#record-search-input').val();
		page = 1;
		this.load();
	},
	sorted       : function () {

	},
	page         : function (o, pg, i) {
		page = pg;
		this.load();
	},
	load         : function () {
		if (!jQuery('#the-list').length) {
			return false;
		}
		if (loading_proccess) {
			return false;
		}

		loading_proccess = true;

		jQuery('#the-list').css({opacity: 0.2});
		jQuery.ajax({
			type    : 'POST',
			url     : '/wp-admin/admin-ajax.php',
			dataType: 'json',
			data    : {
				'action'        : 'destat_show_table_stat',
				'posts_per_page': posts_per_page,
				'page'          : page,
				'date_from'     : date_from,
				'date_to'       : date_to,
				'author'        : author,
				'post_type'     : post_type,
				'category'      : category,
				'search'        : search_text,
				'orderby'       : orderby,
				'order'         : order
			},
			success : function (response) {
				console.log(response);
				jQuery('.destatictics-table-list tbody').html(response.html);
				jQuery('.tablenav-top').html(response.pagination_top);
				jQuery('.tablenav-bottom').html(response.pagination_top);
				jQuery('#the-list').css({opacity: 1});
				loading_proccess = false;
			}
		});
	},
	update_single: function (o, blog_id, post_id, i) {

		jQuery('.postview_' + post_id).css({opacity: 0.3});
		jQuery(o).fadeOut(300);
		jQuery.ajax({
			type    : 'POST',
			url     : '/wp-admin/admin-ajax.php',
			dataType: 'json',
			data    : {
				'action' : 'destat_update_single',
				'post_id': post_id,
				'blog_id': blog_id,
				'i'      : i
			},
			success : function (response) {
				console.log(response);

				jQuery('.postview_' + response.posts.post_id).html(response.posts.html);
				jQuery('.postview_' + response.posts.post_id).removeAttr('style');
				jQuery(o).fadeIn(300);
			}
		});
		return false;
	}
}


jQuery(document).ready(function () {
	//Destatistics.load();

	jQuery('#DeleteDeAccess').click(function () {

		jQuery('.ajax-setting-ga .inside').css({opacity: 0.1});

		var is_network = jQuery(this).data('network');

		jQuery.ajax({
			type    : 'POST',
			url     : '/wp-admin/admin-ajax.php',
			dataType: 'json',
			data    : {
				'action' : 'destat_delete_ga_access',
				'network': is_network
			},
			success : function (response) {

				console.log(response);

				jQuery('.ajax-setting-ga').load(location.href + ' #ajax-setting-ga', function () {
					jQuery('.ajax-setting-ga .inside').removeAttr('style');
				});
			}
		});
		return false;
	});
});
