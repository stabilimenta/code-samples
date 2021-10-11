jQuery(function($) {

	function resizeElements() {
		$('body').addClass( 'ready' );

		$('.trigger').prop('checked', false);

		//collapsing sticky header
		// 		$(window).scroll(function() {
		// 			if ($(this).scrollTop() > 30){  
		// 				$('header#org-header').addClass('sticky');
		// 			}
		// 			else{
		// 				$('header#org-header').removeClass('sticky');
		// 			}
		// 		});

		// Resize reCAPTCHA to fit width of container
		// Width of the reCAPTCHA element, in pixels
		var reCaptchaWidth = 256;
		// Get the containing element's width
		var containerWidth = $('.inv-recaptcha-holder').width();
  
		// Only scale the reCAPTCHA if it won't fit
		// inside the container
		if(reCaptchaWidth > containerWidth) {
			// Calculate the scale
			var captchaScale = containerWidth / reCaptchaWidth;
			// Apply the transformation
			$('.grecaptcha-badge').css({
				'transform':'scale('+captchaScale+')'
			});
		}

		// Find all iframes
		var my_iframes = $( 'iframe' );
 
		// Save the aspect ratio for all iframes
		my_iframes.each(function () {
		  $( this ).data( 'ratio', this.height / this.width )
			// Remove the hardcoded width and height attributes
			.removeAttr( 'width' )
			.removeAttr( 'height' );
			// Get the parent container's width
			var width = $( this ).parent().width();
			$(this).width(width).height( width * $( this ).data( 'ratio' ) );
		});

	}

    $(window).on('load', function() {

		$('.menu-control').addClass('closed');
		resizeElements();
		
		if ( $.isFunction($.fn.colorbox) ) {
			$('.lightbox-g1').colorbox({
				rel:'lightbox-g1',
				// transition:'fade',
				width:'90%', 
				height:'90%',
				current:'',
				close:'<span class="visually-hidden">Close</span>',
				next:'<span class="visually-hidden">Next</span>',
				previous:'<span class="visually-hidden">Previous</span>',
				title: function(){
					  return $(this).siblings('figcaption').text();
					}
			});
		}
    });

	$( document ).ready(function() {
	  
	/* 
		// hack to make Google recaptcha pass WAVE accessibility test
		var onloadCallback = function() {
			console.log('recaptcha-response');
			$('.g-recaptcha-response').each(function () {
				$(this).attr('aria-hidden', true);
				$(this).attr('aria-label', 'do not use');
				$(this).attr('aria-readonly', true);
			});
		};
	*/

		// if menu button clicked... open/close menu
		$('.menu-control').click(function(){
			if ($(this).hasClass('active')) {
				var trans = $(this).removeClass('active');
				setTimeout(function() {
					trans.addClass('closed');
				}, 300);
			} else {
				var trans = $(this).removeClass('closed');
				setTimeout(function() {
					trans.addClass('active');
				}, 300);
			}
			return false;
		});
		
		$('body').not('label[for="nav-trigger"]').click( function(e) {
			if ($('.trigger').prop('checked')){
				//$('.trigger').prop('checked', false);
			}
		});

		// test for webkit (Safari, Chrome) and identify with class
		if ('WebkitAppearance' in document.documentElement.style) {
			$('html').addClass('webkit');
		}

		// remove class that serves as css fail-safe
		$('body').removeClass( 'js-no' );

		//collapsing sticky header
		// 		$(window).scroll(function() {
		// 			if ($(this).scrollTop() > 50){  
		// 				$('header#masthead').addClass('sticky');
		// 			}
		// 			else{
		// 				$('header#masthead').removeClass('sticky');
		// 			}
		// 		});

	 
		// Store the window width
		var windowWidth = $(window).width();
		// Resize Event
		$(window).resize(function(){
			// Check window width has actually changed and it's not just iOS triggering a resize event on scroll
			if ($(window).width() != windowWidth) {
				// Update the window width for next time
				windowWidth = $(window).width();
				// Do stuff here
				var resizeTimer;
				clearTimeout(resizeTimer);
				resizeTimer = setTimeout(function() {
					resizeElements();
				}, 250);
			}
		});

		$('.accordion .control button').closest('.item').addClass('collapsed');
		$('.accordion .control button').closest('.item').find('.collapse').hide();
		$('.accordion .control button').attr('title', 'Click to open or close this section');
		$('.accordion .control button').click(function(){
			$(this).closest('.item').toggleClass('collapsed');
			$(this).closest('.item').find('.collapse').slideToggle(500);
			return false;
		});

		if($().masonry) { 
			var $grid = $('.grid').masonry({
				itemSelector: '.grid-item',
				percentPosition: true,
				columnWidth: '.grid-sizer',
				gutter: '.gutter-sizer',
				percentPosition: true,
			});
		
			// layout Masonry after each image loads
			$grid.imagesLoaded().progress( function() {
			  $grid.masonry();
			});  
		
			var msnry = $grid.data('masonry');
		
			// $('.grid').on( 'scrollThreshold.infiniteScroll', function( event ) {
			//   console.log('Scroll at bottom');
			// });
			
			// init Infinite Scroll
			$grid.infiniteScroll({
				// Infinite Scroll options...
				append: '.grid-item',
				outlayer: msnry,
				path: '.next a',
				status: '.page-load-status',
				history: 'false',
				onInit: function() {
							this.on( 'load', function() {
								// $('.post-nav').each(function () {
								// 	$(this).addClass('visually-hidden');
								// });
								// MOVED BELOW 
								console.log('Infinite Scroll init')
							});
						}
			});
		
		}
		
		if ($().infiniteScroll) { 
			$('.post-nav').each(function () {
				$(this).addClass('visually-hidden');
			});
		}
		
	}); //end ready functions

	window.addEventListener('orientationchange', function() {
		resizeElements();
	}, false);

});

