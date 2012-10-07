<?php
/**
 * Start index page - displays details of a single start, a list of start summary descriptions or a compact table of cms
 *
 * @copyright	Copyright Madfish (Simon Wilkinson) 2012
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
 * @package		cms
 * @version		$sato-san$
*/

include_once "header.php";

$xoopsOption["template_main"] = "cms_start.html";
include_once ICMS_ROOT_PATH . "/header.php";

// Sanitise input parameters
$clean_start_id = isset($_GET["start_id"]) ? (int)$_GET["start_id"] : 0 ;
$clean_tag_id = isset($_GET["tag_id"]) ? (int)$_GET["tag_id"] : 0 ;
$clean_start = isset($_GET["start"]) ? (int)($_GET["start"]) : 0;

// Get the requested start, or retrieve the index page. Only show online cms
$cms_start_handler = icms_getModuleHandler("start", basename(dirname(__FILE__)), "cms");
$criteria = icms_buildCriteria(array('online_status' => '1'));
$startObj = $cms_start_handler->get($clean_start_id, TRUE, FALSE, $criteria);

// Get relative path to document root for this ICMS install. This is required to call the logos correctly if ICMS is installed in a subdirectory
$directory_name = basename(dirname(__FILE__));
$script_name = getenv("SCRIPT_NAME");
$document_root = str_replace('modules/' . $directory_name . '/archiviert_start.php', '', $script_name);

// Optional tagging support (only if Sprockets module installed)
$sprocketsModule = icms::handler("icms_module")->getByDirname("sprockets");
$cms_tag_name = '';

if (icms_get_module_status("sprockets"))
{
	icms_loadLanguageFile("sprockets", "common");
	$sprockets_tag_handler = icms_getModuleHandler('tag', $sprocketsModule->getVar('dirname'), 'sprockets');
	$sprockets_taglink_handler = icms_getModuleHandler('taglink', $sprocketsModule->getVar('dirname'), 'sprockets');
	$criteria = icms_buildCriteria(array('label_type' => '0'));
	$sprockets_tag_buffer = $sprockets_tag_handler->getList($criteria, TRUE, TRUE);
	if ($sprockets_tag_buffer) {
		$sprockets_tag_ids = "(" . implode(',', array_keys($sprockets_tag_buffer)) . ")";
	}
}

// Assign common logo preferences to template
$icmsTpl->assign('display_start_logos', icms::$module->config['display_start_logos']);
$icmsTpl->assign('freestyle_logo_dimensions', icms::$module->config['freestyle_logo_dimensions']);
$icmsTpl->assign('logo_display_width', icms::$module->config['logo_index_display_width']);
if (icms::$module->config['start_logo_position'] == 1) // Align right
{
	$icmsTpl->assign('start_logo_position', 'cms_float_right');
}
else // Align left
{
	$icmsTpl->assign('start_logo_position', 'cms_float_left');
}

/////////////////////////////////////////
////////// VIEW SINGLE START //////////
/////////////////////////////////////////

if($startObj && !$startObj->isNew())
{	
	//comments detailpage
	if ($cmsConfig['com_rule']) {
		$icmsTpl->assign('cms_post_comment', TRUE);
		include_once ICMS_ROOT_PATH . '/include/comment_view.php';
	}
	
	// Update hit counter
	if (!icms_userIsAdmin(icms::$module->getVar('dirname')))
	{
		$cms_start_handler->updateCounter($startObj);
	}
	
	// Convert object to array for easy insertion into templates
	$start = $startObj->toArray();
	
	// Add SEO friendly string to URL
	// seourl
	if (!empty($start['short_url']))
	{
	$start['itemUrl'] .= "&amp;seite=" . $start['short_url'];
	}
			
	// Check if the hit counter should be displayed or not
	if (icms::$module->config['show_view_counter'] == FALSE)
	{
		unset($start['counter']);
	}

	// Adjust logo path for template
	if (!empty($start['logo']))
	{
		$start['logo'] = $document_root . 'uploads/' . $directory_name . '/start/' . $start['logo'];
	}
	
	// Prepare tags for display
	if (icms_get_module_status("sprockets"))
	{
		$start['tags'] = array();
		$start_tag_array = $sprockets_taglink_handler->getTagsForObject($startObj->getVar('start_id'), $cms_start_handler);
		foreach ($start_tag_array as $key => $value)
		{
			$start['tags'][$value] = '<a class="label label-info" href="' . CMS_URL . 'archiviert_start.php?tag_id=' . $value 
					. '" title="' . _CO_CMS_TAGS_ALL_CONTENTS_ON . ' '. $sprockets_tag_buffer[$value]
						.' ' . _CO_CMS_TAGS_ALL_SHOW . '">' . $sprockets_tag_buffer[$value] . '</a>';
		}
		$start['tags'] = implode(' ', $start['tags']);
	}

	// If the start is archiviert, add the archiviert flag to the breadcrumb title
	if ($startObj->getVar('beendet', 'e') == 1)
	{
		$icmsTpl->assign("cms_archiviert_path", _CO_CMS_START_BEENDET);
	}
	
	$icmsTpl->assign("cms_start", $start);

	$icms_metagen = new icms_ipf_Metagen($startObj->getVar("title"), 
			$startObj->getVar("meta_keywords", "n"), $startObj->getVar("meta_description", "n"));
	$icms_metagen->createMetaTags();
	$icmsTpl->assign('logo_display_width', icms::$module->config['logo_single_display_width']);
}

////////////////////////////////////////
////////// VIEW START INDEX //////////
////////////////////////////////////////
else
{	
	// Get a select box (if preferences allow, and only if Sprockets module installed)
	if (icms_get_module_status("sprockets") && icms::$module->config['show_tag_select_box'] == TRUE)
	{
		// Initialise
		$cms_tag_name = '';
		$tagList = array();
		$sprockets_tag_handler = icms_getModuleHandler('tag', $sprocketsModule->getVar('dirname'),
				'sprockets');
		$sprockets_taglink_handler = icms_getModuleHandler('taglink', 
				$sprocketsModule->getVar('dirname'), 'sprockets');

		// Append the tag to the breadcrumb title
		if (array_key_exists($clean_tag_id, $sprockets_tag_buffer) && ($clean_tag_id !== 0))
		{
			$cms_tag_name = $sprockets_tag_buffer[$clean_tag_id];
			$icmsTpl->assign('cms_category_path', $sprockets_tag_buffer[$clean_tag_id]);
		}
		
		// Load the tag navigation select box
		// $action, $selected = null, $zero_option_message = '---', 
		// $navigation_elements_only = TRUE, $module_id = null, $item = null,
		$tag_select_box = $sprockets_tag_handler->getTagSelectBox('archiviert_start.php', $clean_tag_id, 
				_CO_CMS_START_ALL_TAGS, TRUE, icms::$module->getVar('mid'));
		$icmsTpl->assign('cms_tag_select_box', $tag_select_box);
	}
	
	///////////////////////////////////////////////////////////////////
	////////// View cms as list of summary descriptions //////////
	///////////////////////////////////////////////////////////////////
	if (icms::$module->config['index_display_mode'] == TRUE)
	{
		$start_summaries = $linked_start_ids = array();
		
		// Append the tag name to the module title (if preferences allow, and only if Sprockets module installed)
		if (icms_get_module_status("sprockets") && icms::$module->config['show_breadcrumb'] == FALSE)
		{
			if (array_key_exists($clean_tag_id, $sprockets_tag_buffer) && ($clean_tag_id !== 0))
			{
				$cms_tag_name = $sprockets_tag_buffer[$clean_tag_id];
				$icmsTpl->assign('cms_tag_name', $cms_tag_name);
			}
		}
				
		// Retrieve cms for a given tag
		if ($clean_tag_id && icms_get_module_status("sprockets"))
		{
			/**
			 * Retrieve a list of cms JOINED to taglinks by start_id/tag_id/module_id/item
			 */

			$query = $rows = $start_count = '';
			$linked_start_ids = array();
			
			// First, count the number of cms for the pagination control
			$start_count = '';
			$group_query = "SELECT count(*) FROM " . $cms_start_handler->table . ", "
					. $sprockets_taglink_handler->table
					. " WHERE `start_id` = `iid`"
					. " AND `online_status` = '1'"
					. " AND `beendet` = '1'"
					. " AND `tid` = '" . $clean_tag_id . "'"
					. " AND `mid` = '" . icms::$module->getVar('mid') . "'"
					. " AND `item` = 'start'";
			
			$result = icms::$xoopsDB->query($group_query);

			if (!$result)
			{
				echo 'Error';
				exit;	
			}
			else
			{
				while ($row = icms::$xoopsDB->fetchArray($result))
				{
					foreach ($row as $key => $count) 
					{
						$start_count = $count;
					}
				}
			}
			
			// Secondly, get the cms
			$query = "SELECT * FROM " . $cms_start_handler->table . ", "
					. $sprockets_taglink_handler->table
					. " WHERE `start_id` = `iid`"
					. " AND `online_status` = '1'"
					. " AND `beendet` = '1'"
					. " AND `tid` = '" . $clean_tag_id . "'"
					. " AND `mid` = '" . icms::$module->getVar('mid') . "'"
					. " AND `item` = 'start'"
					. " ORDER BY `date` DESC" //geändert von weight ASC zu date DESC
					. " LIMIT " . $clean_start . ", " . icms::$module->config['number_of_cms_per_page'];

			$result = icms::$xoopsDB->query($query);

			if (!$result)
			{
				echo 'Error';
				exit;
			}
			else
			{
				$rows = $cms_start_handler->convertResultSet($result, TRUE, FALSE);
				foreach ($rows as $key => $row) 
				{
					$start_summaries[$row['start_id']] = $row;
				}
			}
		}
				
		// Retrieve cms without filtering by tag
		else
		{
			$criteria = new icms_db_criteria_Compo();
			$criteria->add(new icms_db_criteria_Item('beendet', '1'));
			$criteria->add(new icms_db_criteria_Item('online_status', '1'));

			// Count the number of online cms for the pagination control
			$start_count = $cms_start_handler->getCount($criteria);

			// Continue to retrieve cms for this page view
			$criteria->setStart($clean_start);
			$criteria->setLimit(icms::$module->config['number_of_cms_per_page']);
			$criteria->setSort('date');
			$criteria->setOrder('DESC');
			$start_summaries = $cms_start_handler->getObjects($criteria, TRUE, FALSE);
		}
		
		// Prepare tags. A list of start IDs is used to retrieve relevant taglinks. The taglinks
		// are sorted into a multidimensional array, using the start ID as the key to each subarray.
		// Then its just a case of assigning each subarray to the matching start.
		 
		// Prepare a list of start_id, this will be used to create a taglink buffer, which is used
		// to create tag links for each start
		$linked_start_ids = '';
		foreach ($start_summaries as $key => $value) {
			$linked_start_ids[] = $value['start_id'];
		}
		
		if (icms_get_module_status("sprockets") && !empty($linked_start_ids))
		{
			$linked_start_ids = '(' . implode(',', $linked_start_ids) . ')';

			// Prepare multidimensional array of tag_ids with start_id (iid) as key
			$taglink_buffer = $start_tag_id_buffer = array();
			$criteria = new  icms_db_criteria_Compo();
			$criteria->add(new icms_db_criteria_Item('mid', icms::$module->getVar('mid')));
			$criteria->add(new icms_db_criteria_Item('item', 'start'));
			$criteria->add(new icms_db_criteria_Item('iid', $linked_start_ids, 'IN'));
			$criteria->add(new icms_db_criteria_Item('tid', $sprockets_tag_ids, 'IN'));
			$taglink_buffer = $sprockets_taglink_handler->getObjects($criteria, TRUE, TRUE);
			unset($criteria);

			// Build tags, with URLs for navigation
			foreach ($taglink_buffer as $key => $taglink) {

				if (!array_key_exists($taglink->getVar('iid'), $start_tag_id_buffer)) {
					$start_tag_id_buffer[$taglink->getVar('iid')] = array();
				}
				$start_tag_id_buffer[$taglink->getVar('iid')][] = '<a class="label label-info" href="' . CMS_URL . 
						'archiviert_start.php?tag_id=' . $taglink->getVar('tid') . '" title="' 
						. _CO_CMS_TAGS_ALL_CONTENTS_ON . ' '
						. $sprockets_tag_buffer[$taglink->getVar('tid')]
						.' ' . _CO_CMS_TAGS_ALL_SHOW . '">' 
						. $sprockets_tag_buffer[$taglink->getVar('tid')]
						. '</a>';
			}

			// Convert the tag arrays into strings for easy handling in the template
			foreach ($start_tag_id_buffer as $key => &$value) 
			{
				$value = implode(' ', $value);
			}

			// Assign each subarray of tags to the matching cms, using the item id as marker
			foreach ($start_summaries as $key => &$value) {
				if (!empty($start_tag_id_buffer[$value['start_id']]))
				{
					$value['tags'] = $start_tag_id_buffer[$value['start_id']];
				}
			}
		}
		
		// Prepare start for display
		foreach ($start_summaries as &$start)
		{			
			// Ajust logo paths to allow dynamic image resizing
			if (!empty($start['logo']))
			$start['logo'] = $document_root . 'uploads/' . $directory_name . '/start/'
				. $start['logo'];
			
			// Alter the itemUrl to point at the archiviert cms page, rather than the cms page
			$start['itemUrl'] = str_replace('start.php', 'archiviert_start.php', $start['itemUrl']);
		}
		$icmsTpl->assign('start_summaries', $start_summaries);
		
		// Adjust pagination for tag, if present
		if (!empty($clean_tag_id))
		{
			$extra_arg = 'tag_id=' . $clean_tag_id;
		}
		else
		{
			$extra_arg = TRUE;
		}
		
		// Pagination control
		$pagenav = new icms_view_PageNav($start_count, icms::$module->config['number_of_cms_per_page'],
				$clean_start, 'start', $extra_arg);
		$icmsTpl->assign('cms_navbar', $pagenav->renderNav());
	}
	else 
	{
		//////////////////////////////////////////////////////////////////////////////
		////////// View cms in compact table, optionally filter by tag //////////
		//////////////////////////////////////////////////////////////////////////////
		
		$tagged_start_list = '';
		
		if ($clean_tag_id && icms_get_module_status("sprockets")) 
		{
			// Get a list of start IDs belonging to this tag
			$criteria = new icms_db_criteria_Compo();
			$criteria->add(new icms_db_criteria_Item('tid', $clean_tag_id));
			$criteria->add(new icms_db_criteria_Item('mid', icms::$module->getVar('mid')));
			$criteria->add(new icms_db_criteria_Item('item', 'start'));
			$criteria->setSort('date');
			$criteria->setOrder('DESC');
			$taglink_array = $sprockets_taglink_handler->getObjects($criteria);
			foreach ($taglink_array as $taglink) {
				$tagged_start_list[] = $taglink->getVar('iid');
			}
			$tagged_start_list = "('" . implode("','", $tagged_start_list) . "')";
			unset($criteria);			
		}
		$criteria = new icms_db_criteria_Compo();
		if (!empty($tagged_start_list))
		{
			$criteria->add(new icms_db_criteria_Item('start_id', $tagged_start_list, 'IN'));
		}
		$criteria->add(new icms_db_criteria_Item('beendet', '1'));
		$criteria->add(new icms_db_criteria_Item('online_status', '1'));
		$criteria->setSort('date');
		$criteria->setOrder('DESC');
		
		// Retrieve the table
		$objectTable = new icms_ipf_view_Table($cms_start_handler, $criteria, array());
		$objectTable->isForUserSide();
		$objectTable->addQuickSearch(array('title','description','extended_text'));
		$objectTable->addColumn(new icms_ipf_view_Column("title", "left", "100", 'getItemLink'));
		$objectTable->addColumn(new icms_ipf_view_Column("date", "center", "20"));
		$objectTable->addColumn(new icms_ipf_view_Column("last_update", "center", "20"));
		$objectTable->addColumn(new icms_ipf_view_Column("counter", "center", "20"));
		$objectTable->setDefaultSort('date');
		$objectTable->setDefaultOrder('DESC');
		$icmsTpl->assign("cms_start_table", $objectTable->fetch());
	}
	
	//comments index page
	if ($cmsConfig['com_rule']) {
		$icmsTpl->assign('cms_post_comment', TRUE);
		include_once ICMS_ROOT_PATH . '/include/comment_view.php';
	}
}

// Breadcrumb	
if (icms::$module->config['show_breadcrumb'])
{
	$icmsTpl->assign("show_breadcrumb", icms::$module->config['show_breadcrumb']);
	$icmsTpl->assign("cms_module_home", '<a href="' . ICMS_URL . "/modules/" 
			. icms::$module->getVar("dirname") . '/">' . icms::$module->getVar("name") . "</a>");
	if ($cms_tag_name)
	{
		$icmsTpl->assign("cms_archiviert_path", '<a href="' . ICMS_URL . "/modules/"
		. icms::$module->getVar("dirname") . '/archiviert_start.php">'
		. _CO_CMS_START_BEENDET . "</a>");
	}
	else
	{
		$icmsTpl->assign("cms_archiviert_path", '<a href="' . ICMS_URL . "/modules/"
		. icms::$module->getVar("dirname") . '/archiviert_start.php">'
		. _CO_CMS_START_BEENDET . "</a>");
	}
	
}

// Page title
$icmsTpl->assign("cms_page_title", _CO_CMS_BEENDET_CMS);

include_once "footer.php";