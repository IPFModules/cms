<?php
/**
 * Admin index page of the module
 *
 * Including the start page
 *
 * @copyright	Copyright Madfish (Simon Wilkinson) 2012
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
 * @package		cms
 * @version		$sato-san$
*/

// this two lines solve a problem with the theme-swicher
include_once "../../mainfile.php";
include_once ICMS_ROOT_PATH . "/header.php";

header("location: start.php");
exit;