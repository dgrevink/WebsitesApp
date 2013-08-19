function submitAjaxForm(e) {
	var message = $('message');
	
	e.send({
		onComplete: function(req){
			$('message').innerHTML = req;
			(function(){
				$('message').innerHTML = '';
			 }).delay(5000);
		}
	});
};

function showNotify( data ) {
			var data = eval('(' + data + ')');
			if (data.type == 'info') {
				$.jGrowl(data.message);
			}
			else {
				$.jGrowl(data.message, { header: 'Important' })
			}
}

$(document).ready(function(){

	// CSS tweaks
	$('#header #nav li:last').addClass('nobg');
	$('.block_head ul').each(function() { $('li:first', this).addClass('nobg'); });
	$('.websites-forms-input-select #menus').parent().parent().css('height', '100px');
	$('.websites-forms-input-select #access').parent().parent().css('height', '100px');

	// Messages
	$('.block .message').hide().append('<span class="close" title="Dismiss"></span>').fadeIn('slow');
	$('.block .message .close').hover(
		function() { $(this).addClass('hover'); },
		function() { $(this).removeClass('hover'); }
	);
		
	$('.block .message .close').click(function() {
		$(this).parent().fadeOut('slow', function() { $(this).remove(); });
	});

	// Tabs
	$(".tab_content").hide();
	$("ul.tabs li:first-child").addClass("active").show();
	$(".block").find(".tab_content:first").show();

	$("ul.tabs li").click(function() {
		$(this).parent().find('li').removeClass("active");
		$(this).addClass("active");
		$(this).parents('.block').find(".tab_content").hide();
			
		var activeTab = $(this).find("a").attr("href");
		$(activeTab).show();
		
		// refresh visualize for IE
		$(activeTab).find('.visualize').trigger('visualizeRefresh');
		
		return false;
	});

	// Sidebar Tabs
	$(".sidebar_content").hide();
	
	if(window.location.hash && window.location.hash.match('sb')) {
	
		$("ul.sidemenu li a[href="+window.location.hash+"]").parent().addClass("active").show();
		$(".block .sidebar_content#"+window.location.hash).show();
	} else {
	
		$("ul.sidemenu li:first-child").addClass("active").show();
		$(".block .sidebar_content:first").show();
	}

	$("ul.sidemenu li").click(function() {
	
		var activeTab = $(this).find("a").attr("href");
		window.location.hash = activeTab;
	
		$(this).parent().find('li').removeClass("active");
		$(this).addClass("active");
		$(this).parents('.block').find(".sidebar_content").hide();			
		$(activeTab).show();
		return false;
	});	
			

	// Change language
	$('select#current_language').change(function(){
		current_language = $(this).val();
		if (window.location.pathname == '/admin/') {
			window.location.pathname = '/admin/' + current_language + '/';
		}
		else {
			newPathName = '';
			pathArray = window.location.pathname.split( '/' );
			pathArrayLength = pathArray.length - 1;
//			alert( '<<<<' + window.location.pathname );
			for (i=1; i<pathArrayLength; i++) {
//				alert('>>>>>' + pathArray[i] + '<<<<<');
				newPathName += '/';
				if (i == 2) {
					newPathName += current_language;
				}
				else {
					if (pathArray[i] != '') {
						newPathName += pathArray[i];
					}
				}
			}
				newPathName += '/';
				
			window.location.pathname = newPathName;
//				alert(newPathName);
//			alert(window.location.pathname);
		}
	});
	
	
});