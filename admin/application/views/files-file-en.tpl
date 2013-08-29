<script src="/lib/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>



<div class='uk-article'>
	<h2 class='uk-article-title'>Editing {$fileinfo} ( {$mode} )</h2>

	<input id='filename' type='hidden' value='{$wfile}' />
	<textarea id='contents' style='display:none;'>{$wfilecontent}</textarea>

	<div id='comment'>
			<div class='uk-float-right uk-hidden-small'>
				<input type='button' id='save' class='uk-button uk-button-primary' value='Save' />
			</div>
		</div>
	</div>



	<div class="uk-grid">
		<div class="uk-width-1-1" style='min-height: 600px; position: relative;'>
			<div id="editor" style='position: absolute; top: 1em; left: 3em; bottom: 0; right: 0;'></div>
		</div>
	</div>


</div>

<div id='text-remove' style='display: none;'>...</div>

<script language="Javascript">
mode = "{$mode}";
{literal}

    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/github");
    editor.getSession().setMode("ace/mode/" + mode);
    editor.getSession().setValue($("#contents").text());
//    editor.getSession().setMode({path:"ace/mode/php", pure:true});


	$('#save').click(function(e){
		form = new Object();
		form.filecontent = editor.getValue();
		form.filename = $('#filename').val();
		
		$.ajax({
			type: "POST",
			url: '/admin/files/save/',
			data: form,
			success: function(msg) {
				window.history.go(-1);
			}
		});
	});
{/literal}
</script>
