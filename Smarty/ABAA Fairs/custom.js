function makeTimer(endTime, timerID) {
	//console.log(timerID);
	//endTime = new Date('18 Sept 2020 12:32:00 GMT-04:00');
	endTime = (Date.parse(endTime) / 1000);

	var now = new Date();
	now = (Date.parse(now) / 1000);

	var timerStr = '';
	
	if (endTime > now) {
		var timeLeft = endTime - now;
		var days = Math.floor(timeLeft / 86400);
		var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
		var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600 )) / 60);
		var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));
		//console.log("days:" + days + " hours:" + hours + " minutes:" + minutes + " seconds:" + seconds);

		if (hours < 10) { hours = 0 + hours; }
		if (minutes < 10) { minutes = 0 + minutes; }
		if (seconds < 10) { seconds = 0 + seconds; }
		if ( days > 0) {
			timerStr += '<span class="group"><span class="number">' + days + '</span> <span class="unit">Day';
			if (days != 1) { timerStr += 's'; }
			timerStr += '</span>';
			if ( timerID == 'to-start' )  {
				timerStr += ', </span>';
			} else {
				timerStr += '</span>';
			}
		}
		if ( days > 0 || hours > 0) {
			timerStr += '<span class="group"><span class="number">' + hours + '</span> <span class="unit">Hour';
			if (hours != 1) { timerStr += 's'; }
			timerStr += '</span>';
			if (timerID == 'to-start') {
				if ( days < 1 ) { // case when going to show seconds, so need another comma before minutes
					timerStr += ', ';
				} else {
					timerStr += ' and ';
				}
			}
			timerStr += '</span>';
		} 
		
		if ( days > 0 || hours > 0 || minutes > 0) { 
			timerStr += '<span class="group"><span class="number">' + minutes + '</span> <span class="unit">Minute';
			if (minutes != 1) { timerStr += 's'; }
			timerStr += '</span>';
			if ( days < 1 && timerID == 'to-start' ) { // case when going to show seconds, so need another comma before minutes
				timerStr += ' and </span>';
			} else {
				timerStr += '</span>';
			}
		}
		
		if ( days < 1 ) {
			timerStr += '<span class="group"><span class="number">' + seconds + '</span> <span class="unit">Second';
			if (seconds != 1) { timerStr += 's'; }
			timerStr += '</span>';
			timerStr += '</span>';
		}
		if ( timerID == 'to-start' )  {
			timerStr += ' <span class="group">Until the Fair </span>';
		}
	} else if ( timerID == 'to-start' )  {  /* ie: endTime less than now  and timerID == to start */
		timerStr += '<span class="timer-over started">The Fair Has<br>Started!</span>';
	}
	$('.countdown-timer').html(timerStr);

}

function is_touch_device() {
	return !!('ontouchstart' in window);
}

function resizeElements() {

	// 	reset all trigger checkboxes
	$('.trigger').prop('checked', false); 
	
	// reset search widget
	$('header#masthead .widget form').removeAttr('style');
	$('#access ul.menu').removeAttr('style');
	
	$('#search-filters').removeClass('active').css('top', '');
	// console.log('reset search-filters');

	// Find all iframes and
	$('iframe').each(function () {
		//only looking for ones with specified height and width
		if ((!isNaN($(this).attr('height'))) && (!isNaN($(this).attr('width')))) {
			// something special for recaptchas
			if ($(this).closest('.g-recaptcha').length ) {
				// Resize reCAPTCHA to fit width of container
				// Width of the reCAPTCHA element, in pixels
				var reCaptchaWidth = 304;
				// Get the containing element's width
				var containerWidth = $(this).closest('.recaptcha-holder').width();
				//reset on resize
				$(this).removeAttr('style');
		  
				// Only scale the reCAPTCHA if it won't fit
				// inside the container
				if(reCaptchaWidth > containerWidth) {
					// console.log(containerWidth + ' = containerWidth');
					// Calculate the scale
					var captchaScale = containerWidth / reCaptchaWidth;
					// Apply the transformation
					$(this).css({
						'transform-origin':'center left',
						'transform':'scale('+captchaScale+')'
					});
				}
			} else {			
				// console.log('had height and width');
				var new_width = $(this).parent().width();
				// Save the aspect ratio for all iframes
				$(this).data( "ratio", this.height / this.width );
				$(this).data( "orig_w", this.width );
				$(this).data( "orig_h", this.height );
				// Remove the hardcoded width &#x26; height attributes
				$(this).removeAttr( "width" );
				$(this).removeAttr( "height" );
				// Get the parent container's width
				$(this).width( new_width ).height( Math.round(new_width * $(this).data( "ratio" )) );
			}
		}
	})


   /* 
		******* DETECT SMALL SCREEN DEVICES *****
		uses a stylesheet definition as the test because screen size detection unreliable
		TEST IT, because jquery sometimes isn't returning the same value as specified!
		also, testing for widths can fail because of browser zooming, which will return an unexpected 
		value too.
    */ 
     
	// 	alert (jQuery( 'body').css('border-right-width' ));

	/* detect arbitrary css style to trigger "mobile" events */
	if ($('body').css('border-bottom-style') == 'dotted') { 	  	

		$('body').removeClass('desktop tablet');
		$('body').addClass( 'mobile' );

		/* 
			menus and search slideout are using a hidden checkbox to trigger their events
			everything works basically without javascript, but we use JS to make it better.
		*/
				
		// close other overlays when one is checked
		$('.trigger').click( function(){
			$('.trigger').not(this).prop('checked', false);
		});
		
		// close all by unchecking control checkboxes when page cover is clicked
		$('.cover, #page-content').click( function(){
			$('.trigger').prop('checked', false);
		});
		$('.md-carousel, .lg-carousel').removeClass('carousel');

	} else { 
		/* detect arbitrary css style to trigger "tablet" events */
		if ($('body').css('border-bottom-style') == 'dashed') {
			$('body').removeClass('desktop mobile');
			$('body').addClass( 'tablet' );
			$('.lg-carousel').removeClass('carousel');
		} else { 
			/* detect arbitrary css style to trigger "desktop" events */
			if ($('body').css('border-bottom-style') == 'solid') {
				$('body').removeClass('tablet mobile' );
				$('body').addClass('desktop');
				
				$('#book-details .description').removeClass('expander hidden-content').css('max-height', 'none');
				$('.lg-carousel').addClass('carousel');
				
			}
		}
		/* This stuff fires for tablet and desktops */
		$('.phone-1 a').bind('click', false);
		$('a.phone').bind('click', false);

		$('#access ul.menu').removeAttr( 'style' ); // reset menus from slideToggle
		
		$('.md-carousel').addClass('carousel');
		
	}

}

$( document ).ready(function() {

	// remove class that serves as css fail-safe
	$('body').removeClass( 'no-js' );
	
	$('body').addClass( 'jq-ready' );

	// console.log(getCookie('cookie_policy_accepted'));

	var thisDomain = document.domain;
	var domainSlug = thisDomain.replace(/\.+/g, '_');

    if (getCookie('cookie_policy_accepted_' + domainSlug) != 'true') {
		console.log('cookie disclaimer');
	    $('#cookie-disclaimer').css('display','block');
		var disclaimer_height = $('#cookie-disclaimer').outerHeight();
		padFooter(disclaimer_height);
		// console.log(disclaimer_height);
    }

	if (!!getCookie('results_style')) {
		if (getCookie('results_style') == 'type-1') {
			$('#results-list').removeClass('type-2').addClass('type-1');
			$('button#list').removeClass('active');
			$('button#grid').addClass('active');
		} else if (getCookie('results_style') == 'type-2'){
			$('#results-list').removeClass('type-1').addClass('type-2');
			$('button#list').addClass('active');
			$('button#grid').removeClass('active');
		}
	}

	$('#style-selector button').click(function(){
		/* this allows the visitor to switch the layout of the search results list from a list to a grid */
		var list_style = $(this).attr('data-style');
		setCookie('results_style_' + domainSlug, list_style, 30, thisDomain);
		if (list_style == 'type-1') {
			$('#results-list').removeClass('type-2').addClass('type-1');
			$('button#list').removeClass('active');
			$('button#grid').addClass('active');
		} else if (list_style == 'type-2'){
			$('#results-list').removeClass('type-1').addClass('type-2');
			$('button#list').addClass('active');
			$('button#grid').removeClass('active');
		}
	})

	$('#filters-trigger').click( function(){
		$('#search-filters').toggleClass('active');
		var filterTriggerOffset = $(this).offset();
		filterTriggerOffset = Math.floor(filterTriggerOffset.top);
		filterTriggerOffset += 'px';
		$('#search-filters').css('top', filterTriggerOffset);
		// console.log('active toggled no check on filters position');
	});
	
	$('#search-filters .close').click( function(){
		$('#search-filters').removeClass('active');
	});

	if (!!$.prototype.endlessRiver) {	// check that endlessriver is a loaded function
		$(".endlessriver.sponsors .slides").endlessRiver({speed: 50,buttons: false});
	}

	$('.multi-item-carousel').on('slide.bs.carousel', function (e) {
	  let $e = $(e.relatedTarget),
	      itemsPerSlide = 3,
	      totalItems = $('.carousel-item', this).length,
	      $itemsContainer = $('.carousel-inner', this),
	      it = itemsPerSlide - (totalItems - $e.index());
	  if (it > 0) {
	    for (var i = 0; i < it; i++) {
	      $('.carousel-item', this).eq(e.direction == "left" ? i : 0).
	        // append slides to the end/beginning
	        appendTo($itemsContainer);
	    }
	  }
	});
	    
	// test for webkit (Safari, Chrome) and identify with class
	if ('WebkitAppearance' in document.documentElement.style) {
        $('html').addClass('webkit');
    }
    
	$('.menu-control').next('.menu').removeClass('active');
	$('.menu-control').prop('aria-expanded', false);
	
	$( '.menu-control' ).click(function() {

		// 	close all but the one that is clicked
		$('.menu-control').not(this).next('.menu').removeClass('active');
		$('.menu-control').not(this).prop('aria-expanded', false);
		
		if ($(this).attr('aria-expanded') == 'false' ){
			$(this).attr('aria-expanded', 'true');
		} else {
			$(this).attr('aria-expanded', 'false');
		}
		$(this).toggleClass('active');
		$(this).next('.menu').toggleClass('active');
		
	});
	
	//console.log('custom.js loaded!');

	$('.expander').each(function() {
		/*
			This calculates the height for a div that hides content if it is beyond a given number of lines.
			Uses data-rows html attribute to pass the number of lines desired to be shown into function.
		*/
		var natHeight = $(this).outerHeight();
		var innerHeight = $(this).height();
		var extraHeight = natHeight - innerHeight;
		var lineHeight = $(this).css('line-height');
		lineHeight = lineHeight.replace(/[a-zA-Z]/g,'');
		if (lineHeight < 8) {
			lineHeight = Math.floor(parseInt($(this).css('font-size').replace(/[a-zA-Z]/g,'')) * 1.5);
		}
		var maxLines = $(this).data('rows');
		var minHeight = (lineHeight * maxLines) + extraHeight;
		if (natHeight > minHeight) {
			if (!$(this).hasClass('hidden-content')) {
				$(this).addClass('hidden-content');
				$(this).append('<div class="open"><span tabindex="0" role="button" aria-pressed="false"></span></div>');
			}
			$(this).css('max-height', minHeight);
		}
	});
		
	$('.expander .open').click(function(){
		$(this).parent().toggleClass('active');
	})

	var headHeight = jQuery('header#page-header').height();

	$('#main-menu a').click(function(event) {
		$('#main-menu.active').removeClass('active');
		$('#main-menu-button').removeClass('active').prop('aria-expanded', false);
	});
	
	$('#header-search button.close').click(function(event) {
		$('#main-menu-button').removeClass('active').prop('aria-expanded', false);
		// $('#header-search').collapse('hide');
		//console.log('collpsed!');
	});
			
	$('.jump-menu').change(function(){
		window.location = $(this).val();
	});
			
	// animated anchor scrolling function from css-tricks.com
	$('a[href*="#"]:not([href="#"], .carousel-direction)').click(function() {
		// resizeElements(); // resize elements on page load (function checks window size)

		// adjust offset for fixed header

		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			
			if (target.length) {
				if ($(this).is("#header-search-control")){
				    $('#header-search').on('shown.bs.collapse', function() {
						$('html, body').animate({
						scrollTop: target.offset().top-headHeight
						}, 1000);
						return false;
				    });
				} else {
					$('html, body').animate({
					scrollTop: target.offset().top-headHeight
					}, 1000);
					return false;
				}

			}
		}
	});
	
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
			}, 500);
        }
    });

	$('#cookie-disclaimer .btn').click(function(){
		$('#cookie-disclaimer').slideUp('600');
		setCookie('cookie_policy_accepted_' + domainSlug, true, 30, thisDomain);
		//console.log(getCookie('cookie_policy_accepted'));
		$('#page-footer').css('height', '');
	})

//    checkIsOneFilled();
    
    
    $('.one-of-these-req').on('input', function () {
        checkIsOneFilled();
    });

	$('#header-search form input[type=checkbox]').click(function() {
        checkIsOneFilled();
	});

	$('#header-search form').submit(function(event) {
		if ($(this).hasClass('needs-validation')) {
			event.preventDefault();
			checkIsOneFilled('submit');
		}
	});

}); //end ready functions

function checkIsOneFilled(caller) {
	/*
		this function assures that either author, title OR ISBN is entered when doing a book search
	*/
    let valid = false;
    $('.invalid-feedback').css('display', '');
    $('.one-of-these-req').each(function() {
        if ($(this).val().length > 0){
            valid = true;
        }
    });
    if(valid == true){
	    setTimeout(() => { $('#header-search form').removeClass('needs-validation')},1);
        $('.one-of-these-req').each(function() {
	        if ($(this).prop('required')) {
				$(this).attr('required', false);
				var element = $(this)[0];
				element.setCustomValidity('');
	        }
        });
	    if (caller=='submit') {
		    $('#header-search form').submit();
	    }
    } else {
        $('.invalid-feedback').css('display', 'block');
        $('.one-of-these-req').each(function() {
	        if (!$(this).prop('required')) {
				$(this).attr('required', true);
				var element = $(this)[0];
				element.setCustomValidity('');
	            if (!element.validity.valid) {
	                element.setCustomValidity('One of these fields must be completed.');
	            }
	        }
        });
    }
}

$(window).on('load', function () {
	resizeElements();

	$('.countdown-timer').each(function () {
		var timerID = $(this).attr('id');
		var endTime = new Date($(this).data('endtime') * 1000);
		setInterval(function() { 
			makeTimer(endTime, timerID);
		}, 1000);
	})

});

window.addEventListener('orientationchange', function() {
	resizeElements();
}, false);

function setCookie(name,value,days,domain) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/; SameSite=none; Secure; domain=" + domain;
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {   
    document.cookie = name+'=; Max-Age=-99999999;';  
}

function padFooter(amt) {
	$('#page-footer').css('height', '');
	var origFtrHt = $('#page-footer').outerHeight();
	var newFtrHt = Math.floor(amt + origFtrHt);
	$('#page-footer').outerHeight(newFtrHt);
}
