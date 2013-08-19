<?php
/**
 * forms-advanced Helper Function Library
 *
 * @author David Grevink
 * @version $Id$
 * @copyright starplace.org, 13 December, 2006-2008
 * @package helper
 **/


/**
 *   Generates a hidden form field
 */
function generate_hidden( $name, $default )
{
	$element = "<input type='hidden' name='$name' value=\"$default\" id='$name' />";
	
	return $element;
}


/**
 * Generates an input text
 */
function generate_input_text( $name, $label, $default, $description='Enter some text here.', $error=false, $comment=false, $class='' )
{
	$element = array();

	// Open	
	$element[] = "<span class='websites-forms-input-text' id='{$name}_container'>";

	// Label
	$element[] = "<span class='label' id='{$name}_label'>";
	$element[] = "<label for='$name'>$label </label><br/>";
	$element[] = "</span>";

	// Error
	if ($error) {
		$element[]= "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	
	// Control
	$element[] = "<span class='control'>";
	$element[] = "<input type='text' name='$name' value=\"$default\" id='$name' class='$class' /><span class='hint'>$description<span class='hint-pointer'>&nbsp;</span></span>";
	$element[] = "</span>";

	// Comment
	if ($comment) {
		$element[] = "<span class='comment'><p>$comment</p></span>";
	}
	
	// Close
	$element[] = "</span>";
		
	return implode('', $element);
}

function generate_input_password( $name, $label, $description='Enter some text here.', $error=false, $comment=false, $confirmation_text='Confirmation', $warning_text='Les mots de passe ne correspondent pas.' )
{
	$element = array();

	// Open	
	$element[] = "<span class='websites-forms-input-text' id='{$name}_container'>";

	// Label
	$element[] = "<span class='label' id='{$name}_label'>";
	$element[] = "<label for='$name'>$label </label><br/>";
	$element[] = "</span>";

	// Error
	if ($error) {
		$element .= "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	
	// Control
	$element[] = "<span class='control'>";
	$element[] = "<input type='password' name='$name' id='$name' class='ws-password-original'/>";
	$element[] = "$confirmation_text : ";
	$element[] = "<input type='password' name='$name' id='${name}_CONFIRM'  class='ws-password-confirmation' /><span class='hint'>$description<span class='hint-pointer'>&nbsp;</span></span>";
	$element[] = "<br/><span class='password_strength'>&nbsp;</span><span style='display: none;' class='ws-password-warning' id='${name}_warning'>$warning_text</span>";
	$element[] = "</span>";

	// Comment
	if ($comment) {
		$element[] = "<span class='comment'><p>$comment</p></span>";
	}
	
	// Validation code
	// if both fields are empty, do nothing
	// if something is in both AND equal, show save button
	// else hide save button
	$element[] = "
<script>
	function check_pass(id) {
			$('#' + id ).password_strength({
				container: '#' + id + '_container span.password_strength'
			});
			if ( $('#' + id ).val() == $('#' + id + '_CONFIRM').val() ) {
				$('a.websites-button').show();
				$('a.button').show();
				$('#ws-profile-submit').show();
				$('#' +id + '_warning').hide();
			}
			else {
				$('a.websites-button').hide();
				$('a.button').hide();
				$('#ws-profile-submit').hide();
				$('#' +id + '_warning').show();
			}
	}
	$('#" . $name . "').keyup(function(){check_pass('" . $name . "');});
	$('#" . $name . "_CONFIRM').keyup(function(){check_pass('" . $name . "');});
	
</script>

	";
	
	// Close
	$element[] = "</span>";
		
	return implode('', $element);
}




function generate_input_checkbox( $name, $label, $default, $comment='Click on the checkbox to enable or disable it.' )
{
	$element = array();

	// Open	
	$element[] = "<span class='websites-forms-input-checkbox' id='{$name}_container'>";

	// Label
	$element[] = "<span class='label'>";
	$element[] = "<label for='$name' id='{$name}_label'>$label </label><br/>";
	$element[] = "</span>";

	// Control
	$element[] = "<span class='control'>";
	if ($default)
		$element[] = "<input type='checkbox' name='${name}[]' checked id='$name'/>";
	else
		$element[] = "<input type='checkbox' name='${name}[]' id='$name'/>";
	$element[] = "</span>";

	// Comment
	if ($comment) {
		$element[] = "<span class='comment'><p>$comment</p></span>";
	}
	
	// Close
	$element[] = "</span>";
	
	return implode('', $element);
}




function generate_text_area( $name, $label, $default, $rows, $cols, $class='form_textarea', $error=false, $comment=false )
{
	$element = array();

	// Open	
	$element[] = "<span class='websites-forms-input-textarea' id='{$name}_container'>";

	// Label
	$element[] = "<span class='label'>";
	$element[] = "<label for='$name' id='{$name}_label'>$label </label><br/>";
	$element[] = "</span>";

	// Error
	if ($error) {
		$element[] = "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	
	// Control
	$element[] = "<span class='control'>";
	$element[] = "<textarea name='$name' rows='$rows' cols='$cols' id='$name' class='$class' >$default</textarea>";
	$element[] = "</span>";

	// Comment
	if ($comment) {
		$element[] = "<span class='comment'><p>$comment</p></span>";
	}
	
	// Close
	$element[] = "</span>";
	
	return implode('', $element);
}


function generate_input_file( $name, $label, $description='Select a file.', $error=false, $comment=false, $class='' )
{
	$element = array();

	// Open	
	$element[] = "<span class='websites-forms-input-file' id='{$name}_container'>";

	// Label
	$element[] = "<span class='label' id='{$name}_label'>";
	$element[] = "<label for='$name'>$label </label><br/>";
	$element[] = "</span>";

	// Error
	if ($error) {
		$element[]= "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	
	// Control
	$element[] = "<span class='control'>";
	$element[] = "<input type='file' name='$name' id='$name' class='$class' /><span class='hint'>$description<span class='hint-pointer'>&nbsp;</span></span>";
	$element[] = "</span>";

	// Comment
	if ($comment) {
		$element[] = "<span class='comment'><p>$comment</p></span>";
	}
	
	// Close
	$element[] = "</span>";
		
	return implode('', $element);
}



function generate_select( $name, $label, $items, $default, $error, $comment, $multiple = false )
{
	$element = array();

	// Open	
	$element[] = "<span class='websites-forms-input-select' id='{$name}_container'>";

	// Label
	$element[] = "<span class='label'>";
	$element[] = "<label for='$name' id='{$name}_label'>$label </label><br/>";
	$element[] = "</span>";

	// Error
	if ($error) {
		$element[] = "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	
	// Control
	$element[] = "<span class='control'>";
	$element[] = "<select id='$name' name='" . $name . ($multiple?'[]':'') . "' class='form_select " . ($multiple?'multiple':'') . "' " . ($multiple?'multiple':'') . ">";
	foreach ($items as $key => $value)
	{
		if (is_array($value)) {
			$element[] = "<optgroup label=" . $key . ">";
			foreach($value as $key2 => $value2) {
				if ($key2 == $default)
					$element[] = "<option value='$key2' selected >$value2</option>";
				else
					$element[] = "<option value='$key2'>$value2</option>";
			}
			$element[] = "</optgroup>";
		}
		else {
			if (is_array($default)) {
				if (in_array($key, $default))
					$element[] = "<option value='$key' selected >$value</option>";
				else
					$element[] = "<option value='$key'>$value</option>";
			}
			else {
				if ($key == $default)
					$element[] = "<option value='$key' selected >$value</option>";
				else
					$element[] = "<option value='$key'>$value</option>";
			}
		}
	}
	$element[] = "</select>";
	$element[] = "</span>";

	// Comment
	if ($comment) {
		$element[] = "<span class='comment'><p>$comment</p></span>";
	}
	
	// Close
	$element[] = "<div style='clear: both;'>&nbsp;</div></span>";
	
	return implode('', $element);
}

function generate_checkbox_group( $name, $label, $items, $default_items, $error, $comment='Click on the checkbox to enable or disable it.' )
{
	$element = array();

	// Open	
	$element[] = "<span class='websites-forms-checkbox-group' id='{$name}_container'>";

	// Label
	$element[] = "<span class='label'>";
	$element[] = "<label for='$name' id='{$name}_label'>$label </label><br/>";
	$element[] = "</span>";

	// Error
	if ($error) {
		$element[] = "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	
	// Control
	$element[] = "<span class='control'>";
	foreach ($items as $key => $value)
	{
		if (!is_array($default_items)) {
				$element[] = "<div><input type='checkbox' name='${name}[]' id='${name}_${key}' value='$key'/><label for='${name}_${key}' class='radio'>$value</label></div>";
		}
		else {
			if (in_array($key,$default_items))
				$element[] = "<div><input type='checkbox' name='${name}[]' id='${name}_${key}' value='$key' checked/><label for='${name}_${key}' class='radio'>$value</label></div>";
			else
				$element[] = "<div><input type='checkbox' name='${name}[]' id='${name}_${key}'  value='$key'/><label for='${name}_${key}' class='radio'>$value</label></div>";
		}
	}
	$element[] = "</span>";

	// Comment
	if ($comment) {
		$element[] = "<span class='comment'><p>$comment</p></span>";
	}
	
	// Close
	$element[] = "</span><div style='clear: both;'></div>";
	
	return implode('', $element);
}


function generate_ckfinder( $name, $label, $default, $description='Click on icon to browse server...', $error = false, $comment = false, $class='' )
{
	$element = array();

	// Open	
	$element[] = "<span class='websites-forms-ckfinder' id='{$name}_container'>";

	// Label
	$element[] = "<span class='label'>";
	$element[] = "<label for='$name' id='{$name}_label'>$label </label><br/>";
	$element[] = "</span>";

	// Error
	if ($error) {
		$element[] = "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	
	// Control
	$element[] = "<span class='control'>";
	$element[] = "<input type='text' name='$name' value='$default' id='$name' class='ckfinder-holder $class' /><input class='button_browse_server' type='button' value='...' onclick=\"browse_" . $name . "('" . $name . "');\" /><span class='hint'>$description<span class='hint-pointer'>&nbsp;</span></span>";
	$element[] = "</span>";

	$element[] = "<script>";
	$element[] = "\n\n\nfunction browse_" . $name . "() { CKFinder.popup( '/lib/js/ckfinder/', null, null, set_" . $name . " ) ; }";
	$element[] = "\nfunction set_" . $name . "(fileURL, data) { document.getElementById('" . $name . "').setAttribute('value', fileURL); }";
	$element[] = "</script>";

	
	// Comment
	if ($comment) {
		$element[] = "<span class='comment'><p>$comment</p></span>";
	}
	
	// Close
	$element[] = "</span><div style='clear: both;'></div>";
	
	return implode('', $element);
}






##################################################################################################



function generate_select_input( $name, $label, $items, $default, $extra_title, $extra_value, $description='Select an item from this pull-down menu.' )
{
	$element  = "<p>$label</p>";
	$element .= "<select name='$name' class='form_select' onchange='handle_select_input(this, ${name}_extra);'>";
	foreach ($items as $key => $value)
	{
		if ($key == $default)
			$element .= "<option value='$key' selected >$value</option>";
		else
			$element .= "<option value='$key'>$value</option>";
	}
	if ($extra_title) {
		if ($default == 'extra') {
			$hidden = "style='display: inline;'";
			$element .= "<option value='extra' selected>$extra_title</option>";
		}
		else {
			$hidden = "style='display: none;'";
			$element .= "<option value='extra'>$extra_title</option>";
		}
	}
	$element .= "</select>";
	if ($extra_title) {
		$element .= "<input type='text' name='${name}_extra' value='$extra_value' $hidden class='select-input-extra'/>";
	}
	$element .= "<span class='hint'>$description<span class='hint-pointer'>&nbsp;</span></span>";

	return $element;
}

function generate_multiple_select( $name, $label, $items, $default_items, $select_first = false, $description='Select one or more items from the list.', $error )
{
	$element  = "<label for='$name'>$label </label>";
	if ($error) {
		$element .= "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	$element .= "<select multiple size='6' name='$name' class='form_select_multiple'>";
	foreach ($items as $key => $value)
	{
		if ($select_first)
		{
			$element .= "<option value='$key' selected >$value</option>";
			$select_first = false;
		}
		else
		{
			if (!is_array($default_items))
				$element .= "<option value='$key'>$value</option>";
			else
				if (in_array($key,$default_items))
					$element .= "<option value='$key' selected >$value</option>";
				else
					$element .= "<option value='$key'>$value</option>";
		}
	}
	$element .= "</select><span class='hint'>$description<span class='hint-pointer'>&nbsp;</span></span>";
	
	return $element;
}

function generate_radio_group( $name, $label, $items, $default )
{
	$element  = "<div class='radio-group'><p>$label </p>";
	
	foreach ($items as $key => $value)
	{
		if ($key == $default)
			$element .="<input type='radio' name='$name' value='$key' id='${name}_${key}' checked class='form_input_radio'><label for='${name}_${key}' class='radio'>$value</label>";
		else
			$element .="<input type='radio' name='$name' value='$key' id='${name}_${key}' class='form_input_radio'><label for='${name}_${key}' class='radio'>$value</label>";
		if (count($items) > 2) {
			$element .='<br/>';
		}
	}

	if (count($items) <= 2) {
		$element .='<br/>';
	}
	$element .= '</div>';

	return $element;
}


function generate_checkbox_group_input( $name, $label, $items, $default_items, $extra_title, $extra_value, $description='Select one or more items from the list.', $error )
{
	$element  = "<label for='$name'>$label </label>";
	if ($error) {
		$element .= "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	$element .= "<div class='checkbox-group'>";
	foreach ($items as $key => $value)
	{
		if (!is_array($default_items)) {
				$element .= "<input type='checkbox' name='${name}[]' id='${name}_${key}' value='$key'/><label for='${name}_${key}' class='radio'>$value</label>";
		}
		else {
			if (in_array($key,$default_items))
				$element .= "<input type='checkbox' name='${name}[]' id='${name}_${key}' value='$key' checked/><label for='${name}_${key}' class='radio'>$value</label>";
			else
				$element .= "<input type='checkbox' name='${name}[]' id='${name}_${key}'  value='$key'/><label for='${name}_${key}' class='radio'>$value</label>";
		}
		
		if (count($items) > 2) {
			$element .='<br/>';
		}
	}
	if ($extra_title) {
		$key = 'extra';
		$element .= "<input type='checkbox' name='${name}[]' id='${name}_${key}'  value='$key'/><label for='${name}_${key}' class='radio'>$extra_title</label> ";
		$element .= "<input type='text' name='${name}_extra' value='$extra_value' class='select-input-extra'/>";
	}
	$element .= "</div><span class='hint'>$description<span class='hint-pointer'>&nbsp;</span></span>";
	
	
	return $element;
}




?>