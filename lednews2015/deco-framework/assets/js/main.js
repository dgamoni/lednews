function deco_load_more_deputat_news(obj) {

	var page = obj.data('page');
	var action = obj.data('action');
	var terms = obj.data('terms');
	var id_content_block = "." + obj.data('block-content');
	//console.log("action=" + action + "&page=" + page + "&content_format=" + content_format + "&limit=" + limit);
	jQuery('.overlayer2').show();
	jQuery(id_content_block).css({'opacity': 0.7});
	obj.animate({'opacity': 0}, 200, function () {
		obj.css({'visibility': 'hidden'});
	});

	jQuery.ajax({
		type    : "POST",
		url     : location.origin + '/wp-admin/admin-ajax.php',
		dataType: "json",
		data    : "action=" + action + "&page=" + page + "&terms=" + terms,
		success : function (a) {
			console.log(a);
			if (a.content) {
				obj.data('page', a.page);
				jQuery(id_content_block).append(a.content);
				jQuery(id_content_block).css({'opacity': 1});
			}
			if (a.page < a.max_pages) {
				jQuery(id_content_block).css({'opacity': 1});
				obj.css({'visibility': 'visible'}).animate({'opacity': 1}, 200);
			}
			jQuery('.overlayer2').hide();

		}
	});
}

function deco_load_more_not_deputat_news(obj) {

	var page = obj.data('page');
	var action = obj.data('action');
	var terms = obj.data('terms');
	var id_content_block = "#" + obj.data('block-content');

	jQuery('.overlayer2').show();
	jQuery(id_content_block).css({'opacity': 0.7});
	obj.animate({'opacity': 0}, 200, function () {
		obj.css({'visibility': 'hidden'});
	});

	jQuery.ajax({
		type    : "POST",
		url     : location.origin + '/wp-admin/admin-ajax.php',
		dataType: "json",
		data    : "action=" + action + "&page=" + page + "&terms=" + terms,
		success : function (a) {
			console.log(a);
			if (a.content) {
				obj.data('page', a.page);
				jQuery(id_content_block).append(a.content);
				jQuery(id_content_block).css({'opacity': 1});
			}
			if (a.page < a.max_pages) {
				jQuery(id_content_block).css({'opacity': 1});
				obj.css({'visibility': 'visible'}).animate({'opacity': 1}, 200);
			}
			jQuery('.overlayer2').hide();

		}
	});
}
function deco_load_more_archive_news(obj) {

	var page = obj.data('page');
	var action = obj.data('action');
	var category_name = obj.data('category-name');
	var year = obj.data('year');
	var month = obj.data('month');
	var day = obj.data('day');
	var author = obj.data('author');
	var tag = obj.data('tag');
	var id_content_block = "." + obj.data('block-content');
	var search_string = obj.data('search');

	jQuery('.overlayer2').show();
	jQuery(id_content_block).css({'opacity': 0.7});
	obj.animate({'opacity': 0}, 200, function () {
		obj.css({'visibility': 'hidden'});
	});

	jQuery.ajax({
		type    : "POST",
		url     : location.origin + '/wp-admin/admin-ajax.php',
		dataType: "json",
		data    : "action=" + action + "&page=" + page + "&category_name=" + category_name + "&year=" + year + "&month=" + month + "&day=" + day + "&tag=" + tag + "&athor=" + author + "&search=" + search_string,
		success : function (a) {
			console.log(a);
			if (a.content) {
				obj.data('page', a.page);
				jQuery(id_content_block).append(a.content);
				jQuery(id_content_block).css({'opacity': 1});
			}
			if (a.page < a.max_pages) {
				jQuery(id_content_block).css({'opacity': 1});
				obj.css({'visibility': 'visible'}).animate({'opacity': 1}, 200);
			}
			jQuery('.overlayer2').hide();

		}
	});
}

