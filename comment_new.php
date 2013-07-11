<?php
/**
 * New comment form
 *
 * This file holds the configuration information of this module
 *
 * @copyright	(c) by René Sato
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		佐藤レネー René Sato <rene.sato@gmx.de>
 * @package		cms
 * @version		$sato-san$
*/

include_once "header.php";
$com_itemid = isset($_GET["com_itemid"]) ? (int)$_GET["com_itemid"] : 0;
if ($com_itemid > 0) {
	$cms_start_handler = icms_getModuleHandler("start", basename(dirname(__FILE__)), "cms");
	$startObj = $cms_start_handler->get($com_itemid);
	
	if ($startObj && !$startObj->isNew()) {
		$bodytext = $startObj->getVar('description');
		$com_replytext = '';
		if ($bodytext != '') {
			$com_replytext .= $bodytext;
		}
		$com_replytitle = $startObj->getVar('title');
		include_once ICMS_ROOT_PATH .'/include/comment_new.php';
	}
}