// CMS Tree Menu Functions


function add_node() {

	var default_layout = '';
	for (i in layouts) {
		default_layout = layouts[i].name;
		break;	
	}

	if (tree.selected) {
		if (tree.selected.id != null) {
			default_layout = tree.selected.data.layout;
		}
		tree.selected.insert({
			text: 'nouveau_' + max_id,
			id: max_id,
			data: {
				hidden: 0,
				layout: default_layout,
				label: 'Nouveau_' + max_id,
				slabel: 'Nv_' + max_id,
				seo: false,
				description:'',
				keywords:'',
				placeholder_1_type : '',
				placeholder_2_type : '',
				placeholder_3_type : '',
				placeholder_4_type : '',
				placeholder_5_type : '',
				placeholder_6_type : '',
				placeholder_7_type : '',
				placeholder_8_type : '',
				placeholder_9_type : '',
				placeholder_1_value : '',
				placeholder_2_value : '',
				placeholder_3_value : '',
				placeholder_4_value : '',
				placeholder_5_value : '',
				placeholder_6_value : '',
				placeholder_7_value : '',
				placeholder_8_value : '',
				placeholder_9_value : ''
			}
		});
		max_id ++;
	};
}

function delete_node() {
	if (tree.selected) {
		if (tree.selected.id == null) {
			alert("Vous ne pouvez pas effacer ce noeud.");
		}
		else if (default_page_id == tree.selected.id) {
			alert("Vous ne pouvez pas enlever la page de demarrage ! Selectionnez une autre page, mettez la en demarrage et ensuite revenez a la page actuelle pour l'effacer.");
		}
		else {
			if (confirm("Etes-vous sur ?\nCette operation ne peut vraiment pas etre annulee et affectera l'entree de menu et ses descendants dans toutes les langues du site !")) {
				tree.selected.remove();
			}
		}
	}
}

function save_tree() {
	if (tree.selected) {
		store_node(tree.selected, false);
		jsonString = Json.toString({ tree: tree.serialize(), max_id: max_id, current_language: current_language, default_page_id: default_page_id } );
		new Ajax('../../sitemenu/save', {
			postBody: jsonString,
			method: 'post',
			onComplete: function(req){
				$('message').innerHTML = req;
				(function(){
					$('message').innerHTML = '';
				 }).delay(5000);
			}
		}).request();
	}
}

function dump_node() {
	if (tree.selected) {
		t = tree.serialize(tree.selected);
		j = Json.toString(t);
		alert(j);
	}
}

/*
** Redraws the form
*/
function update_controls() {
	if (!tree_read_only) {
		$('description').disabled = !$('seo').checked;
		$('keywords').disabled = !$('seo').checked;
		if (tree.selected) {
			if ($(tree.selected.data.layout)) $(tree.selected.data.layout).checked = true;

			update_placeholders();

		}
		default_node = tree.get(default_page_id);
		if (default_node != null) {
			default_node.icon = '/lib/js/mootree/node-flagged-ideva.gif';
				default_node.update();
		}
		
	}
}

/*
** Hides and shows the relevant placeholders according to the selected node layout choice
*/
function update_placeholders() {
	if (tree.selected) {
		if (tree.selected.id != null) {
			var counter = 1;
			if (tree.selected.data.layout != '') {
				layouts[tree.selected.data.layout].placeholders.each(function(key){
					update_placeholder(key);
					placeholder = $(key);
					placeholder.style.display = 'block';
					counter++;
				});
			}
			for(;counter<=9;counter++) {
				placeholder = $('placeholder_' + counter);
				placeholder.style.display = 'none';
			}
		}
	}
}

function update_placeholder(key) {
	div = $(key + '_div');
	type = $(key + '_type');
	$(div).empty();
	if (type.getValue() == 'content') {
		create_content(div, key + '_value');
	}
	if (type.getValue() == 'block') {
		create_block(div, key + '_value');
	}
	if (type.getValue() == 'widget') {
		create_widget(div, key + '_value');
	}
	if (type.getValue() == 'ad') {
		create_ad(div, key + '_value');
	}
	if (type.getValue() == 'form') {
		create_form(div, key + '_value');
	}
	if (type.getValue() == 'empty') {
		create_empty(div, key + '_value');
	}
}

/*
** Erases SEO form values
*/
function clear_seo() {
	$('description').value = '';
	$('keywords').value = '';
}	

/*
** Saves the current page values back into the selected node.
** Called when the node focus changes two times, once for saving,
** a second time to set the page to the new node value
*/
function store_node(node, state) {
	if (!tree_read_only) {
		if (state == false) {
			node.text = $('path').value;
			node.data.label = $('label').value;
			node.data.slabel = $('slabel').value;
			node.data.seo   = $('seo').checked;
			node.data.keywords   = $('keywords').value;
			node.data.description   = $('description').value;
			node.data.hidden = $('hidden').checked;
			for(i=0; i< document.layouts.layout.length; i++) {
				if (document.layouts.layout[i].checked == true) {
					node.data.layout = document.layouts.layout[i].id;
				}
			};
			node.data.placeholder_1_type = $('placeholder_1_type').getValue();
			node.data.placeholder_2_type = $('placeholder_2_type').getValue();
			node.data.placeholder_3_type = $('placeholder_3_type').getValue();
			node.data.placeholder_4_type = $('placeholder_4_type').getValue();
			node.data.placeholder_5_type = $('placeholder_5_type').getValue();
			node.data.placeholder_6_type = $('placeholder_6_type').getValue();
			node.data.placeholder_7_type = $('placeholder_7_type').getValue();
			node.data.placeholder_8_type = $('placeholder_8_type').getValue();
			node.data.placeholder_9_type = $('placeholder_9_type').getValue();
			node.data.placeholder_1_value = get_placeholder_value($('placeholder_1_value'));
			node.data.placeholder_2_value = get_placeholder_value($('placeholder_2_value'));
			node.data.placeholder_3_value = get_placeholder_value($('placeholder_3_value'));
			node.data.placeholder_4_value = get_placeholder_value($('placeholder_4_value'));
			node.data.placeholder_5_value = get_placeholder_value($('placeholder_5_value'));
			node.data.placeholder_6_value = get_placeholder_value($('placeholder_6_value'));
			node.data.placeholder_7_value = get_placeholder_value($('placeholder_7_value'));
			node.data.placeholder_8_value = get_placeholder_value($('placeholder_8_value'));
			node.data.placeholder_9_value = get_placeholder_value($('placeholder_9_value'));
			node.update();
			
		}
		if (state == true) {
			update_controls();
			$('path').value = node.text; /* + " " + node.id;*/
			$('label').value = node.data.label;
			$('slabel').value = node.data.slabel;
			$('seo').checked = node.data.seo;
			$('keywords').value = node.data.keywords;
			$('description').value = node.data.description;
			$('hidden').checked = node.data.hidden;
			set_select('placeholder_1_type', node.data.placeholder_1_type);
			set_select('placeholder_2_type', node.data.placeholder_2_type);
			set_select('placeholder_3_type', node.data.placeholder_3_type);
			set_select('placeholder_4_type', node.data.placeholder_4_type);
			set_select('placeholder_5_type', node.data.placeholder_5_type);
			set_select('placeholder_6_type', node.data.placeholder_6_type);
			set_select('placeholder_7_type', node.data.placeholder_7_type);
			set_select('placeholder_8_type', node.data.placeholder_8_type);
			set_select('placeholder_9_type', node.data.placeholder_9_type);
			update_placeholders();
			set_select('placeholder_1_value', node.data.placeholder_1_value);
			set_select('placeholder_2_value', node.data.placeholder_2_value);
			set_select('placeholder_3_value', node.data.placeholder_3_value);
			set_select('placeholder_4_value', node.data.placeholder_4_value);
			set_select('placeholder_5_value', node.data.placeholder_5_value);
			set_select('placeholder_6_value', node.data.placeholder_6_value);
			set_select('placeholder_7_value', node.data.placeholder_7_value);
			set_select('placeholder_8_value', node.data.placeholder_8_value);
			set_select('placeholder_9_value', node.data.placeholder_9_value);
			$('description').disabled = !$('seo').checked;
			$('keywords').disabled = !$('seo').checked;
		}
//		update_controls();
	}
}

/*
** Submits the save language form
*/
function change_language() {
	if (tree.selected) store_node(tree.selected, false);
	document.language.submit();
}

/*
** Sets the current selected node as the default page
*/
function set_default() {
	if (!tree.selected) {
		alert('Vous devez selectionner une page dans le menu avant de la mettre en demarrage.');
	}
	else {
		if (tree.selected.id) {
		    old_node = tree.get(default_page_id);
		    if (old_node != null) {
		    	default_page_id = tree.selected.id;
	    		old_node.icon = null;
	    		old_node.test = 'nice';
		    	old_node.update();
		    	tree.selected.icon = '/lib/js/mootree/node-flagged-ideva.gif';
				tree.selected.update();
	   		 }
		}
	}
}

/*
** Moves a node up the tree
** This function does not hop branches
** Saves the tree and calls submit() on the page after 2 seconds because of refresh on the tree
*/
function move_node_up() {
	if (tree.selected) {
		node = tree.selected;
		node_index = -1;
		for(i=0;i<tree.selected.parent.nodes.length;i++) {
			if (tree.selected.parent.nodes[i] == tree.selected) {
				node_index = i;
			}
		}
		if (node_index > 0) {
			tree.disable();
			temp_node = tree.selected.parent.nodes[node_index-1];
			tree.selected.parent.nodes[node_index-1] = node;
			tree.selected.parent.nodes[node_index] = temp_node;
			save_tree();
			(function(){
				document.language.submit();
			 }).delay(2000);
		}
	}
}

/*
** Moves a node down the tree
** This function does not hop branches
** Saves the tree and calls submit() on the page after 2 seconds because of refresh on the tree
*/
function move_node_down() {
	if (tree.selected) {
		node = tree.selected;
		node_index = -1;
		for(i=0;i<tree.selected.parent.nodes.length;i++) {
			if (tree.selected.parent.nodes[i] == tree.selected) {
				node_index = i;
			}
		}
		if ( node_index < (tree.selected.parent.nodes.length-1) ) {
			tree.disable();
			temp_node = tree.selected.parent.nodes[node_index+1];
			tree.selected.parent.nodes[node_index+1] = node;
			tree.selected.parent.nodes[node_index] = temp_node;
			save_tree();
			(function(){
				document.language.submit();
			 }).delay(2000);
		}
	}
}






/*
*
* Content Editor functions
*
*
*/

/*
** Fetches the current value of the specified FCKEditor as XHTML
*/
function getEditorValue( instanceName ) {
	var oEditor = FCKeditorAPI.GetInstance( instanceName ) ;
	return oEditor.GetXHTML( true ) ; // "true" means you want it formatted.
}

/*
** Sets the value of the specified FCKEditor
*/
function setEditorValue( instanceName, text ) {
	var oEditor = FCKeditorAPI.GetInstance( instanceName ) ;
	oEditor.SetHTML( text ) ;
}

/*
** Calls change_content again to reload the content from the system
*/
function reload_content() {
	if (tree.selected) {
		change_content(tree.selected, true);
	}
}

/*
** Loads the content associated with the selected node into FCKEdit
*/
function change_content(node, state) {
	if (state) {
		id = -1;
		if (tree.selected) {
			id = tree.selected.id;
			if (id == null) {
				alert("Ceci n'est pas une page dont vous pouvez changer le contenu.");
			}
			else {
				jsonString = Json.toString({ id: id, language: current_language } );
				new Ajax('../../contents/get', {postBody: jsonString, method: 'post', onComplete: function(req){setEditorValue('fckeditor1', req);}}).request();
			}
		}
	}
}

/*
** Saves the content associated with the selected node from FCKEdit to the server
*/
function save_content() {
	if (tree.selected) {
		id = tree.selected.id;
		jsonString = Json.toString({ id: id, language: current_language, menu_name: tree.selected.text, data: getEditorValue( 'fckeditor1' ) } );
		new Ajax('../../contents/save', {
			postBody: jsonString,
			method: 'post',
			onComplete: function(req){
				$('message').innerHTML = req;
				(function(){
					$('message').innerHTML = '';
				 }).delay(5000);
			}
		}).request();
	}
}



function get_placeholder_value(ph) {
	if (ph == null) {
		return '';
	}
	else {
		return ph.getValue();
	}
}


function set_select(SelectName, Value) {
  SelectObject = $(SelectName);
  if (SelectObject != null) {
	for(index = 0; index < SelectObject.length; index++) {
		if(SelectObject[index].value == Value)
			SelectObject.selectedIndex = index;
	}
  }
}


function toggleDiv( id ) {
	Fx.Slide(id).toggle();
}