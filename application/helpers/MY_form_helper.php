<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2012, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

//--------------------------------------------------------------------

/**
 * Form Helpers
 *
 * Creates HTML5 extensions for the standard CodeIgniter form helper.
 *
 * These functions also wrap the form elements as necessary to create
 * the styling that the Bootstrap-inspired admin theme requires to
 * make it as simple as possible for a developer to maintain styling
 * with the core. Also makes changing the core a snap.
 *
 * All methods (including overridden versions of the originals) now
 * support passing a final 'label' attribute that will create the
 * label along with the field.
 *
 * @package    Bonfire
 * @subpackage Helpers
 * @category   Helpers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/array_helpers.html
 *
 */

if ( ! function_exists('_form_common'))
{
	/**
	 * Used by many of the new functions to wrap the input in the correct
	 * tags so that the styling is automatic.
	 *
	 * @access private
	 *
	 * @param string $type    A string with the name of the element type.
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function _form_common($type='text', $data='', $value='', $label='', $extra='', $tooltip = '',$add_on = false)
	{
		$defaults = array('type' => $type, 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

		// If name is empty at this point, try to grab it from the $data array
		if (empty($defaults['name']) && is_array($data) && isset($data['name']))
		{
			$defaults['name'] = $data['name'];
			unset($data['name']);
		}

		// If label is empty at this point, try to grab it from the $data array
		if (empty($label) && is_array($data) && isset($data['label']))
		{
			$label = $data['label'];
			unset($data['label']);
		}

		// If tooltip is empty at this point, try to grab it from the $data array
		if (empty($tooltip) && is_array($data) && isset($data['tooltip']))
		{
			$tooltip = $data['tooltip'];
			unset($data['tooltip']);
		}

		$error = '';

		if (function_exists('form_error'))
		{
			if (form_error($defaults['name']))
			{
				$error   = ' error';
				$tooltip = '<span class="help-inline">' . form_error($defaults['name']) . '</span>' . PHP_EOL;
			}
		}

		if($add_on){
			$add_on = "<span class='add-on'>{$add_on}</span>";
		}

		$output = _parse_form_attributes($data, $defaults);

		$output = <<<EOL

<div class="control-group {$error}">
	<label class="control-label" for="{$defaults['name']}">{$label}</label>
	<div class="controls">
		 {$add_on}
		 <input {$output} {$extra} />
		<span class="attribute">{$tooltip}</span>
	</div>
</div>

EOL;

		return $output;

	}//end _form_common()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_input'))
{
	/**
	 * Returns a properly templated text input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_input($data = '', $value = '', $extra = '')
	{
		$tooltip = "";
		$data_maks="";
		$required = "";
		if(isset($data['tooltip'])){
			$tooltip = "<span class='help-block'>{$data['tooltip']}</span>";
		}
		if(isset($data['mask'])){
			$data_maks="data-mask='{$data['mask']}'";
		}
		// if(!empty($data['required'])){
		// 	if($data['required']){
		// 		//$required = "required";
		// 	}
		// }


		$label = $data['label'];
		
		$defaults = array('type' => 'text', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);
		
		$form_imput = "
		<div class='form-group'>
			<label for='exampleTooltip'>{$label}</label>
			<input "._parse_form_attributes($data, $defaults).$extra." class='form-control' {$data_maks}  placeholder='{$label}'>
			{$tooltip}
		</div>

		";
		

		return $form_imput;
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_email'))
{
	/**
	 * Returns a properly templated email input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_email($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('email', $data, $value, $label, $extra, $tooltip);

	}//end form_email()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_password'))
{
	/**
	 * Returns a properly templated password input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_password($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('password', $data, $value, $label, $extra, $tooltip);

	}//end form_password()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_url'))
{
	/**
	 * Returns a properly templated URL input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_url($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('url', $data, $value, $label, $extra, $tooltip);

	}//end form_url()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_telephone'))
{
	/**
	 * Returns a properly templated Telephone input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_telephone($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('tel', $data, $value, $label, $extra, $tooltip);

	}//end form_telephone()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_number'))
{
	/**
	 * Returns a properly templated number input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_number($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('number', $data, $value, $label, $extra, $tooltip);

	}//end form_number()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_color'))
{
	/**
	 * Returns a properly templated color input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_color($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('color', $data, $value, $label, $extra, $tooltip);

	}//end form_color()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_search'))
{
	/**
	 * Returns a properly templated search input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_search($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('search', $data, $value, $label, $extra, $tooltip);

	}//end form_search()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_date'))
{
	/**
	 * Returns a properly templated date input field.
	 *
	 * @param string $data    Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param string $value   Either a string with the value, or blank if an array is passed to the $data param.
	 * @param string $label   A string with the label of the element.
	 * @param string $extra   A string with any additional items to include, like Javascript.
	 * @param string $tooltip A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_date($data='', $value='', $label='', $extra='', $tooltip = '')
	{
		return _form_common('date', $data, $value, $label, $extra, $tooltip);

	}//end form_date()
}

//--------------------------------------------------------------------

if ( ! function_exists('form_dropdown'))
{
	/**
	 * Returns a properly templated date dropdown field.
	 *
	 * @param string $data     Either a string with the element name, or an array of key/value pairs of all attributes.
	 * @param array  $options  Array of options for the drop down list
	 * @param string $selected Either a string of the selected item or an array of selected items
	 * @param string $label    A string with the label of the element.
	 * @param string $extra    A string with any additional items to include, like Javascript.
	 * @param string $tooltip  A string for inline help or a tooltip icon
	 *
	 * @return string A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_dropdown($data, $options=array(), $selected='', $label='', $extra='', $tooltip = '',$extra_html='')
	{
		$defaults = array('name' => (( ! is_array($data)) ? $data : ''));

		// If name is empty at this point, try to grab it from the $data array
		if (empty($defaults['name']) && is_array($data) && isset($data['name']))
		{
			$defaults['name'] = $data['name'];
			unset($data['name']);
		}

		$output = _parse_form_attributes($data, $defaults);

		if ( ! is_array($selected))
		{
			$selected = array($selected);
		}

		// If no selected state was submitted we will attempt to set it automatically
		if (count($selected) === 0)
		{
			// If the form name appears in the $_POST array we have a winner!
			if (isset($_POST[$data['name']]))
			{
				$selected = array($_POST[$data['name']]);
			}
		}

		$options_vals = '';
		foreach ($options as $key => $val)
		{
			$key = (string) $key;

			if (is_array($val) && ! empty($val))
			{
				$options_vals .= '<optgroup label="'.$key.'">'.PHP_EOL;

				foreach ($val as $optgroup_key => $optgroup_val)
				{
					$sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';

					$options_vals .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
				}

				$options_vals .= '</optgroup>'.PHP_EOL;
			}
			else
			{
				$sel = (in_array($key, $selected)) ? ' selected="selected"' : '';

				$options_vals .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
			}
		}

		$error = '';

		if (function_exists('form_error'))
		{
			if (form_error($defaults['name']))
			{
				$error   = ' error';
				$tooltip = '<span class="help-inline">' . form_error($defaults['name']) . '</span>' . PHP_EOL;
			}
		}

		$label = empty($label)?(isset($data['label'])?$data['label']:""):$label;

		$output = <<<EOL


		<div class="form-group form-group-select2">
			<label for="{$defaults['name']}">{$label}</label>
			<select id="{$defaults['name']}" class="form-control" {$output} {$extra}>
				{$options_vals}
			</select>
			{$extra_html}
			{$tooltip}
		</div>

EOL;

		return $output;

	}//end form_dropdown()



	/**
 * Textarea field
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_textarea'))
{
	function form_textarea($data = '', $value = '', $label='',  $extra = '', $tooltip = '')
	{
		// $defaults = array('name' => (( ! is_array($data)) ? $data : ''), 'cols' => '40', 'rows' => '10');

		// if ( ! is_array($data) OR ! isset($data['value']))
		// {
		// 	$val = $value;
		// }
		// else
		// {
		// 	// $val = $data['value'];
		// 	// unset($data['value']); // textareas don't use the value attribute
		// }

		// $name = (is_array($data)) ? $data['name'] : $data;
		// $textarea = "<textarea "._parse_form_attributes($data, $defaults).$extra.">".form_prep($val, $name)."</textarea>";
		// // return "<textarea "._parse_form_attributes($data, $defaults).$extra.">".form_prep($val, $name)."</textarea>";

		$label = empty($label)?(isset($data['label'])?$data['label']:""):$label;

		// $error = '';

		// if (function_exists('form_error'))
		// {
		// 	if (form_error($defaults['name']))
		// 	{
		// 		$error   = ' error';
		// 		$tooltip = '<span class="help-inline">' . form_error($defaults['name']) . '</span>' . PHP_EOL;
		// 	}
		// }

		$value = @$data['value'];

		$output = <<<EOL

		<div class='form-group'>
			<label for='exampleTooltip'>{$label}</label>
			
			<textarea class="form-control ckeditor" id="{$data['name']}" name="{$data['name']}" rows="7" placeholder="Textarea">{$value}</textarea>
		</div>


		

EOL;

// 		$output = <<<EOL

// <div class="control-group {$error}">
// 	<label class="control-label" for="{$defaults['name']}">{$label}</label>
// 	<div class="controls">
// 			{$textarea}
// 		{$tooltip}
// 	</div>
// </div>

// EOL;

		return $output;
	}
}

if ( ! function_exists('form_checkbox_admin'))
{
	function form_checkbox_admin($data = '', $value = '', $label='',  $extra = '', $tooltip = '')
	{

		$defaults = array('name' => (( ! is_array($data)) ? $data : ''), 'cols' => '40', 'rows' => '10');
		$name = (is_array($data)) ? $data['name'] : $data;
		$label = empty($label)?(isset($data['label'])?$data['label']:""):$label;

		$val = "";
		$error = '';
		if ( ! is_array($data) OR ! isset($data['value']))
		{
			$val = $value;
		}
		else
		{
			$val = $data['value'];
			unset($data['value']); 
		}
		if(empty($val)){
			$val = "1";
		}
		$checked = $val == "1"?"checked":"";

		if (function_exists('form_error'))
		{
			if (form_error($defaults['name']))
			{
				$error   = ' error';
				$tooltip = '<span class="help-inline">' . form_error($defaults['name']) . '</span>' . PHP_EOL;
			}
		}

		$output = <<<EOL

		<div class="control-group {$error}">
			<label class="control-label" for="{$defaults['name']}">{$label}</label>
			<div class="controls">
					<input type="checkbox" name="{$name}" value="{$val}" {$checked} />
				{$tooltip}
			</div>
		</div>

EOL;

		return $output;
	}
}

/**
 * Textarea field
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_single_upload'))
{
	function form_single_upload($data = '', $value = '', $label='',  $extra = '', $tooltip = '')
	{
		$defaults = array('name' => (( ! is_array($data)) ? $data : ''), 'cols' => '40', 'rows' => '10');

		$name = (is_array($data)) ? $data['name'] : $data;
		// $textarea = "<textarea "._parse_form_attributes($data, $defaults).$extra.">".form_prep($val, $name)."</textarea>";
		// return "<textarea "._parse_form_attributes($data, $defaults).$extra.">".form_prep($val, $name)."</textarea>";

		$label = empty($label)?(isset($data['label'])?$data['label']:""):$label;

		$error = '';

		if (function_exists('form_error'))
		{
			if (form_error($defaults['name']))
			{
				$error   = ' error';
				$tooltip = '<span class="help-inline">' . form_error($defaults['name']) . '</span>' . PHP_EOL;
			}
		}
		// dump($data);die;
		$size = isset($data['options']->size)?$data['options']->size:"";
		list($w,$h) = explode("x", $size);

		$file_image_path = "http://www.placehold.it/{$size}/EFEFEF/AAAAAA";
		if(!empty($data['value'])){
			$file_image_path = base_url($data['options']->image_path.$data['value']);
		}
		
		// dump($data);
		$img="";
		if($data['value']){
			$img = "<img src='".getImagesPath()."/{$data['value']}' />";
		}

		$w = $w>300?300:$w;
		$h = $h>200?200:$h;

$output = <<<EOL

<div class="form-group example-twitter-oss">
	<label for="id_for">{$label}</label>
	<div class="col-lg-12">
	  	<div class="fileinput fileinput-new" data-provides="fileinput">
		  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 150px; height: 100px;">
			{$img}
		  </div>
		  <div>
		    <span class="btn btn-default btn-file"><span class="fileinput-new">Selecione imagem</span>
		    <span class="fileinput-exists">Alterar</span>
		    <input type="file" value="{$data['value']}" name="{$data['id']}"></span>
		    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
		  </div>
		</div>
	</div>
</div>


EOL;



// 		$output = <<<EOL
// 		<div class="form-group {$error}">
//           <label class="col-lg-2 control-label" for="{$defaults['name']}">{$label}</label>
//           <div class="col-lg-10">
// 				<div class="fileupload fileupload-new" data-provides="fileupload" data-name="{$name}">
// 				  <div class="fileupload-new thumbnail" style="width: {$w}px; height: {$h}px;">
// 				  <img src="{$file_image_path}" />
// 				  </div>
// 				  <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
// 				  <div>
// 				    <span class="btn btn-file">
// 				    	<span class="fileupload-new">Selecione uma imagem</span>
// 				    	<span class="fileupload-exists">Change</span>
// 				    	<input type="file" />
// 				    </span>
// 				    <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remover</a>
// 				  </div>
// 				</div>

// 				{$tooltip}
// 			</div>
// 		</div>

// EOL;

		return $output;
	}
}


}
