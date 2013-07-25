<?php
/**
 * Admin page to manage cms
 *
 * List, add, edit and delete start objects
 *
 * @copyright	Copyright Madfish (Simon Wilkinson) 2012
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
 * @package		cms
 * @version		$sato-san$
*/

/**
 * Edit a Start
 *
 * @param int $start_id start to be edited
*/
function editstart($start_id = 0, $clone = false)
{
	global $cms_start_handler, $icmsModule, $icmsAdminTpl, $cmsConfig;

	$startObj = $cms_start_handler->get($start_id);
	$sprocketsModule = icms::handler("icms_module")->getByDirname("sprockets");

	if (!$clone && !$startObj->isNew()) {
		
		$startObj->loadTags();
		$startObj->loadCategories();
		
		$startObj->setVar("last_update", time());
		icms::$module->displayAdminMenu(0, _AM_CMS_CMS . " > " . _CO_ICMS_EDITING);
		$sform = $startObj->getForm(_AM_CMS_START_EDIT, 'addstart');
		$sform->assign($icmsAdminTpl);
	}
	elseif (!$startObj->isNew() && $clone)
	{

		$startObj->loadTags();
		$startObj->loadCategories();

		//show the new date in the form
		$startObj->setVar("last_update", time());
		$startObj->setVar("start_id", 0);
		$startObj->setVar("counter", 0);
		$startObj->setVar("notification_sent", 0);
		$startObj->setVar("start_comments", 0);
		$startObj->setVar("short_url", '');
		$startObj->setVar("meta_description", '');
		$startObj->setVar("meta_keywords", '');
		$startObj->setVar("date", time ());
		$startObj->setVar("last_update", 0);
		$startObj->setNew();
		$icmsModule->displayAdminMenu(0, _AM_CMS_CMS . " > " . _AM_CMS_START_CLONING);
		$sform = $startObj->getForm(_AM_CMS_START_CLONE, "addstart");

	}
	else
	{
		//set the username as default within the form
		$uid = (is_object(icms::$user)) ? icms::$user->getVar("uid") : 0;
		$startObj->setVar("creator", $uid);

		$icmsModule->displayAdminMenu(0, _AM_CMS_CMS . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $startObj->getForm(_AM_CMS_START_CREATE, "addstart");
		$member_handler = icms::handler('icms_member');
		$grantedView = array_keys($member_handler->getGroupList());
		$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item("groupid", ICMS_GROUP_ANONYMOUS, "!="));
		$criteria->add(new icms_db_criteria_Item("groupid", ICMS_GROUP_USERS, "!="));
		$grantedSubmit = array_keys($member_handler->getGroupList($criteria));
		unset($member_handler, $criteria);
		if($grantedView) {
			$sform->setElementValue("start_perm_read", $grantedView);
		}
		if($grantedSubmit) {
			$sform->setElementValue("start_perm_edit", $grantedSubmit);
		}

	}
	$sform->assign($icmsAdminTpl);
	$icmsAdminTpl->display("db:cms_admin_start.html");
}

include_once "admin_header.php";

$clean_op = "";
$cms_start_handler = icms_getModuleHandler("start", basename(dirname(dirname(__FILE__))), "cms");
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ("mod", "changedField", "addstart", "del", "view", "clone", "changeWeight", "changeBeendet", "visible", "");

if (isset($_GET["op"])) $clean_op = htmlentities($_GET["op"]);
if (isset($_POST["op"])) $clean_op = htmlentities($_POST["op"]);

$clean_start_id = isset($_GET["start_id"]) ? (int)$_GET["start_id"] : 0 ;
$clean_tag_id = isset($_GET['tag_id']) ? (int)$_GET['tag_id'] : 0 ;

if (in_array($clean_op, $valid_op, TRUE))
{
	switch ($clean_op)
	{
		//clone a content
		case "clone" :
			icms_cp_header();
			editstart($clean_start_id, true);
			break;

		case "mod":
		case "changedField":
			icms_cp_header();
			editstart($clean_start_id);
			break;

		case "addstart":
			$controller = new icms_ipf_Controller($cms_start_handler);
			$controller->storeFromDefaultForm(_AM_CMS_START_CREATED, _AM_CMS_START_MODIFIED);
			break;

		case "del":
			$controller = new icms_ipf_Controller($cms_start_handler);
			$controller->handleObjectDeletion();
			break;

		case "view":
			$startObj = $cms_start_handler->get($clean_start_id);
			icms_cp_header();
			$startObj->displaySingleObject();
			break;

		case "changeWeight":
			foreach ($_POST['mod_cms_Start_objects'] as $key => $value)
			{
				$changed = TRUE;
				$itemObj = $cms_start_handler->get($value);

				if ($itemObj->getVar('weight', 'e') != $_POST['weight'][$key])
				{
					$itemObj->setVar('weight', intval($_POST['weight'][$key]));
					$changed = TRUE;
				}
				if ($changed)
				{
					$cms_start_handler->insert($itemObj);
				}
			}
			$ret = '/modules/' . basename(dirname(dirname(__FILE__))) . '/admin/start.php';
			redirect_header(ICMS_URL . $ret, 2, _AM_CMS_START_WEIGHTS_UPDATED);
			break;

		case "visible":
			$visibility = $cms_start_handler->toggleOnlineStatus($clean_start_id, 'online_status');
			$ret = '/modules/' . basename(dirname(dirname(__FILE__))) . '/admin/start.php';
			if ($visibility == 0)
			{
				redirect_header(ICMS_URL . $ret, 2, _AM_CMS_START_INVISIBLE);
			}
			else
			{
				redirect_header(ICMS_URL . $ret, 2, _AM_CMS_START_VISIBLE);
			}
			break;

		case "changeBeendet":
			$fertigstellungStatus = $cms_start_handler->toggleFertigstellung($clean_start_id, 'beendet');
			$ret = '/modules/' . basename(dirname(dirname(__FILE__))) . '/admin/start.php';
			if ($fertigstellungStatus == 0)
			{
				redirect_header(ICMS_URL . $ret, 2, _AM_CMS_START_ACTIVE);
			}
			else
			{
				redirect_header(ICMS_URL . $ret, 2, _AM_CMS_START_ARCHIVIERT);
			}
			break;

		default:
			icms_cp_header();
			$icmsModule->displayAdminMenu(0, _AM_CMS_CMS);

			// Display a single start, if a start_id is set
			if ($clean_start_id)
			{
				$startObj = $cms_start_handler->get($clean_start_id);
				$startObj->displaySingleObject();
			}

			// Display a tag + category select filter (if the Sprockets module is installed)
			if (icms_get_module_status("sprockets")) {

				////////////////////////////////////
				////////// TAG SELECT BOX //////////
				////////////////////////////////////
				$tag_select_box = '';
				$taglink_array = $tagged_start_list = $tagged_article_list = array();
				$sprockets_tag_handler = icms_getModuleHandler('tag', 'sprockets', 'sprockets');
				$sprockets_taglink_handler = icms_getModuleHandler('taglink', 'sprockets', 'sprockets');

				$tag_select_box = $sprockets_tag_handler->getTagSelectBox('start.php', $clean_tag_id,
					_AM_CMS_START_ALL_CMS, FALSE, icms::$module->getVar('mid'));

				if ($clean_tag_id) {

					// get a list of start IDs belonging to this tag
					$criteria = new icms_db_criteria_Compo();
					$criteria->add(new icms_db_criteria_Item('tid', $clean_tag_id));
					$criteria->add(new icms_db_criteria_Item('mid', icms::$module->getVar('mid')));
					$criteria->add(new icms_db_criteria_Item('item', 'start'));
					$taglink_array = $sprockets_taglink_handler->getObjects($criteria);

					$criteria = new icms_db_criteria_Compo();
					foreach ($taglink_array as $taglink) {
					$tagged_start_list[] = $taglink->getVar('iid');
					}
					if ($tagged_start_list) {
						$tagged_start_list = "('" . implode("','", $tagged_start_list) . "')";
						// use the list to filter the persistable table
						$criteria->add(new icms_db_criteria_Item('start_id', $tagged_start_list, 'IN'));
					}
				}

				/////////////////////////////////////////
				////////// CATEGORY SELECT BOX //////////
				/////////////////////////////////////////
				$category_select_box = '';
				$taglink_array = $categorised_start_list = array();

				$category_select_box = $sprockets_tag_handler->getCategorySelectBox('start.php',
							$clean_tag_id, _AM_CMS_START_ALL_CATEGORIES, icms::$module->getVar('mid'));

				if ($clean_tag_id)
				{
					// Get a list of start IDs belonging to this tag
					$criteria = new icms_db_criteria_Compo();
					$criteria->add(new icms_db_criteria_Item('tid', $clean_tag_id));
					$criteria->add(new icms_db_criteria_Item('mid', icms::$module->getVar('mid')));
					$criteria->add(new icms_db_criteria_Item('item', 'start'));
					$taglink_array = $sprockets_taglink_handler->getObjects($criteria);
					foreach ($taglink_array as $taglink) {
						$categorised_start_list[] = $taglink->getVar('iid');
					}
					$categorised_start_list = "('" . implode("','", $categorised_start_list) . "')";

					// Use the list to filter the persistable table
					$criteria = new icms_db_criteria_Compo();
					$criteria->add(new icms_db_criteria_Item('start_id', $categorised_start_list, 'IN'));
				}
			}

			// Display the tag/category select boxes in a table, side by side to save space
			if (!empty($tag_select_box) || !empty($category_select_box))
			{
				$select_box_code = '<table><tr>';
				if (!empty($tag_select_box)) {
					$select_box_code .= '<td><h3>' . _AM_CMS_START_FILTER_BY_TAG . '</h3></td>';
				}
				if (!empty($category_select_box)) {
					$select_box_code .= '<td><h3>' . _AM_CMS_START_FILTER_BY_CATEGORY . '</h3></td>';
				}
				$select_box_code .= '</tr><tr>';
				if (!empty($tag_select_box)) {
					$select_box_code .= '<td>' . $tag_select_box . '</td>';
				}
				if (!empty($category_select_box)) {
					$select_box_code .= '<td>' . $category_select_box . '</td>';
				}
				echo $select_box_code . '</tr></table>';
			}

			if (empty($criteria)) {
				$criteria = null;
			}

			$objectTable = new icms_ipf_view_Table($cms_start_handler, $criteria);
			$objectTable->addQuickSearch(array('title','subtitle','description','extended_text'));

			//get Preview
			$objectTable->addColumn(new icms_ipf_view_Column("title", FALSE, FALSE, 'getPreviewItemLink'));

			$objectTable->addColumn(new icms_ipf_view_Column("date", "center", "130"));
			$objectTable->addColumn(new icms_ipf_view_Column("last_update", "center", "130"));
			$objectTable->addColumn(new icms_ipf_view_Column("counter", "center", "130"));
			//$objectTable->addColumn(new icms_ipf_view_Column('weight', 'center', TRUE, 'getWeightControl'));
			//$objectTable->addActionButton("changeWeight", FALSE, _SUBMIT);
			$objectTable->addColumn(new icms_ipf_view_Column("beendet", "center", "100", TRUE));
			$objectTable->addColumn(new icms_ipf_view_Column("online_status", "center", "100", TRUE));
			$objectTable->setDefaultSort('last_update'); //changed from date to last_update
			$objectTable->setDefaultOrder('DESC'); //changed from ASC to DESC
			$objectTable->addIntroButton("addstart", "start.php?op=mod", _AM_CMS_START_CREATE);
			$objectTable->addFilter('beendet', 'beendet_filter');
			$objectTable->addFilter('online_status', 'online_status_filter');

			//make a link for the detailpage within the ACP
			$objectTable->addCustomAction( 'getViewItemLink' );
			//show clone icon in the ACP
			$objectTable->addCustomAction('getCloneItemLink');

			$icmsAdminTpl->assign("cms_start_table", $objectTable->fetch());
			$icmsAdminTpl->display("db:cms_admin_start.html");

			break;
	}
	icms_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */
