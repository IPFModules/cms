<?php
/**
 * Class representing cms start objects
 *
 * @copyright	Copyright Madfish (Simon Wilkinson) 2012
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
 * @package		cms
 * @version		$sato-san$
*/

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

class mod_cms_Start extends icms_ipf_seo_Object
{
	/**
	 * Constructor
	 *
	 * @param mod_cms_Start $handler Object handler
	 */
	public function __construct(&$handler)
	{		
		icms_ipf_object::__construct($handler);
		
		$sprocketsModule = icms::handler("icms_module")->getByDirname("sprockets");
		
		$this->quickInitVar("start_id", XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar("title", XOBJ_DTYPE_TXTBOX, TRUE);
		$this->quickInitVar("logo", XOBJ_DTYPE_IMAGE, FALSE);
		$this->quickInitVar("website", XOBJ_DTYPE_TXTBOX, FALSE);
		$this->quickInitVar("beendet", XOBJ_DTYPE_TXTBOX, TRUE, FALSE, FALSE, 0);
		$this->hideFieldFromForm(array("beendet"));
		$this->initNonPersistableVar('tag', XOBJ_DTYPE_INT, 'tag', FALSE, FALSE, FALSE, TRUE);
		$this->quickInitVar("description", XOBJ_DTYPE_TXTAREA, TRUE);
		$this->quickInitVar("extended_text", XOBJ_DTYPE_TXTAREA, FALSE);
		$this->quickInitVar("history", XOBJ_DTYPE_TXTAREA, FALSE);
		$this->quickInitVar("creator", XOBJ_DTYPE_INT, TRUE, FALSE, FALSE, 1); //make Admin as default
		$this->quickInitVar("date", XOBJ_DTYPE_LTIME, TRUE);
		$this->quickInitVar("last_update", XOBJ_DTYPE_LTIME, TRUE);
		$this->quickInitVar('start_comments', XOBJ_DTYPE_INT); //commets
		$this->hideFieldFromForm('start_comments');
		$this->hideFieldFromSingleView('start_comments');
		//$this->quickInitVar("weight", XOBJ_DTYPE_INT, TRUE, FALSE, FALSE, 0); // ausgeschaltet, da Sortierung nach Datum
		$this->quickInitVar("online_status", XOBJ_DTYPE_INT, TRUE, FALSE, FALSE, 1);
		$this->initCommonVar("counter");
		$this->initCommonVar("dohtml");
		$this->initCommonVar("dobr");
		$this->initCommonVar("doimage");
		$this->initCommonVar("dosmiley");
		$this->setControl("logo", "image");
		$this->setControl("beendet", "yesno");
		$this->setControl("creator", "user");
		$this->setControl("online_status", "yesno");
		
		// Set controls: Allow WYSIWYG editor support in text areas
		$this->setControl("description", "dhtmltextarea");
		$this->setControl("extended_text", "dhtmltextarea");
		$this->setControl("history", "dhtmltextarea");
		
		// Set image path
		$this->setControl('logo', array('name' => 'image'));
		$url = ICMS_URL . '/uploads/' . basename(dirname(dirname(__FILE__))) . '/';
		$path = ICMS_ROOT_PATH . '/uploads/' . basename(dirname(dirname(__FILE__))) . '/';
		$this->setImageDir($url, $path);
		
		// Only display the tag field if the sprockets module is installed
		if (icms_get_module_status("sprockets"))
		{
			$this->setControl('tag', array(
			'name' => 'selectmulti',
			'itemHandler' => 'tag',
			'method' => 'getTags',
			'module' => 'sprockets'));
		}
		else 
		{
			$this->hideFieldFromForm('tag');
			$this->hideFieldFromSingleView ('tag');
		}

		// Intialise SEO functionality
		$this->initiateSEO();
	}

	/**
	 * Overriding the icms_ipf_Object::getVar method to assign a custom method on some
	 * specific fields to handle the value before returning it
	 *
	 * @param str $key key of the field
	 * @param str $format format that is requested
	 * @return mixed value of the field that is requested
	 */
	public function getVar($key, $format = "s")
	{
		if ($format == "s" && in_array($key, array("beendet", "online_status")))
		{
			return call_user_func(array ($this,	$key));
		}
		return parent::getVar($key, $format);
	}
	
	/**
	 * Returns a weight control for the start admin table view
	 * @return mixed
	 */
	public function getWeightControl()
	{
		$control = new icms_form_elements_Text('','weight[]',5,7,$this->getVar('weight', 'e'));
		$control->setExtra('style="text-align:center;"');
		return $control->render();
	}
	
	/**
	 * Converts beendet to human readable icon with toggle link
	 * 
	 * @return string
	 */
	public function beendet()
	{
		$beendet = $this->getVar('beendet', 'e');
		if ($beendet == TRUE) 
		{
			return '<a href="' . ICMS_URL . '/modules/' . basename(dirname(dirname(__FILE__)))
				. '/admin/start.php?start_id=' . $this->getVar('start_id') . '&amp;op=changeBeendet" title="' . _CO_CMS_ARCHIVED_YES . '">
				<img src="' . ICMS_IMAGES_SET_URL . '/actions/button_ok.png" alt="' . _CO_CMS_ARCHIVED_YES . '" /></a>';
		}
		else
		{
			return '<a href="' . ICMS_URL . '/modules/' . basename(dirname(dirname(__FILE__)))
				. '/admin/start.php?start_id=' . $this->getVar('start_id') . '&amp;op=changeBeendet" title="' . _CO_CMS_ARCHIVED_NO . '">
				<img src="' . ICMS_IMAGES_SET_URL . '/actions/button_cancel.png" alt="' . _CO_CMS_ARCHIVED_NO . '" /></a>';
		}
	}

	/**
	 * Converts online_status to human readable icon with toggle link
	 * 
	 * @return string
	 */
	public function online_status()
	{
		$online_status = $this->getVar('online_status', 'e');
		if ($online_status == TRUE) 
		{
			return '<a href="' . ICMS_URL . '/modules/' . basename(dirname(dirname(__FILE__)))
				. '/admin/start.php?start_id=' . $this->getVar('start_id') . '&amp;op=visible" title="' . _CO_CMS_START_ONLINE . '">
				<img src="' . ICMS_IMAGES_SET_URL . '/actions/button_ok.png" alt="' . _CO_CMS_START_ONLINE . '" /></a>';
		}
		else
		{
			return '<a href="' . ICMS_URL . '/modules/' . basename(dirname(dirname(__FILE__)))
				. '/admin/start.php?start_id=' . $this->getVar('start_id') . '&amp;op=visible" title="' . _CO_CMS_START_OFFLINE . '">
				<img src="' . ICMS_IMAGES_SET_URL . '/actions/button_cancel.png" alt="' . _CO_CMS_START_OFFLINE . '" /></a>';
		}
	}
		
	/**
	 * Load tags linked to this start
	 *
	 * @return void
	 */
	public function loadTags()
	{
		$ret = '';
		
		if (!isset($sprocketsModule))
		{
			$sprocketsModule = icms::handler("icms_module")->getByDirname("sprockets");
		}
		
		if (icms_get_module_status("sprockets")) {
			$sprockets_taglink_handler = icms_getModuleHandler('taglink',
					$sprocketsModule->getVar('dirname'), 'sprockets');
			$ret = $sprockets_taglink_handler->getTagsForObject($this->id(), $this->handler);
			$this->setVar('tag', $ret);
		}
	}
	
	/**
	* Retrieve start comment info (number of comments)
	*
	* @return str start comment info
	*/
	function getCommentsInfo() {
		$start_comments = $this->getVar('start_comments');
		if ($start_comments) {
			return '<a class="comment_jo" href="' . $this->getItemLink(true) . '#comments_container" title="' . _CO_CMS_COMMENTS_TITLE . ' ' . $this->getVar('title') . '">' . sprintf(_CO_CMS_COMMENTS_INFO, $start_comments) . '</a>';
		} else {
			return '<a class="comment_no" href="' . $this->getItemLink(true) . '#comments_container" title="' . _CO_CMS_COMMENTS_TITLE . ' ' . $this->getVar('title') . '">' . sprintf(_CO_CMS_NO_COMMENT, $start_comments) . '</a>';
		}
	}
	
	//detailpage content preview within the ACP (Aktions)
	public function getViewItemLink() {
		$ret = '<a href="' . CMS_ADMIN_URL . 'start.php?op=view&amp;start_id=' . $this->getVar('start_id', 'e') . '" title="' . _CO_CMS_START_VIEW . '"><img src="' . ICMS_IMAGES_SET_URL . '/actions/viewmag.png" /></a>';
			return $ret;
	}
	
	//detailpage from the ACP to the front end
	function getPreviewItemLink() {
		$short_url = $this->short_url();
		if (!empty($short_url))
		{
			$seo_url = '<a href="' . $this->getItemLink(TRUE) . '&amp;seite=' . $this->short_url() 
					. '">' . $this->getVar('title', 'e') . '</a>';
		}
		else
		{
			$seo_url = $this->getItemLink(FALSE);
		}
		
		return $seo_url;
	}
	
	/**
	* Overridding IcmsPersistable::toArray() method to add a few info
	*
	* @return array of article info
	*/
	function toArray() {
		$ret = parent::toArray();
	
		$ret['start_comment_info'] = $this->getCommentsInfo();
		$ret['itemLink'] = $this->getItemLink();
		
		//icons for the frontend
		$ret['editItemLink'] = $this->getEditItemLink(FALSE, TRUE, TRUE);
		$ret['deleteItemLink'] = $this->getDeleteItemLink(FALSE, TRUE, TRUE);
		return $ret;
	}
	
	
}