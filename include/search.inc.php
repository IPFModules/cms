<?php
/**
 * cms version infomation
 *
 * This file holds the configuration information of this module
 *
 * @copyright	Copyright Madfish (Simon Wilkinson) 2012
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
 * @package		cms
 * @version		$sato-san$
*/

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

function cms_search($queryarray, $andor, $limit, $offset, $userid)
{
	$cms_start_handler = icms_getModuleHandler("start", basename(dirname(dirname(__FILE__))), "cms");
	$startArray = $cms_start_handler->getCmsForSearch($queryarray, $andor, $limit, $offset, $userid);
	$ret = array();

	foreach ($startArray as $start) 
	{
		$item['image'] = "images/start.png";
		$item['link'] = $start->getItemLink(TRUE) . '&amp;seite=' . $start->short_url();
		$item['title'] = $start->getVar("title");
		$item['time'] = $start->getVar("date", "e");
		$item['uid'] = $start->getVar("creator");
		$ret[] = $item;
		unset($item);
	}

	return $ret;
}