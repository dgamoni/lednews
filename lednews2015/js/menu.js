/**
 * Theme functions file
 *
 * Contains handlers for navigation, accessibility, header sizing
 * footer widgets and Featured Content slider
 *
 */
( function( $ ) {
	var body    = $( 'body' ),
		_window = $( window );

	// Enable menu toggle for small screens.
	( function() {
		var nav = $( '#primary-navigation' ), button, menu;
		if ( ! nav ) {
			return;
		}

		button = nav.find( '.menu-toggle' );
		if ( ! button ) {
			return;
		}

		// Hide button if menu is missing or empty.
		menu = nav.find( '.nav-menu' );
		if ( ! menu || ! menu.children().length ) {
			button.hide();
			return;
		}

		$( '.menu-toggle' ).on( 'click.codium_light', function() {
			nav.toggleClass( 'toggled-on' );
		} );
	} )();

	

	$( function() {
		// Search toggle.
		$( '.search-toggle' ).on( 'click.codium_light', function( event ) {
			var that    = $( this ),
				wrapper = $( '.search-box-wrapper' );

			that.toggleClass( 'active' );
			wrapper.toggleClass( 'hide' );

			if ( that.is( '.active' ) || $( '.search-toggle .screen-reader-text' )[0] === event.target ) {
				wrapper.find( '.search-field' ).focus();
			}
		} );


		// Focus styles for menus.
		$( '.primary-navigation, .secondary-navigation' ).find( 'a' ).on( 'focus.codium_light blur.codium_light', function() {
			$( this ).parents().toggleClass( 'focus' );
		} );
	} );


} )( jQuery );

var sticky = document.querySelector('.header');
//var origOffsetY = sticky.offsetHeight;
var origOffsetY = sticky.offsetTop;

function onScroll(e) {
  window.scrollY >= origOffsetY ? sticky.classList.add('fixed') :
                                  sticky.classList.remove('fixed');
}

document.addEventListener('scroll', onScroll);

    
jQuery(document).ready(function($){
	
        // handle the mouseenter functionality
        jQuery(".img").mouseenter(function(){
            jQuery(this).addClass("hover");
        })
        // handle the mouseleave functionality
        .mouseleave(function(){
            jQuery(this).removeClass("hover");
        });



        // gallery

        $('.variable-width').slick({
		  //dots: true,
		  infinite: true,
		  speed: 300,
		  slidesToShow: 1,
		  centerMode: true,
		  variableWidth: true
		});

		//slider
		$('.multiple-items').slick({
		  infinite: true,
		  // centerMode: true,
		  slidesToShow: 3,
		  slidesToScroll: 1,
		  // centerPadding: '60px',
            responsive: [
                            {
                              breakpoint: 1000,
                              settings: {
                                slidesToShow: 2,
                              }
                            },
                            {
                              breakpoint: 650,
                              settings: {
                                slidesToShow: 1,
                              }
                            },
                            // {
                            //   breakpoint: 480,
                            //   settings: {
                            //     slidesToShow: 1,
                            //   }
                            // }
                        ]
		});

		//scroll
	$('#scroller').css('right',(($(window).width() - 1100)/2) - 105 + 'px');
    $(window).scroll(function(){
        if ($(this).scrollTop() > 180) {
            if(!$('#scroller').hasClass('show')){
                $('#scroller').addClass('show');
            }
            if(!$('.mainhead').hasClass('sticky')){
                $('.mainhead').addClass('sticky');
            }
        } else {
            if($('#scroller').hasClass('show')) {
                $('#scroller').removeClass('show');
            }
            if($('.mainhead').hasClass('sticky')){
                $('.mainhead').removeClass('sticky');
            }
        }
    })
    $(document).on('click', '#scroller', function (e) {
        e.preventDefault();
        $('body, html').animate({scrollTop: 0}, 200);
    });


    //  ----------------------------------ajax 
    var click_show_more_post = false;

    /*	AJAX pagination	*/
    $('.show_more_post').on('click', function () {

        if (click_show_more_post)
            return false;
        click_show_more_post = true;
        var obj = $(this),
            post_type = obj.data('post-type'),
            max_pages = obj.data('max-pages'),
            current_page = obj.data('current-page'),
            next_page = obj.data('next-page'),
            taxonomy_name = obj.data('taxonomy-name'),
            taxonomy_term = obj.data('taxonomy-term'),
            search_string = obj.data('search-string'),
			exclude_posts = obj.data('exclude-posts'),
            action = obj.data('action'),
            posts_per_page = obj.data('posts-per-page'),
            inner_class = obj.data('inner-class'),
            author_id = obj.data('author-id');

        //console.log(action);

        $(inner_class).animate({
            'opacity': 0.1
        }, 200);
        $('.noajax').animate({
            'opacity': 0.1
        }, 200);

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: "POST",
            dataType: 'json',
            data: {
                'action': action,
                'post_type': post_type,
                'author_id': author_id,
                'posts_per_page': posts_per_page,
                'max_pages': max_pages,
                'current_page': current_page,
                'next_page': next_page,
                'taxonomy_name': taxonomy_name,
                'taxonomy_term': taxonomy_term,
                'search_string': search_string,
                'exclude_post': exclude_posts
            },
            success: function (response) {

            	console.log(response);
            	

                obj.data('current-page', response.current_page);
                obj.data('next-page', response.next_page);


                if (response.hide_link) {
                    obj.animate({
                        'opacity': 0
                    }, 400, function () {
                        obj.remove()
                    });
                }

                if (response.html) {
                    $(inner_class).append(response.html);
                }

                $(inner_class).animate({
                    'opacity': 1
                }, 400, function () {
                    $(this).removeAttr('style');
                });
                $('.noajax').animate({
                    'opacity': 1
                }, 400, function () {
                    $(this).removeAttr('style');
                });

                click_show_more_post = false;

                // search highlight
                if(typeof(hls_query) != 'undefined'){
                  $(".search-results .type-post .entry-title, .search-results .type-post .entry-content").highlight(hls_query);
                }


            }
        });

        return false;
    });
//end ajax

// colorpiker
$('.color-informer').colpick({
    layout:'hex',
    submit:1,
    colorScheme:'light',
    onChange:function(hsb,hex,rgb,el,bySetColor) {
        $(el).css('border-color','#'+hex);
        // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
        if(!bySetColor) $(el).val(hex);
    }
}).keyup(function(){
    $(this).colpickSetColor(this.value);

});
// colorpiker button
$('.button-color').colpick({
    layout:'hex',
    submit:1,
    colorScheme:'light',
    onChange:function(hsb,hex,rgb,el,bySetColor) {
        $(el).css('border-color','#'+hex);
        // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
        if(!bySetColor) $(el).val(hex);
        
        var button_color = hex;
        //console.log(button_color);
        var button_size = $('.button-size').val();
        $('#inner-informer-button').html("<script> var widget_embed = 'button'; var size_button = '"+button_size+"'; var button_color='"+button_color+"'; </script>");
        $('#base-inner-informer-button').html('<script src="http://test.lednews.ru/widget/wp-widget-button.js" type="text/javascript"></script>');
        $('#xmp-informer-button').html('<xmp><!-- custom код кнопки lednews.ru --><script type="text/javascript"> var widget_embed = "button";var size_button = "'+button_size+'"; var button_color="'+button_color+'";</script><script src="http://test.lednews.ru/widget/wp-widget-button.js" type="text/javascript"></script><div id="embed-widget-container2"></div><!-- end код кнопки lednews.ru --></xmp>');
    }
}).keyup(function(){
    $(this).colpickSetColor(this.value);


});



// informer new script
$('.change-informer').change(function(event) {
    //console.log($(this).val());
    var this_posttype = $('.change-informer-post_type').val();
    var count = $('.change-informer-count').val();
    var informer_width = $('.change-informer-width').val();
    var informer_type = $('.informer_type').val();

    var img_border_px = $('.change-informer-img-border-px').val();
    var img_color = $('.change-informer-img-color').val();
    var img_border_type = $('.change-informer-img-border-type').val();
    //console.log(img_color);

    var font = $('.change-font').val();

    $('#inner-informer').html("<script>var widget_embed = 'posts'; var posttype = '"+this_posttype+"';var count = '"+count+"';var width_iframe = '"+informer_width+"'; var informer_type = '"+informer_type+"'; var img_border_px = '"+img_border_px+"'; var img_color = '"+img_color+"'; var img_border_type = '"+img_border_type+"'; var fontt = '"+font+"';</script>");
    $('#base-inner-informer').html('<script src="http://test.lednews.ru/widget/wp-widget.js" type="text/javascript"></script>');
    $('#xmp-informer').html("<xmp><!-- custom informer code lednews --><script>var widget_embed = 'posts'; var posttype = '"+this_posttype+"';var count = '"+count+"'; var width_iframe = '"+informer_width+"'; var informer_type = '"+informer_type+"'; var img_border_px = '"+img_border_px+"'; var img_color = '"+img_color+"'; var img_border_type = '"+img_border_type+"'; var fontt = '"+font+"';</script><script src='http://test.lednews.ru/widget/wp-widget.js' type='text/javascript'></script><div id='embed-widget-container'></div><!-- informer code lednews --></xmp>");
    
});

// informer new script for button
$('.change-informer-button').change(function(event) {
     var button_size = $('.button-size').val();
     var button_color = $('.button-color-val').val();
     //console.log(button_color);
    
    $('#inner-informer-button').html("<script> var widget_embed = 'button'; var size_button = '"+button_size+"'; var button_color='"+button_color+"'; </script>");
    $('#base-inner-informer-button').html('<script src="http://test.lednews.ru/widget/wp-widget-button.js" type="text/javascript"></script>');
    $('#xmp-informer-button').html('<xmp><!-- custom код кнопки lednews.ru --><script type="text/javascript"> var widget_embed = "button";var size_button = "'+button_size+'"; var button_color="'+button_color+'";</script><script src="http://test.lednews.ru/widget/wp-widget-button.js" type="text/javascript"></script><div id="embed-widget-container2"></div><!-- end код кнопки lednews.ru --></xmp>');
    
});



 


// var iframe = top.frames[name].document;
// var css = '' +
//           '<style type="text/css">' +
//           '.inf-thumbnail{display:none}' +
//           '</style>';
// iframe.open();
// iframe.write(css);
// iframe.close();






// adaptiv informer iframe
var windowsize = $(window).width();

$(window).resize(function() {
  windowsize = $(window).width();
  if (windowsize < 632) {
 
    //console.log(windowsize);
    // $('#led_informer_iframe').contents().find('li').css({
    //     background-color: 'antiquewhite'
    // });

  }
});


// $('.colpick_submit').click(function(event) {
//      var button_size = $('.button-size').val();
//       var button_color = $('.button-color-val').val();
//      console.log(button_color);
    
//     $('#inner-informer-button').html("<script> var widget_embed = 'button'; var size_button = '"+button_size+"'; </script>");
//     $('#base-inner-informer-button').html('<script src="http://test.lednews.ru/widget/wp-widget-button.js" type="text/javascript"></script>');

   
//     $('#xmp-informer-button').html('<xmp><!-- custom код кнопки lednews.ru --><script type="text/javascript"> var widget_embed = "button";var size_button = "'+button_size+'";</script><script src="http://test.lednews.ru/widget/wp-widget-button.js" type="text/javascript"></script><div id="embed-widget-container2"></div><!-- end код кнопки lednews.ru --></xmp>');
    
// });



		// post 
		//$('.uptolike-buttons-content').css('display', 'none'); 


		// $('.post-share, .uptolike-buttons-content').hover(function () {
		//     $(".uptolike-buttons-content").css({display: 'inline-block'});
		// }, function () {
		//     $(".uptolike-buttons-content").css({display: 'none'});
		// });


    
    // search highlight
    if(typeof(hls_query) != 'undefined'){
      $(".search-results .type-post .entry-title, .search-results .type-post .entry-content").highlight(hls_query);
    }

		
});    


function deco_soc_sharing_window(url, name) {

    var popup_width = 300;
    var popup_height = 400;
    var popup_top = Math.max(0, (window.outerHeight - popup_height) / 2);
    var popup_left = Math.max(0, (window.outerWidth - popup_width) / 2);

    if (window.showModalDialog) {
        window.showModalDialog(url, name, "dialogWidth:500px;dialogHeight:500px");
    } else {
        window.open(url, name, 'height=500,width=500,toolbar=no,directories=no,status=no,linemenubar = no,scrollbars = no,resizable=no,modal=yes,left=' + popup_left + ',top=' + popup_top);
    }
}



