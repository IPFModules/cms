<?php
/**
 * Common file of the module included on all pages of the module
 *
 * @copyright	Copyright Madfish (Simon Wilkinson) 2012
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
 * @package		cms
 * @version		$sato-san$
*/

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

if (!defined("CMS_DIRNAME")) define("CMS_DIRNAME", $modversion["dirname"] = basename(dirname(dirname(__FILE__))));
if (!defined("CMS_URL")) define("CMS_URL", ICMS_URL."/modules/".CMS_DIRNAME."/");
if (!defined("CMS_ROOT_PATH")) define("CMS_ROOT_PATH", ICMS_ROOT_PATH."/modules/".CMS_DIRNAME."/");
if (!defined("CMS_IMAGES_URL")) define("CMS_IMAGES_URL", CMS_URL."images/");
if (!defined("CMS_ADMIN_URL")) define("CMS_ADMIN_URL", CMS_URL."admin/");

// Include the common language file of the module
icms_loadLanguageFile("cms", "common");

// comments
$cmsConfig = icms_getModuleConfig( CMS_DIRNAME );
