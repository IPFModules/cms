<?php
/**
 * Footer page included at the end of each page on user side of the mdoule
 *
 * @copyright	Copyright Madfish (Simon Wilkinson) 2012
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
 * @package		cms
 * @version		$sato-san$
*/

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

$icmsTpl->assign("cms_adminpage", "<a href='" . ICMS_URL . "/modules/" . icms::$module->getVar("dirname") . "/admin/index.php'>" ._MD_CMS_ADMIN_PAGE . "</a>");
$icmsTpl->assign("cms_is_admin", icms_userIsAdmin(CMS_DIRNAME));
$icmsTpl->assign('cms_url', CMS_URL);
$icmsTpl->assign('cms_images_url', CMS_IMAGES_URL);

$xoTheme->addStylesheet(CMS_URL . 'module' . ((defined("_ADM_USE_RTL") && _ADM_USE_RTL) ? '_rtl' : '') . '.css');

include_once ICMS_ROOT_PATH . '/footer.php';