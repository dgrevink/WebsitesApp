<script type="text/javascript" src="/lib/js/jquery/jquery.password_strength.js"></script>

<link rel="stylesheet" media="screen" type="text/css" href="/lib/js/jquery/colorpicker/css/colorpicker.css" />
<script type="text/javascript" src="/lib/js/jquery/colorpicker/js/colorpicker.js"></script>

<script type="text/javascript" src="/admin/application/lib/js/tables.js"></script>

<!-- ============== BEGIN list ============== -->
{if $listing_type == 'list'}

<script type="text/javascript" src="/lib/js/jquery/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="/admin/application/lib/js/tables_list.js"></script>

<link rel="stylesheet" href="/lib/js/jquery/datatables/css/demo_page.css" type="text/css">
<link rel="stylesheet" href="/lib/js/jquery/datatables/css/demo_table.css" type="text/css">


<script type="text/javascript" src="/lib/js/jquery/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/js/jquery/fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />


<div class='uk-article'>
	<h2 class='uk-article-title'>{$current_page_title} </h2>
	<div id='comment'>
		<div class='uk-alert'>{$current_table_description}&nbsp;
			{if $current_table_id}
				<div class='uk-float-right  uk-hidden-small'>
					<a class='uk-button uk-button-primary uk-button-mini' href='/admin/fr/tables/tabledefinitions/edit/{$current_table_id}' title='Commenter...' target='_blank'><i class="uk-icon-comment"></i><span style="margin-left: -4px;">&nbsp;</span></a>
				</div>
			{/if}
		</div>
	</div>

	<div class='uk-float-right uk-margin-bottom'>
		{if isset($showcreatebutton)}<a href='create/0' class='uk-button uk-button-primary'>Ajouter...</a>{/if}
		{if isset($showexportbutton)}<a href='export' class='uk-button'>Exporter...</a>{/if}
	</div>

	<div id='tables'>
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="table-main">
			{$table_structure}
		</table>
		<br/><br/><br/><br/>
	</div>

</div>	



<script>
	var current_language = '{$current_language}';
	var current_table    = '{$current_table}';
	{if isset($fieldcondition)}
	var fieldcondition = '/' + '{$field}' + '/' + {$fieldcondition};
	{else}
	var fieldcondition = '';
	{/if}
	record_links = '';
{literal}
	sCurrentLanguage = '{/literal}{$current_language}{literal}';
	$(document).ready(function(){

			oTableMain = $('#table-main').dataTable( {
				"sPaginationType": "full_numbers",
				"bStateSave": true,
				"iDisplayLength": "25",
				"bAutoWidth": false,
				"bInfo": true,
				"bJQueryUI": false,
				"oLanguage": {
					"sProcessing": "Chargement...",
					"sLengthMenu": "Afficher _MENU_ enregistrements par page " + record_links,
					"sZeroRecords": "Aucune donn&eacute;es.",
					"sInfo": "Donn&eacute;es _START_ &agrave; _END_ affich&eacute;es sur _TOTAL_ enregistements.",
					"sInfoEmpty": "Pas de donn&eacute;es.",
					"sInfoFiltered": "(Pour un total de _MAX_ enregistrements)",
					"sSearch": "",
					"oPaginate": {
						"sFirst":    "Premier",
						"sPrevious": "Pr&eacute;c&eacute;dent",
						"sNext":     "Suivant",
						"sLast":     "Dernier"
					}
				},
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": '/admin/index.php/tables/loadrecords/' + current_table + '/',
				
				"fnDrawCallback": function(){
										$('img.table-thumb').parent().fancybox();
		$('.cpicker-list-display').each(function(){
			adjustColour(this);
		});

					/*
					$('select.inlinefieldselector').parent().css('width').slice(0, ($('select.inlinefieldselector').parent().css('width').length-2) ) - 25
	
					$('#table-main tbody td span.varchar').editable( '/admin/index.php/tables/updatefieldtext/' + current_table + '/', {
								"placeholder": "[Vide...]",
								"indicator": "Sauvegarde...",
								"callback": function( sValue, y ) {
									alert(aPos);
									var aPos = oTable.fnGetPosition( this );
									oTable.fnUpdate( sValue, aPos[0], aPos[1] );
								}
							} );
					*/
				},
				"fnInitComplete": function(){
				}
			} );
	
	});
{/literal}
</script>


{/if}
<!-- ============== END list ============== -->










<!-- ============== BEGIN detail ============== -->
{if $listing_type == 'detail'}

<script>
    {literal}

    function close_window( message ) {
    	window.opener.record_posted(message);
    	window.close();
    }
    
    Date.format = 'yyyy/mm/dd';

    $(document).ready(function(){
		$.datepicker.setDefaults($.datepicker.regional['fr']);
    	$.datepicker.setDefaults( {
    		autoSize: false,
    		duration: 'fast',
    		dateFormat: 'd MM yy',
    		minDate: new Date(2010, 1 - 1, 1),
    		numberOfMonths: [1, 3]
/*    		dateFormat: 'yyyy-mm-dd' */
    	} );
		$('.datepicker').datepicker();

		$('input.cpicker').each(function(){
			$(this).css('background-color', '#' + $(this).val());
			adjustColour(this);
		});
		$('input.cpicker').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val(hex);
				$(el).css('background-color', '#' + $(el).val());
				adjustColour(el);
				$(el).ColorPickerHide();
			},
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
			}
		})
		.bind('keyup', function(){
			$(this).ColorPickerSetColor(this.value);
		});
		
		$('select.multiple').chosen();

		
    });
    
    {/literal}
</script>



<div class='uk-article'>
	<h2 class='uk-article-title'>{$current_page_title} </h2>

{if $showsavebutton != ''}
<div class='uk-float-right'>
	<a class='uk-button uk-button-primary'  href='#' onclick="forms.contentform.submit(); return false;">Sauvegarder</a>
</div>
{/if}

<form name='contentform' id='contentform' class='uk-form uk-form-horizontal' action='../' method='post' enctype='utf-8'>
{$code}
<script>
{$rich_editors}
</script><br/><br/><br/><br/>
</form>
{if $showsavebutton != ''}

<div class='uk-float-right'>
	<a class='uk-button uk-button-primary'  href='#' onclick="forms.contentform.submit(); return false;">Sauvegarder</a>
</div>



</div>


{/if}
{/if}
<!-- ============== END detail ============== -->

