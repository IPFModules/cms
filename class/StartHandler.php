<?php
/**
 * Classes responsible for managing cms start objects
 *
 * @copyright	Copyright Madfish (Simon Wilkinson) 2012
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
 * @package		cms
 * @version		$sato-san$
*/

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

class mod_cms_StartHandler extends icms_ipf_Handler
{
	private $_contentArray;
	/**
	 * Constructor
	 *
	 * @param icms_db_legacy_Database $db database connection object
	 */
	public function __construct(&$db)
	{
		global $cmsConfig;
		parent::__construct($db, "start", "start_id", "title", "description", "cms");
		$this->enableUpload(array("image/gif", "image/jpeg", "image/pjpeg", "image/png"), 2512000, 3800, 2600);
		/**
		 * hier füge ich die PERM-Namen zu, so dass das system weiß, welche man aufsetzen muss
		 */
		if($cmsConfig['enable_perm'] == 1) {
			$this->addPermission("start_perm_read", _CO_CMS_START_START_PERM_READ);
			$this->addPermission("start_perm_edit", _CO_CMS_START_START_PERM_EDIT);
		}
	}

	public function getContentList($showNull = FALSE) {
		if(!count($this->_contentArray)) {
			$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item("online_status", 1));
			$this->setGrantedObjectsCriteria($criteria, "start_perm_read");
			$contents = $this->getObjects($criteria, TRUE, FALSE);
			if($showNull) {
				$this->_contentArray[0] = '--------------';
			}
			foreach ($contents as $key => $value) {
				$this->_contentArray[$key] = $value['title'];
			}
		}
		return $this->_contentArray;
	}

	/**
	 * Toggles the online_status field and updates the object
	 *
	 * @return null
	 */
	public function toggleOnlineStatus($id)
	{
		$status = '';

		// Load the object that will be manipulated
		$startObj = $this->get($id);

		// Toggle the online status field and update the object
		if ($startObj->getVar('online_status', 'e') == 1) {
			$startObj->setVar('online_status', 0);
			$status = 0;
		} else {
			$startObj->setVar('online_status', 1);
			$status = 1;
		}
		$this->insert($startObj, TRUE);

		return $status;
	}

	/**
	* Toggles the fertigstellung field and updates the object
	*
	* @return null
	*/
	public function toggleFertigstellung($id)
	{
	$status = '';

		// Load the object that will be manipulated
		$startObj = $this->get($id);

		// Toggle the beendet field and update the object
		if ($startObj->getVar('beendet', 'e') == 1) {
				$startObj->setVar('beendet', 0);
		$status = 0;
		} else {
		$startObj->setVar('beendet', 1);
		$status = 1;
			}
		$this->insert($startObj, TRUE);

		return $status;
	}

	/**
	 * Converts beendet value to human readable text
	 *
	 * @return array
	 */
	public function beendet_filter()
	{
		return array(0 => _AM_CMS_START_NO, 1 => _AM_CMS_START_YES);
	}

	/**
	 * Converts status value to human readable text
	 *
	 * @return array
	 */
	public function online_status_filter()
	{
		return array(0 => _AM_CMS_START_OFFLINE, 1 => _AM_CMS_START_ONLINE);
	}

	/**
	 * Provides the global search functionality for the Cms module
	 *
	 * @param array $queryarray
	 * @param string $andor
	 * @param int $limit
	 * @param int $offset
	 * @param int $userid
	 * @return array
	 */
	public function getCmsForSearch($queryarray, $andor, $limit, $offset, $userid)
	{
		$count = $results = '';
		$criteria = new icms_db_criteria_Compo();

		if ($userid != 0)
		{
			$criteria->add(new icms_db_criteria_Item('creator', $userid));
		}

		if ($queryarray)
		{
			$criteriaKeywords = new icms_db_criteria_Compo();
			for ($i = 0; $i < count($queryarray); $i++) {
				$criteriaKeyword = new icms_db_criteria_Compo();
				$criteriaKeyword->add(new icms_db_criteria_Item('title', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
				$criteriaKeyword->add(new icms_db_criteria_Item('subtitle', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
				$criteriaKeyword->add(new icms_db_criteria_Item('description', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
				$criteriaKeyword->add(new icms_db_criteria_Item('extended_text', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR'); //neu hinzugefügt
				$criteriaKeywords->add($criteriaKeyword, $andor);
				unset ($criteriaKeyword);
			}
			$criteria->add($criteriaKeywords);
		}

		$criteria->add(new icms_db_criteria_Item('online_status', TRUE));

		/*
		 * Improving the efficiency of search
		 *
		 * The general search function is not efficient, because it retrieves all matching records
		 * even when only a small subset is required, which is usually the case. The full records
		 * are retrieved so that they can be counted, which is used to display the number of
		 * search results and also to set up the pagination controls. The problem with this approach
		 * is that a search generating a very large number of results (eg. > 650) will crash out.
		 * Maybe its a memory allocation issue, I don't know.
		 *
		 * A better approach is to run two queries: The first a getCount() to find out how many
		 * records there are in total (without actually wasting resources to retrieve them),
		 * followed by a getObjects() to retrieve the small subset that are actually needed.
		 * Due to the way search works, the object array needs to be padded out
		 * with the number of elements counted in order to preserve 'hits' information and to construct
		 * the pagination controls. So to minimise resources, we can just set their values to '1'.
		 *
		 * In the long term it would be better to (say) pass the count back as element[0] of the
		 * results array, but that will require modification to the core and will affect all modules.
		 * So for the moment, this hack is convenient.
		 */

		// Count the number of search results WITHOUT actually retrieving the objects
		$count = $this->getCount($criteria);

		$criteria->setStart($offset);
		$criteria->setSort('title');
		$criteria->setOrder('ASC');

		// Retrieve the subset of results that are actually required.
		// Problem: If show all results # < shallow search #, then the all results preference is
		// used as a limit. This indicates that shallow search is not setting a limit! The largest
		// of these two values should always be used
		if (!$limit) {
			global $icmsConfigSearch;
			$limit = $icmsConfigSearch['search_per_page'];
		}

		$criteria->setLimit($limit);
		$results = $this->getObjects($criteria, FALSE, TRUE);

		// Pad the results array out to the counted length to preserve 'hits' and pagination controls.
		// This approach is not ideal, but it greatly reduces the load for queries with large result sets
		$results = array_pad($results, $count, 1);

		return $results;
	}

	//protected function beforeInsert(& $obj) {
	//	$dsc = $obj->getVar("description", "s");
	//	$dsc = icms_core_DataFilter::checkVar($dsc, "html", "input");
	//	$obj->setVar("description", $dsc);

	//	$body = $obj->getVar("extended_text", "s");
	//	$body = icms_core_DataFilter::checkVar($body , "html", "input");
	//	$obj->setVar("extended_text", $body);
	//	return TRUE;
	//}

	/**
	 * Stores tags when a start is inserted or updated
	 *
	 * @param object $obj CmsStart object
	 * @return bool
	 */
	protected function afterSave(& $obj)
	{
		global $cmsConfig;
		$sprocketsModule = icms::handler("icms_module")->getByDirname("sprockets");

		// Only update the taglinks if the object is being updated from the add/edit form (POST).
		// The taglinks should *not* be updated during a GET request (ie. when the toggle buttons
		// are used to change the Fertigstellung status or online status). Attempting to do so will
		// trigger an error, as the database should not be updated during a GET request.
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && icms_get_module_status("sprockets"))
		{
			$sprockets_taglink_handler = '';
			$sprockets_taglink_handler = icms_getModuleHandler('taglink',
					$sprocketsModule->getVar('dirname'), 'sprockets');

			// Store tags
			$sprockets_taglink_handler->storeTagsForObject($obj, 'tag', 0);

			// Store categories
			$sprockets_taglink_handler->storeTagsForObject($obj, 'category', '1');
		}
		/**
		 * hier füge ich die PERMS hinzu, wenn die config sagt, ohne perm.. dann kann immer jeder lesen, auch wenn später die perms erlaubt werden
		 */
		if($cmsConfig['enable_perm'] == 0) {
			$module = icms::handler('icms_module')->getByDirname(basename(dirname(dirname(__FILE__))));
			$perm_handler = icms::handler('icms_member_groupperm');
			$perm_handler->deleteByModule($module->getVar("mid"), "start_perm_read", $obj->id());
			$group_handler = icms::handler('icms_member_group');
			$groups = $group_handler->getObjects(NULL, TRUE);
			unset($group_handler);
			foreach (array_keys($groups) as $group_id) {
				$perm_handler->addRight("start_perm_read", $obj->id(), $group_id, $module->getVar("mid"));
			}
			$perm_handler->addRight("start_perm_edit", $obj->id(), ICMS_GROUP_ADMIN, $module->getVar("mid"));
			unset($perm_handler, $module);
		}

		//notifications
		//if ($obj->updating_counter)
		//	return true;
		if ((!$obj->getVar("notification_sent", "e") || $obj->getVar("notification_sent", "e") == 0 ) && $obj->getVar("online_status", "e") == TRUE) {
			$obj->sendNotifFillingPublished();
			$obj->setVar("notification_sent", 1);
			$this->insert($obj);
		} else {
			$obj->sendNotifFillingUpdated();
		}
		return TRUE;
	}

	/**
	 * Deletes taglinks when a start is deleted
	 *
	 * @param object $obj CmsStart object
	 * @return bool
	 */
	protected function afterDelete(& $obj)
	{
		$notification_handler = icms::handler('icms_data_notification');
		// delete global notifications
		$notification_handler->unsubscribeByItem($module_id, $start_id);

		$sprocketsModule = $notification_handler = $module_handler = $module = $module_id
				= $category = $item_id = '';

		$sprocketsModule = icms::handler("icms_module")->getByDirname("sprockets");

		if (icms_get_module_status("sprockets"))
		{
			$sprockets_taglink_handler = icms_getModuleHandler('taglink',
					$sprocketsModule->getVar('dirname'), 'sprockets');
			$sprockets_taglink_handler->deleteAllForObject($obj);
		}

		return TRUE;
	}

	/**
	* Update number of comments on a start
	*
	* @param int $start_id id of the start to update
	* @param int $total_num total number of comments so far in this start
	* @return VOID
	*/
	public function updateComments($start_id, $total_num) {
		$startObj = $this->get($start_id);
		if ($startObj && !$startObj->isNew()) {
			$startObj->setVar('start_comments', $total_num);
			$this->insert($startObj, true);
		}
	}
}
