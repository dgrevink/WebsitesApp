if (typeof jQuery == 'undefined') {
	alert('jQuery needed for media-support.js');
}
else {
	$(document).ready(function(){
		/* Convert Media */
		$('a.audio').media( { width: 400, height: 20 } );
		$('a.video').media();

		$('a.video-320x240').media( { width: 320, height: 260 } );
		$('a.video-400x300').media( { width: 400, height: 320 } );
		$('a.video-512x384').media( { width: 512, height: 404 } );
		$('a.video-640x480').media( { width: 640, height: 500 } );
		$('a.video-800x600').media( { width: 800, height: 620 } );

		$('a.video-320x180').media( { width: 320, height: 200 } );
		$('a.video-640x380').media( { width: 640, height: 400 } );
		$('a.video-1280x720').media( { width: 1280, height: 740 } );

		$('a.media').media();
	});
}
