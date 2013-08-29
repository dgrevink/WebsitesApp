<script type="text/javascript" src="/lib/js/jquery/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="/admin/application/lib/js/tables_list.js"></script>

<link rel="stylesheet" href="/lib/js/jquery/datatables/css/demo_page.css" type="text/css">
<link rel="stylesheet" href="/lib/js/jquery/datatables/css/demo_table.css" type="text/css">

<script type="text/javascript" src="/lib/js/jquery/jquery.form.js"></script>

<script type="text/javascript" src="/admin/application/lib/js/forms_list.js"></script>

<div class='uk-article'>
<h2 class='uk-article-title'>Forms</h2>

	<div id='status'></div>
	<div class='uk-alert'>This module is used to handle all the forms on the site.</div>

	<div class='uk-float-right uk-margin-bottom'>
		{if $WSR_FORMS_CREATE}<a href='#' class='uk-button uk-button-primary' onclick="createForm('{$current_language}'); return false;">Create a form...</a>{/if}
	</div>

	<div id='tables'>
	<table class="display" id="table-main">
		<thead>
			<tr>
				<th>Form</th>
				<th>Last filled</th>
				<th>Answers</th>
			</tr>
		</thead>
		<tbody>
			{section name=forms loop=$forms}
			<tr>
				<td style='min-width: 300px;'>
					<a href='{$forms[forms].id}'>
						{$forms[forms].title}
					</a>
					{if $WSR_FORMS_CREATE}
					<div class='row-controls' style='width: 230px;'>
						<ul>
							<li><a href='#' onclick="dupForm( '{$forms[forms].id}', 'en' ); return false;" style='font-size: 10px; color: #999; text-decoration: none;'>&rarr; EN&nbsp; Copy this form to english...</a></li>
							<li><a href='#' onclick="dupForm( '{$forms[forms].id}', 'fr' ); return false;" style='font-size: 10px; color: #999; text-decoration: none;'>&rarr; FR&nbsp; Copy this form to french...</a></li>
							<li><a href='#' onclick="delForm( '{$forms[forms].id}' ); return false;"><img src='/admin/application/lib/images/icons/icon-remove.gif'/> Delete...</a></li>
						</ul>
					</div>
					{/if}
				</td>
				<td class='data' style='width: 150px;'>{$forms[forms].last_response}</td>
				<td class='data' style='width: 100px;'>{$forms[forms].total_responses}</td>
			</tr>
			{/section}
		</tbody>
	</table>
	</div>

</div>

<script>
{literal}

$(document).ready(function(){

			oTableMain = $('#table-main').dataTable( {
				"sPaginationType": "full_numbers",
				"bStateSave": true,
				"iDisplayLength": "25",
				"bAutoWidth": false,
				"bInfo": true,
				"bJQueryUI": false,
				"oLanguage": {
					"sProcessing": "Loading...",
					"sLengthMenu": "Display _MENU_ records per page ",
					"sZeroRecords": "No records found.",
					"sInfo": "Data _START_ to _END_ displayed on a total of _TOTAL_ records.",
					"sInfoEmpty": "No data.",
					"sInfoFiltered": "(For a total of _MAX_ records)",
					"sSearch": "",
					"oPaginate": {
						"sFirst":    "First",
						"sPrevious": "Previous",
						"sNext":     "Next",
						"sLast":     "Last"
					}
				}
			} );
});
{/literal}
</script>
