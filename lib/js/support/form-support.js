/*
$(document).ready(function() {
	$('.ws-form').each(function(){
		id = $(this).parent().attr('id');
		var options = {
			 target: '#' + id,
			 beforeSubmit: function(formData, jqForm, options) {
				$('html,body').animate({scrollTop: $('#' + id).offset().top},100);
			 },
			 afterSubmit: function() {
				// destroy recaptcha object if instanciated
				if (typeof Recaptcha != 'undefined') {
					Recaptcha.destroy();
				}
			 }
		}
		$(this).ajaxForm(options);
	});
});
*/

function submitForm( id ) {
	var options = {
		 target: '#' + id,
		 beforeSubmit: function(formData, jqForm, options) {
			$('html,body').animate({scrollTop: $('#' + id).offset().top},100);
		 },
		 afterSubmit: function() {
			// destroy recaptcha object if instanciated
			if (typeof Recaptcha != 'undefined') {
				Recaptcha.destroy();
			}
		 }
	}
	$('#' + id + ' form').ajaxForm(options);
	
	$('#' + id + ' form').submit();
	
	
}


/*
function submitForm( id ) {
	form = $('#' + id + ' form');
	if (form) {
		var options = {
			 target: '#' + id,
			 beforeSubmit: function(formData, jqForm, options) {
				$('html,body').animate({scrollTop: $('#' + id).offset().top},100);
			 }
		};
		form.ajaxSubmit(options);
		
		// destroy recaptcha object if instanciated
		if (typeof Recaptcha != 'undefined') {
			Recaptcha.destroy();
		}
	}
}
*/
