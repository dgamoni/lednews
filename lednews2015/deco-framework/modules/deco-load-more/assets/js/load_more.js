/**
 * Load more js function
 */

jQuery(document).on('click', 'a.download-more', function () {

    function noimgfound(){
        jQuery('body').find('.img-load').each(function () {
            if (!jQuery(this).find('img').length) {
                jQuery(this).parent().parent().addClass('no-img-found');
            }
        });
    }
    noimgfound();



    event.preventDefault();
    var obj = jQuery(this);
    var data;
    var max_num_pages = obj.data('max-num-pages');
    var content_block = obj.data('content-block');
    var url;
    var lang = obj.data('lang');

    data = '&page=' + obj.data('page');
    data += '&cat=' + obj.data('cat');
    data += '&name_type=' + obj.data('name-type');
    data += '&tax_type=' + obj.data('tax-type');
    data += '&uri=' + obj.data('uri');
    data += '&post_not_in=' + obj.data('post-not-in');
    data += '&max_num_pages=' + max_num_pages;
    data += '&content_block=' + content_block;
    data += '&is_search=' + obj.data('is-search');
    data += '&is_author=' + obj.data('is-author');
    data += '&lang1=' + lang;

    if (lang) {
        url = "/wp-admin/admin-ajax.php?lang=" + lang;
    } else {
        url = "/wp-admin/admin-ajax.php";
    }


    jQuery('a.download-more').css({'visibility': 'hidden'});

    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: "action=load_more" + data,
        success: function (a) {
            console.log(a);
            jQuery(content_block).append(a.content);
            jQuery('#deco-paginate').remove();
            jQuery(content_block).append(a.paginate);
            jQuery('a.download-more').data('max_num_pages', max_num_pages);
            jQuery('a.download-more').css({'visibility': 'visible'});

            noimgfound();
        }
    })
});
