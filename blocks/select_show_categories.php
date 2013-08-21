<?php
/**
 * List cms block file
 *
 * This file holds the functions needed for the list cms block
 *
 * @copyright	http://smartfactory.ca The SmartFactory
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		marcan aka Marc-Andre Lanciault <marcan@smartfactory.ca>
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
function show_show_categories_cms($options)
{
    global $cmsConfig,$xoTheme;

$cmsModule = icms::handler("icms_module")->getByDirname('cms');

include_once(ICMS_ROOT_PATH . '/modules/' . $cmsModule->getVar('dirname') . '/include/common.php');
$cms_start_handler = icms_getModuleHandler('start', $cmsModule->getVar('dirname'), 'cms');

if(icms_get_module_status("sprockets")) {
	$sprocketsModule = icms_getModuleInfo("sprockets");
	icms_loadLanguageFile('sprockets', 'common');
	$sprockets_tag_handler = icms_getModuleHandler("tag", $sprocketsModule->getVar("dirname"), "sprockets");
	$cms_mid = $cmsModule->getVar("mid");
	$cids = $categories = $subs = array();
	
	// Build a category tree. This can be used to retrieve parent and child objects as required	
	include ICMS_ROOT_PATH . '/modules/' . $sprocketsModule->getVar('dirname') . '/include/angry_tree.php';
	$criteria = icms_buildCriteria(array('mid' => $cms_mid, 'label_type' => '1'));
	// It is better to return as objects, in order to avoid the automatic use of object->toArray
	// during conversion. The problem with toArray() is that it triggers all the getVar overrides,
	// and if any of those require database queries (for example looking up the parent's name)
	// it will make those queries for every object involved individually, which can be very 
	// expensive. A better way is to return objects and then manually convert them to an array if
	// required. See the function toArrayWithoutOverrides() referenced below for a method.
	$categories = $sprockets_tag_handler->getObjects($criteria, TRUE, TRUE);
	$categoryTree = new IcmsPersistableTree($categories, 'tag_id', 'parent_id', null);

	if($options[0]) {
		$sprockets_taglink_handler = icms_getModuleHandler("taglink", $sprocketsModule->getVar("dirname"), "sprockets");
		$cnt = FALSE;
		if($cmsConfig['enable_perm'] == 1) {
			$perm_handler = new icms_ipf_permission_Handler($cms_start_handler);
			$grantedItems = $perm_handler->getGrantedItems("start_perm_read");
			$cnt = count($grantedItems);
			if($cnt) {
				$perm = new icms_db_criteria_Item("iid","(".implode(",", $grantedItems).")", "IN");
			} else {
				$perm = new icms_db_criteria_Item("iid", "(0)", "IN");
			}
		}
		foreach(array_keys($categories) as $k) {
			$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item("mid", $cms_mid));
			$criteria->add(new icms_db_criteria_Item("tid", $k));
			if($cmsConfig['enable_perm'] == 1) {
				$criteria->add($perm);
			}
			$cids[$k] = $sprockets_taglink_handler->getCount($criteria);
			unset($criteria);
		}
		$block['categories_count'] = $cids;
	}
	
	unset($criteria, $categories);
	
	// We can now use the tree to retrieve objects without generating additional queries
	// Retrieve the parent categories (those with parent_id = 0)
	$categories = $categoryTree->getFirstChild(0);
	foreach ($categories as &$category) {
		$category = $category->toArrayWithoutOverrides();
		// Retrieve the subcategories for each parent
		$subs = $categoryTree->getFirstChild($category['tag_id']);
		foreach ($subs as &$sub) {
			$sub = $sub->toArrayWithoutOverrides();
			$sub['itemLink'] = '<a href="' . ICMS_URL . '/modules/cms/start.php?tag_id=' 
					. $sub['tag_id'] . '">' . $sub['title'] . '</a>';
		}
		$category['subs'] = $subs;
	}
	$block['categories'] = $categories;
	
}

$xoTheme->addStylesheet('/modules/' . CMS_DIRNAME . '/module.css');

return $block;
}


/**
 * Edit recent cms block options
 *
 * @param array $options
 * @return string
 */
function edit_show_categories_cms($options)
{
	$ele = new icms_form_elements_Radioyn("", "options[0]", $options[0]);

	// Select number of list cms to display in the block
	$form = '<table>';
	$form .= '<tr><td>' . _MB_CMS_SHOW_CATEGORIES . '</td>';
	$form .= '<td>' . $ele->render() . '</td></tr>';

	$form .= '</table>';

	return $form;
}
