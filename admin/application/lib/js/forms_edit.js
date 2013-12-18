$(document).ready(function(){
    $("#tabs").tabs();

	CKEDITOR.replace('formintroduction', {
		height: 500,
		customConfig: '/application/views/ckeditor/config.js'
	});
	CKEDITOR.replace('formthanks', {
		height: 500,
		customConfig: '/application/views/ckeditor/config.js'
	});
	CKEDITOR.replace('formerrors', {
		height: 500,
		customConfig: '/application/views/ckeditor/config.js'
	});
	CKEDITOR.replace('formmailuser', {
		height: 500,
		customConfig: '/application/views/ckeditor/config.js'
	});
	CKEDITOR.replace('formmailadmin', {
		height: 500,
		customConfig: '/application/views/ckeditor/config.js'
	});
	CKEDITOR.replace('formcontesterror', {
		height: 500,
		customConfig: '/application/views/ckeditor/config.js'
	});

	$.ajax({
		url: "/admin/forms/get_questions/" + $('#form_id').val(),
		success: function(msg){
			questions = eval(msg);
			total_questions = questions.length;
			drawquestions();
		}
	});
	
	$("a.save-form").bind( "click", saveForm );
	
	$("select[name='max_questions']").change(function(e){
		max_questions = $("select[name='max_questions']").val();
	});
	
	max_questions = $("select[name='max_questions']").val();
	
	$('#locked').change( function(e) {
		check_questions();
	});

	check_questions();

});

function check_questions() {
	if ( $('#locked').attr('checked') ) {
    	disable_questions();
    }
    else {
    	enable_questions();
    }
}

function enable_questions() {
	$('#fragment-3 input').removeAttr('disabled');
	$('#fragment-3 select').removeAttr('disabled');
	$('#fragment-3 textarea').removeAttr('disabled');
	$('img.close-button').css('display', 'block');
}

function disable_questions() {
	$('#fragment-3 input').attr("disabled", true);
	$('#fragment-3 select').attr("disabled", true);
	$('#fragment-3 textarea').attr("disabled", true);
	$('img.close-button').css('display', 'none');
}

function saveForm() {

    var fckIntroduction = CKEDITOR.instances.formintroduction.getData();
    var fckThanks 		= CKEDITOR.instances.formthanks.getData();
    var fckErrors 		= CKEDITOR.instances.formerrors.getData();
    var fckMailUser		= CKEDITOR.instances.formmailuser.getData();
    var fckMailAdmin	= CKEDITOR.instances.formmailadmin.getData();
    var fckContestError	= CKEDITOR.instances.formcontesterror.getData();

    form = {
    	id: 			$("#form_id").val(),
    	language: 		$("#form_language").val(),
    	type: 			$('select[name=form_type]').val(),
    	title: 			$('input[name=form_title]').val(),

    	introduction:	fckIntroduction,
    	thanks:			fckThanks,
    	errors:			fckErrors,
    	mailuser:		fckMailUser,
    	mailadmin:		fckMailAdmin,
    	contesterror:	fckContestError,

    	emailsender:	$('#emailsender').prop('checked'),
    	emailadmin:		$('#emailadmin').prop('checked'),
    	userquestions:	$('#userquestions').prop('checked'),
    	usecaptcha:		$('#usecaptcha').prop('checked'),
    	emailextra:		$('#emailextra').val(),
    	tabledefinitions_id:			$("select[name='tabledefinitions_id']").val(),
    	locked:		$("#locked").prop('checked'),
    	max_questions:	$("select[name='max_questions']").val()
    };
    
    totalquestions = $('#questions ul li').length;
    questions = new Array( totalquestions );
	counter = 0;
	$('#questions ul li').each( function(i) {
		questions[counter] = new Object();
		questions[counter].order = counter + 1;
		questions[counter].name = this.id;
		questions[counter].type = $('#' + this.id + '_type').val();
		questions[counter].label = $('#' + this.id + '_label').val();
		questions[counter].comment = $('#' + this.id + '_comment').val();
		questions[counter].error = $('#' + this.id + '_error').val();
		questions[counter].mandatory = $('#' + this.id + '_mandatory').attr('checked');
		
		switch (questions[counter].type) {
			case 'text':
			case 'longtext':
			case 'email':
			case 'hidden':
			case 'hidden':
			case 'select':
			case 'radio':
			case 'multicheckbox':
				questions[counter].values = $('#' + this.id + '_values').val();
			break;
			case 'tablelink':
			case 'attachment':
				questions[counter].params1 = $('#' + this.id + '_params1').val();
				questions[counter].params2 = $('#' + this.id + '_params2').val();
			break;
		}
//		console.log(questions[counter].values);
				
		counter++;
	});
	
	form.questions = questions;
	form.total_questions = questions.length;
	

	for(var i=0; i < questions.length ; i++) {
		form['questions_' + (i + 1) + '_order'] = questions[i].order;
		form['questions_' + (i + 1) + '_name'] = questions[i].name;
		form['questions_' + (i + 1) + '_type'] = questions[i].type;
		form['questions_' + (i + 1) + '_label'] = questions[i].label;
		form['questions_' + (i + 1) + '_comment'] = questions[i].comment;
		form['questions_' + (i + 1) + '_error'] = questions[i].error;
		form['questions_' + (i + 1) + '_mandatory'] = questions[i].mandatory;
		switch (questions[i].type) {
			case 'text':
			case 'longtext':
			case 'email':
			case 'hidden':
			case 'password':
				form['questions_' + (i + 1) + '_values'] = questions[i].values;
			break;
			case 'select':
			case 'radio':
			case 'multicheckbox':
				form['questions_' + (i + 1) + '_values'] = '';
				console.log(questions[i].values);
				questions[i].values = questions[i].values.split("\n");
				
//				form['questions_' + (i + 1) + '_values'] = questions[i].values;
				for(var j=0; j < questions[i].values.length; j++)
				{
					form['questions_' + (i + 1) + '_values'] = form['questions_' + (i + 1) + '_values'] + questions[i].values[j] + '|';
				}
			break;
			case 'tablelink':
			case 'attachment':
				form['questions_' + (i + 1) + '_params1'] = questions[i].params1;
				form['questions_' + (i + 1) + '_params2'] = questions[i].params2;
			break;
		}
	}

	$.ajax({
    	type: "POST",
    	url: '/admin/forms/save/' + form.id,
    	data: form,
    	success: function(msg) {
    		showNotify(msg);
//    		$('#status').html(msg).fadeIn(1).fadeTo(5000, 1).fadeOut(1000);
    	}
    });


}

function drawquestions() {
    for (var j=0; j < questions.length; j++) {
    	//console.log(questions[j].madatory);
    	$('#questions ul').append(tmpl(questions[j].type + 'tpl', questions[j]));

		id = questions[j].name;
    }


   	$('#questions ul').sortable( {
			axis: 'y',
    		cursor: 'move'
	});


	unbind();

	bind();

	$('.adder a').bind("click", addQuestion );
}

function unbind() {
	$("a.button-add-item").unbind();
	$("a.button-delete-item").unbind();
	$("select.select-item").unbind();

	$("img.close-button").unbind();
}

function bind() {
	$("a.button-add-item").bind( "click", addElement );
	$("a.button-delete-item").bind( "click", deleteElement );
	$("select.select-item").bind( "click", selectElement );

	$("a.button-sort-items").bind( "click", helperSort );
	$("a.button-delete-items").bind( "click", helperEmpty );

	$("a.button-helper-sexes").bind( "click", helperSexes );
	$("a.button-helper-canada-provinces").bind( "click", helperCanadaProvinces );
	$("a.button-helper-provenances").bind( "click", helperProvenances );


	$("img.close-button").bind( "click", removeQuestion );

}

function addQuestion() {

    totalquestions = $('#questions ul li').length;
    
    if (totalquestions >= max_questions) {
    	alert('Contactez votre administrateur pour vous accorder des questions supplementaires.');
    	return false;
    }

	type = $('#label_type').val();
	title = $('#label_title').val();
	
	if (title != '') {
		name = title.norm();
	
		if ($('#' + name).length != 0) {
			alert('Cette question existe deja dans ce formulaire.');
		}
		else {
			unbind();

		   	$('#questions ul').sortable( "disable" );

			
			question = new Object();
		
			question.name = name;
			question.type = type;
			question.label = title;
			question.values = "";
			question.comment = "";
			question.error = "";
			question.mandatory = false;
			question.params1 = '';
			question.params2 = '';
		
			html = tmpl(question.type + 'tpl', question);
			$('#questions ul').append(html);
	
			bind();	

		   	$('#questions ul').sortable( "refresh" );

		   	$('#questions ul').sortable( "enable" );
		   	
		   	$.scrollTo($('#' + name), 1000);
		   	
		   	$('#' + name).effect("pulsate", { times: 3 }, 500);

		}
	}
}

function deleteElement( element ) {
	id = $(this).attr('id');
	id = id.substring(0, id.length - 14 );
	value = $('#' + id + "_select").val();
	if (value != "") {
		$('#' + id + "_select").removeOption(value);
	}
}

function addElement( element ) {
	id = $(this).attr('id');
	id = id.substring(0, id.length - 11 );
	value = $("#" + id + "_select_text").val();
	if (value != "") {
		$('#' + id + "_select").addOption(value, value );
	}	
}

function selectElement( element ) {
	id = $(this).attr('id');
	id = id.substring(0, id.length - 7 );
	value = $("#" + id + "_select").val();
	if (value != "") {
		$('#' + id + "_select_text").val(value );
	}	
}

function helperSexes( element ) {
	id = $(this).attr('id');
	id = id.substring(0, id.length - 20 );
	$('#' + id + "_select").addOption("homme", "Homme" );
	$('#' + id + "_select").addOption("femme", "Femme" );
}

function helperCanadaProvinces( element ) {
	id = $(this).attr('id');
	id = id.substring(0, id.length - 31 );
	$('#' + id + "_select").addOption("Quebec", 					"Quebec" );
	$('#' + id + "_select").addOption("Ontario",					"Ontario" );
	$('#' + id + "_select").addOption("Nova Scotia", 				"Nova Scotia" );
	$('#' + id + "_select").addOption("New Brunswick",				"New Brunswick" );
	$('#' + id + "_select").addOption("Manitoba",					"Manitoba" );
	$('#' + id + "_select").addOption("British Columbia",			"British Columbia" );
	$('#' + id + "_select").addOption("Prince Edward Island",		"Prince Edward Island" );
	$('#' + id + "_select").addOption("Saskatchewan",				"Saskatchewan" );
	$('#' + id + "_select").addOption("Alberta",					"Alberta" );
	$('#' + id + "_select").addOption("Newfoundland and Labrador",	"Newfoundland and Labrador" );
}

function helperProvenances( element ) {
	id = $(this).attr('id');
	id = id.substring(0, id.length - 26 );
	$('#' + id + "_select").addOption("Québec - Bas-Saint-Laurent", "Québec - Bas-Saint-Laurent");
	$('#' + id + "_select").addOption("Québec - Saguenay-Lac-Saint-Jean", "Québec - Saguenay-Lac-Saint-Jean");
	$('#' + id + "_select").addOption("Québec - Capitale-Nationale", "Québec - Capitale-Nationale");
	$('#' + id + "_select").addOption("Québec - Mauricie", "Québec - Mauricie");
	$('#' + id + "_select").addOption("Québec - Estrie", "Québec - Estrie");
	$('#' + id + "_select").addOption("Québec - Montréal", "Québec - Montréal");
	$('#' + id + "_select").addOption("Québec - Outaouais", "Québec - Outaouais");
	$('#' + id + "_select").addOption("Québec - Abitibi-Témiscamingue", "Québec - Abitibi-Témiscamingue");
	$('#' + id + "_select").addOption("Québec - Côte-Nord", "Québec - Côte-Nord");
	$('#' + id + "_select").addOption("Québec - Nord-du-Québec", "Québec - Nord-du-Québec");
	$('#' + id + "_select").addOption("Québec - Gaspésie-Îles-de-la-Madeleine", "Québec - Gaspésie-Îles-de-la-Madeleine");
	$('#' + id + "_select").addOption("Québec - Chaudière-Appalaches", "Québec - Chaudière-Appalaches");
	$('#' + id + "_select").addOption("Québec - Laval", "Québec - Laval");
	$('#' + id + "_select").addOption("Québec - Lanaudière", "Québec - Lanaudière");
	$('#' + id + "_select").addOption("Québec - Laurentides", "Québec - Laurentides");
	$('#' + id + "_select").addOption("Québec - Montérégie", "Québec - Montérégie");
	$('#' + id + "_select").addOption("Québec - Centre-du-Québec", "Québec - Centre-du-Québec");

	$('#' + id + "_select").addOption("Canada - Alberta", "Canada - Alberta");
	$('#' + id + "_select").addOption("Canada - Colombie-Britannique", "Canada - Colombie-Britannique");
	$('#' + id + "_select").addOption("Canada - Île-du-Prince-Édouard", "Canada - Île-du-Prince-Édouard");
	$('#' + id + "_select").addOption("Canada - Manitoba", "Canada - Manitoba");
	$('#' + id + "_select").addOption("Canada - Nouveau-Brunswick", "Canada - Nouveau-Brunswick");
	$('#' + id + "_select").addOption("Canada - Nouvelle-Écosse", "Canada - Nouvelle-Écosse");
	$('#' + id + "_select").addOption("Canada - Nunavut", "Canada - Nunavut");
	$('#' + id + "_select").addOption("Canada - Ontario", "Canada - Ontario");
	$('#' + id + "_select").addOption("Canada - Saskatchewan", "Canada - Saskatchewan");
	$('#' + id + "_select").addOption("Canada - Terre-Neuve-et-Labrador", "Canada - Terre-Neuve-et-Labrador");
	$('#' + id + "_select").addOption("Canada - Territoires-du-Nord-Ouest", "Canada - Territoires-du-Nord-Ouest");
	$('#' + id + "_select").addOption("Canada - Yukon", "Canada - Yukon");

	$('#' + id + "_select").addOption("USA", "USA");
	$('#' + id + "_select").addOption("Mexique", "Mexique");

	$('#' + id + "_select").addOption("Allemagne", "Allemagne");
	$('#' + id + "_select").addOption("Autriche", "Autriche");
	$('#' + id + "_select").addOption("Belgique", "Belgique");
	$('#' + id + "_select").addOption("Danemark", "Danemark");
	$('#' + id + "_select").addOption("Espagne", "Espagne");
	$('#' + id + "_select").addOption("Finlande", "Finlande");
	$('#' + id + "_select").addOption("France", "France");
	$('#' + id + "_select").addOption("Irlande", "Irlande");
	$('#' + id + "_select").addOption("Italie", "Italie");
	$('#' + id + "_select").addOption("Luxembourg", "Luxembourg");
	$('#' + id + "_select").addOption("Monaco", "Monaco");
	$('#' + id + "_select").addOption("Norvège", "Norvège");
	$('#' + id + "_select").addOption("Pays-Bas", "Pays-Bas");
	$('#' + id + "_select").addOption("Portugal", "Portugal");
	$('#' + id + "_select").addOption("Royaume-Uni", "Royaume-Uni");
	$('#' + id + "_select").addOption("Russie", "Russie");
	$('#' + id + "_select").addOption("Suède", "Suède");
	$('#' + id + "_select").addOption("Suisse", "Suisse");

	$('#' + id + "_select").addOption("Europe de l'Est", "Europe de l'Est");
	$('#' + id + "_select").addOption("Asie", "Asie");
	$('#' + id + "_select").addOption("Amérique du Sud", "Amérique du Sud");
	$('#' + id + "_select").addOption("Australie", "Australie");
	$('#' + id + "_select").addOption("Moyen-Orient", "Moyen-Orient");

}


function helperSort( element ) {
	id = $(this).attr('id');
	id = id.substring(0, id.length - 12 );
	$('#' + id + "_select").sortOptions();
}

function helperEmpty( element ) {
	id = $(this).attr('id');
	id = id.substring(0, id.length - 13 );
	$('#' + id + "_select").empty();
}

function removeQuestion( element ) {
	if (confirm("Etes-vous certain de vouloir effacer cette question ?")) {
		id = $(this).attr('id');
		id = id.substring(0, id.length - 7 );
		$("#" + id).slideUp("fast", function() {
			$("#" + id).remove();
		});
	}
}

String.prototype.norm = function() {
	text = this.replace(/^\s*|\s(?=\s)|\s*$/g, "");
	text = removeDiacritics( text );
	text = text.replace(/[^a-zA-Z 0-9_]+/g,'');
	text = trim(text);
	text = replace(text, ' ', '-');
	text = text.toLowerCase();
	return text;
}

function replace(string,text,by) {

    var strLength = string.length, txtLength = text.length;
    if ((strLength == 0) || (txtLength == 0)) return string;

    var i = string.indexOf(text);
    if ((!i) && (text != string.substring(0,txtLength))) return string;
    if (i == -1) return string;

    var newstr = string.substring(0,i) + by;

    if (i+txtLength < strLength)
        newstr += replace(string.substring(i+txtLength,strLength),text,by);

    return newstr;
}



function removeDiacritics( text ) {
    text = replace(text,unescape('%C0'),'A');
    text = replace(text,unescape('%C1'),'A');
    text = replace(text,unescape('%C2'),'A');
    text = replace(text,unescape('%C3'),'A');
    text = replace(text,unescape('%C4'),'A');
    text = replace(text,unescape('%C5'),'A');
    text = replace(text,unescape('%C6'),'AE');
    text = replace(text,unescape('%C7'),'C');
    text = replace(text,unescape('%C8'),'E');
    text = replace(text,unescape('%C9'),'E');
    text = replace(text,unescape('%CA'),'E');
    text = replace(text,unescape('%CB'),'E');
    text = replace(text,unescape('%CC'),'I');
    text = replace(text,unescape('%CD'),'I');
    text = replace(text,unescape('%CE'),'I');
    text = replace(text,unescape('%CF'),'I');
    text = replace(text,unescape('%D0'),'D');
    text = replace(text,unescape('%D1'),'N');
    text = replace(text,unescape('%D2'),'O');
    text = replace(text,unescape('%D3'),'O');
    text = replace(text,unescape('%D4'),'O');
    text = replace(text,unescape('%D5'),'O');
    text = replace(text,unescape('%D6'),'O');
    text = replace(text,unescape('%D7'),'O');
    text = replace(text,unescape('%D8'),'O');
    text = replace(text,unescape('%D9'),'U');
    text = replace(text,unescape('%DA'),'U');
    text = replace(text,unescape('%DB'),'U');
    text = replace(text,unescape('%DC'),'U');
    text = replace(text,unescape('%DD'),'Y');
    text = replace(text,unescape('%DE'),'P');
    text = replace(text,unescape('%DF'),'B');
    text = replace(text,unescape('%E0'),'a');
    text = replace(text,unescape('%E1'),'a');
    text = replace(text,unescape('%E2'),'a');
    text = replace(text,unescape('%E3'),'a');
    text = replace(text,unescape('%E4'),'a');
    text = replace(text,unescape('%E5'),'a');
    text = replace(text,unescape('%E6'),'ae');
    text = replace(text,unescape('%E7'),'c');
    text = replace(text,unescape('%E8'),'e');
    text = replace(text,unescape('%E9'),'e');
    text = replace(text,unescape('%EA'),'e');
    text = replace(text,unescape('%EB'),'e');
    text = replace(text,unescape('%EC'),'i');
    text = replace(text,unescape('%ED'),'i');
    text = replace(text,unescape('%EE'),'i');
    text = replace(text,unescape('%EF'),'i');
    text = replace(text,unescape('%F0'),'&');
    text = replace(text,unescape('%F1'),'n');
    text = replace(text,unescape('%F2'),'o');
    text = replace(text,unescape('%F3'),'o');
    text = replace(text,unescape('%F4'),'o');
    text = replace(text,unescape('%F5'),'o');
    text = replace(text,unescape('%F6'),'o');
    text = replace(text,unescape('%F7'),'o');
    text = replace(text,unescape('%F8'),'o');
    text = replace(text,unescape('%F9'),'u');
    text = replace(text,unescape('%FA'),'u');
    text = replace(text,unescape('%FB'),'u');
    text = replace(text,unescape('%FC'),'u');
    text = replace(text,unescape('%FD'),'y');
    text = replace(text,unescape('%FE'),'p');
    text = replace(text,unescape('%FF'),'y');
    
    return text;
}


function trim(str, chars) {
    return ltrim(rtrim(str, chars), chars);
}

function ltrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function rtrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}



function tools_eraseresults( id ) {
	if (confirm("Vous ne pourrez pas recuperer ces donnes. Voulez-vous continuer ?")) {
		$('#formresults').load('/admin/forms/erase/' + id);
	}
}

