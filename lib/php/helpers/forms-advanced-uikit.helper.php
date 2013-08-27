<?php
/**
 * forms-advanced-uikit Helper Function Library
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
	$error_class = '';
	if ($error) $error_class = 'uk-form-danger';

	// Open	
	$element[] = "<div class='uk-form-row' id='{$name}_container'>";

	// Label
	$element[] = "<label class='uk-form-label' for='$name'>$label</label>";
	
	// Control
	$label = strip_tags($label);
	$element[] = "<div class='uk-form-controls'>";
	$element[] = "<input type='text' name='$name' value=\"$default\" id='$name' class='$class uk-form-width-large $error_class' placeholder=\"$label\" />";
	$element[] = " <span class='uk-form-help-inline'>$comment" . ($error?$error:'') . "</span>";
	$element[] = "</div>";

//	$element[] = " <span class='uk-form-help-inline'>$description</span>";

	// Close
	$element[] = "</div>";
		
	return implode('', $element);
}

function generate_input_password( $name, $label, $description='Enter some text here.', $error=false, $comment=false, $confirmation_text='Confirmation', $warning_text='Les mots de passe ne correspondent pas.' )
{
	$element = array();
	$error_class = '';
	if ($error) $error_class = 'uk-form-danger';

	// Open	
	$element[] = "<div class='uk-form-row' id='{$name}_container'>";

	// Label
	$element[] = "<label class='uk-form-label' for='$name'>$label</label>";

	// Control
	$label = strip_tags($label);
	$element[] = "<div class='uk-form-controls'>";
	$element[] = "<input type='password' name='$name' id='$name' class='uk-form-width-large $error_class ws-password-original'/>";
	$element[] = " <span class='uk-form-help-inline'>$comment" . ($error?$error:'') . "</span>";
	$element[] = "</div>";
	$element[] = "</div>";
	$element[] = "<div class='uk-form-row' id='{$name}_container'>";
	$element[] = "<label class='uk-form-label' for='${name}_CONFIRM'>$confirmation_text</label>";
	$element[] = "<div class='uk-form-controls'>";
	$element[] = "<input type='password' name='$name' id='${name}_CONFIRM'  class='$class uk-form-width-large $error_class ws-password-confirmation' />";
	$element[] = " <span class='uk-form-help-inline'><span class='password_strength'>&nbsp;</span>&nbsp;<span style='display: none;' class='ws-password-warning' id='${name}_warning'>$warning_text</span></span>";
	$element[] = "</div>";

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
				$('#' + id).removeClass('uk-form-danger');
				$('#' + id + '_CONFIRM').removeClass('uk-form-danger');
				$('#' + id).addClass('uk-form-success');
				$('#' + id + '_CONFIRM').addClass('uk-form-success');
			}
			else {
				$('a.websites-button').hide();
				$('a.button').hide();
				$('#ws-profile-submit').hide();
				$('#' +id + '_warning').show();
				$('#' + id).addClass('uk-form-danger');
				$('#' + id + '_CONFIRM').addClass('uk-form-danger');
				$('#' + id).removeClass('uk-form-success');
				$('#' + id + '_CONFIRM').removeClass('uk-form-success');
			}
	}
	$('#" . $name . "').keyup(function(){check_pass('" . $name . "');});
	$('#" . $name . "_CONFIRM').keyup(function(){check_pass('" . $name . "');});
	
</script>

	";
	
	// Close
	$element[] = "</div>";
		
	return implode('', $element);
}




function generate_input_checkbox( $name, $label, $default, $comment='Click on the checkbox to enable or disable it.' )
{
	$element = array();

	// Open	
	$element[] = "<div class='uk-form-row' id='{$name}_container'>";

	// Label
	$element[] = "<label class='uk-form-label' for='$name'>$label</label>";

	// Control
	$label = strip_tags($label);
	$element[] = "<div class='uk-form-controls'>";
	if ($default)
		$element[] = "<input type='checkbox' name='${name}[]' checked id='$name'/>";
	else
		$element[] = "<input type='checkbox' name='${name}[]' id='$name'/>";
	$element[] = " <span class='uk-form-help-inline'>$comment</span>";
	$element[] = "</div>";

	// Close
	$element[] = "</div>";

	return implode('', $element);
}




function generate_text_area( $name, $label, $default, $rows, $cols, $class='form_textarea', $error=false, $comment=false )
{
	$element = array();
	$error_class = '';
	if ($error) $error_class = 'uk-form-danger';

	// Open	
	$element[] = "<div class='uk-form-row' id='{$name}_container'>";

	// Label
	$element[] = "<label class='uk-form-label' for='$name'>$label</label>";

	// Control
	$label = strip_tags($label);
	$element[] = "<div class='uk-form-controls'>";
	$element[] = "<textarea name='$name' rows='$rows' cols='$cols' id='$name' class='$class $error_class' >$default</textarea>";
	$element[] = " <span class='uk-form-help-inline'>$comment" . ($error?$error:'') . "</span>";
	$element[] = "</div>";

	// Close
	$element[] = "</div>";


	
	return implode('', $element);
}


function generate_input_file( $name, $label, $description='Select a file.', $error=false, $comment=false, $class='' )
{
	$element = array();

	$error_class = '';
	if ($error) $error_class = 'uk-form-danger';

	// Open	
	$element[] = "<div class='uk-form-row' id='{$name}_container'>";

	// Label
	$element[] = "<label class='uk-form-label' for='$name'>$label</label>";

	// Control
	$label = strip_tags($label);
	$element[] = "<div class='uk-form-controls'>";
	$element[] = "<input type='file' name='$name' id='$name' class='$class $error_class' />";
	$element[] = " <span class='uk-form-help-inline'>$comment" . ($error?$error:'') . "</span>";
	$element[] = "</div>";

	// Close
	$element[] = "</div>";

}



function generate_select( $name, $label, $items, $default, $error, $comment, $multiple = false )
{
	$element = array();
	$error_class = '';
	if ($error) $error_class = 'uk-form-danger';

	// Open	
	$element[] = "<div class='uk-form-row' id='{$name}_container'>";

	// Label
	$element[] = "<label class='uk-form-label' for='$name'>$label</label>";

	// Control
	$label = strip_tags($label);
	$element[] = "<div class='uk-form-controls'>";
	$element[] = "<select id='$name' name='" . $name . ($multiple?'[]':'') . "' class='$error_class form_select  uk-form-width-large " . ($multiple?'multiple':'') . "' " . ($multiple?'multiple':'') . ">";
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
	$element[] = " <span class='uk-form-help-inline'>$comment" . ($error?$error:'') . "</span>";
	$element[] = "</div>";

	// Close
	$element[] = "</div>";

	return implode('', $element);
}

function generate_checkbox_group( $name, $label, $items, $default_items, $error, $comment='Click on the checkbox to enable or disable it.' )
{
	$element = array();
	$error_class = '';
	if ($error) $error_class = 'uk-form-danger';

	// Open	
	$element[] = "<div class='uk-form-row' id='{$name}_container'>";

	// Label
	$element[] = "<label class='uk-form-label' for='$name'>$label</label>";


	// Control
	$element[] = "<div class='uk-form-controls'>";
	foreach ($items as $key => $value)
	{
		if (!is_array($default_items)) {
				$element[] = "<input type='checkbox' name='${name}[]' id='${name}_${key}' value='$key' /> <label for='${name}_${key}' class='radio'>$value</label><br/>";
		}
		else {
			if (in_array($key,$default_items))
				$element[] = "<input type='checkbox' name='${name}[]' id='${name}_${key}' value='$key' checked/> <label for='${name}_${key}' class='radio'>$value</label><br/>";
			else
				$element[] = "<input type='checkbox' name='${name}[]' id='${name}_${key}'  value='$key'/> <label for='${name}_${key}' class='radio'>$value</label><br/>";
		}
	}
	$element[] = " <span class='uk-form-help-inline'>$comment" . ($error?$error:'') . "</span>";

	// Close
	$element[] = "</div>";

	
	return implode('', $element);
}




function generate_ckfinder( $name, $label, $default, $description='Click on icon to browse server...', $error = false, $comment = false, $class='' )
{
	$element = array();

	$error_class = '';
	if ($error) $error_class = 'uk-form-danger';

	// Open	
	$element[] = "<div class='uk-form-row' id='{$name}_container'>";

	// Label
	$element[] = "<label class='uk-form-label' for='$name'>$label</label>";

	// Control
	$label = strip_tags($label);
	$element[] = "<div class='uk-form-controls'>";
	$element[] = "<input type='text' name='$name' value='$default' id='$name' class='ckfinder-holder $error_class $class uk-form-width-large' />";
	$element[] = " <input class='uk-button' type='button' value='...' onclick=\"browse_" . $name . "('" . $name . "');\" />";
	$element[] = " <span class='uk-form-help-inline'>$comment" . ($error?$error:'') . "</span>";
	$element[] = "</div>";

	$element[] = "<script>";
	$element[] = "\n\n\nfunction browse_" . $name . "() { CKFinder.popup( '/lib/js/ckfinder/', null, null, set_" . $name . " ) ; }";
	$element[] = "\nfunction set_" . $name . "(fileURL, data) { document.getElementById('" . $name . "').setAttribute('value', fileURL); }";
	$element[] = "</script>";

	// Close
	$element[] = "</div>";
	
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
