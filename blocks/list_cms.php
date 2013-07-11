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
function show_list_cms($options)
{
	$cmsModule = icms::handler("icms_module")->getByDirname('cms');
	$sprocketsModule = icms::handler("icms_module")->getByDirname("sprockets");
		
	include_once(ICMS_ROOT_PATH . '/modules/' . $cmsModule->getVar('dirname') . '/include/common.php');
	$cms_start_handler = icms_getModuleHandler('start', $cmsModule->getVar('dirname'), 'cms');
	
	if (icms_get_module_status("sprockets"))
	{
		icms_loadLanguageFile("sprockets", "common");
		$sprockets_taglink_handler = icms_getModuleHandler('taglink', $sprocketsModule->getVar('dirname'), 'sprockets');
	}
	
	$criteria = new icms_db_criteria_Compo();
	$startList = $cms = array();

	// Get a list of content filtered by tag
	if (icms_get_module_status("sprockets") && $options[3] != 0)
	{
		$query = "SELECT `start_id` FROM " . $cms_start_handler->table . ", "
			. $sprockets_taglink_handler->table
			. " WHERE `start_id` = `iid`"
			. " AND `tid` = '" . $options[3] . "'"
			. " AND `mid` = '" . $cmsModule->getVar('mid') . "'"
			. " AND `item` = 'start'"
			. " AND `online_status` = '1'"
			. " AND `beendet` = '0'"
			. " ORDER BY `date` DESC"; //geändert von weight ASC zu date DESC

		$result = icms::$xoopsDB->query($query);

		if (!$result)
		{
			echo 'Error: List cms block';
			exit;

		}
		else
		{
			$rows = $cms_start_handler->convertResultSet($result, TRUE, FALSE);
			foreach ($rows as $key => $row) 
			{
				$start_list[$key] = $row['start_id'];
			}
		}
	}
	// Otherwise just get a list of all content
	else 
	{
		$criteria->add(new icms_db_criteria_Item('online_status', '1'));
		$criteria->add(new icms_db_criteria_Item('beendet', '0'));
		$criteria->setSort('date');
		$criteria->setOrder('DESC');
		$start_list = $cms_start_handler->getList($criteria);
		$start_list = array_flip($start_list);
	}
	
	// Pick list content from the list, if the block preference is so set
	if ($options[1] == TRUE) 
	{
		shuffle($start_list);
	}
	
	// Cut the start list down to the number of required entries and set the IDs as criteria
	$start_list = array_slice($start_list, 0, $options[0], TRUE);
	$criteria->add(new icms_db_criteria_Item('start_id', '(' . implode(',', $start_list) . ')', 'IN'));
	$criteria->setSort('date');
	$criteria->setOrder('DESC');
			
	// Retrieve the cms and assign them to the block
	$cms = $cms_start_handler->getObjects($criteria, TRUE, FALSE);
	
	// Need to shuffle them again as the DB will return them in order whether you like it or not
	if ($options[1] == TRUE)
	{
		shuffle($cms);
	}

	// Check if an 'updated' notice should be displayed. this works by comparing the time since the
	// start was last updated against the length of time that an updated notice should be shown 
	// (as set in the module preferences)
	
	if (icms_getConfig('show_last_updated', $cmsModule->getVar('dirname')))
	{
		$update_periods = array(
			0 => 0,
			1 => 86400,		// Show updated notice for 1 day
			2 => 259200,	// Show updated notice for 3 days
			3 => 604800,	// Show updated notice for 1 week
			4 => 1209600,	// Show updated notice for 2 weeks
			5 => 1814400,	// Show updated notice for 3 weeks
			6 => 2419200	// Show updated notice for 4 weeks
			);
		$updated_notice_period = $update_periods[icms_getConfig('updated_notice_period', $cmsModule->getVar('dirname'))];
	}
	
	// Check if updated notices and view counter should be shown
	// Update logo paths
	// Add SEO string to URL
	// Show view counter
	foreach ($cms as $key => &$object)
	{
		// Update notices
		if (icms_getConfig('show_last_updated', $cmsModule->getVar('dirname')))
		{
			$updated = strtotime($object['date']);
			if ((time() - $updated) < $updated_notice_period)
			{
				$object['date'] = date(icms_getConfig('date_format', $cmsModule->getVar('dirname')), $updated);
				$object['updated'] = TRUE;
			}
		}
		
		// Logo paths
		if (icms_getConfig('display_start_logos', $cmsModule->getVar('dirname')) == TRUE && !empty ($object['logo']))
		{
			$object['logo'] = ICMS_URL . '/uploads/' . $cmsModule->getVar('dirname') . '/start/' . $object['logo'];
		}
		else
		{
			unset($object['logo']);
		}
		
		// Add SEO friendly string to URL
		// seourl
		if (!empty($object['short_url']))
		{
			$object['itemUrl'] .= "&amp;seite=" . $object['short_url'];
		}
		
		// View counter
		if (icms_getConfig('show_view_counter') == FALSE)
		{
			unset($object['counter']);
		}
	}
	
	// Prepare tags. A list of start IDs is used to retrieve relevant taglinks. The taglinks
	// are sorted into a multidimensional array, using the start ID as the key to each subarray.
	// Then its just a case of assigning each subarray to the matching start.

	// Prepare a list of start_id, this will be used to create a taglink buffer, which is used
	// to create tag links for each start
	$linked_start_ids = '';
	foreach ($cms as $key => $value) {
		$linked_start_ids[] = $value['start_id'];
	}
	
	if (icms_get_module_status("sprockets") && !empty($linked_start_ids))
	{
		$linked_start_ids = '(' . implode(',', $linked_start_ids) . ')';
		
		// Get a reference array of tags
		$sprockets_tag_handler = icms_getModuleHandler('tag', $sprocketsModule->getVar('dirname'), 'sprockets');
		$criteria = icms_buildCriteria(array('label_type' => '0'));
		$sprockets_tag_buffer = $sprockets_tag_handler->getList($criteria, TRUE, TRUE);
		if ($sprockets_tag_buffer) {
			$sprockets_tag_ids = "(" . implode(',', array_keys($sprockets_tag_buffer)) . ")";
		}

		// Prepare multidimensional array of tag_ids with start_id (iid) as key
		$taglink_buffer = $start_tag_id_buffer = array();
		$criteria = new  icms_db_criteria_Compo();
		$criteria->add(new icms_db_criteria_Item('mid', $cmsModule->getVar('mid')));
		$criteria->add(new icms_db_criteria_Item('item', 'start'));
		$criteria->add(new icms_db_criteria_Item('iid', $linked_start_ids, 'IN'));
		$criteria->add(new icms_db_criteria_Item('tid', $sprockets_tag_ids, 'IN'));
		$taglink_buffer = $sprockets_taglink_handler->getObjects($criteria, TRUE, TRUE);
		unset($criteria);

		// Build tags, with URLs for navigation
		foreach ($taglink_buffer as $key => $taglink)
		{
			if (!array_key_exists($taglink->getVar('iid'), $start_tag_id_buffer))
			{
				$start_tag_id_buffer[$taglink->getVar('iid')] = array();
			}
			$start_tag_id_buffer[$taglink->getVar('iid')][] = '<a class="label label-info" href="' . ICMS_URL . '/modules/' 
					. $cmsModule->getVar('dirname') . '/start.php?tag_id=' 
					. $taglink->getVar('tid') . '">' 
					. $sprockets_tag_buffer[$taglink->getVar('tid')]
					. '</a>';
		}

		// Convert the tag arrays into strings for easy handling in the template
		foreach ($start_tag_id_buffer as $key => &$value) 
		{
			$value = implode(' ', $value);
		}

		// Assign each subarray of tags to the matching cms, using the item id as marker
		foreach ($cms as $key => &$value) 
		{
			if (!empty($start_tag_id_buffer[$value['start_id']]))
			{
				$value['tags'] = $start_tag_id_buffer[$value['start_id']];
			}
		}
	}
	
	// Assign to template
	$block['list_cms'] = $cms;
	$block['show_logos'] = $options[2];
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
function edit_list_cms($options) 
{
	$cmsModule = icms::handler("icms_module")->getByDirname('cms');
	include_once(ICMS_ROOT_PATH . '/modules/' . $cmsModule->getVar('dirname') . '/include/common.php');
	$cms_start_handler = icms_getModuleHandler('start', $cmsModule->getVar('dirname'), 'cms');
	
	// Select number of list cms to display in the block
	$form = '<table>';
	$form .= '<tr><td>' . _MB_CMS_LIST_LIMIT . '</td>';
	$form .= '<td>' . '<input type="text" name="options[0]" value="' . $options[0] . '"/></td></tr>';
	
	// RND the cms? NB: Only works if you do not cache the block
	$form .= '<tr><td>' . _MB_CMS_LIST_OR_FIXED . '</td>';
	$form .= '<td><input type="radio" name="options[1]" value="1"';
	if ($options[1] == 1) 
	{
		$form .= ' checked="checked"';
	}
	$form .= '/>' . _MB_CMS_LIST_YES;
	$form .= '<input type="radio" name="options[1]" value="0"';
	if ($options[1] == 0) 
	{
		$form .= 'checked="checked"';
	}
	$form .= '/>' . _MB_CMS_LIST_NO . '</td></tr>';	
	
	// Show start logos, or just a simple list?
	$form .= '<tr><td>' . _MB_CMS_LIST_LOGOS_OR_LIST . '</td>';
	$form .= '<td><input type="radio" name="options[2]" value="1"';
	if ($options[2] == 1) 
	{
		$form .= ' checked="checked"';
	}
	$form .= '/>' . _MB_CMS_LIST_YES;
	$form .= '<input type="radio" name="options[2]" value="0"';
	if ($options[2] == 0) 
	{
		$form .= 'checked="checked"';
	}
	$form .= '/>' . _MB_CMS_LIST_NO . '</td></tr>';		
	
	// Optionally display results from a single tag - but only if sprockets module is installed
	$sprocketsModule = icms::handler("icms_module")->getByDirname("sprockets");

	if (icms_get_module_status("sprockets"))
	{
		$sprockets_tag_handler = icms_getModuleHandler('tag', $sprocketsModule->getVar('dirname'), 'sprockets');
		$sprockets_taglink_handler = icms_getModuleHandler('taglink', $sprocketsModule->getVar('dirname'), 'sprockets');
		
		// Get only those tags that contain content from this module
		$criteria = '';
		$relevant_tag_ids = array();
		$criteria = icms_buildCriteria(array('mid' => $cmsModule->getVar('mid')));
		$cms_module_taglinks = $sprockets_taglink_handler->getObjects($criteria, TRUE, TRUE);
		foreach ($cms_module_taglinks as $key => $value)
		{
			$relevant_tag_ids[] = $value->getVar('tid');
		}
		$relevant_tag_ids = array_unique($relevant_tag_ids);
		$relevant_tag_ids = '(' . implode(',', $relevant_tag_ids) . ')';
		unset($criteria);

		$criteria = new icms_db_criteria_Compo();
		$criteria->add(new icms_db_criteria_Item('tag_id', $relevant_tag_ids, 'IN'));
		$tagList = $sprockets_tag_handler->getList($criteria);

		$tagList = array(0 => _MB_CMS_LIST_ALL) + $tagList;
		$form .= '<tr><td>' . _MB_CMS_LIST_TAG . '</td>';
		// Parameters icms_form_elements_Select: ($caption, $name, $value = null, $size = 1, $multiple = TRUE)
		$form_select = new icms_form_elements_Select('', 'options[3]', $options[3], '1', FALSE);
		$form_select->addOptionArray($tagList);
		$form .= '<td>' . $form_select->render() . '</td></tr>';
	}
	
	$form .= '</table>';
	
	return $form;
}