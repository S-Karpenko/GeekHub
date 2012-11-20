<?php
/**
 * NoNumber Framework Helper File: VersionCheck
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

class NNVersions
{
	public static $instance = null;

	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new NoNumberVersions;
		}

		return self::$instance;
	}
}

class NoNumberVersions
{
	var $_version = '12.11.7';

	function getMessage($name = '', $xml = '', $version = '')
	{
		if (!$name || (!$xml && !$version)) {
			return '';
		}

		$alias = preg_replace('#[^a-z]#', '', strtolower($name));

		if ($xml) {
			$xml = JApplicationHelper::parseXMLInstallFile(JPATH_SITE . '/' . $xml);
			if ($xml && isset($xml['version'])) {
				$version = $xml['version'];
			}
		}

		if (!$version) {
			return '';
		}

		JHtml::_('jquery.framework');
		JFactory::getDocument()->addScript(JURI::root(true) . '/plugins/system/nnframework/js/script.min.js?v=' . $this->_version);
		$url = 'download.nonumber.nl/extensions.php?j=30&e=' . $alias;
		$script = "
			jQuery(document).ready(function() {
				nnScripts.loadajax(
					'" . $url . "',
					'nnScripts.displayVersion( data, \"" . $alias . "\", \"" . str_replace(array('FREE', 'PRO'), '', $version) . "\" )',
					'nnScripts.displayVersion( \"\" )'
				);
			});
		";
		JFactory::getDocument()->addScriptDeclaration($script);

		return '<div class="alert alert-error" style="display:none;" id="nonumber_version_' . $alias . '">' . $this->getMessageText($alias, $version) . '</div>';
	}

	function getMessageText($alias, $version)
	{
		jimport('joomla.filesystem.file');

		$is_pro = !(strpos($version, 'PRO') === false);
		$version = str_replace(array('FREE', 'PRO'), '', $version);

		$has_nnem = 0;
		if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_' . $alias . '/' . $alias . '.xml')
			|| JFile::exists(JPATH_ADMINISTRATOR . '/components/com_' . $alias . '/com_' . $alias . '.xml')
		) {
			$has_nnem = 1;
		}

		$url = 'http://www.nonumber.nl/' . $alias . '#download';
		if ($has_nnem) {
			$url = 'index.php?component=nonumbermanager';
		}

		$msg = '<strong>'
			. JText::_('NN_NEW_VERSION_AVAILABLE')
			. ': <a href="' . $url . '" target="_blank">'
			. JText::sprintf('NN_UPDATE_TO', '<span id="nonumber_newversionnumber_' . $alias . '"></span>')
			. '</a></strong><br /><em>'
			. JText::sprintf('NN_CURRENT_VERSION', $version)
			. ' ('
			. JText::_('NN_ONLY_VISIBLE_TO_ADMIN')
			. ')</em>';

		return html_entity_decode($msg, ENT_COMPAT, 'UTF-8');
	}

	function getCopyright($name, $version)
	{
		$html = array();
		$html[] = '<p style="text-align:center;">';
		$html[] = $name;
		if ($version) {
			if (!(strpos($version, 'PRO') === false)) {
				$version = str_replace('PRO', '', $version);
				$version .= ' <small>[PRO]</small>';
			} else if (!(strpos($version, 'FREE') === false)) {
				$version = str_replace('FREE', '', $version);
				$version .= ' <small>[FREE]</small>';
			}
			$html[] = ' v' . $version;
		}
		$html[] = ' - ' . JText::_('COPYRIGHT') . ' &copy; ' . date('Y') . ' NoNumber ' . JText::_('ALL_RIGHTS_RESERVED');
		$html[] = '</p>';

		return implode('', $html);
	}

	static function getXMLVersion($element = 'nnframework', $type = 'system', $admin = 1, $urlformat = 0)
	{
		if (!$element) {
			$element = 'nnframework';
		}
		if (!$type) {
			$type = 'system';
		}
		if (!strlen($admin)) {
			$admin = 1;
		}

		switch ($type) {
			case 'component':
			case 'components':
			case 'module':
			case 'modules':
				$type .= in_array($type, array('component', 'module')) ? 's' : '';
				if ($admin) {
					$path = JPATH_ADMINISTRATOR;
				} else {
					$path = JPATH_SITE;
				}
				$path .= '/' . $type . '/' . ($type == 'modules' ? 'mod_' : 'com_') . $element . '/' . ($type == 'modules' ? 'mod_' : '') . $element . '.xml';
				break;
			default:
				$path = JPATH_PLUGINS . '/' . $type . '/' . $element . '/' . $element . '.xml';
				break;
		}

		$version = '';
		$xml = JApplicationHelper::parseXMLInstallFile($path);
		if ($xml && isset($xml['version'])) {
			$version = trim($xml['version']);
			if ($urlformat) {
				$version = '?v=' . strtolower(str_replace(array('FREE', 'PRO'), array('f', 'p'), $version));
			}
		}

		return $version;
	}
}
