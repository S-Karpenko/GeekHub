<?php
/**
 * Element: AssignmentSelection
 * Displays Assignment Selection radio options
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

require_once JPATH_PLUGINS . '/system/nnframework/helpers/text.php';

class JFormFieldNN_AssignmentSelection extends JFormField
{
	public $type = 'AssignmentSelection';
	var $_version = '12.11.7';

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		JFactory::getDocument()->addStyleSheet(JURI::root(true) . '/plugins/system/nnframework/css/style.min.css?v=' . $this->_version);

		require_once __DIR__ . '/toggler.php';
		$toggler = new nnFieldToggler;

		$this->value = (int) $this->value;
		$label = $this->def('label');
		$param_name = $this->def('name');
		$noshow = $this->def('noshow', 0);

		$html = array();

		if ($label) {
			$label = NNText::html_entity_decoder(JText::_($label));

			$html[] = '</div>';
			if (!$noshow) {
				$html[] = $toggler->getInput(array('div' => 1, 'param' => 'show_assignments|' . $param_name, 'value' => '1|1,2'));
			}

			$class = 'well well-small nn_well';
			if ($this->value === 1) {
				$class .= ' alert-success';
			} else if ($this->value === 2) {
				$class .= ' alert-error';
			}
			$html[] = '<div class="' . $class . '">';

			$html[] = '<div class="control-group">';

			$html[] = '<div class="control-label">';
			$html[] = '<label><h4 class="nn_assignmentselection-header">' . $label . '</h4></label>';
			$html[] = '</div>';

			$html[] = '<div class="controls">';
			$html[] = '<fieldset id="' . $this->id . '"  class="radio btn-group">';

			$onclick = ' onclick="nnScripts.setToggleTitleClass(this, 0)"';
			$html[] = '<input type="radio" id="' . $this->id . '0" name="' . $this->name . '" value="0"' . ((!$this->value) ? ' checked="checked"' : '') . $onclick . '/>';
			$html[] = '<label class="nn_btn-ignore" for="' . $this->id . '0">' . JText::_('NN_IGNORE') . '</label>';

			$onclick = ' onclick="nnScripts.setToggleTitleClass(this, 1)"';
			$html[] = '<input type="radio" id="' . $this->id . '1" name="' . $this->name . '" value="1"' . (($this->value === 1) ? ' checked="checked"' : '') . $onclick . '/>';
			$html[] = '<label class="nn_btn-include" for="' . $this->id . '1">' . JText::_('NN_INCLUDE') . '</label>';

			$onclick = ' onclick="nnScripts.setToggleTitleClass(this, 2)"';
			$onclick .= ' onload="nnScripts.setToggleTitleClass(this, ' . $this->value . ', 7)"';
			$html[] = '<input type="radio" id="' . $this->id . '2" name="' . $this->name . '" value="2"' . (($this->value === 2) ? ' checked="checked"' : '') . $onclick . '/>';
			$html[] = '<label class="nn_btn-exclude" for="' . $this->id . '2">' . JText::_('NN_EXCLUDE') . '</label>';

			$html[] = '</fieldset>';
			$html[] = '</div>';

			$html[] = '</div>';
			$html[] = '<div class="clearfix"> </div>';

			$html[] = $toggler->getInput(array('div' => 1, 'param' => $param_name, 'value' => '1,2'));
			$html[] = '<div><div>';
		} else {
			if (!$noshow) {
				$html[] = $toggler->getInput(array('div' => 1));
			}
			$html[] = $toggler->getInput(array('div' => 1));
		}

		return '</div>' . implode('', $html);
	}

	private function def($val, $default = '')
	{
		return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
	}
}
