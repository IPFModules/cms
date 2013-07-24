<?php
/**
 * List cms block file
 *
 * This file holds the functions needed for the list cms block
 *
 * @copyright	http://smartfactory.ca The SmartFactory
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
 * Modified for use in the Podcast module by Madfish
 * @version		$sato-san$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

/**
 * Prepare list content block for display
 *
 * @param array $options
 * @return array 
 */
function show_select_content_cms($options)
{
	global $cmsConfig;
	$cmsModule = icms::handler("icms_module")->getByDirname('cms');
	//$sprocketsModule = icms::handler("icms_module")->getByDirname("sprockets");
		
	include_once(ICMS_ROOT_PATH . '/modules/' . $cmsModule->getVar('dirname') . '/include/common.php');
	$cms_start_handler = icms_getModuleHandler('start', $cmsModule->getVar('dirname'), 'cms');
	
	$cms = $cms_start_handler->get($options[0]);
	$accessGranted = ($cmsConfig['enable_perm'] == 1) ? $cms->accessGranted("start_perm_read") : TRUE;
	// Assign to template
	$block['select_content_cms'] = ($accessGranted && $cms->getVar("online", "e") == 1) ? $cms->toArray() : FALSE;
	
	// Create a Smarty-Link for the block <{$block.itemUrl}>
	$block['itemUrl'] = ($accessGranted && $cms->getVar("online", "e") == 1) ? $cms->getItemLink(TRUE).'&seite='.$cms->short_url() : FALSE;
	
	$block['show_logos'] = $options[1];
	$block['logo_block_display_width'] = icms_getConfig('logo_block_display_width', $cmsModule->getVar('dirname'));
	if (icms_getConfig('start_logo_position', $cmsModule->getVar('dirname') == 1)) // Align right
	{
		$block['start_logo_position'] = 'float:right; margin: 0em 0em 1em 1em;';
	}
	else // Align left
	{
		$block['start_logo_position'] = 'float:left; margin: 0em 1em 1em 0em;';
	}
	$block['freestyle_logo_dimensions'] = icms_getConfig('freestyle_logo_dimensions', $cmsModule->getVar('dirname'));
	
	return $block;
}

/**
 * Edit recent cms block options
 *
 * @param array $options
 * @return string 
 */
function edit_select_content_cms($options) 
{
	$cmsModule = icms::handler("icms_module")->getByDirname('cms');
	include_once(ICMS_ROOT_PATH . '/modules/' . $cmsModule->getVar('dirname') . '/include/common.php');
	$cms_start_handler = icms_getModuleHandler('start', $cmsModule->getVar('dirname'), 'cms');
	$selcontent = new icms_form_elements_Select('', 'options[0]', $options[0]);
	$selcontent->addOptionArray($cms_start_handler->getContentList(FALSE));
	
	// Select number of list cms to display in the block
	$form = '<table>';
	$form .= '<tr><td>' . _MB_CMS_LIST_CONTENT . '</td>';
	$form .= '<td>' . $selcontent->render() . '</td></tr>';
	
	
	$form .= '<tr><td>' . _MB_CMS_LIST_CONTENT_LOGOS_OR_LIST . '</td>';
	$form .= '<td><input type="radio" name="options[1]" value="1"';
	if ($options[1] == 1) 
	{
		$form .= ' checked="checked"';
	}
	$form .= '/>' . _MB_CMS_LIST_CONTENT_YES;
	$form .= '<input type="radio" name="options[1]" value="0"';
	if ($options[1] == 0) 
	{
		$form .= 'checked="checked"';
	}
	$form .= '/>' . _MB_CMS_LIST_CONTENT_NO . '</td></tr>';		
	
	$form .= '</table>';
	
	return $form;
}
