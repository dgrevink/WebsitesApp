<?php

WSLoader::load_base('log');

WSLoader::load_dictionary('languages');

WSLoader::load_support('yaml');
WSLoader::load_support('json');

WSLoader::load_helper('forms-advanced-uikit');
WSLoader::load_helper('file');
WSLoader::load_support('phpthumb');

$userlanguage = '';

/**
 * Tables
 *
 * Configurable advanced table editor
 *
 * @package admin
 * @subpackage tables
 *
 */
class Tables extends WSController {

	public $module_name = 'Tables';
	public $module_version = '2.0';

	function Tables($smarty=null, $language=null, $current_language=null, $params=null, $parameters=null, &$auth=null) {
		parent::WSController();
		
		$this->smarty 			= (isset($this->smarty)				? $this->smarty				: null);
		$this->language 		= (isset($this->language)			? $this->language			: null);
		$this->current_language = (isset($this->current_language)	? $this->current_language	: null);
		$this->params 			= (isset($this->params)				? $this->params				: null);
		$this->parameters 		= (isset($this->parameters)			? $this->parameters			: null);
		$this->auth 			= (isset($this->auth)				? $this->auth				: null);

		$this->smarty 			= ($smarty==null			? $this->smarty				: $smarty);
		$this->language 		= ($language==null			? $this->language			: $language);
		$this->current_language = ($current_language==null	? $this->current_language	: $current_language);
		$this->params 			= ($params==null			? $this->params				: $params);
		$this->parameters 		= ($parameters==null		? $this->parameters			: $parameters);
		$this->auth				= ($auth==null				? $this->auth				: $auth);
	}

	function _index() {
		if (!$this->_check_rights(WSR_DATA)) {
			return false;
		}

		global $userlanguage;

		$config = new WSConfig;
		$config->load( dirname(__FILE__) . '/../../../application/config/' );

		$current_table = MyActiveRecord::FindFirst('tabledefinitions', "name like '" . $this->params[2] . "'");
		
		if (!$this->_check_data_rights($current_table->name)) {
			return false;
		}
		
		if (!$current_table) {
			$smarty_contents->assign('current_page_title', "Erreur");
			return ('fatal: unknown table');
		}

		$smarty_contents = new Template;
		$contents = null;

		$smarty_contents->assign('current_page_title', "<a href='.'>" . $current_table->title . '</a>');
		$smarty_contents->assign('current_short_page_title', $current_table->title);
		
		
		if (!isset($this->params[3])) {
			$this->params[3] = 'list';
		}

		$rich_editors = array();

		$mode = 'edit';	
		switch ($this->params[3]) {
			case 'create':
			case 'inline':
				$record = MyActiveRecord::Create($current_table->name);
				if ($this->params[3] == 'create') {
					$mode = 'create';
				}
				if ($this->params[3] == 'inline') {
					$mode = 'inline';
				}
			case 'edit':
				$smarty_contents->assign('listing_type', 'detail');
				if ($mode == 'edit') {
					$record = MyActiveRecord::FindById($current_table->name, $this->params[4]);
				}
				$table_fields = $current_table->find_children('tablefields', null, "porder asc, name asc");
				$code = array();
				$code_hidden = array();
				$can_edit_structure = ($this->_check_data_rights('tablefields', 'modify'));
				foreach($table_fields as $field) {
					$form_field_title = $field->title;
					if ($mode == 'inline') {
						$form_field_title = 'inline_' . $current_table->name . '_' .  $field->title;
					}
					
					$edit_code = '';
					if ($can_edit_structure) {
						$edit_code = " <a href=\"../../tablefields/edit/" . $field->id . "\"><img src=\"/admin/application/lib/images/icons/icon-duplicate.gif\"/></a>";
					}

					if (!$field->showedit) {
						$field->type = WST_HIDDEN; // Overload type to hidden
					}
					switch($field->type) {
						case WST_HIDDEN:
							$code_hidden[] = generate_hidden($form_field_title, $record->{$field->title});
						break;
						case WST_TABLE_LINK:
							$table_related = substr($field->title, 0, strlen($field->title) - 3);
							$lchar = $table_related[strlen($table_related)-1];
							if (is_numeric($lchar)) {
								$table_related = substr($table_related, 0, strlen($table_related) -1 );
							}
							$table_related_definition = MyActiveRecord::FindFirst('tabledefinitions', "name = '$table_related'");
							$related_records = MyActiveRecord::FindAll($table_related, "language = '" . $this->current_language . "'", @$table_related_definition->sortparams);
							$related_select = array();
							foreach($related_records as $related_record) {
								if ($table_related == 'groups') {
									if ( ($related_record->id == 1) && (!$this->_is_admin())) {
										continue;
									}
								}
								if (method_exists($related_record, 'get_link_title')) {
									$link_title = $related_record->get_link_title($current_table->id);
									if (!$link_title) {
										continue;
									}
									else {
										$related_select[$related_record->id] = $link_title;
									}
								}
								else {
									$related_select[$related_record->id] = $related_record->title;
								}
							}
							$inline_add_code = '';
							if ( (@$table_related_definition->inlineadd == 1) && ($mode != 'inline')) {
								$related_select['-1'] = '>>> AJOUTER UN NOUVEL &Eacute;L&Eacute;MENT...';
								$c = "<div class='inline-add-code' id='" . $field->title . "-popup' style='display: none;'>Chargement...";
								$c .= "</div>";
								$c .= "<script>";
								$c .= "$('select#" . $field->title . "').change(function(){";
								$c .= "  if ($(this).val() != -1) {";
								$c .= "    var panel = $(this).attr('id') + '-popup';";
								$c .= "    $('#' + panel).html('Chargement...').fadeOut();";
								$c .= "    $('#' + panel).parent().parent().height(46);";
								$c .= "  } else {";
								$c .= "    var panel = $(this).attr('id') + '-popup';";
								$c .= "    $('#' + panel).load('/admin/fr/tables/" . $table_related . "/inline/', function(){";
								$c .= "      height = $(this).height();";
								$c .= "      $('#' + panel).parent().parent().height( height + 70);";
								$c .= "    }).fadeIn();";
								$c .= "  ";
								$c .= "  }";
								$c .= "});";
								$c .= "$('a.collapse-button').click(function(){";
								$c .= "  $(this).parent().css('height', '10px');";
								$c .= "  return false;";
								$c .= "});";
								$c .= "</script>";
								$inline_add_code = $c;
							}
							if ( ($mode == 'create') && ($current_table->name == 'tablefields') ) {
								$record->{$field->title} = $this->params[4];
							}
							$code[] = generate_select( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $related_select, $record->{$field->title}, false, $inline_add_code . nl2br($field->description) . $edit_code ."\n");
						break;
						case WST_TEXT:
							$code[] = generate_text_area( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $record->{$field->title}, 8, 100, '', false, nl2br($field->description) . $edit_code );
						break;
						case WST_PASSWORD:
							$code[] = generate_input_password( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', '', false, nl2br($field->description) . $edit_code );
						break;
						case WST_BOOLEAN:
							$code[] = generate_input_checkbox( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $record->{$field->title}, nl2br($field->description) . $edit_code );
						break;
						case WST_LANGUAGE:
							$select = WSDLanguages::getSubSet($config->get('languages'));
							if ($mode == 'create') {
								$record->{$field->title} = $this->current_language;
							}
							$code[] = generate_select( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $select, $record->{$field->title}, false, nl2br($field->description) . $edit_code );
						break;
						case WST_REASONABLE_LANGUAGES:
							$select = WSDLanguages::getSubSet($config->get('reasonable_languages','system'));
							if ($mode == 'create') {
								$record->{$field->title} = $this->current_language;
							}
							$code[] = generate_select( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $select, $record->{$field->title}, false, nl2br($field->description) . $edit_code );
						break;
						case WST_PLACEHOLDER:
							$select = array();
							$select[''] = '---';
							for($i=1;$i<=9;$i++) {
								$select[$i] = "Position # " . $i;
							}
							$code[] = generate_select( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $select, $record->{$field->title}, false, nl2br($field->description) . $edit_code );
						break;
						case WST_FIELD_TYPE:
							$field_types = WSConstants::$field_types;
							asort($field_types);
							$code[] = generate_select( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $field_types, $record->{$field->title}, false, nl2br($field->description) . $edit_code );
						break;
						case WST_ORDER:
							$select = array();
							for($i=1;$i<=99;$i++) {
								$stri = (string) $i;
								if (strlen($stri) < 2) $stri = '0' . $stri;
								$select[$stri] = $stri;
							}
							$code[] = generate_select( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $select, $record->{$field->title}, false, nl2br($field->description) . $edit_code );
						break;
						case WST_TIME:
							$times = array();
							for($i=7;$i<24;$i++) {
								$t = ($i % 24);
								if (strlen($t) == 1) {
									$t = '0' . $t;
								}
								$times[$t . ':00'] = $t . ':00';
								$times[$t . ':30'] = $t . ':30';
							}
							$code[] = generate_select( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $times, $record->{$field->title}, false, nl2br($field->description) . $edit_code );
						break;
						case WST_DATE:
							if ($mode != 'edit') {
								$record->{$field->title} = date("Y-m-d");
							}
							$code[] = generate_input_text( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', ucwords(trim(strftime('%e %B %Y', strtotime($record->{$field->title})))), '', false, nl2br($field->description) . $edit_code, 'datepicker');
						break;
						case WST_COLOR:
							$code[] = generate_input_text( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $record->{$field->title}, '', false, nl2br($field->description) . $edit_code, 'cpicker' );
						break;
						case WST_TABLE_RIGHTS:
							$tables = MyActiveRecord::FindAll('tabledefinitions', null, 'menu_fr asc, system asc, porder asc, title asc');
							$items = array();
							foreach($tables as $table) {
								if ($table->system) {
									$items[$table->name] = "<i class='uk-icon-exclamation'></i> {$table->menu_fr} - " . $table->title;
									$items[$table->name . '_modify'] = "<i class='uk-icon-exclamation'></i> {$table->menu_fr} - " . $table->title . ' - Ajout/Modification';
									$items[$table->name . '_export'] = "<i class='uk-icon-exclamation'></i> {$table->menu_fr} - " . $table->title . ' - Export';
								}
								else {
									$items[$table->name] = "{$table->menu_fr} - " . $table->title;
									$items[$table->name . '_modify'] = "{$table->menu_fr} - " . $table->title . ' - Ajout/Modification';
									$items[$table->name . '_export'] = "{$table->menu_fr} - " . $table->title . ' - Export';
								}
							}
							$code[] = generate_checkbox_group( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $items, unserialize($record->{$field->title}), false, nl2br($field->description) . $edit_code );
						break;
						case WST_MODULES_RIGHTS:
							$items = array();
							foreach(WSConstants::$modules_rights as $key => $value) {
								$items[$key] = $value;
							}
							$code[] = generate_checkbox_group( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $items, unserialize($record->{$field->title}), false, nl2br($field->description) . $edit_code );
						break;
						case WST_HTML:
							$code[] = generate_text_area( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $record->{$field->title}, 10, 100, '');

							$rich_editors[] = "CKEDITOR.replace('" . $form_field_title . "',{ customConfig: '/application/views/ckeditor/config.js'	});";
						break;
						case WST_FILE:
						case WST_IMAGE:
							$code[] = generate_ckfinder( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $record->{$field->title}, '', false, nl2br($field->description) . $edit_code);
						break;
						case WST_KEYWORDS:
							$dbkeywords = MyActiveRecord::FindAll(
									$field->title,
									"language = '" . $this->current_language . "'",
									'title asc');
							$keywords = array();
							foreach ($dbkeywords as $keyword) { $keywords[$keyword->id] = $keyword->title; }
							$selected_ids = array();
							$selected = explode(',', $record->{$field->title});
							foreach($selected as $keyword) {
								$k = MyActiveRecord::FindFirst($field->title, "title = '" . $keyword . "' and language = '" . $this->current_language . "'");
								if ($k) {
									$selected_ids[] = $k->id;
								}
							}
							$code[] = generate_select( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $keywords, $selected_ids, false, nl2br($field->description) . $edit_code, true );
						break;
						default:
							$code[] = generate_input_text( $form_field_title, "<abbr title='" . $form_field_title ."'>" . $field->name . '</abbr>', $record->{$field->title}, '', false, nl2br($field->description) . $edit_code );
						break;
					}
				}
				$code[] = generate_hidden('wsadmin_mode', $mode);
				$code[] = generate_hidden('wsadmin_table', $current_table->name);
				$code = array_merge($code, $code_hidden);
				if (($current_table->childtable) != '') {
					$code[] = "<a href='../../" . $current_table->childtable . "/create/" . $current_table->id . "' target='_blank'>Ajouter un sous-&eacute;l&eacute;ment...</a>";
					$code[] = "<ul class='tablefield-list'>";
						foreach($record->find_children($current_table->childtable) as $cr) {
							$title = (method_exists($cr, 'get_link_title'))?$cr->get_link_title():$cr->title;
							$code[] = "<li><a href='../../" . $current_table->childtable . "/edit/" . $cr->id . "' target='_blank'>" . $title . '</li>';
						}
					$code[] = '</ul>';
				}
				$smarty_contents->assign('code', implode('', $code));
				$smarty_contents->assign('rich_editors', implode('', $rich_editors) );
				if ($mode == 'create') {
					$smarty_contents->assign('current_page_title', "<a href='..'><i class='uk-icon-undo'></i> Ajout dans &#8220;" . $current_table->title . '&#8221;</a>');
				}
				else {
					$smarty_contents->assign('current_page_title', "<a href='..' title=\"Cliquez ici pour annuler l'opération.\"><i class='uk-icon-undo'></i> Modification de &#8220;" . $record->title . "&#8221; dans &#8220;" . $current_table->title . '&#8221;</a>');
				}
				$smarty_contents->assign('showsavebutton', ($this->_check_data_rights($current_table->name, 'modify'))?'ok':'');
				$smarty_contents->assign('mode', $mode);
				if ($mode == 'inline') {
					echo implode('', $code);
					die();
				}
			break;
			case 'linked-table':
				$smarty_contents->assign('listing_type', 'list');
				$smarty_contents->assign('current_language', $this->current_language);
				$smarty_contents->assign('current_table', $current_table->name);
				$smarty_contents->assign('field', $this->params[4]);
				$smarty_contents->assign('fieldcondition', $this->params[5]);
			break;
			case 'export':
				$this->_export();
			break;
			
			// LIST MODE
			default:
				// If wsadmin_mode is set, it means we have to store or create the record
				if (isset($_POST['wsadmin_mode'])) {
					$mode = $_POST['wsadmin_mode'];
					$tablename = $_POST['wsadmin_table'];
					$current_table = MyActiveRecord::FindFirst('tabledefinitions', "name = '" . $_POST['wsadmin_table'] ."'");
					if ( ($current_table) && ($this->_check_data_rights($current_table->name, 'modify')) ) {
						$current_table_fields = $current_table->find_children('tablefields');
						$record = null;
						if ($mode == 'edit') {
							$record = MyActiveRecord::FindById($current_table->name, $_POST['id']);
						}
						else {
							$record = MyActiveRecord::Create($current_table->name);
						}
						foreach($current_table_fields as $field) {
							if ($field->title != 'id') {
								switch ($field->type) {
									case WST_PASSWORD:
										if (trim($_POST[$field->title]) != '') {
											$record->{$field->title} = md5($_POST[$field->title]);
										}
									break;
									case WST_STRING:
									case WST_TEXT:
										$record->{$field->title} = htmlentities($_POST[$field->title], ENT_QUOTES, "utf-8" );
									break;
									case WST_DATE:
										$d = $_POST[$field->title];
//										d($d);
//										$d = substr($d, strpos($d, ',') + 2, strlen($d));
										$d = str_ireplace('Janvier', 'january', $d);
										$d = str_ireplace('Février', 'february', $d);
										$d = str_ireplace('Mars', 'march', $d);
										$d = str_ireplace('Avril', 'april', $d);
										$d = str_ireplace('Mai', 'may', $d);
										$d = str_ireplace('Juin', 'june', $d);
										$d = str_ireplace('Juillet', 'july', $d);
										$d = str_ireplace('Août', 'august', $d);
										$d = str_ireplace('Septembre', 'september', $d);
										$d = str_ireplace('Octobre', 'october', $d);
										$d = str_ireplace('Novembre', 'november', $d);
										$d = str_ireplace('Décembre', 'december', $d);
//										d($d);
										$record->{$field->title} = date('Y-m-d', strtotime($d) );
									break;
									case WST_HTML:
										$record->{$field->title} = $_POST[$field->title];
									break;
									case WST_BOOLEAN:
										$record->{$field->title} = isset($_POST[$field->title]);
									break;
									case WST_TABLE_LINK:
										// If we are in a table link field, and the value is -1, we have to create a subrecord in the related table
										if ($_POST[$field->title] == '-1') {
											$inline_table_name = substr($field->title, 0, strlen($field->title)-3);
											$inline_table = MyActiveRecord::FindFirst('tabledefinitions', "name = '" . $inline_table_name ."'");
											if ($inline_table) {
												$inline_table_fields = $inline_table->find_children('tablefields');
												$inline_record = MyActiveRecord::Create($inline_table->name);
												foreach($inline_table_fields as $ifield) {
													if ($ifield->title != 'id') {
														switch ($ifield->type) {
															case WST_PASSWORD:
																if (trim($_POST['inline_' . $inline_table_name . '_' . $ifield->title]) != '') {
																	$inline_record->{$ifield->title} = md5($_POST['inline_' . $inline_table_name . '_' . $ifield->title]);
																}
															break;
															case WST_STRING:
															case WST_TEXT:
																$inline_record->{$ifield->title} = htmlentities($_POST['inline_' . $inline_table_name . '_' . $ifield->title], ENT_QUOTES, "utf-8" );
															break;
															case WST_HTML:
																$inline_record->{$ifield->title} = $_POST['inline_' . $inline_table_name . '_' . $ifield->title];
															break;
															case WST_BOOLEAN:
																$inline_record->{$ifield->title} = isset($_POST['inline_' . $inline_table_name . '_' . $ifield->title]);
															break;
															case WST_TABLE_LINK:
																$inline_record->{$ifield->title} = $_POST['inline_' . $inline_table_name . '_' . $ifield->title];
															break;
															case WST_TABLE_RIGHTS:
															case WST_MODULES_RIGHTS:
																$items = array();
																if (isset($_POST['inline_' . $inline_table_name . '_' . $ifield->title]) && is_array($_POST['inline_' . $inline_table_name . '_' . $ifield->title])) {
																	foreach($_POST['inline_' . $inline_table_name . '_' . $ifield->title] as $item) {
																		$items[$item] = $item;
																	}
																}
																$inline_record->{$ifield->title} = serialize($items);
															break;
															default:
																$inline_record->{$ifield->title} = $_POST['inline_' . $inline_table_name . '_' . $ifield->title];
															break;
														}
													}
												}
												$inline_result = false;
												if ( method_exists($inline_record, 'saveadvanced') ) {
													$inline_result = $inline_record->saveadvanced($mode);
												}
												else {
													$inline_result = $inline_record->save();
												}
												if ($inline_result) {
													$_POST[$field->title] = $inline_record->id;
												}
												else {
													$result = false;
												}
												
												
											}
										}
										$record->{$field->title} = $_POST[$field->title];
									break;
									case WST_TABLE_RIGHTS:
									case WST_MODULES_RIGHTS:
										$items = array();
										if (isset($_POST[$field->title]) && is_array($_POST[$field->title])) {
											foreach($_POST[$field->title] as $item) {
												$items[$item] = $item;
											}
										}
										$record->{$field->title} = serialize($items);
									break;
									case WST_KEYWORDS:
										$keywords = array();
										if (isset($_POST[$field->title]) && is_array($_POST[$field->title])) {
											$dbkeywords = MyActiveRecord::FindAll($field->title, "language = '" . $this->current_language . "'");
											foreach($_POST[$field->title] as $keyword_id) {
												$keywords[] = $dbkeywords[$keyword_id]->title;
											}
										}
										$record->{$field->title} = implode(',', $keywords);
									break;
									default:
										$record->{$field->title} = $_POST[$field->title];
									break;
								}
							}
						}
						
						$result = null;
//						d($record);
						if ( method_exists($record, 'saveadvanced') ) {
							$result = $record->saveadvanced($mode);
						}
						else {
							$result = $record->save();
						}
						
						if ($result) {
							if ($mode == 'create') {
								WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Created record #' . $record->id . ' ( ' . $record->title . ' ) on table ' . $current_table->name );
							}
							else {
								WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Updated record #' . $record->id . ' ( ' . $record->title . ' ) on table ' . $current_table->name );
							}
							$smarty_contents->assign('created_id', $record->id);
							$smarty_contents->assign('operation_text', "<span class='ok'>Donn&eacute;es sauvegard&eacute;es !</span>");
						}
						else {
							if ($mode == 'create') {
								WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not create record ( ' . $record->title . ' ) on table ' . $current_table->name );
							}
							else {
								WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not update record ( ' . $record->title . ' ) on table ' . $current_table->name );
							}
							$smarty_contents->assign('created_id', '000000');
							$smarty_contents->assign('operation_text', "<span class='error'>Erreur &agrave; la sauvegarde de ces donn&eacute;es.</span>");
						}
					}
				}
				// End create or edit record
				
				$smarty_contents->assign('table_structure', $this->_generateTableStructure($current_table->name));
				$smarty_contents->assign('listing_type', 'list');
				$smarty_contents->assign('current_language', $this->current_language);
				$smarty_contents->assign('current_table', $current_table->name);
				$smarty_contents->assign('current_table_description', nl2br($current_table->{'description_' . $userlanguage}));

				if ($this->_check_data_rights('tabledefinitions', 'modify')) {
					$smarty_contents->assign('current_table_id', $current_table->id);
				}

				if ($this->_check_data_rights($current_table->name, 'modify')) {
					$smarty_contents->assign('showcreatebutton', true);
				}

				if ($this->_check_data_rights($current_table->name, 'export')) {
					$smarty_contents->assign('showexportbutton', true);
				}
			break;
		}


		return $smarty_contents->fetch( 'tables-index-' . $this->language . '.tpl' );
	}


	function _generateTableStructure($name) {
		$thead = '';
		$tbody = '';
		$tfoot = '';
		$current_table = MyActiveRecord::FindFirst('tabledefinitions', "name = '" . $name ."'");
		if ( ($current_table) && ($this->_check_data_rights($current_table->name)) ) {
				$current_table_fields = $current_table->find_children('tablefields', null,"porder asc, name asc");
				foreach($current_table_fields as $field) {
					if ($field->showlist) {
						$thead .= "<th width='" . $field->width . "'>";
						$thead .= $field->name;
						if ($this->_check_data_rights('tablefields', 'modify')) {
							$thead .= " <a href='../tablefields/edit/" . $field->id . "'><img src='/admin/application/lib/images/icons/icon-duplicate.gif' /></a>";
						}
						$thead .= "</th>\n";
						$tfoot .= "<th>" . $field->name . "</th>\n";
					}
				}
//				$thead .= "<th width='40px'>Action</th>";
				$tfoot .= "<th>Action</th>";
				return 		"<thead><tr>" . $thead . "</tr></thead>\n"
						.	"<tbody><tr>" . $tbody . "</tr></tbody>\n"
//						.	"<tfoot><tr>" . $tfoot . "</tr></tfoot>";
				;
		}

		return "Mauvaise Table";
	}


	/**
	 * loadrecords()
	 * AJAX URL: http://[SITE]/admin/tables/loadrecords/[TABLE_NAME]/
	 * loads all records from the table specified in url formatted for the jquery plugin nestedsortable widget.
	 * @global array params is used to check 
	 */
	function loadrecords() {
		setlocale(LC_ALL, array('fr_FR.utf8', 'fr_FR'));

		WSLoader::load_base('templates');

		$this->current_language = $_GET['sCurrentLanguage'];

		WSLoader::load_support('json');

		// Check if a parameter is given to the function, die otherwise.
		if (!isset($this->params[0])) {
			die();
		}
		
		$current_table = MyActiveRecord::FindFirst('tabledefinitions', "name like '" . $this->params[0] . "'");
		
		if (!$current_table) die('404');

		$json = new Services_JSON;

		$object = new stdClass();;

		$table_fields = MyActiveRecord::FindAll('tablefields', "tabledefinitions_id = " . $current_table->id, "porder asc, name asc");
		
		$used_table_fields = array();
		$key = 0;
		foreach($table_fields as $field) {
			if ($field->showlist) {
				$used_table_fields[$key] = $field;
				$key++;
			}
		}

		// SEARCH
		$_GET['sSearch'] = htmlentities($_GET['sSearch'], ENT_QUOTES, "utf-8" );
		
		$search_clause = "language = '" . $this->current_language . "'";
		if (trim($_GET['sSearch']) != '') {
			$temp = array();
			foreach($table_fields as $field) {
				if ($field->showlist != 0) {
					if (!endsWith($field->title, '_id')) {
						$temp[] = $field->title . " LIKE \"%" . $_GET['sSearch'] . "%\"";
					}
					else {
						$related_table = substr($field->title, 0 , strlen($field->title)-3);
						$related_clause_items = array();
						foreach(MyActiveRecord::FindAll($related_table, "title like \"%" . $_GET['sSearch'] . "%\"", 'id asc') as $rr) {
							$related_clause_items[] = $field->title ." = " . $rr->id;
						}
						$related_clause = implode(' OR ', $related_clause_items);
						if ($related_clause != '') {
							$temp[] = ' (' . $related_clause . ') ';
						}
					}
				}
			}
			if (count($temp) > 0) {
				$search_clause = '(' . implode(' OR ', $temp) . ') and ' . $search_clause;
			}
		}

		// ORDER
		$sOrder = '';
		if ( !isset( $_GET['iSortCol_0'] ) ) {
			$sOrder = $current_table->sortparams;
		}
		else {
			for ( $i=0 ; $i<mysql_real_escape_string( $_GET['iSortingCols'] ) ; $i++ )
			{
				$sOrder .= $used_table_fields[$_GET['iSortCol_'.$i]]->title."
				 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
			$sOrder = substr_replace( $sOrder, "", -2 );
		}

		$table_records = MyActiveRecord::FindAll($current_table->name, $search_clause, $sOrder, $_GET['iDisplayLength'], $_GET['iDisplayStart']);



		$object->aaData =  array();

		$count = 0;		
		foreach ($table_records as $record) {
			// Hide admin super users from other people
			if ( ($this->params[0] == 'users') && ($record->groups_id == 1) && (!$this->_is_admin()) ) {
				continue;
			}
			$count++;
			
			$info = array();
			$first = true;
			foreach($table_fields as $field) {
				$disabled = ( (!$field->listeditable) || (isset($this->params[1])) || (!$this->_check_data_rights($current_table->name, 'modify')) )?'disabled ':'';
				if ($field->showlist) {
					switch ($field->type) {
						case WST_TABLE_LINK:
							$table_related = substr($field->title, 0, strlen($field->title) - 3);

							$lchar = $table_related[strlen($table_related)-1];
							if (is_numeric($lchar)) {
								$table_related = substr($table_related, 0, strlen($table_related) -1 );
							}

							$table_related_definition = MyActiveRecord::FindFirst('tabledefinitions', "name like '" . $table_related . "'");

							$control = array();
							
							if ($disabled != '') {
								$related_record = MyActiveRecord::FindById($table_related, $record->{$field->title});
								if (!$related_record) {
									$info[] = "###";
								}
								else {
    								if (method_exists($related_record, 'get_link_title')) {
	    								$info[] = $related_record->get_link_title($current_table->id);
    								}
    								else{
	    								$info[] = $related_record->title;
    								}
								}
							}
							else {
								$related_records = MyActiveRecord::FindAll($table_related, "language = '" . $this->current_language . "'", @$table_related_definition->sortparams);
	    						$control[] = "<select " . $disabled . " class='inlinefieldselector' onchange=\"updateselect( '" . $current_table->name . "', '" . $record->id . "', '" . $field->title . "', this.value );\">";
	    						foreach($related_records as $related_record) {
									// Hide admin super user group from other people
									if ( ($table_related == 'groups') && ($related_record->id == 1) && (!$this->_is_admin()) ) {
									}
									else {	
		    							if ($related_record->id == $record->{$field->title}) {
		    								if (method_exists($related_record, 'get_link_title')) {
			    								$control[] = "<option selected value='" . $related_record->id . "'>" . $related_record->get_link_title($current_table->id) . "</item>";
		    								}
		    								else{
			    								$control[] = "<option selected value='" . $related_record->id . "'>" . $related_record->title . "</item>";
		    								}
		    							}
		    							else {
		    								if (method_exists($related_record, 'get_link_title')) {
			    								$control[] = "<option value='" . $related_record->id . "'>" . $related_record->get_link_title($current_table->id) . "</item>";
		    								}
		    								else{
			    								$control[] = "<option value='" . $related_record->id . "'>" . $related_record->title . "</item>";
		    								}
		    							}
									}
	    						}
	    						$control[] = "</select>";
	    						if (isset($related_records[$record->{$field->title}])) {
	    							$add_link = '';
//	    							$add_link = "<a class='inlineaddrecord' href='../" . $table_related . "/create/0#" . $current_table->name . "' target='_blank'>+</a>";
	    							if (!$this->_check_data_rights($table_related, 'modify')) {
	    								$add_link = '';
	    							}
	    							$info[] = "<div class='inlinefieldselectorcontainer'>" . implode("", $control) . $add_link . "</div>";
	    						}
	    						else {
	    							$info[] = "###";
	    						}
							}
							
						break;
						case WST_FIELD_TYPE:
							$info[] = WSConstants::$field_types[$record->{$field->title}];
						break;
						case WST_TIME:
							$times = array();
							for($i=7;$i<24;$i++) {
								$t = ($i % 24);
								if (strlen($t) == 1) {
									$t = '0' . $t;
								}
								$times[$t . ':00'] = $t . ':00';
								$times[$t . ':30'] = $t . ':30';
							}
							$control = array();
							$control[] = "<select class='inlinefieldselector inlinefieldselectortime' onchange=\"updateselect( '" . $current_table->name . "', '" . $record->id . "', '" . $field->title . "', this.value );\">";
							foreach($times as $s) {
								if ($s == $record->{$field->title}) {
									$control[] = "<option selected value='" . $s . "'>" . $s . "</item>";
								}
								else {
									$control[] = "<option value='" . $s . "'>" . $s . "</item>";
								}
							}
							$control[] = "</select>";
							$info[] = implode("", $control);
						break;
						case WST_ORDER:
							$select = array();
							for($i=1;$i<=99;$i++) {
								$stri = (string) $i;
								if (strlen($stri) < 2) $stri = '0' . $stri;
								$select[$stri] = $stri;
							}
							$control = array();
							$control[] = "<select class='inlinefieldselector' onchange=\"updateselect( '" . $current_table->name . "', '" . $record->id . "', '" . $field->title . "', this.value );\">";
							foreach($select as $s) {
								if ($s == $record->{$field->title}) {
									$control[] = "<option selected value='" . $s . "'>" . $s . "</item>";
								}
								else {
									$control[] = "<option value='" . $s . "'>" . $s . "</item>";
								}
							}
							$control[] = "</select>";
							$info[] = implode("", $control);
						break;
						case WST_PLACEHOLDER:
							$tpl = new Template;
							$select = array();
							$select[''] = '---';
							for($i=1;$i<=9;$i++) {
								$select[$i] = "Position # " . $i;
							}
							$control = array();
							$control[] = "<select class='inlinefieldselector' onchange=\"updateselect( '" . $current_table->name . "', '" . $record->id . "', '" . $field->title . "', this.value );\">";
							foreach($select as $skey => $s) {
								if ($skey == $record->{$field->title}) {
									$control[] = "<option selected value='" . $skey . "'>" . $s . "</item>";
								}
								else {
									$control[] = "<option value='" . $skey . "'>" . $s . "</item>";
								}
							}
							$control[] = "</select>";
							$info[] = implode("", $control);
						break;
						case WST_BOOLEAN:
							$control = array();
							if ($record->{$field->title}) {
								$control[] = "<input type='checkbox' " . $disabled . "class='inlinefieldcheckbox' checked='checked' onclick=\"updatecheckbox( '" . $current_table->name . "', '" . $record->id . "', '" . $field->title . "', this.checked );\" />";
							}
							else {
								$control[] = "<input type='checkbox' " . $disabled . "class='inlinefieldcheckbox' onclick=\"updatecheckbox( '" . $current_table->name . "', '" . $record->id . "', '" . $field->title . "', this.checked );\"/>";
							}
							$info[] = implode("", $control);
						break;
						case WST_IMAGE:
							if (trim($record->{$field->title}) != '') {
								$info[] = "<a href='" . $record->{$field->title} . "' target='_blank'><img class='table-thumb' src='/admin/tables/resize_image/" . $record->{$field->title} . "' /></a>";
							}
							else {
								$info[] = "";
							}
						break;
						case WST_LANGUAGE:
							$select = WSDLanguages::getSubSet($config->get('languages'));
							$info[] = $select[$record->{$field->title}];
						break;
						case WST_REASONABLE_LANGUAGES:
							$select = WSDLanguages::getSubSet($config->get('reasonable_languages','system'));
							$info[] = $select[$record->{$field->title}];
						break;
						case WST_DATE:
							$info[] = str_ireplace(' ', '&nbsp;', ucwords(trim(strftime('%e %B %Y', strtotime($record->{$field->title})))));
						break;
						case WST_COLOR:
							$info[] = "<div style='background-color: #" . $record->{$field->title} . "' class='cpicker-list-display'>" . $record->{$field->title} . "</div>";
						break;
						default:
							if ($first) {
								$first = false;
								$title = $record->{$field->title};
								if (method_exists($record, 'get_link_title')) {
									$title = $record->get_link_title($field->title);
								}
								if (trim($title) == '') {
									$title = '###';
								}
								$controls = '';
								if ($this->_check_data_rights($current_table->name, 'modify')) {
									if (!isset($this->params[1])) {
										$controls .= "<div class='row-controls'>";
										$controls .= "<ul>";
										$controls .= "<li><a href='create/0'><img class='button-duplicate-record-item' title='Ajouter...' src='/admin/application/lib/images/icons/icon-plus.gif' /> Ajouter</a></li>";
										$controls .= "<li><a href='edit/" . $record->id . "'><img class='button-duplicate-record-item' title='Modifier...' src='/admin/application/lib/images/icons/icon-norm.gif' /> Modifier</a></li>";
										//$controls .= "<li><a href='#' onclick=\"createrecord('" .  $this->current_language . "', '" . $current_table->name . "', 'create', 0, '" .  $current_table->name . "'," . $record->id . ");\"><img title='Ajouter...' src='/admin/application/lib/images/icons/icon-plus.gif' /> Ajouter (Advanced)</a></li>";
										$controls .= "<li><a href='#' onclick=\"duplicaterecord('" .  $current_table->name . "'," . $record->id . ");\"><img title='Dupliquer...' src='/admin/application/lib/images/icons/icon-duplicate.gif' /> Dupliquer</a></li>";
										$controls .= "<li><a href='#' onclick=\"deleterecord('" .  $current_table->name . "'," . $record->id . ");\"><img title='Effacer...' src='/admin/application/lib/images/icons/icon-remove.gif' /> Effacer</a></li>";
										$controls .= "</ul>";
										$controls .= "</div>";
									}
								}
								if (!$this->_check_data_rights($current_table->name, 'modify')) {
									$info[] = $title;
								}
								else {
									if (isset($this->params[1])) {
										$info[] = "<a href='../../" . $current_table->name . '/edit/' . $record->id . "' title='" . $record->title . "'>" . $title . "</a>" . $controls;
									}
									else {
										$info[] = "<a href='edit/" . $record->id . "' title='" . $record->title . (isset($record->titleseo)?' (' . $record->titleseo . ')':'') . "' id='row-" . $record->id . "'>" . $title . "</a>" . $controls;
									}
								}
							}
							else {
								$title = $record->{$field->title};
								if (!$disabled) {
									$title = "<span class='varchar'>" . $title . "</span>";
								}
//								if (method_exists($record, 'display_list')) {
//									$title = $record->display_list($field->title);
//								}
								$info[] = $title;
							}
						break;
					}
				}
			}
			$object->aaData[] = $info;
		}

		// Params
		$object->iTotalRecords = MyActiveRecord::Count($current_table->name, "language = '" . $this->current_language . "'");
//		if ($search_clause == '') {
//			$object->iTotalDisplayRecords =  MyActiveRecord::Count($current_table->name);
//		}
//		else {
			$object->iTotalDisplayRecords = MyActiveRecord::Count($current_table->name, $search_clause);
//		}


		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
		header("Cache-Control: no-cache, must-revalidate" ); 
		header("Pragma: no-cache" );
		header("Content-type: text/x-json");

		print_r( $json->encode($object) );
	}


	
	function updatefield() {
		$table = $this->params[0];
		$id = $this->params[1];
		$field = $this->params[2];
		$value = $this->params[3];

		if (!$this->_check_data_rights($table, 'modify')) {
			$id = 0;
		}

		if ($id == 0) {
			echo '{ "type": "error", "message": "Operation interdite."}';
			die();
		}
		$record = MyActiveRecord::FindById($table, $id);
		if (!$record) {
			echo '{ "type": "error", "message": "Erreur &agrave; la sauvegarde de ces donn&eacute;es."}';
		}
		else {
			if ($table == 'users') {
				if ( ($record->groups_id == 1) && (!$this->_is_admin())) {
					echo '{ "type": "error", "message": "Erreur vous ne pouvez pas faire cela !!!"}';
					die();
				}
			}

			$record->$field = $value;
			if ($record->save()) {
				WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Updated record #' . $record->id . ' ( ' . $record->title . ' ) on table ' . $table . ': ' . $field . ' is now set to ' . $value );
				echo '{ "type": "info", "message": "Donn&eacute;es sauvegard&eacute;es !"}';
			}
			else {
				WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could NOT update record #' . $record->id . ' ( ' . $record->title . ' ) on table ' . $table . ': ' . $field . ' could not be set to ' . $value );
				echo '{ "type": "error", "message": "Erreur &agrave; la sauvegarde de ces donn&eacute;es."}';
			}
		}
	}
	
	function updatefieldtext() {
		d($_GET, true);
	}
	
	function deleterecord() {
		$table = $this->params[0];
		$id = $this->params[1];

		if (!$this->_check_data_rights($table, 'modify')) {
			$id = 0;
			echo '{ "type": "error", "message": "Erreur &agrave; l\'effacement des donn&eacute;es."}';
			die();
		}

		if ($id == 0) {
			echo '{ "type": "error", "message": "Erreur &agrave; l\'effacement des donn&eacute;es."}';
			die();
		}
		$record = MyActiveRecord::FindById($table, $id);
		if (!$record) {
			echo '{ "type": "error", "message": "Erreur &agrave; l\'effacement des donn&eacute;es."}';
		}
		else {
			if ($record->destroy()) {
				WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Deleted record #' . $record->id . ' ( ' . $record->title . ' ) on table ' . $table );
				echo '{ "type": "info", "message": "Donn&eacute;es effac&eacute;es !"}';
			}
			else {
				WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not delete record #' . $record->id . ' ( ' . $record->title . ' ) on table ' . $table );
				echo '{ "type": "error", "message": "Erreur &agrave; l\'effacement des donn&eacute;es."}';
			}
		}
	}

	function duplicaterecord() {
		$table = $this->params[0];
		$id = $this->params[1];
		
		if (!$this->_check_data_rights($table, 'modify')) {
			$id = 0;
		}

		if ($id == 0) {
			echo 0;
			die();
		}
		$record = MyActiveRecord::FindById($table, $id);
		if (!$record) {
			echo 0;
		}
		else {
			$old_id = $record->id;
			$old_title = $record->title;
			$record->id = null;
			$record->title .= ' COPIE';

				
			if ($record->save()) {

				
				$tabledef = MyActiveRecord::FindFirst('tabledefinitions', "name ='$table'");

				if ($tabledef) {
					if (class_exists($tabledef->childtable)) {
						$childtabledef = MyActiveRecord::FindFirst($tabledef->childtable);
						if ($childtabledef) {
							foreach (MyActiveRecord::FindAll($tabledef->childtable, $table . "_id = " . $old_id ) as $dupchild) {
								$dupchild->id = null;
								$dupchild->{$table . '_id'} = $record->id;
								$dupchild->save();
							}
						}
					}
				}
				
				WSLog::admin( WS_INFO, $this->auth->session['username'], 0, 'Duplicated record #' . $old_id . ' ( ' . $old_title . ' ) on table ' . $table . ' to record #' . $record->id . ' ( ' . $record->title . ' ) ');
				echo '{ "type": "info", "message": "Donn&eacute;es dupliqu&eacute;es !", "id":"' . $record->id . '"}';
			}
			else {
				WSLog::admin( WS_ERROR, $this->auth->session['username'], 0, 'Could not duplicate record #' . $record->id . ' ( ' . $record->title . ' ) on table ' . $table );
				echo '{ "type": "error", "message": "Erreur &agrave; la destruction des donn&eacute;es."}';
			}
		}
	}


	function resize_image() {
		ini_set('memory_limit', -1);
		$file = WS_ROOT . implode('/', $this->params);

		if (file_exists($file)) {
			$thumb = PhpThumbFactory::create($file);
			$thumb->adaptiveResize(150,20);
			$thumb->show();
		}
	}
	
	function getaddform() {
		$this->current_language = $this->params[0];
		$table = $this->params[1];
		$mode = $this->params[2];
		$id = $this->params[3];
		$caller_table = $this->params[4];
		$caller_id = $this->params[5];
		
		d($this->params);

		$current_table = MyActiveRecord::FindFirst('tabledefinitions', "name like '" . $table . "'");
		
		if (!$this->_check_data_rights($current_table->name)) {
			return false;
		}
		
		if (!$current_table) {
			$smarty_contents->assign('current_page_title', "Erreur");
			return ('fatal: unknown table');
		}

		$rich_editors = array();

		$code = array();
		switch ($mode) {
			case 'create':
				$record = MyActiveRecord::Create($current_table->name);
				$mode = 'create';
			case 'edit':
				//$smarty_contents->assign('listing_type', 'detail');
				if ($mode == 'edit') {
					$record = MyActiveRecord::FindById($current_table->name, $this->params[4]);
				}
				$table_fields = $current_table->find_children('tablefields', null, "porder asc, name asc");
				$can_edit_structure = ($this->_check_data_rights('tablefields', 'modify'));
				foreach($table_fields as $field) {
					$edit_code = '';
					if ($can_edit_structure) {
						$edit_code = " <a href=\"../../tablefields/edit/" . $field->id . "\"><img src=\"/admin/application/lib/images/icons/icon-duplicate.gif\" style='vertical-align: middle;'/></a>";
					}

					if (!$field->showedit) {
						$field->type = WST_HIDDEN; // Overload type to hidden
					}
					switch($field->type) {
						case WST_HIDDEN:
							$code[] = generate_hidden($field->title, $record->{$field->title});
						break;
						case WST_TABLE_LINK:
							$table_related = substr($field->title, 0, strlen($field->title) - 3);
							$lchar = $table_related[strlen($table_related)-1];
							if (is_numeric($lchar)) {
								$table_related = substr($table_related, 0, strlen($table_related) -1 );
							}
							$related_records = MyActiveRecord::FindAll($table_related, "language = '" . $this->current_language . "'", 'title asc');
							$related_select = array();
							foreach($related_records as $related_record) {
								if ($table_related == 'groups') {
									if ( ($related_record->id == 1) && (!$this->_is_admin())) {
										continue;
									}
								}
								if (method_exists($related_record, 'get_link_title')) {
									$link_title = $related_record->get_link_title($current_table->id);
									if (!$link_title) {
										continue;
									}
									else {
										$related_select[$related_record->id] = $link_title;
									}
								}
								else {
									$related_select[$related_record->id] = $related_record->title;
								}
							}
							if ( ($mode == 'create') && ($current_table->name == 'tablefields') ) {
								$record->{$field->title} = $this->params[4];
							}
							$code[] = generate_select( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', $related_select, $record->{$field->title}, false, nl2br($field->description) . $edit_code );
						break;
						case WST_TEXT:
							$code[] = generate_text_area( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', $record->{$field->title}, 8, 100, '', false, nl2br($field->description) . $edit_code );
						break;
						case WST_PASSWORD:
							$code[] = generate_input_password( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', '', false, nl2br($field->description) . $edit_code );
						break;
						case WST_BOOLEAN:
							$code[] = generate_input_checkbox( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', $record->{$field->title}, nl2br($field->description) . $edit_code );
						break;
						case WST_LANGUAGE:
							$select = WSDLanguages::getSubSet($config->get('languages'));
							$code[] = generate_select( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', $select, $record->{$field->title}, false, nl2br($field->description) . $edit_code );
						break;
						case WST_REASONABLE_LANGUAGES:
							$select = WSDLanguages::getSubSet($config->get('reasonable_languages','system'));
							$code[] = generate_select( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', $select, $record->{$field->title}, false, nl2br($field->description) . $edit_code );
						break;
						case WST_FIELD_TYPE:
							$field_types = WSConstants::$field_types;
							asort($field_types);
							$code[] = generate_select( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', $field_types, $record->{$field->title}, false, nl2br($field->description) . $edit_code );
						break;
						case WST_ORDER:
							$select = array();
							for($i=1;$i<=50;$i++) {
								$select[$i] = $i;
							}
							$code[] = generate_select( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', $select, $record->{$field->title}, false, nl2br($field->description) . $edit_code );
						break;
						case WST_TIME:
							$times = array();
							for($i=7;$i<24;$i++) {
								$t = ($i % 24);
								if (strlen($t) == 1) {
									$t = '0' . $t;
								}
								$times[$t . ':00'] = $t . ':00';
								$times[$t . ':30'] = $t . ':30';
							}
							$code[] = generate_select( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', $times, $record->{$field->title}, false, nl2br($field->description) . $edit_code );
						break;
						case WST_DATE:
							$code[] = generate_input_text( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', ucwords(trim(strftime('%e %B %Y', strtotime($record->{$field->title})))), '', false, nl2br($field->description) . $edit_code, 'datepicker');
						break;
						case WST_TABLE_RIGHTS:
							$tables = MyActiveRecord::FindAll('tabledefinitions', null, 'title asc');
							$items = array();
							foreach($tables as $table) {
								$items[$table->name] = $table->title;
								$items[$table->name . '_modify'] = $table->title . ' - Ajout/Modification';
								$items[$table->name . '_export'] = $table->title . ' - Export';
							}
							$code[] = generate_checkbox_group( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', $items, unserialize($record->{$field->title}), false, nl2br($field->description) . $edit_code );
						break;
						case WST_MODULES_RIGHTS:
							$items = array();
							foreach(WSConstants::$modules_rights as $key => $value) {
								$items[$key] = $value;
							}
							$code[] = generate_checkbox_group( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', $items, unserialize($record->{$field->title}), false, nl2br($field->description) . $edit_code );
						break;
						case WST_HTML:
							$code[] = generate_text_area( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', $record->{$field->title}, 10, 100, '');

							$rich_editors[] = "CKEDITOR.replace('" . $field->title . "',{ customConfig: '/application/views/ckeditor/config.js'	});" ;
						break;
						case WST_FILE:
						case WST_IMAGE:
							$code[] = generate_ckfinder( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', $record->{$field->title}, '', false, nl2br($field->description) . $edit_code);
						break;
						default:
							$code[] = generate_input_text( $field->title, "<abbr title='" . $field->title ."'>" . $field->name . '</abbr>', $record->{$field->title}, '', false, nl2br($field->description) . $edit_code );
						break;
					}
				}
				$code[] = generate_hidden('wsadmin_mode', $mode);
				$code[] = generate_hidden('wsadmin_table', $current_table->name);
				if (($current_table->childtable) != '') {
					$code[] = "<a href='../../" . $current_table->childtable . "/create/" . $current_table->id . "' target='_blank'>Ajouter un sous-&eacute;l&eacute;ment...</a>";
					$code[] = "<ul class='tablefield-list'>";
						foreach($record->find_children($current_table->childtable) as $cr) {
							$title = (method_exists($cr, 'get_link_title'))?$cr->get_link_title():$cr->title;
							$code[] = "<li><a href='../../" . $current_table->childtable . "/edit/" . $cr->id . "' target='_blank'>" . $title . '</li>';
						}
					$code[] = '</ul>';
				}
			break;
		}
		echo implode('', $code) . "<script>" . implode('', $rich_editors) . "</script>";
	}
	
	// Export current table to CSV
	function _export() {
		// Check if current user has rights to export
		if ( (isset($this->params[2])) && ($this->_check_data_rights($this->params[2], 'export')) ) {
			$table = MyActiveRecord::FindFirst('tabledefinitions', "name like '" . $this->params[2] . "'");
			if ($table) {
				//header("Content-Type: application/vnd.ms-excel"); 
				header("Content-type: application/octet-stream");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Content-Disposition: attachment; filename=\"" . $table->title . ".csv\"");

				$code = '';

				$table->fields = $table->find_children('tablefields', 'showlist = 1', 'porder asc');
				
				foreach($table->fields as $field) {
					$code .= '"' . (html_entity_decode( $field->name ,ENT_COMPAT,'UTF-8')) . '"' . "\t";
				}
				$code .= "\n";
				foreach(MyActiveRecord::FindAll($table->name, null, $table->sortparams) as $record) {
					foreach($table->fields as $field) {
						if ($field->type != WST_TABLE_LINK) {
							$code .=  '"' . (html_entity_decode( $record->{$field->title} ,ENT_COMPAT,'utf-8')) . '"' . "\t";
						}
						else {
							$table_related = substr($field->title, 0, strlen($field->title) - 3);
							$indexed = substr($table_related, -1, 1);
							if ( is_numeric($indexed) ) {
								$table_related = substr($table_related, 0, strlen($table_related)-1);
							}
							$related_record = MyActiveRecord::FindFirst($table_related, 'id = ' . $record->{$field->title});
							if (method_exists($related_record, 'get_link_title')) {
								$code .=  '"' . (html_entity_decode( $related_record->get_link_title() ,ENT_COMPAT,'utf-8')) . '"' . "\t";
							}
							else {
								$code .=  '"' . (html_entity_decode( @$related_record->title ,ENT_COMPAT,'utf-8')) . '"' . "\t";
							}
						}
					}
					$code .=  "\n";
				}
				
				echo chr(255) . chr(254) . mb_convert_encoding($code, 'UTF-16LE', 'UTF-8');
				
			}
		}
		die();
	}


	function _check_rights( $level ) {
		$user = MyActiveRecord::FindFirst('users', "username like '" . $this->auth->session['username'] . "'");
		$user_group = $user->find_parent('groups');
		global $userlanguage;
		$modules 	= unserialize($user_group->rights);
		$tables 	= unserialize($user_group->datarights);
		$userlanguage = $user->language;
		if (!isset($modules[$level])) {
			return false;
		}
		else {
			return true;
		}
	}

	function _is_admin() {
		$user = MyActiveRecord::FindFirst('users', "username like '" . $this->auth->session['username'] . "'");
		return ($user->groups_id == 1);
	}

	function _check_data_rights( $table, $role='view' ) {
		if (!isset($this->user)) {
			$this->user = MyActiveRecord::FindFirst('users', "username like '" . $this->auth->session['username'] . "'");
			$this->user_group = $this->user->find_parent('groups');
			$this->tables 	= unserialize($this->user_group->datarights);
		}
		if ($role != 'view') {
			$table = $table . '_' . $role;
		}
		if (!isset($this->tables[$table])) {
			return false;
		}
		else {
			return true;
		}
	}

}


