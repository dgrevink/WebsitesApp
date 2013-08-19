<?php

WSLoader::load_helper('file');
WSLoader::load_support('mail');
WSLoader::load_support('captcha');
WSLoader::load_helper('forms');
WSLoader::load_base('templates');
WSLoader::load_helper('misc');
WSLoader::load_base('log');

/**
 * Website Form and Poll Handler
 * This class cannot be called directly. It is to be used only as part of a placeholder.
 *
 * @package		admin
 * @package 	site
 * @subpackage 	forms
 * @author		David Grevink
 * @link		http://websitesapp.com/
 */
class Forms extends WSController {

	var $forms = null;
	var $form_id = 1;
	var $posted_form = null;
	var $email_title = '';

	function process($id = null) {

		// Get form ID
		$this->form_id = 1;
		if ($id) {
			$this->form_id = $id;
		}
		else {
			if (isset($this->params[0])) {
				$this->form_id =$this->params[0];
			}
		}
		if (!is_numeric($this->form_id)) {
			echo "Invalid parameter.";
			die();
		}
		
		// Get form
		$this->form = MyActiveRecord::FindById('formsdefinitions', $this->form_id);
		if (!$this->form) {
			echo "Invalid form id.";
			die();
		}


		// Check if forms is posted and if so, process the content 		
		// -----------------------------------------------------------------------------------------------------

		$posted = isset($_POST['posted']);
		$saved = false;
		$errors = null;

		$smarty = new Template;
		$contents = '';

		if ($posted) {
			// Check validity of fields
			$errors = $this->_check();
			
			if (count($errors) == 0) {
				$this->_process();
			}

			// Process if type of form is a contest
			if ($this->form->type == 'contest-email') {
				if (count($errors) == 0) {
					list($name, $email) = $this->_get_first_mail();
					if ($email) {
						$table = MyActiveRecord::FindById('tabledefinitions', $this->form->tabledefinitions_id);
						$record = MyActiveRecord::FindFirst($table->name, $name . " = '" . $email . "'");
						if ($record) {
							$errors['wsf-contest-email'] = true;
						}
					}
				}
			}


			if (count($errors) == 0) {
				$formsresponse = MyActiveRecord::Create('formsresponses');
				$formsresponse->formsdefinitions_id = $this->form->id;
				$formsresponse->responsedate = $formsresponse->DbDateTime();
				$formsresponse->values = serialize($this->posted_form);
				$formsresponse->save();
				
				if ($this->form->emailadmin == '1') {
					$this->_send_email($this->config->get('contactmail'), $this->form->mailadmin);
					
				}
				
				if ($this->form->emailsender == '1') {
					$email = false;
					$questions = unserialize($this->form->questions);
					foreach ($questions as $question) {
						if ($question->type == 'email') {
							$email = $this->posted_form[$question->name];
							break;
						}
					}
					if ($email) {
						$this->_send_email($email, $this->form->mailuser, $this->form->userquestions);
					}
				}
				
				if (trim($this->form->emailextra) != '') {
					foreach(explode(',',$this->form->emailextra) as $email) {
						$email = ltrim($email);
						$email = rtrim($email);
						if ($email == 'sender') $email = $this->posted_form['email'];
						$this->_send_email($email, $this->form->mailuser, true, true);
					}
				}
				
				if ($this->form->tabledefinitions_id != 0) {
					$table = MyActiveRecord::FindById('tabledefinitions', $this->form->tabledefinitions_id);
					if ($table) {
						$record = MyActiveRecord::Create(ucfirst($table->name));
						
						foreach($this->posted_form as $key => $value) {
							$val = htmlentities($value, ENT_NOQUOTES, 'UTF-8');
							if ($val == '') {
								$val = htmlentities($value);
							}
							$record->$key = $val;
						}
						
						if (method_exists($record, 'saveadvanced')) {
							$record->saveadvanced();
						}
						else {
							$record->save();
						}

						
						$smarty->assign('contents', $this->form->thanks);
						$smarty->assign('posted', true);
						$saved = true;
					}
				}
			}
		} # Form processed
		
		

		// If the form has not been processed (saved) because it is the first time or if there are errors, we have to display it  		
		// ---------------------------------------------------------------------------------------------------------------------
		if (!$saved) {
			$smarty->assign('form_title', $this->form->title);
			$smarty->assign('form_introduction', $this->form->introduction);

			$contents .= '<ol>';
			
			$questions = unserialize($this->form->questions);
			foreach($questions as $item) {
				$key = $item->name;
				if ($item->type == 'separator') {
					$contents .= "<li><em id='title_{$key}'>" . $item->label . "</em>";
					if (trim($item->comment) != '') {
						$contents .= "<br/><span id='comment_{$key}'>" . nl2br($item->comment) . '</span>';
					}
					$contents .= "<hr/></li>";
				}
				else {
					$contents .= '<li>';
					$key = 'field_' . $key;
					$error_message = isset($errors[$key])?$item->error:'';
					if ($item->mandatory) {
						$item->label = $item->label . " <span class='mandatory'>*</span>";
					}
					switch ($item->type) {
						case 'title':
						break;
						case 'text':
						case 'email':
							$contents .= generate_input_text($key, $item->label, htmlentities(@$_POST[$key], ENT_NOQUOTES, 'UTF-8'), '', $error_message);
							break;
						case 'password':
							$contents .= generate_input_password($key, $item->label, '', $error_message, false);
							break;
						case 'longtext':
							$contents .= generate_text_area($key, $item->label, htmlentities(@$_POST[$key], ENT_NOQUOTES, 'UTF-8'), 8, 60, $error_message);
							break;
						case 'radio':
							$contents .= generate_radio_group($key, $item->label, $item->values, @$_POST[$key]);
							break;
						case 'select':
							$contents .= generate_select( $key, $item->label, $item->values, @$_POST[$key], $error_message );
							break;
						case 'tablelink':
							$records = MyActiveRecord::FindAll($item->params1, $item->params2);
							$item->values = array();
							foreach ($records as $record) {
								if (method_exists($record, 'get_link_title_forms')) {
									$item->values[$record->id] = $record->get_link_title_forms();
								}
								else {
									$item->values[$record->id] = $record->title;
								}
							}
							$contents .= generate_select( $key, $item->label, $item->values, @$_POST[$key], '' );
							break;
						case 'attachment':
							$contents .= generate_input_file($key, $item->label, '', $error_message);
							break;
						case 'multicheckbox':
							$contents .= generate_checkbox_group($key, $item->label, $item->values, @$_POST[$key], '', $error_message);
							break;
						case 'hidden':
							$contents .= generate_hidden($key, $item->values);
							break;
						default:
							$contents .= $item->type;
							$contents .= 'unknown';
						break;
					}
					$contents .= '</li>';
	
				}
			} // foreach

			// Display a captcha if the form has one defined			
			if ($this->form->usecaptcha == 1) {

  				$contents .= "<script>";
				$contents .= "$(document).ready(function(){";
				$contents .= "$.getScript('http://www.google.com/recaptcha/api/js/recaptcha_ajax.js', function(){Recaptcha.create('" . $this->config->get('recaptcha_public') . "', 'recaptcha_holder', { theme: '" . $this->config->get('recaptcha_theme') . "', lang: '" . $this->form->language . "' });});";
				$contents .= "});";
				$contents .= "</script>";
				$error_message = '';
				if (isset($errors['captcha'])) {
					if ($this->form->language == 'fr') {
						$error_message = 'Captcha incorrect !';
					}
					else {
						$error_message = 'Incorrect captcha !';
					}
					
					$error_message = "<span class='form-error'>{$error_message}<span class='pointer'>&nbsp;</span></span>";
				}
				$contents .= "<li>{$error_message}<div id='recaptcha_holder'></div></li";
			}
	
			$contents .= '</ol>';
			
			$smarty->assign('form_id', 'form_' . $this->form_id);
			$smarty->assign('form', $contents);
		} // Form created
		
		// Display generated page according to the page's layout
		if (!$id) {
			$smarty->display( 'form_' . $this->form_id . '.tpl');
		}
		else {
			$form = array();

			$form['type'] = $this->form->type;
			$form['title'] = $this->form->title;
			$form['introduction'] = $this->form->introduction;
			$form['thanks'] = $this->form->thanks;
			$form['errors'] = $this->form->errors;
			$form['contesterror'] = $this->form->contesterror;
			$form['saved'] = $saved;
			$form['error_list'] = $errors;
			$form['posted'] = $posted ;
			$form['code'] = $contents;
			$form['id'] = $this->form_id;
			$form['language'] = $this->form->language;

			return $form;
		}
		
	}

	/**
	 *
	 * display( $id )
	 *
	 * Displays form of ID. This is called either via URL ( http://site/forms/display/[ID] ) where it shows direcly on screen the form,
	 * or via a function call where the return value is a string containing the HTML for the form.
	 *
	 */	
	function display($id = null) {
		// See if we are called directly or via URL
		$direct = false;
		if ($id == null) {
			$id = $this->params[0];
			$direct = true;
		}

		// Process the form ( get content, check if posted, process if posted, .... )
		$form = $this->process($id);

		$form_code = '';
		switch ($form['type']) {
			case 'survey':
				$form_code = "<div class='survey' id='form_" . $form['id'] . "'>";
				$form_code .=  "<h2 id='form_" . $form['id'] . "_title'>" . $form['title'] . "</h2>";
				if (!$form['posted']) {
					$form_code .= "<form class='ws-form' method='post' action='/forms/display/{$form['id']}/' enctype='multipart/form-data;charset=UTF-8'>";
					$form_code .= "<input type='hidden' name='posted' id='posted' value='posted' />";
					$form_code .= $form['code'];
					$form_code .= "</form>";
					$form_code .= "<script>";
					$form_code .= "	$('#form_" . $form['id'] . " input[type=radio]').click( function(e) { submitForm( 'form_" . $form['id'] . "' ); } );";
					$form_code .= "</script>";
				}
				else {
					// Compute results
					$form_code .= $this->_survey_results($id);
				}
				$form_code .= "</div>";
			break;
			default:
				// Build form code for display or string return
				$form_code = "<div class='form' id='form_" . $form['id'] . "'>";
				$form_code .=  "<h2 id='form_" . $form['id'] . "_title'>" . $form['title'] . "</h2>";
				if (!$form['posted']) {
					$form_code .= $form['introduction'];
					$form_code .= "<form class='ws-form' method='post' action='/forms/display/{$form['id']}/' enctype='multipart/form-data;charset=UTF-8'>";
					$form_code .= "<input type='hidden' name='posted' id='posted' value='posted' />";
					$form_code .= $form['code'];
					$form_code .= "<hr/>";
					if ($form['language'] == 'fr') {
						$form_code .= "<input type='submit' name='sendbutton' class='ws-submit-button' value='Envoyer' onclick='submitForm( \"form_" . $form['id']  . "\" ); return false;'/>";
					}
					else {
						$form_code .= "<input type='submit' name='sendbutton' class='ws-submit-button' value='Send' onclick='submitForm( \"form_" . $form['id']  . "\" ); return false;'/>";
					}
					$form_code .= "</form>";
				}
				else {
					if (count($form['error_list']) > 0) {
						if (isset($form['error_list']['wsf-contest-email'])) {
							$form_code .= $form['contesterror'];
						}
						else {
							$form_code .= $form['errors'];
							$form_code .= "<form class='ws-form' method='post' action='/forms/display/{$form['id']}/' enctype='multipart/form-data;charset=UTF-8'>";
							$form_code .= "<input type='hidden' name='posted' id='posted' value='posted' />";
							$form_code .= $form['code'];
							$form_code .= "<hr/>";
							if ($form['language'] == 'fr') {
								$form_code .= "<input type='submit' name='sendbutton' class='ws-submit-button' value='Envoyer' onclick='submitForm( \"form_" . $form['id']  . "\" ); return false;'/>";
							}
							else {
								$form_code .= "<input type='submit' name='sendbutton' class='ws-submit-button' value='Envoyer' onclick='submitForm( \"form_" . $form['id']  . "\" ); return false;'/>";
							}
							$form_code .= "</form>";
						}
					}
					else {
						$form_code .= $form['thanks'];
					}
				}
				$form_code .= "</div>";
			break;
		}
		if (!$direct) return $form_code;
		else echo $form_code;
	}
	
	function _check() {
		$errors = array();
		$questions = unserialize($this->form->questions);
		
		foreach($questions as $item) {
			$key = $item->name;

			switch ($item->type) {
				case 'email':
					if ($item->mandatory) {
						if (!valid_email($_POST['field_' . $key])) {
							$errors['field_' . $key] = true;
						}
					}
					break;
				case 'multi-checkbox-value':
				case 'multicheckbox':
					$posted_count = count(@$_POST['field_' . $key]);
					if ($item->mandatory) {
						if ($posted_count <= 0) {
							$errors['field_' . $key] = true;
						}
					}
					break;
				break;
				case 'select':
					$selected_value = $item->values[$_POST['field_'.$key]];
					if (strlen($selected_value) >= 2) {
						if (substr($selected_value, 0, 2) == '--') {
								$errors['field_'.$key] = true;
						}
					}
					break;
				case 'attachment':
					if ($item->mandatory) {
						if (!isset($_FILES[$_POST['field_' . $key]])) {
							$errors['field_' . $key] = true;
						}
					}
					if (isset($_FILES[$_POST['field_' . $key]])) {
						$accepted_extensions = array(
							'txt',
							'jpg',
							'jpeg',
							'gif',
							'png',
							'pdf',
							'doc',
							'xls',
							'docx',
							'xlsx'
						);
						if (!in_array(file_extension($_FILES[$_POST['field_' . $key]]['name']), $accepted_extensions )) {
							$errors['field_' . $key] = true;
						}
					}
				break;
				default:
					if ($item->mandatory) {
						if (trim($_POST['field_' . $key]) == '') {
							$errors['field_' . $key] = true;
						}
					}
				break;
			}
		} // foreach
		if ($this->form->usecaptcha == 1) {
			$resp = recaptcha_check_answer ($this->config->get('recaptcha_private'), $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
			if (!$resp->is_valid) {
				$errors['captcha'] = true;
			}
		}
		return $errors;
	}
	
	function _process() {
	//d($_POST);
	//d($_FILES);
	
		$this->posted_form = array();
		foreach ($_POST as $key => $value) {
			if (strstr($key, '_file_upload')) continue;
			//d($key);
			if (strstr($key, 'field_')) {
				$subkey = substr($key, 6, strlen($key)-6);
				if (!is_array($value)) {
					$subvalue = '';
					$questions = unserialize($this->form->questions);
					switch ($questions[$subkey]->type) {
						case 'radio':
							$subvalue = html_entity_decode($questions[$subkey]->values[$value], ENT_NOQUOTES, "UTF-8");
						break;
						case 'select':
							$index = $value;
							if (isset($questions[$subkey]->values[$value])) {
								$subvalue = html_entity_decode($questions[$subkey]->values[$value], ENT_NOQUOTES, "UTF-8");
							}
							else {
								foreach($questions[$subkey]->values as $subs) {
									if (is_array($subs)) {
										foreach($subs as $key2 => $item2) {
											if ($key2 == $index) {
												$subvalue = html_entity_decode($item2, ENT_NOQUOTES, "UTF-8");
												break;
											}
										}
									}
								}
							}
							if ($questions[$subkey]->order == 1) {
								$this->email_title = $subvalue;
							}
						break;
						
						case 'tablelink':
							$record = MyActiveRecord::FindById($questions[$subkey]->params1, $value);
							if (method_exists($record, 'get_link_title')) {
								$subvalue = $record->get_link_title();
							}
							else {
								$subvalue = $record->title;
							}
						break;
						
						case 'attachment':
//							$subvalue = html_entity_decode($value, ENT_NOQUOTES, "UTF-8");
							$dest_dir = WS_ROOT . $questions[$subkey]->params1;
							if (!isset($_FILES[$value])) {
								$subvalue = "N/A";
							}
							else {
								$dest_file_extension = file_extension($_FILES[$value]['name']);
								$dest_file_basename = basename($_FILES[$value]['name'], '.' . $dest_file_extension);
								
								$dest_file = normalize_string(uniqid() . '-' . $dest_file_basename ) . '.' . strtolower($dest_file_extension);
								move_uploaded_file($_FILES[$value]['tmp_name'], $dest_dir . $dest_file);
								$subvalue = "<a href='" . $questions[$subkey]->params1 . $dest_file . "' target='_blank'>" . $questions[$subkey]->params1 . $dest_file . "</a>";
							}
						break;

						case 'email':
						case 'title':
						case 'text':
						case 'checkbox':
						case 'multicheckbox':
						case 'radio-multi-checkbox':
						case 'longtext':
						default:
							$subvalue = html_entity_decode($value, ENT_NOQUOTES, "UTF-8");
						break;
					}
					$this->posted_form[$subkey] = $subvalue;
				}
				else {
					$questions = unserialize($this->form->questions);
					$count = 1;
					$subvalues = '';
					foreach ($value as $item) {
						$subvalues .= html_entity_decode($questions[$subkey]->values[$item]);
						if ($count < count($value)) {
							$subvalues .= ', ';
						}
						$count++;
					}
					if ($questions[$subkey]->type == 'multi-checkbox-value') {
						if (isset($_POST[$key . '_extra'])) {
							$subvalues .= ', ' . html_entity_decode($_POST[$key . '_extra']);
						}
					}
					$this->posted_form[$subkey] = $subvalues;
				}
			}
		}
	}
	
	function _send_email($email, $message, $add_questions = true, $use_template = false) {
		$this->load_support('mail');
		$mail = new htmlMimeMail();
		$mail->setFrom($this->config->get('contactmail'));
		$subject = utf8_accents_to_ascii(html_entity_decode($this->config->get('company')));
		$subject = '[' . $subject . '] - ' . html_entity_decode($this->form->title);
		if ($this->email_title) {
			$subject .= ' - ' . $this->email_title;
		}
		$mail->setSubject($subject);
		
		$tpl_file = (!file_exists(WS_APPLICATION_FOLDER . '/views/mails/' . $this->form_id . '.tpl'))?'default.tpl':($this->form_id . '.tpl');
		
		$use_template = (file_exists(WS_APPLICATION_FOLDER . '/views/mails/' . $tpl_file)) && (!$use_template);

		if (!$use_template) {
			$mail->html = '<p>' . $message . '</p>';
			if ($add_questions) {
				$mail->html .=  $this->_to_html();
			}
			//$mail->html .= '-----' . $tpl_file . '------' . $use_template; 
		}
		else {
			$t = new Template;
			$t->assign('title', $this->form->title);
			$t->assign('message', $message);
			if ($add_questions) {
				$t->assign('infos', $this->_to_html());
			}
			$t->template_dir = WS_APPLICATION_FOLDER . '/views/mails/';
			$mail->html = $t->fetch($tpl_file);
		}
		
		$result = $mail->send(array($email));
		save_file(WS_APPLICATION_FOLDER . '/../backups/mails/mail-' . $this->form_id . '-' . date("Ymd-His") . '.eml', $mail->getRFC822(array($email)));
	}
	
	function _survey_results() {
		$question = array_pop(unserialize($this->form->questions));

		$code = '<p>' . $question->label . '</p>';

		$values = array();
		foreach ($question->values as $value) {
			$values[$value] = 0;
		}

		$responses = $this->form->find_children('formsresponses');
		$counter = 0;
		foreach ($responses as $response) {
			$key = array_pop(unserialize($response->values));
			$key = htmlentities($key, ENT_NOQUOTES, "UTF-8" );
			$values[$key]++;
			$counter++;
		}
		
		$values_computed = array();		
		foreach($values as $key => $value) {
			$values_computed[$key] = round( $value / $counter * 100 );
		}

		$code .= "<ul>";
		foreach($values_computed as $key => $value) {
			$code .= "<li><span class='item'>" . $key . "</span><span class='value'>" . $value . " %</span></li>";
		}
		$code .= "<ul>";
		return $code;
	}
	
	function _to_html() {
		$html = '';
		foreach ($this->posted_form as $key => $value) {
			$html .= '<p>';
			$questions = unserialize($this->form->questions);
			if (is_array($value)) {
				$value = implode(', ', $value);
			}
			$val = htmlentities($value,ENT_NOQUOTES, 'UTF-8');
			if ($val == '') {
				$val = htmlentities($value);
			}
			
			$html .= '<em>' . $questions[$key]->label . ': </em> ' . $val;
			$html .= '</p>';
		}
		return $html;
	}
	
	function _get_first_mail() {
		$email = false;
		$name = false;
		$questions = unserialize($this->form->questions);
		foreach ($questions as $question) {
			if ($question->type == 'email') {
				$email = $this->posted_form[$question->name];
				$name  = $question->name;
				break;
			}
		}
		
		return array($name, $email);
	}
	
}
