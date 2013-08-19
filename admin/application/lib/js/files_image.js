			var api;
			$(window).load(function(){
			     api = $.Jcrop('#cropbox', {
					bgColor:	'white',
					bgOpacity:	.4,
					onChange:	showCoords,
					onSelect:	showCoords
			    });
			});
			
			$('#crop').click( function(e) {
				$('#dialog-text').html("Cette operation ne pourra pas etre annulee. Travaillez sur une copie si vous n'êtes pas sûr de vous ! Voulez vous vraiment decouper cette image ?");
				$("#dialog-text").dialog({
					resizable: false,
					width: 440,
					modal: true,
					buttons: {
						'OK': function() {
							$(this).dialog('close');
							form = new Object();
							form.x = $('#x').val();
							form.y = $('#y').val();
							form.x2 = $('#x2').val();
							form.y2 = $('#y2').val();
							form.width = $('#w').val();
							form.height = $('#h').val();
							form.destwidth = $('#width').val();
							form.destheight = $('#height').val();
							form.filename = $('#filename').val();
							form.crop = true;
							
							$.ajax({
								type: "POST",
								url: '/admin/files/crop/',
								data: form,
								success: function(msg) {
									window.location.reload();
								}
							});
						},
						'Annuler': function() {
							$(this).dialog('close');
						}
					}
				});
			});

			$('#pad').click( function(e) {
				if ( ($('#width').val() == '') || ($('#height').val() == '') ) {
					$('#dialog-text').html("Vous devez spécifier une largeur et une hauteur pour faire un padding.");
					$("#dialog-text").dialog({
						resizable: false,
						width: 440,
						modal: true,
						buttons: {
							'OK': function() {
								$(this).dialog('close');
							}
						}
					});
				}
				else {
					$('#dialog-text').html("Cette operation ne pourra pas etre annulee. Travaillez sur une copie si vous n'êtes pas sûr de vous ! Voulez vous vraiment padder cette image ?");
					$("#dialog-text").dialog({
						resizable: false,
						width: 440,
						modal: true,
						buttons: {
							'OK': function() {
								$(this).dialog('close');
								form = new Object();
								form.x = $('#x').val();
								form.y = $('#y').val();
								form.x2 = $('#x2').val();
								form.y2 = $('#y2').val();
								form.width = $('#w').val();
								form.height = $('#h').val();
								form.destwidth = $('#width').val();
								form.destheight = $('#height').val();
								form.filename = $('#filename').val();
								form.crop = false;
								
								$.ajax({
									type: "POST",
									url: '/admin/files/crop/',
									data: form,
									success: function(msg) {
										window.location.reload();
									}
								});
							},
							'Annuler': function() {
								$(this).dialog('close');
							}
						}
					});
				}
			});

			$('#rotate').click( function(e) {
					$('#dialog-text').html("Cette operation ne pourra pas etre annulee. Travaillez sur une copie si vous n'êtes pas sûr de vous ! Voulez vous vraiment réorienter cette image ?");
					$("#dialog-text").dialog({
						resizable: false,
						width: 440,
						modal: true,
						buttons: {
							'OK': function() {
								$(this).dialog('close');
								form = new Object();
								form.angle = $('#angle').val();
								form.filename = $('#filename').val();
								
								$.ajax({
									type: "POST",
									url: '/admin/files/rotate/',
									data: form,
									success: function(msg) {
										window.location.reload();
									}
								});
							},
							'Annuler': function() {
								$(this).dialog('close');
							}
						}
					});
			});

			$('#filter-go').click( function(e) {
					$('#dialog-text').html("Cette operation ne pourra pas etre annulee. Travaillez sur une copie si vous n'êtes pas sûr de vous ! Voulez vous vraiment réorienter cette image ?");
					$("#dialog-text").dialog({
						resizable: false,
						width: 440,
						modal: true,
						buttons: {
							'OK': function() {
								$(this).dialog('close');
						        $.blockUI({ 
    						        theme:     false, 
        						    message: '<h1>Opération en cours, veuillez patienter SVP.</h1>'
       							});
								form = new Object();
								form.action = $('#filtre').val();
								form.filename = $('#filename').val();
								
								$.ajax({
									type: "POST",
									url: '/admin/files/set_filter/',
									data: form,
									success: function(msg) {
										var timestamp = new Date().getTime();
										var img_src = $('#cropbox').attr('src');
										$('#cropbox').attr('src', img_src + '?' + timestamp);
										$.unblockUI();
									}
								});
							},
							'Annuler': function() {
								$(this).dialog('close');
							}
						}
					});
			});

			$('#halve').click( function(e) {
					$('#dialog-text').html("Cette operation ne pourra pas etre annulee. Travaillez sur une copie si vous n'êtes pas sûr de vous ! Voulez vous vraiment réduire cette image de moitié ?");
					$("#dialog-text").dialog({
						resizable: false,
						width: 440,
						modal: true,
						buttons: {
							'OK': function() {
								$(this).dialog('close');
								form = new Object();
								form.x = 0;
								form.y = 0;
								form.x2 = $('#cropbox').width();
								form.y2 = $('#cropbox').height();
								form.width = $('#cropbox').width();
								form.height = $('#cropbox').height();
								form.destwidth = $('#cropbox').width() / 2;
								form.destheight = $('#cropbox').height() / 2;
								form.filename = $('#filename').val();
								form.crop = true;
									
									$.ajax({
										type: "POST",
										url: '/admin/files/crop/',
										data: form,
										success: function(msg) {
											window.location.reload();
										}
									});
							},
							'Annuler': function() {
								$(this).dialog('close');
							}
						}
					});
			});


			$('#setaspect').click( function(e) {
				width = $('#width').val();
				height = $('#height').val();
				if ( (width != '') && (height == '') ) {
					height = Math.round( ( width * $('#cropbox').height() ) / $('#cropbox').width() );
					$('#height').val(height);
				}
				else if ( (width == '') && (height != '') ) {
					width = Math.round( ( height * $('#cropbox').width() ) / $('#cropbox').height() );
					$('#width').val(width);
				}
				ratio = width / height;
				api.setOptions( { aspectRatio: ratio } );
			});
			
			function showCoords(c) {
				$('#x').val(c.x);
				$('#y').val(c.y);
				$('#x2').val(c.x2);
				$('#y2').val(c.y2);
				$('#w').val(c.w);
				$('#h').val(c.h);
				$('span#completecoords').text(c.x + ',' + c.y + ',' + c.x2 + ',' + c.y2);
				$('#image-selection-size').text(c.w + 'x' + c.h);
			}

