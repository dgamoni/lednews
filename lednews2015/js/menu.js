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
    })



});    
