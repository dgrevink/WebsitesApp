function renameFile(e,t){$("#dialog-text").html("<p>"+l.get("FILES_RENAME_NEWNAME")+"</p><input id='ws-input-text-data' type='text' value='"+t+"'/>");$("#dialog-text").dialog({resizable:!1,width:440,modal:!0,open:function(e,t){$("#ws-input-text-data").focus();$("#ws-input-text-data").keypress(function(e){e.keyCode=="13"&&$(".ui-dialog-buttonpane > button:first").focus().click()})},buttons:{OK:function(){nfile=$("#ws-input-text-data").val();$(this).dialog("close");nfile!=""&&$.ajax({type:"POST",url:"/admin/files/rename/",data:"dir="+e+"&old="+t+"&new="+nfile,success:function(e){if(e=="OK"){window.location.href=window.location.href;document.location.reload()}}})},Annuler:function(){$(this).dialog("close")}}})}function createFile(e){nfile=prompt(l.get("FILES_FILE_CREATE"));nfile!=null&&$.ajax({type:"POST",url:"/admin/files/create_file/",data:"dir="+e+"&filename="+nfile,success:function(e){if(e=="OK"){window.location.href=window.location.href;document.location.reload()}}})}function createDir(e){$("#dialog-text").html("<p>"+l.get("FILES_DIR_CREATE")+"</p><input id='ws-input-text-data' type='text'/>");$("#dialog-text").dialog({resizable:!1,width:440,modal:!0,open:function(e,t){$("#ws-input-text-data").focus();$("#ws-input-text-data").keypress(function(e){e.keyCode=="13"&&$(".ui-dialog-buttonpane > button:first").focus().click()})},buttons:{OK:function(){nfile=$("#ws-input-text-data").val();$(this).dialog("close");nfile!=""&&$.ajax({type:"POST",url:"/admin/files/createdir/",data:"dir="+e+"&dirname="+nfile,success:function(e){if(e=="OK"){window.location.href=window.location.href;document.location.reload()}}})},Annuler:function(){$(this).dialog("close")}}})}function removeCommentDir(e){$("#dialog-text").html(l.get("FILES_COMMENT_DELETE"));$("#dialog-text").dialog({resizable:!1,width:440,modal:!0,buttons:{OK:function(){$.ajax({type:"POST",url:"/admin/files/removecommentdir/",data:"dir="+e,success:function(e){e=="OK"&&$("#comment .text").html("")}});$(this).dialog("close")},Annuler:function(){$(this).dialog("close")}}})}function commentDir(e){$.ajax({type:"POST",url:"/admin/files/getcommentdir/",data:"dir="+e,success:function(t){$("#dialog-text").html("<p>"+l.get("FILES_COMMENT_NEW")+"</p><input id='ws-input-text-data' type='text' value='"+t+"'/>");$("#dialog-text").dialog({resizable:!1,width:440,modal:!0,open:function(e,t){$("#ws-input-text-data").focus();$("#ws-input-text-data").keypress(function(e){e.keyCode=="13"&&$(".ui-dialog-buttonpane > button:first").focus().click()})},buttons:{OK:function(){nfile=$("#ws-input-text-data").val();$(this).dialog("close");nfile!=""&&$.ajax({type:"POST",url:"/admin/files/commentdir/",data:"dir="+e+"&comment="+nfile,success:function(e){if(e=="OK"){window.location.href=window.location.href;document.location.reload()}}})},Annuler:function(){$(this).dialog("close")}}})}})}function commentFile(e){$.ajax({type:"POST",url:"/admin/files/getcommentfile/",data:"dir="+e,success:function(t){$("#dialog-text").html("<p>"+l.get("FILES_COMMENT_NEW")+"</p><input id='ws-input-text-data' type='text' value='"+t+"'/>");$("#dialog-text").dialog({resizable:!1,width:440,modal:!0,open:function(e,t){$("#ws-input-text-data").focus();$("#ws-input-text-data").keypress(function(e){e.keyCode=="13"&&$(".ui-dialog-buttonpane > button:first").focus().click()})},buttons:{OK:function(){nfile=$("#ws-input-text-data").val();$(this).dialog("close");$.ajax({type:"POST",url:"/admin/files/commentfile/",data:"dir="+e+"&comment="+nfile,success:function(e){if(e=="OK"){window.location.href=window.location.href;document.location.reload()}}})},Annuler:function(){$(this).dialog("close")}}})}})}function normFile(e,t,n){$("#dialog-text").html(l.get("FILES_NORMALIZE_ITEM"));$("#dialog-text").dialog({resizable:!1,width:440,modal:!0,buttons:{OK:function(){$.ajax({type:"POST",url:"/admin/files/normalize/",data:"dir="+e+"&old="+t+"&key="+n,success:function(e){if(e=="OK"){window.location.href=window.location.href;document.location.reload()}}});$(this).dialog("close")},Annuler:function(){$(this).dialog("close")}}})}function duplFile(e,t){$.ajax({type:"POST",url:"/admin/files/duplicate/",data:"dir="+e+"&old="+t,success:function(e){if(e=="OK"){window.location.href=window.location.href;document.location.reload()}}})}function delFile(e,t){$("#dialog-text").html(l.get("FILES_DELETE_ITEM"));$("#dialog-text").dialog({resizable:!1,width:440,modal:!0,buttons:{OK:function(){$.ajax({type:"POST",url:"/admin/files/delete/",data:"dir="+e+"&old="+t,success:function(e){if(e=="OK"){window.location.href=window.location.href;document.location.reload()}}});$(this).dialog("close")},Annuler:function(){$(this).dialog("close")}}})}function normDir(e){$("#dialog-text").html(l.get("FILES_NORMALIZE_DIR"));$("#dialog-text").dialog({resizable:!1,width:440,modal:!0,buttons:{OK:function(){$.ajax({type:"POST",url:"/admin/files/normalize_dir/",data:"dir="+e,success:function(e){if(e=="OK"){window.location.href=window.location.href;document.location.reload()}}});$(this).dialog("close")},Annuler:function(){$(this).dialog("close")}}})}l=new Translations(sCurrentLanguage);$(document).ready(function(){oTableMain=$("#table-main").dataTable({sPaginationType:"full_numbers",bStateSave:!0,iDisplayLength:"25",bAutoWidth:!1,bInfo:!0,bJQueryUI:!1,oLanguage:{sProcessing:l.get("FILES_TABLE_LOAD"),sLengthMenu:l.get("FILES_TABLE_LENGTH_MENU"),sZeroRecords:l.get("FILES_TABLE_ZERO_RECORDS"),sInfo:l.get("FILES_TABLE_INFO"),sInfoEmpty:l.get("FILES_TABLE_NO_DATA"),sInfoFiltered:l.get("FILES_TABLE_INFO_FILTERED"),sSearch:"",oPaginate:{sFirst:l.get("FILES_TABLE_FIRST"),sPrevious:l.get("FILES_TABLE_PREVIOUS"),sNext:l.get("FILES_TABLE_NEXT"),sLast:l.get("FILES_TABLE_LAST")}}});$("img.table-thumb").parent().fancybox();$("a.thumb-image-setter").click(function(){$.cookie("websites-thumb-size",$(this).text());window.location.href=window.location.href;document.location.reload();return!1});$("#file-toggle-all").change(function(){$(".file-toggle").attr("checked",$("#file-toggle-all").attr("checked"));$("#file-action-controls").hide();$(".file-toggle:checked").each(function(){$("#file-action-controls").show()})});$(".file-toggle").change(function(){$("#file-action-controls").hide();$(".file-toggle:checked").each(function(){$("#file-action-controls").show()})});$("#file-action").change(function(){action=$(this).val();if(action=="delete"){$("#dialog-text").html(l.get("FILES_DELETE_ITEMS"));$("#dialog-text").dialog({resizable:!1,width:440,modal:!0,buttons:{OK:function(){files="";$(".file-toggle:checked").each(function(){files=files+$(this).attr("rel")+","});$.ajax({type:"POST",url:"/admin/files/deleteall/",data:"files="+files,success:function(e){if(e=="OK"){window.location.href=window.location.href;document.location.reload()}}});$(this).dialog("close")},Annuler:function(){$(this).dialog("close");$("#file-action").val("nothing")}}})}action=="move"&&$.get("/admin/files/getdirs/",function(e){var t=[];dirs=e.split(",");dirselect="";for(i=0;i<dirs.length;i++)dirselect=dirselect+"<option value='"+dirs[i]+"'>"+dirs[i]+"</option>";dirselect="<br/>&nbsp;<select id='destination-dir'>"+dirselect+"</select><br/>";$("#dialog-text").html(l.get("FILES_MOVE_ITEMS_PART1")+dirselect+l.get("FILES_MOVE_ITEMS_PART2"));$("#dialog-text").dialog({resizable:!1,width:440,modal:!0,buttons:{OK:function(){files="";dest=$("#destination-dir").val();$(".file-toggle:checked").each(function(){files=files+$(this).attr("rel")+","});$.ajax({type:"POST",url:"/admin/files/move/",data:"dest="+dest+"&files="+files,success:function(e){if(e=="OK"){window.location.href=window.location.href;document.location.reload()}}});$(this).dialog("close")},Annuler:function(){$(this).dialog("close");$("#file-action").val("nothing")}}})})})});