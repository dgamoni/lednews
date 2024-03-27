jQuery(document).ready(function () {
    jQuery('#comment_auth_button').click(function () {
        jQuery('.loader-box').show();
        var deco_url_reload_page = location.href;
        var deco_comment_text_before_auth = jQuery('.decomments-editor').val();
        var data = jQuery('#modal-reg').serialize();
        jQuery.ajax({
            type: "POST",
            url: location.origin + '/wp-admin/admin-ajax.php',
            data: "action=deco_get_is_email_registered&" + data,
            dataType: 'json',
            success: function (a) {
                deco_wsl_addon_debuger(a);
                if (a.status == 'error_email') {
                    jQuery('#modal-auth-popup-body-login .modal-error').html(a.mes);
                    jQuery('.loader-box').hide();
                } else if (a.status == 'user_exist') {
                    deco_wsl_addon_debuger('user exist');
                    deco_wsl_addon_debuger(data);
                    url_action = location.origin + '/wp-login.php';
                    deco_auth_or_register_and_ajax_content_reload(url_action, data, deco_comment_text_before_auth, deco_url_reload_page, 'auth');
                } else if (a.status == 'error_pass') {
                    jQuery('#modal-auth-popup-body-login .modal-error').html(a.mes);
                    jQuery('.loader-box').hide();
                } else {
                    //Пользователя нет, регистрируем
                    deco_wsl_addon_debuger('no user exist');
                    url_action = location.origin + '/wp-admin/admin-ajax.php';
                    var user_login = jQuery('#user-name-log').val();
                    data += "&user_email=" + user_login + '&user_login=' + user_login + '&action=deco_registered_and_logging_user_in_process';
                    deco_auth_or_register_and_ajax_content_reload(url_action, data, deco_comment_text_before_auth, deco_url_reload_page, 'reg');
                }

            }
        });
        return false;
    });

    jQuery('#comment_reset_button').click(function () {
        jQuery('.loader-box').show();
        var user_login = jQuery('#reset_user_login').val();
        jQuery.ajax({
            type: "POST",
            url: location.origin + '/wp-admin/admin-ajax.php',
            data: "action=deco_password_pre_reset_check_email&user_login=" + user_login,
            dataType: 'json',
            success: function (a) {
                deco_wsl_addon_debuger(a);
                if (a.status == 'success') {
                    deco_wsl_addon_reset_pass(user_login);
                } else {
                    jQuery('.loader-box').hide();
                    jQuery('#modal-auth-popup-body-reset-pass .modal-error').html(a.mes);
                }
            }
        });
        return false;
    });

    jQuery(document).on('click touchstart', '.wp-social-login-provider-list .wp-social-login-provider, .wsl_connect_with_provider', function () {
        jQuery('.loader-box').show();
        var current_url = location.href;
        var url = jQuery(this).attr('href');
        var arr_url = url.split('redirect_to=');
        var popup_width = 300;
        var popup_height = 400;
        var popup_top = Math.max(0, (window.outerHeight - popup_height) / 2);
        var popup_left = Math.max(0, (window.outerWidth - popup_width) / 2);

        url = arr_url[0] + 'redirect_to=' + location.origin + '/?popup_auth=1';
        //deco_wsl_addon_debuger(url);

        var deco_comment_text_before_auth = jQuery('.decomments-editor').val();
        closeModal();

        var win_soclog = window.open(url, 'Авторизация через социальную сеть', 'width=' + popup_width + ',height=' + popup_height + ',toolbar=no,directories=no,status=no,linemenubar=no,scrollbars=no,resizable=no,modal=yes,left=' + popup_left + ',top=' + popup_top);

        deco_check_is_logged_success(win_soclog, current_url, deco_comment_text_before_auth);

        return false;
    });
});


function deco_wsl_addon_reset_pass(user_login) {
    jQuery.ajax({
        type: "POST",
        url: location.origin + '/wp-login.php?action=lostpassword',
        data: "user_login=" + user_login,
        dataType: 'html',
        success: function (res) {
            jQuery('#deco-form-reset-pass').remove();
            var mess = jQuery('<div>' + res + '</div>').find('.message').text();
            deco_wsl_addon_debuger(res);
            jQuery('#deco-auth-modal-reset-message').html('<p>' + mess + '</p>').show();
            jQuery('.loader-box').hide();
        }
    });
}

function deco_auth_or_register_and_ajax_content_reload(url_action, data, deco_comment_text_before_auth, deco_url_reload_page, action) {

    var data_type;
    if (action == 'reg') {
        data_type = 'json';
    } else {
        data_type = 'html';
    }

    jQuery.ajax({
        type: "POST",
        url: url_action,
        data: data,
        dataType: data_type,
        success: function (a) {
            if (action == 'reg') {
                jQuery('.loader-box').hide();
                deco_wsl_addon_debuger('registration');
                jQuery('#modal-auth-popup-body-login .modal-error').html(a.mes);
            } else {
                deco_wsl_addon_debuger('logining');
                jQuery('body').load(deco_url_reload_page + " #subbody", function () {
                    if (deco_comment_text_before_auth) {
                        jQuery('.decomments-editor').val(deco_comment_text_before_auth);
                    }
                    deco_reinit_js_functions_after_load_content();
                    closeModal();
                });

            }
        }
    });

}

function deco_check_is_logged_success(win_soclog, current_url, deco_comment_text_before_auth) {
    deco_wsl_addon_debuger('start');
    jQuery.ajax({
        type: "POST",
        url: location.origin + '/wp-admin/admin-ajax.php',
        data: "action=deco_get_auth_is_success",
        dataType: 'json',
        success: function (a) {
            deco_wsl_addon_debuger(a);
            if (a.status == 1) {
                jQuery('body').load(current_url + " #subbody", function () {
                    if (deco_comment_text_before_auth) {
                        jQuery('.decomments-editor').val(deco_comment_text_before_auth);
                    }
                    deco_reinit_js_functions_after_load_content();
                    win_soclog.close();
                    win_soclog = 'undefined';
                    deco_wsl_addon_debuger('ok soc auth');
                });
            } else {
                deco_wsl_addon_debuger('no auth');
                if (win_soclog.closed) {
                    closeModal();
                    deco_wsl_addon_debuger('popup closed');
                    win_soclog = 'undefined';
                    deco_wsl_addon_debuger('stop check soc auth');
                } else {
                    deco_check_is_logged_success(win_soclog, current_url, deco_comment_text_before_auth);
                }

            }
        }
    });
}


function deco_reinit_js_functions_after_load_content() {
    jQuery('#header').hcSticky({
        responsive: true,
        top: parseInt($('body').attr('data-top'))
    });
    var contentHeight = jQuery(".main .post_height").height();
    var sidebarHeight = jQuery(".main  .sidebar").height();

    if (sidebarHeight < contentHeight) {
        jQuery(".related_posts").addClass("fullWidth");
        // jQuery('.comment_box.wrap .column_c23').css('margin-left','-100px');
        // jQuery('.comment_box').width(1260);
        jQuery('.comment_box').addClass('comm_full');
        jQuery('.comment_box.wrap .column_c13').css({
            'visibility': 'visible',
            'opacity': '1'
        });
    }
    else {
        jQuery('.comment_box.wrap .column_c13').remove();
        // jQuery('.comment_box.wrap .column_c23').width(735).css('margin-left','0');
        jQuery('.comment_box.wrap .column_c23').addClass('comm_small');

    }


    if (jQuery('.widget.widget_list + .widget_banners.widget_list').exists() && jQuery('.related_posts').exists()) {

        var wLength = jQuery('.sidebar>.widget_banners.widget_list').length;

        jQuery(".sidebar>.widget_banners.widget_list").eq(wLength - 1).wrapAll("<div class='sidebar_hcSticky' />");
        jQuery(window).scroll(function () {
            var topla = jQuery(".sidebar_hcSticky").offset().top - jQuery(window).scrollTop();
            var odorobla = jQuery(".related_posts").offset().top - 120;
            var barbaras = jQuery(window).scrollTop() + jQuery(".sidebar_hcSticky .widget_banners.widget_list").height() + 200;
            var brichka = odorobla - jQuery(".sidebar_hcSticky .widget_banners.widget_list").height() - jQuery(".sidebar_hcSticky").offset().top - 81;
            if (topla < 150) {
                if (odorobla < barbaras) {
                    jQuery(".sidebar_hcSticky .widget_banners.widget_list").css({
                        "position": "absolute",
                        "top": brichka + "px",
                        "left": "0px",
                        "width": "300px"
                    });
                    console.log('odorobla  barbaras');
                } else {
                    jQuery(".sidebar_hcSticky .widget_banners.widget_list").css({
                        "position": "absolute",
                        "top": (jQuery(window).scrollTop() - jQuery(".sidebar_hcSticky").offset().top) + 114,
                        "left": "0px",
                        "width": "300px"
                    });
                    console.log('else1');
                }
            } else {
                jQuery(".sidebar_hcSticky .widget_banners.widget_list").removeAttr("style");
                console.log('else2');
            }
        });
    }
    jQuery('body').prepend("<link rel='stylesheet' id='dashicons-css'  href='http://ain.stage.decollete.com.ua/wp-includes/css/dashicons.min.css?ver=4.1.1' type='text/css' media='all' /> <link rel='stylesheet' id='admin-bar-css'  href='http://ain.stage.decollete.com.ua/wp-includes/css/admin-bar.min.css?ver=4.1.1' type='text/css' media='all' />");


}

// тут js для модалки
var closeModal = function () {
    jQuery('.main-box').animate({top: '-100%'}, 200, function () {
        jQuery('#modal-auth .flipper').removeClass('rotate');
    });
    jQuery('.modal').fadeOut(200);
    jQuery('.loader-box').hide();

};
jQuery('.acc-in-social, .login_zone a, .open_log_modal').click(function (e) {
    //e.preventDefault();
    var $modal = jQuery('.modal');
    if ($modal.css('display') != 'block') {
        jQuery('.modal').css('display', 'block').fadeIn(200);
        jQuery('.main-box').animate({opacity: 1, top: '50%'}, 200);
        var firstClick = true;
        if (jQuery('.modal').hasClass('close-on-but')) {
            jQuery('.modal').removeClass('close-on-but');
            return false;
        }
        jQuery(document).bind('click.myEvent', function (e) {
            if (!firstClick && jQuery(e.target).closest('.main-box').length == 0) {
                closeModal();
                jQuery(document).unbind('click.myEvent');
            }
            firstClick = false;
        });
    }
});

function deco_wsl_addon_debuger(data) {
    var deco_wls_debug_enable = 0;
    if (deco_wls_debug_enable) {
        console.log(data);
    }
}
jQuery(document).on('click', '.close', function () {
    closeModal();
    jQuery('.modal').addClass('close-on-but');
    deco_wsl_addon_debuger('blablabla');
});
