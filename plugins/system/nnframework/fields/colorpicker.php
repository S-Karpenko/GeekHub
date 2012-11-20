<?php
/**
 * Element: ColorPicker
 * Displays a textfield with a color picker
 *
 * @package         NoNumber Framework
 * @version         12.11.7
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2012 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

jimport('joomla.form.formfield');

class JFormFieldNN_ColorPicker extends JFormField
{
	public $type = 'ColorPicker';

	protected function getInput()
	{
		$field = new nnFieldColorPicker;
		return $field->getInput($this->name, $this->id, $this->value, $this->element->attributes());
	}
}

class nnFieldColorPicker
{
	private $_version = '12.11.7';

	function getInput($name, $id, $value, $params)
	{
		$this->name = $name;
		$this->id = $id;
		$this->value = $value;
		$this->params = $params;
		$action = '';

		if ($this->def('inlist', 0) && $this->def('action')) {
			$this->name = $name . $id;
			$this->id = $name . $id;
			$action = ' onchange="' . $this->def('action') . '"';
		}

		JFactory::getDocument()->addScript(JURI::root(true) . '/plugins/system/nnframework/js/colorpicker.min.js?v=' . $this->_version);
		JFactory::getDocument()->addStyleSheet(JURI::root(true) . '/plugins/system/nnframework/css/colorpicker.min.css?v=' . $this->_version);

		$this->value = strtoupper(preg_replace('#[^a-z0-9]#si', '', $this->value));
		$color = strtolower($this->value);

		$colors = array(
			'' => 'JNONE',
			'999999' => 'Light Gray',
			'555555' => 'Gray',
			'000000' => 'Black',
			'@1' => '',
			'049cdb' => 'Blue',
			'0064cd' => 'Dark blue',
			'46a546' => 'Green',
			'9d261d' => 'Red',
			'@2' => '',
			'ffc40d' => 'Yellow',
			'f89406' => 'Orange',
			'c3325f' => 'Pink',
			'7a43b6' => 'Purple'
		);

		$html = array();
		$html[] = '<select ' . $action . ' name="' . $this->name . '" id="' . $this->id . '" class="nncolorpicker chzn-done">';
		foreach ($colors as $val => $name) {
			if (!$name) {
				$html[] = '<option></option>';
			} else {
				$html[] = '<option value="' . $val . '"' . ($val == $color ? ' selected="selected"' : '') . '>' . JText::_($name) . '</option>';
			}
		}
		$html[] = '</select>';

		return implode('', $html);
	}

	private function def($val, $default = '')
	{
		return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
	}
}
