jQuery(document).ready(function($) {
	$('.tab-content:not(.current)').css('display', 'none');
	$('body').on('click', '.tabs-nav a', function(event) {
        event.preventDefault();
        $(this).parent().addClass("current");
        $(this).parent().siblings().removeClass("current");
        var tab = $(this).attr("href");
        tab = tab.replace("#", "."); // change anchor tag ID to class so it works with repeating sections
        // alert(tab);
		$(this).closest('.cmb2-tabs').children('.tab-content').not(tab).css('display', 'none');
		$(this).closest('.cmb2-tabs').children(tab).fadeIn();
    });
    
    // These are for a character counter and limiter for SEO Meta Description
	if($('.seo-desc-count textarea').length > 0 ){ //check that element exists before beginning to avoid throwing errors
		var maxLength = 160;
		$('.seo-desc-count textarea').attr('maxlength',maxLength); // set maximum allowable on field attribute
		var initLength = $('.seo-desc-count textarea').val().length; // get current length on page load
		var initLength = maxLength-initLength;
		$('#chars').text(initLength);

		$('.seo-desc-count textarea').keyup(function() {
			var length = $(this).val().length;
			var length = maxLength-length;
			$('#chars').text(length);
		});
	}
    
    // These are for a character counter and limiter for SEO Title tag
	if($('.seo-title-count input').length > 0 ){ //check that element exists before beginning to avoid throwing errors
		var maxTitleLength = 55;
		$('.seo-title-count input').attr('maxlength',maxTitleLength); // set maximum allowable on field attribute
		var initTitleLength = $('.seo-title-count input').val().length; // get current length on page load
		var initTitleLength = maxTitleLength-initTitleLength;
		$('#title-chars').text(initTitleLength);

		$('.seo-title-count input').keyup(function() {
			var TitleLength = $(this).val().length;
			var TitleLength = maxTitleLength-TitleLength;
			$('#title-chars').text(TitleLength);
		});
	}

	$('.content-classes input[type=checkbox]').change(function() {
		var ThisClass = $(this).val();
		if (ThisClass != null) {
			if ($(this).is(':checked')) {
				// $(this).closest('.tab-content').find('iframe').contents().find('body').css('border', '15px lime solid');
				$(this).closest('.tab-content').find('iframe').contents().find('body').addClass(ThisClass);
			} else {
				$(this).closest('.tab-content').find('iframe').contents().find('body').removeClass(ThisClass);
			}
		}
	});
// 	$('div[name~="section-background-color"] button.color-alpha').change(function() {
// 		alert('beans');
// 		var ThisColor = $(this).css('background');
// 		if ((ThisColor != null) && (ThisColor !=="#")) {
// 			alert (ThisColor);
// 		}
// 	});

// 	$( 'div[class~="section-image"] input.cmb2-upload-file' ).on( "change", function() {
// 	  alert( 'Beer' );
// 	});
// 	$( 'div[class~="section-image"] input.cmb2-upload-file' ).val().trigger( "change" );

$("input[type=hidden]").bind("change", function() {
    console.log($(this).val()); 
 });

	$('div[class~="section-image"] input.cmb2-upload-file').change(function() {
		alert('background image!');
// 		var ThisColor = $(this).css('background');
// 		if ((ThisColor != null) && (ThisColor !=="#")) {
// 			alert (ThisColor);
// 		}
	});

});
