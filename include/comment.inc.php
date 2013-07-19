<?php
/**
 * Comment include file
 *
 * File holding functions used by the module to hook with the comment system of ImpressCMS
 *
 * @copyright	(c) by René Sato
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		佐藤レネー René Sato <rene.sato@gmx.de>
 * @package		cms
 * @version		$sato-san$
*/

function cms_com_update($start_id, $total_num) {
    $cms_start_handler = icms_getModuleHandler("start", basename(dirname(dirname(__FILE__))), "cms");
    $cms_start_handler->updateComments($start_id, $total_num);
}

function cms_com_approve(&$comment) {
    // notification mail here
}