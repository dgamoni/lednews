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

            }
        });

        return false;
    });
//end ajax

		// post 
		//$('.uptolike-buttons-content').css('display', 'none'); 


		// $('.post-share, .uptolike-buttons-content').hover(function () {
		//     $(".uptolike-buttons-content").css({display: 'inline-block'});
		// }, function () {
		//     $(".uptolike-buttons-content").css({display: 'none'});
		// });
		
});    
