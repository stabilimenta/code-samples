jQuery(function($) {
	/**
	 * jquery.inview.js
	 * forked from http://github.com/zuk/jquery.inview/
	*/
	(function (factory) {
	  if (typeof define == 'function' && define.amd) {
		// AMD
		define(['jquery'], factory);
	  } else if (typeof exports === 'object') {
		// Node, CommonJS
		module.exports = factory(require('jquery'));
	  } else {
		  // Browser globals
		factory(jQuery);
	  }
	}(function ($) {

	  var inviewObjects = [], viewportSize, viewportOffset,
		  d = document, w = window, documentElement = d.documentElement, timer;

	  $.event.special.inview = {
		add: function(data) {
		  inviewObjects.push({ data: data, $element: $(this), element: this });
		  // Use setInterval in order to also make sure this captures elements within
		  // "overflow:scroll" elements or elements that appeared in the dom tree due to
		  // dom manipulation and reflow
		  // old: $(window).scroll(checkInView);
		  //
		  // By the way, iOS (iPad, iPhone, ...) seems to not execute, or at least delays
		  // intervals while the user scrolls. Therefore the inview event might fire a bit late there
		  //
		  // Don't waste cycles with an interval until we get at least one element that
		  // has bound to the inview event.
		  if (!timer && inviewObjects.length) {
			 timer = setInterval(checkInView, 250);
		  }
		},

		remove: function(data) {
		  for (var i=0; i<inviewObjects.length; i++) {
			var inviewObject = inviewObjects[i];
			if (inviewObject.element === this && inviewObject.data.guid === data.guid) {
			  inviewObjects.splice(i, 1);
			  break;
			}
		  }

		  // Clear interval when we no longer have any elements listening
		  if (!inviewObjects.length) {
			 clearInterval(timer);
			 timer = null;
		  }
		}
	  };

	  function getViewportSize() {
		var mode, domObject, size = { height: w.innerHeight, width: w.innerWidth };

		// if this is correct then return it. iPad has compat Mode, so will
		// go into check clientHeight/clientWidth (which has the wrong value).
		if (!size.height) {
		  mode = d.compatMode;
		  if (mode || !$.support.boxModel) { // IE, Gecko
			domObject = mode === 'CSS1Compat' ?
			  documentElement : // Standards
			  d.body; // Quirks
			size = {
			  height: domObject.clientHeight,
			  width:  domObject.clientWidth
			};
		  }
		}

		return size;
	  }

	  function getViewportOffset() {
		return {
		  top:  w.pageYOffset || documentElement.scrollTop   || d.body.scrollTop,
		  left: w.pageXOffset || documentElement.scrollLeft  || d.body.scrollLeft
		};
	  }

	  function checkInView() {
		if (!inviewObjects.length) {
		  return;
		}

		var i = 0, $elements = $.map(inviewObjects, function(inviewObject) {
		  var selector  = inviewObject.data.selector,
			  $element  = inviewObject.$element;
		  return selector ? $element.find(selector) : $element;
		});

		viewportSize   = viewportSize   || getViewportSize();
		viewportOffset = viewportOffset || getViewportOffset();

		for (; i<inviewObjects.length; i++) {
		  // Ignore elements that are not in the DOM tree
		  if (!$.contains(documentElement, $elements[i][0])) {
			continue;
		  }

		  var $element      = $($elements[i]),
			  elementSize   = { height: $element[0].offsetHeight, width: $element[0].offsetWidth },
			  elementOffset = $element.offset(),
			  inView        = $element.data('inview');

		  // Don't ask me why because I haven't figured out yet:
		  // viewportOffset and viewportSize are sometimes suddenly null in Firefox 5.
		  // Even though it sounds weird:
		  // It seems that the execution of this function is interferred by the onresize/onscroll event
		  // where viewportOffset and viewportSize are unset
		  if (!viewportOffset || !viewportSize) {
			return;
		  }

		  if (elementOffset.top + elementSize.height > viewportOffset.top &&
			  elementOffset.top < viewportOffset.top + viewportSize.height &&
			  elementOffset.left + elementSize.width > viewportOffset.left &&
			  elementOffset.left < viewportOffset.left + viewportSize.width) {
			if (!inView) {
			  $element.data('inview', true).trigger('inview', [true]);
			}
		  } else if (inView) {
			$element.data('inview', false).trigger('inview', [false]);
		  }
		}
	  }

		$(w).on("scroll resize scrollstop", function() {
			viewportSize = viewportOffset = null;
		});

		// IE < 9 scrolls to focused elements without firing the "scroll" event
		if (!documentElement.addEventListener && documentElement.attachEvent) {
			documentElement.attachEvent("onfocusin", function() {
				viewportOffset = null;
			});
		}

	}));

	function resizeElements() {
		$('body').addClass( 'ready' );

		$(".trigger").prop("checked", false);

		//collapsing sticky header
		$(window).scroll(function() {
			if ($(this).scrollTop() > 30){  
				$('header#org-header').addClass("sticky");
			}
			else{
				$('header#org-header').removeClass("sticky");
			}
		});

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
		var my_iframes = $( "iframe" );
 
		// Save the aspect ratio for all iframes
		my_iframes.each(function () {
		  $( this ).data( "ratio", this.height / this.width )
			// Remove the hardcoded width &#x26; height attributes
			.removeAttr( "width" )
			.removeAttr( "height" );
		});
 
		my_iframes.each( function() {
			// Get the parent container&#x27;s width
			var width = $( this ).parent().width();
			$(this).width(width).height( width * $( this ).data( "ratio" ) );
		});

	}

    $(window).on("load", function() {
		$('#homepage-page .down-arrow').show(200);
		resizeElements();
    });

	$( document ).ready(function() {
	  
		$("body").not('label[for="nav-trigger"]').click( function(e) {
			if ($(".trigger").prop("checked")){
				//$('.trigger').prop('checked', false);
			}
		});

		// test for webkit (Safari, Chrome) and identify with class
		if ('WebkitAppearance' in document.documentElement.style) {
			$("html").addClass("webkit");
		}

		// remove class that serves as css fail-safe
		$("body").removeClass( "js-no" );

		//collapsing sticky header
		$(window).scroll(function() {
			if ($(this).scrollTop() > 50){  
				$('header#masthead').addClass("sticky");
			}
			else{
				$('header#masthead').removeClass("sticky");
			}
		});

		// search button open	*** uses css for animation ***
		/*
		$('#search-control').click(function(){
			$('#masthead .search-box').addClass('active');
			$('#masthead input.search-term').focus();
			return false;
		});
	
		// search button close		
		$('.page-body, #colophon').click(function(){
			$('#masthead .search-box').removeClass('active');
		});

		//prevent empty submit
		$('#masthead .search-box').submit(function(event) {
			if ($(this).find('.search-term').val() == '') {
				event.preventDefault();
				$('#masthead .search-box').removeClass('active');
			}
		});
		*/
	 
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

		// collapsible sections
		// 	$('.accordion .item').each(function () {
		// 		console.log('item!');
		// 	});

		$('.accordion .control button').closest('.item').addClass('collapsed');
		$('.accordion .control button').closest('.item').find('.collapse').hide();
		$('.accordion .control button').attr('title', 'Click to open or close this section');
		$('.accordion .control button').click(function(){
			$(this).closest('.item').toggleClass('collapsed');
			$(this).closest('.item').find('.collapse').slideToggle(500);
			return false;
		});

		// animated anchor scrolling function from css-tricks.com
		$('a[href*="#"]:not([href="#"])').click(function() {
			resizeElements(); // resize elements on page load (function checks window size)
			// adjust offset for fixed header
			var headHeight = $('#org-header').height();
			// console.log(headHeight);
			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			  var target = $(this.hash);
			  var anchor = this.hash.slice(1);
			  target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			  if (target.length) {
				if (anchor == 'results') {
					//console.log(target.offset().top-headHeight-35 + ' WTF!!!??');
					$('html, body').animate({
						scrollTop: target.offset().top-headHeight-35 // adjust for recht's desire to move this anchor 
					}, 1000);
					$('#homepage-page .down-arrow').hide(600);
				} else {
					$('html, body').animate({
						scrollTop: target.offset().top-headHeight
					}, 1000);
				}
				
				return false;
			  }
			}
		});
	
		// 	$('.down-arrow').click(function() {
		// 		var docHeight = $(document).height();
		// 		var arrowPos = $(this).offset();
		// 		var clickDistance = Math.floor($(window).height() * 0.9);
		// 		if ((docHeight - arrowPos.top) < clickDistance ) {
		// 			clickDistance = docHeight - arrowPos.top;
		// 		}
		// 		if (clickDistance > 50) {
		// 			$('html, body').animate({
		// 				scrollTop: clickDistance + arrowPos.top
		// 			}, 1000);
		// 		}
		// 		return false;
		// 	});
		$('#intro').on('inview', function(event, isInView) {
			if (isInView) {
				$('#homepage-page .down-arrow').show(600);
			} else {
				$('#homepage-page .down-arrow').hide(600);
				// element has gone out of viewport
			}
		});
	
	}); //end ready functions
});

window.addEventListener("orientationchange", function() {
	resizeElements();
}, false);
