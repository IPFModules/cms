<?php
/**
 * 
 * File: /print.php
 * 
 * print single start object
 * 
 * @copyright	Copyright QM-B (Steffen Flohrer) 2012
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * ----------------------------------------------------------------------------------------------------------
 * 				Cms
 * @since		1.00
 * @author		QM-B <qm-b@hotmail.de>
 * @version		$Id$
 * @package		cms
 *
 */

include_once 'header.php';

$cms_start_handler = icms_getModuleHandler("start", basename(dirname(__FILE__)), "cms");

$clean_start_id = isset($_GET['start_id']) ? filter_input(INPUT_GET, 'start_id', FILTER_SANITIZE_NUMBER_INT) : 0;
$clean_short_url = isset($_GET['seite'] ) ? filter_input(INPUT_GET, 'seite') : '';

if ($clean_short_url != '' && $clean_start_id == 0) {
	$criteria = new icms_db_criteria_Compo();
	$criteria->add(new icms_db_criteria_Item("short_url", urlencode($clean_short_url)));
	$startObj = $cms_start_handler->getObjects($criteria);
	$startObj = $startObj [0];
	$clean_start_id = $startObj->getVar("start_id", "e");
} else {
	$startObj = $cms_start_handler->get($clean_start_id);
}

if (!$startObj || !is_object($startObj) || $startObj->isNew()) {
	redirect_header(icms_getPreviousPage(), 2, _NOPERM);
}

if (!$startObj->getVar("online_status", "e") == 1){
	redirect_header(icms_getPreviousPage(), 3, _NOPERM);
}

$icmsTpl = new icms_view_Tpl();
global $icmsConfig;

$seite = $startObj->toArray();
$printtitle = $icmsConfig['sitename']." - ". ' > ' . strip_tags($startObj->getVar('title','n' ));

$icmsTpl->assign('printtitle', $printtitle);
$icmsTpl->assign('start', $seite);

$icmsTpl->display('db:cms_print.html');