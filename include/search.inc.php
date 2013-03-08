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
	global $icmsConfigSearch;
	
	$startArray = $ret = array();
	$count = $number_to_process = $starts_left = '';
	
	$cms_start_handler = icms_getModuleHandler("start", basename(dirname(dirname(__FILE__))), "cms");
	$startArray = $cms_start_handler->getCmsForSearch($queryarray, $andor, $limit, $offset, $userid);
	
	// Count the number of records
	$count = count($startArray);
	
	// The number of records actually containing start objects is <= $limit, the rest are padding
	$starts_left = ($count - ($offset + $icmsConfigSearch['search_per_page']));
	if ($starts_left < 0) {
		$number_to_process = $icmsConfigSearch['search_per_page'] + $starts_left; // $starts_left is negative
	} else {
		$number_to_process = $icmsConfigSearch['search_per_page'];
	}

	// Process the actual starts (not the padding)
	for ($i = 0; $i < $number_to_process; $i++)
	{
		$item['image'] = "images/start.png";
		$item['link'] = $startArray[$i]->getItemLink(TRUE) . '&amp;title=' . $startArray[$i]->short_url();
		$item['title'] = $startArray[$i]->getVar("title");
		$item['time'] = $startArray[$i]->getVar("date", "e");
		$item['uid'] = $startArray[$i]->getVar("creator");
		$ret[] = $item;
		unset($item);
	}

	// Restore the padding (required for 'hits' information and pagination controls). The offset
	// must be padded to the left of the results, and the remainder to the right or else the search
	// pagination controls will display the wrong results (which will all be empty).
	// Left padding = -($limit + $offset)
	$ret = array_pad($ret, -($offset + $number_to_process), 1);
	
	// Right padding = $count
	$ret = array_pad($ret, $count, 1);

	return $ret;
}