<?php
/**
 * Configuring the amdin side menu for the module
 *
 * @copyright	Copyright Madfish (Simon Wilkinson) 2012
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
 * @package		cms
 * @version		$sato-san$
*/

global $icmsConfig;

$adminmenu[] = array(
	"title" => _MI_CMS_CMS,
	"link" => "admin/start.php");

$module = icms::handler("icms_module")->getByDirname(basename(dirname(dirname(__FILE__))));

$headermenu[] = array(
	"title" => _CO_ICMS_GOTOMODULE,
	"link" => ICMS_URL . "/modules/" . $module->getVar("dirname") . "/");
$headermenu[] = array(
	"title" => _PREFERENCES,
	"link" => "../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" 
		. $module->getVar("mid"));
$headermenu[] = array(
	"title" => _MI_CMS_BLOCKS,
	"link" => ICMS_URL . "/modules/system/admin.php?fct=blocksadmin&filtersel=mid&filtersel2="
		. $module->getVar("mid"));
$headermenu[] = array(
	"title" => _MI_CMS_TEMPLATES,
	"link" => '../../system/admin.php?fct=tplsets&op=listtpl&tplset=' 
		. $icmsConfig['template_set'] . '&moddir=' . $module->getVar("dirname"));
$headermenu[] = array(
	"title" => _MI_CMS_COMMENTS,
	"link" => ICMS_URL . "/modules/system/admin.php?module=" . icms::$module -> getVar("mid") 
		. "&status=0&limit=100&fct=comments&selsubmit=Go");
$headermenu[] = array(
	"title" => _CO_ICMS_UPDATE_MODULE,
	"link" => ICMS_URL . "/modules/system/admin.php?fct=modulesadmin&amp;op=update&amp;module=" 
		. $module->getVar("dirname"));
$headermenu[] = array(
	"title" => _MODABOUT_ABOUT,
	"link" => ICMS_URL . "/modules/" . $module->getVar("dirname") . "/admin/about.php");
$headermenu[] = array(
	"title" => '<b>' . _MI_CMS_MANUAL . '</b>',
	"link" => ICMS_URL . "/modules/" . $module->getVar("dirname") . "/admin/manual.php");

unset($module_handler);