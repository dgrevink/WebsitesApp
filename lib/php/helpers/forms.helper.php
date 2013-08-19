<?php
/**
 * html_forms Helper Function Library
 *
 * @author David Grevink
 * @version $Id$
 * @copyright starplace.org, 13 December, 2006
 * @package helper
 **/

function generate_select( $name, $label, $items, $default, $error=false )
{
	$element  = "<p><label for='$name' id='{$name}_label'>$label </label></p>";
	if ($error) {
		$element .= "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	$element .= "<select name='$name' id='$name' class='form_select'>";
	foreach ($items as $key => $value)
	{
		if (is_array($value)) {
			$element .= "<optgroup label=" . $key . ">";
			foreach($value as $key2 => $value2) {
				if ($key2 == $default)
					$element .= "<option value='$key2' selected >$value2</option>";
				else
					$element .= "<option value='$key2'>$value2</option>";
			}
			$element .= "</optgroup>";
		}
		else {
			if ($key == $default)
				$element .= "<option value='$key' selected >$value</option>";
			else
				$element .= "<option value='$key'>$value</option>";
		}
	}
	$element .= "</select>";

	return $element;
}

function generate_select_input( $name, $label, $items, $default, $extra_title, $extra_value, $description='Select an item from this pull-down menu.' )
{
	$element  = "<p><label for='$name' id='{$name}_label'>$label</label></p>";
	$element .= "<select name='$name' id='$name' class='form_select' onchange='handle_select_input(this, ${name}_extra);'>";
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
	$element  = "<p><label for='$name' id='{$name}_label'>$label </label></p>";
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

function generate_input_text( $name, $label, $default, $description='Enter some text here.', $error=false, $class='' )
{
	$element = "<p><label for='$name' id='{$name}_label'>$label </label></p>";
	if ($error) {
		$element .= "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	$element .= "<input type='text' name='$name' value='$default' id='$name' class='$class form_input_text' /><span class='hint'>$description<span class='hint-pointer'>&nbsp;</span></span>";
	
	return $element;
}

function generate_input_file( $name, $label, $description='Select a file.', $error=false, $class='' )
{
	$element = "<p><label for='${name}_file_upload' id='{$name}_label'>$label </label></p>";
	if ($error) {
		$element .= "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	$element .= "<input type='file' name='${name}_file_upload' id='${name}_file_upload' class='$class form_input_text' /><span class='hint'>$description<span class='hint-pointer'>&nbsp;</span></span>";
	$element .= "<input type='hidden' name='${name}' id='${name}' value='${name}_file_upload'/>";
	
	return $element;
}

function generate_file_selector( $name, $label, $default, $description='Enter some text here.', $error=false )
{
	$element = "<p><label for='$name' id='{$name}_label'>$label </label></p>";
	if ($error) {
		$element .= "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	$element .= "<input type='file' name='$name' value='$default' id='$name' class='form_file_selector' /><span class='hint'>$description<span class='hint-pointer'>&nbsp;</span></span>";
	
	return $element;
}

function generate_hidden( $name, $default )
{
	$element = "<input type='hidden' name='$name' value='$default' id='$name' />";
	
	return $element;
}

function generate_input_password( $name, $label, $default, $description='Enter a secure password.', $error )
{
	$element  = "<p><label for='$name' id='{$name}_label'>$label </label></p>";
	if ($error) {
		$element .= "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	$element .= "<input type='password' name='$name' value='$default' id='$name' class='form_input_password' /><span class='hint'>$description<span class='hint-pointer'>&nbsp;</span></span>";
	
	return $element;
}

function generate_input_checkbox( $name, $label, $default, $description='Click on the checkbox to enable or disable it.' )
{
	$element  = "<p><label for='$name' id='{$name}_label'>$label </label></p>";
	if ($default)
		$element .= "<input type='checkbox' name='${name}[]' checked id='$name' class='form_input_checkbox'/><span class='hint'>$description<span class='hint-pointer'>&nbsp;</span></span>";
	else
		$element .= "<input type='checkbox' name='${name}[]' id='$name' class='form_input_checkbox' /><span class='hint'>$description<span class='hint-pointer'>&nbsp;</span></span>";
	
	return $element;
}



function generate_text_area( $name, $label, $default, $rows, $cols, $error=false, $class='form_textarea' )
{
	$element  = "<br/><label for='$name' id='{$name}_label'>$label </label><br/>";
	if ($error) {
		$element .= "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	$element .= "<textarea name='$name' rows='$rows' cols='$cols' id='$name' class='$class' >$default</textarea>";
	
	return $element;
}

function generate_radio_group( $name, $label, $items, $default )
{
	$element  = "<div class='radio-group'><p id='{$name}_label'>$label </p>";
	
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

function generate_checkbox_group( $name, $label, $items, $default_items, $description='Select one or more items from the list.', $error )
{
	$element  = "<p id='{$name}_label'>$label</p>";
	if ($error) {
		$element .= "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	$element .= "<div class='checkbox-group'>";
	foreach ($items as $key => $value)
	{
		if (!is_array($default_items)) {
				$element .= "<span class='checkbox-item'><input type='checkbox' name='${name}[]' id='${name}_${key}' value='$key'/><label for='${name}_${key}' class='radio'>$value</label></span>";
		}
		else {
			if (in_array($key,$default_items))
				$element .= "<span class='checkbox-item'><input type='checkbox' name='${name}[]' id='${name}_${key}' value='$key' checked/><label for='${name}_${key}' class='radio'>$value</label></span>";
			else
				$element .= "<span class='checkbox-item'><input type='checkbox' name='${name}[]' id='${name}_${key}'  value='$key'/><label for='${name}_${key}' class='radio'>$value</label></span>";
		}
		
		if (count($items) > 2) {
			//$element .='<br/>';
		}
	}
	$element .= "</div><span class='hint'>$description<span class='hint-pointer'>&nbsp;</span></span>";
	
	
	return $element;
}


function generate_checkbox_group_input( $name, $label, $items, $default_items, $extra_title, $extra_value, $description='Select one or more items from the list.', $error )
{
	$element  = "<label for='$name' id='{$name}_label'>$label </label>";
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


function generate_ckfinder( $name, $label, $default, $button_label='Browse Server...', $description='Enter some text here.', $error=false, $class='' )
{
	$element = "<p><label for='$name' id='{$name}_label'>$label </label></p>";
	if ($error) {
		$element .= "<span class='form-error'>$error<span class='pointer'>&nbsp;</span></span>";
	}
	$element .= "<input type='text' name='$name' value='$default' id='$name' class='form_input_text $class' /><input class='button_browse_server' type='button' value='" . $button_label . "' onclick=\"browse_" . $name . "('" . $name . "');\" /><span class='hint'>$description<span class='hint-pointer'>&nbsp;</span></span>";
	
	$element .= "<script>";
	$element .= "function browse_" . $name . "() { CKFinder.Popup( '', null, null, set_" . $name . " ) ; }";
	$element .= "function set_" . $name . "(value) { document.getElementById('" . $name . "').setAttribute('value', value); }";
	$element .= "</script>";

	
	return $element;
}


?>