function restorehistory(e){$("#dialog-text").html(l.get("CONTENT_VERSION_CONFIRMATION"));$("#dialog-text").dialog({resizable:!1,width:440,modal:!0,buttons:{OK:function(){$(this).dialog("close");$.get("/admin/Content/restoreHistory/"+e,function(){window.location.reload()})},Annuler:function(){$(this).dialog("close")}}})}function jQuerySubmit(e){$.blockUI({theme:!1,message:"<h3 style='padding: 10px 20px;'>Sauvegarde en cours, veuillez patienter SVP.</h3>"});el=$(e);$("#fckeditor1")&&$("#fckeditor1").val(CKEDITOR.instances.fckeditor1.getData());$("#fckeditor2")&&$("#fckeditor2").val(CKEDITOR.instances.fckeditor2.getData());$("#fckeditor3")&&$("#fckeditor3").val(CKEDITOR.instances.fckeditor3.getData());$("#fckeditor4")&&$("#fckeditor4").val(CKEDITOR.instances.fckeditor4.getData());$("#fckeditor5")&&$("#fckeditor5").val(CKEDITOR.instances.fckeditor5.getData());$("#fckeditor_comment")&&$("#fckeditor_comment").val(CKEDITOR.instances.fckeditor_comment.getData());el.ajaxSubmit(function(e){$.unblockUI();showNotify(e);$("#history").load("/admin/Content/getHistorySelect/"+recordid,{},function(){$("#history select").change(function(){restorehistory($("#history select").val())})})})}function showHideContainers(){lockUI();$("div.placeholder").each(function(){layout=$("input.layout-selector").fieldValue();phid=$(this).attr("id");phid=phid.substr(phid.indexOf("_")+1,phid.length);phlist[layout][phid-1]?$(this).fadeIn():$(this).fadeOut()});unlockUI()}function redrawContainer(){phid=$(this).attr("id");phid=phid.substr(phid.indexOf("_")+1,phid.length);phid=phid.substr(0,1);phindex="#placeholder_"+phid+"_div";phindex_value="placeholder_"+phid+"_value";phindex_param="placeholder_"+phid+"_value_param";value=$(this).val();value=value.split("-");position=value[0];switch(position){case"ad":create_ad($(phindex),phid);break;case"block":create_block($(phindex),phid);break;case"form":create_form($(phindex),phid);break;case"widget":create_widget($(phindex),phindex_value);$(phindex+" .widgets-lister-detail").html($(phindex+" select :selected").attr("title"));$(".widgets-lister").change(function(){id=$(this).parent().attr("id");$("#"+id+" .widgets-lister-detail").html($("#"+id+" select :selected").attr("title"))});break;case"empty":create_empty($(phindex));break;case"content":create_content($(phindex));break;default:}}function updateUI(){$("div.placeholder").each(function(){layout=$("input.layout-selector").fieldValue();phid=$(this).attr("id");phid=phid.substr(phid.indexOf("_")+1,phid.length);phindex="placeholder_"+phid+"_type";phindex_param="placeholder_"+phid+"_param";phindex_div="#placeholder_"+phid+"_div";phindex_value="placeholder_"+phid+"_value";phindex_value_param="placeholder_"+phid+"_value_param";$("#"+phindex).val(phoptions[phindex]);current_placeholder_params=phoptions[phindex].split("-");current_placeholder_type=current_placeholder_params[0];current_placeholder_value="";current_placeholder_params.length>1&&(current_placeholder_value=1);switch(current_placeholder_type){case"ad":create_ad($(phindex_div),phindex_value);$("#"+phindex_value).val(current_placeholder_value);break;case"block":create_block($(phindex_div),phindex_value);$("#"+phindex_value).val(current_placeholder_value);break;case"form":create_form($(phindex_div),phindex_value);$("#"+phindex_value).val(current_placeholder_value);break;case"widget":create_widget($(phindex_div),phindex_value);$("#"+phindex_value).val(current_placeholder_value);$("#"+phindex_value_param).val(phparams[phindex_param]);$(phindex_div+" .widgets-lister-detail").html($(phindex_div+" select :selected").attr("title"));$(".widgets-lister").change(function(){id=$(this).parent().attr("id");$("#"+id+" .widgets-lister-detail").html($("#"+id+" select :selected").attr("title"))});break;case"empty":create_empty($(phindex_div));break;case"content":create_content($(phindex_div));break;default:}})}function lockUI(){$("input.layout-selector").unbind("change",showHideContainers);$("select.placeholder_selector").unbind("change",redrawContainer)}function unlockUI(){$("input.layout-selector").bind("change",showHideContainers);$("select.placeholder_selector").bind("change",redrawContainer)}l=new Translations(sCurrentLanguage);$(document).ready(function(){$("#tabs").tabs();$("#title").keyup(function(e){var t=$("#title").val()+"";t!="undefined"&&$("#sub-page-title").html("<a href='"+complete_path+"' target='_blank'>"+$("#title").val()+"</a>")});var e=$("#title").val()+"";e!="undefined"&&$("#sub-page-title").html("<a href='"+complete_path+"' target='_blank'>"+$("#title").val()+"</a>");$("#save-page").click(function(){jQuerySubmit("#contentform");return!1});$("#history").load("/admin/Content/getHistorySelect/"+recordid,{},function(){$("#history select").change(function(){restorehistory($("#history select").val())})});$("#fckeditor1")&&CKEDITOR.replace("fckeditor1",{height:400,customConfig:"/application/views/ckeditor/config.js"});$("#fckeditor2")&&CKEDITOR.replace("fckeditor2",{height:400,customConfig:"/application/views/ckeditor/config.js"});$("#fckeditor3")&&CKEDITOR.replace("fckeditor3",{height:400,customConfig:"/application/views/ckeditor/config.js"});$("#fckeditor4")&&CKEDITOR.replace("fckeditor4",{height:400,customConfig:"/application/views/ckeditor/config.js"});$("#fckeditor5")&&CKEDITOR.replace("fckeditor5",{height:400,customConfig:"/application/views/ckeditor/config.js"});$("#fckeditor_comment")&&CKEDITOR.replace("fckeditor_comment",{height:300,customConfig:"/application/views/ckeditor/config.js"});$(".toggle-content-2").click(function(){if($(".toggle-content-2").html()=="+"){$(".toggle-content-2").html("-");$(".content-2-holder").fadeIn()}else{$(".toggle-content-2").html("+");$(".toggle-content-3").html("+");$(".toggle-content-4").html("+");$(".toggle-content-5").html("+");$(".content-2-holder").fadeOut();$(".content-3-holder").fadeOut();$(".content-4-holder").fadeOut();$(".content-5-holder").fadeOut()}return!1});$(".toggle-content-3").click(function(){if($(".toggle-content-3").html()=="+"){$(".toggle-content-3").html("-");$(".content-3-holder").fadeIn()}else{$(".toggle-content-3").html("+");$(".toggle-content-4").html("+");$(".toggle-content-5").html("+");$(".content-3-holder").fadeOut();$(".content-4-holder").fadeOut();$(".content-5-holder").fadeOut()}return!1});$(".toggle-content-4").click(function(){if($(".toggle-content-4").html()=="+"){$(".toggle-content-4").html("-");$(".content-4-holder").fadeIn()}else{$(".toggle-content-4").html("+");$(".toggle-content-5").html("+");$(".content-4-holder").fadeOut();$(".content-5-holder").fadeOut()}return!1});$(".toggle-content-5").click(function(){if($(".toggle-content-5").html()=="+"){$(".toggle-content-5").html("-");$(".content-5-holder").fadeIn()}else{$(".toggle-content-5").html("+");$(".content-5-holder").fadeOut()}return!1});if($("#fckeditor2")&&$("#fckeditor2").val()!=""){$(".toggle-content-2").html("-");$(".content-2-holder").show()}if($("#fckeditor3")&&$("#fckeditor3").val()!=""){$(".toggle-content-2").html("-");$(".toggle-content-3").html("-");$(".content-2-holder").show();$(".content-3-holder").show()}if($("#fckeditor4")&&$("#fckeditor4").val()!=""){$(".toggle-content-2").html("-");$(".toggle-content-3").html("-");$(".toggle-content-4").html("-");$(".content-2-holder").show();$(".content-3-holder").show();$(".content-4-holder").show()}if($("#fckeditor5")&&$("#fckeditor5").val()!=""){$(".toggle-content-2").html("-");$(".toggle-content-3").html("-");$(".toggle-content-4").html("-");$(".toggle-content-5").html("-");$(".content-2-holder").show();$(".content-3-holder").show();$(".content-4-holder").show();$(".content-5-holder").show()}updateUI();unlockUI();showHideContainers()});