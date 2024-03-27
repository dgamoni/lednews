jQuery(document).ready(function ($) {
	/****************
	 **    SEARCH
	 *****************/
		// Button
	jQuery('.search-text').click(function (e) {
		e.preventDefault();
		jQuery('body').addClass('search-activated');
		jQuery('.header-search-container').fadeIn(100);
		jQuery('.header-search-input').val('').focus();

		// bind esc key to close search window
		jQuery(document).keyup(function (e) {
			if (e.which == 27) {
				dc_close_search();
			}
		});
	});
// Keyup
	jQuery('.header-search-input').bindWithDelay("keyup", function (e) {
		if (jQuery(this).val() != '') {
			dc_search(jQuery(this).val());
		} else {
			jQuery('.header-search-results').html('');
			jQuery('.header-search-all').addClass('hide');
		}
	}, 400);
// AJAX
	// var url;
	// url = "/wp-admin/admin-ajax.php";
	
	var dc_search = function (term) {
		if (term != "") {
			jQuery('.header-search-results').fadeOut(400);
			loading_bar(true);
			jQuery.ajax({
				type   : "post",
				url    : location.origin + '/wp-admin/admin-ajax.php',
				data   : {action: 'dc_ajax_search', term: term},
				success: function (data, textStatus, xhr) {
					console.log(data);
					if (xhr.status == 204 || typeof data === 'undefined') {
						loading_bar(false);
						jQuery('.header-search-results').html('<h3>Ничего не найдено</h3>');
						jQuery('.header-search-all').addClass('hide');
					} else {
						jQuery('.header-search-container').scrollTop();
						// append results to ul element
						jQuery('.header-search-results').html(data);
						loading_bar(false);
						jQuery('.header-search-all').attr('href', location.origin + '/?s=' + term).removeClass('hide');
					}
				},
				error  : function () {
					loading_bar(false);
					jQuery('.header-search-all').addClass('hide');
				}
			});
		}
	}
// Loading Bar
	function loading_bar(start) {
		if (start) {
			if (jQuery("#preloader").length === 0) {
				jQuery(".header-search-container").append(jQuery("<div>&nbsp;</div>").attr("id", "preloader"));
				jQuery("#preloader").width((50 + Math.random() * 30) + "%");
			}
		} else {
			jQuery("#preloader").width("101%").delay(200).fadeOut(300, function () {
				jQuery(this).remove();
				jQuery('.header-search-results').fadeIn(400);
			});
		}
	}
});


// Close search window
function dc_close_search() {
	jQuery('.header-search-container').fadeOut(100);
	jQuery('body').removeClass('search-activated');
	jQuery('.header-search-input').val('').blur();
	jQuery('.header-search-all').addClass('hide');
	jQuery('.header-search-results').html('');
}