/*
These are includes, do not remove !

@codekit-prepend "../../../../lib/js/jquery/jquery-1.8.2.js"
@codekit-prepend "../../../../lib/js/jquery/jquery.ecrypt.js"
@codekit-prepend "../../../../lib/js/jquery/jquery.cookie.js"
@codekit-prepend "../../../../lib/js/jquery/fancybox/fancybox/jquery.fancybox-1.3.4.js"
@codekit-prepend "../../../../lib/js/jquery/jquery.form.js"
@codekit-prepend "../../../../lib/js/support/form-support.js"
@codekit-prepend "../../../../lib/js/jquery/anythingslider/js/jquery.anythingslider.js"
*/

$(document).ready(function() {
    // Decrypt all emails
    $('a').edecrypt();

	$("tr:even").addClass("evenrow");
	$(".testimony p").append("<div class='c66'></div><div class='c99'></div>");
	
//	var maxHeight = 0;
//	$('#content .col').each(function(){
//		height = $(this).height();
//		if (height > maxHeight) { maxHeight = height; }
//	}).promise().done(function(){
//		maxHeight = maxHeight - 10;
//		$('#content .col').height(maxHeight);
//	});

	$('#slider').anythingSlider({
		theme: 'default',
		mode: 'fade',
		buildArrows: false,
		buildNavigation: false,
		buildStartStop: false,
		autoPlay: true,
		hashTags: false,
		animationTime: 600,
		delay: 3000
	});
	
	$(window).scroll( function() {
		if ($(window).scrollTop() > $('#content-wrapper').offset().top) {
			$('.left-col').addClass('floating');
		}
		else {
			$('.left-col').removeClass('floating');
		}
	} );

	
});


