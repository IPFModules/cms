<?php
/**
 * File containing onUpdate and onInstall functions for the module
 *
 * This file is included by the core in order to trigger onInstall or onUpdate functions when needed.
 * Of course, onUpdate function will be triggered when the module is updated, and onInstall when
 * the module is originally installed. The name of this file needs to be defined in the
 * icms_version.php
 *
 * <code>
 * $modversion['onInstall'] = "include/onupdate.inc.php";
 * $modversion['onUpdate'] = "include/onupdate.inc.php";
 * </code>
 *
 * @copyright	Copyright Madfish (Simon Wilkinson) 2012
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
 * @package		cms
 * @version		$sato-san$
*/

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

// this needs to be the latest db version
define('CMS_DB_VERSION', 1);

/**
 * it is possible to define custom functions which will be call when the module is updating at the
 * correct time in update incrementation. Simpy define a function named <direname_db_upgrade_db_version>
 */
/*
function cms_db_upgrade_1() {
}
function cms_db_upgrade_2() {
}
*/

function icms_module_update_cms($module)
{

	// create an uploads directory for logos
	$path = ICMS_ROOT_PATH . '/uploads/' . basename(dirname(dirname(__FILE__)));
	$directory_exists = $file_exists = $writeable = TRUE;

	// check if upload directory exists, make one if not, and write an empty index file
	if (!is_dir($path)) {
		$directory_exists = mkdir($path, 0777);
		$path .= '/index.html';
		
		// add an index file to prevent index lookups
		if (!is_file($path)) {
			$filename = $path;	
			$contents = '<script>history.go(-1);</script>';
			$handle = fopen($filename, 'wb');
			$result = fwrite($handle, $contents);
			fclose($handle);
			chmod($path, 0644);
		}
	}

	// Authorise some image mimetypes for convenience
	cms_authorise_mimetypes();
	
	/**
	* Using the IcmsDatabaseUpdater to automaticallly manage the database upgrade dynamically
	* according to the class defined in the module
	*/
	$icmsDatabaseUpdater = XoopsDatabaseFactory::getDatabaseUpdater();
	$icmsDatabaseUpdater->moduleUpgrade($module);
	return TRUE;
}

function icms_module_install_cms($module)
{
	// insert some licenses so that it is ready for use on installation
	$queries = array();
	
	// a generic tag to hold untagged content
	$queries[] = "INSERT into " . icms::$xoopsDB->prefix('cms_start') . " (
			`start_id`, 
			`title`, 
			`subtitle`, 
			`logo`, 
			`website`, 
			`beendet`, 
			`description`, 
			`extended_text`, 
			`history`, 
			`creator`, 
			`date`, 
			`last_update`, 
			`start_comments`, 
			`online_status`, 
			`counter`, 
			`dohtml`, 
			`doimage`, 
			`dosmiley`, 
			`meta_keywords`, 
			`meta_description`, 
			`short_url`) values 
	(1, 'My first demo content is ready', 'Thank you for installing the cms module', '', 'http://www.impresscms.de', '0', '<p><a href=\"http://www.impresscms.de/content/impresscms-demo-content-cms-module.png\" title=\"ACP of ImpressCMS\" rel=\"lightbox\"><img style=\"float: left; margin: 0px 10px 5px 0px;\" src=\"http://www.impresscms.de/content/impresscms-demo-content-cms-module_thumb.png\" /></a>Vestibulum pretium blandit sem vel consequat. Phasellus convallis pellentesque ipsum ac lobortis. Suspendisse adipiscing magna non tellus ornare vel ullamcorper diam elementum. Nam dictum, diam ac porttitor pellentesque, eros ligula tempor urna, vel porttitor urna magna in sapien? Donec quis dolor risus. Vivamus rutrum tristique lorem, non rutrum felis porta quis. Pellentesque quis nisl et quam tempor ultrices eu in tortor.</p>\r\n<p>Aliquam ipsum dolor, vulputate vel semper nec, mollis quis augue. Nulla id mauris a enim pellentesque malesuada at eget nulla. Pellentesque molestie luctus laoreet. Ut lectus sem, accumsan sit amet 
laoreet sed, dapibus eu tortor. Etiam consectetur, quam a mattis dignissim, mi elit dignissim orci, ut adipiscing urna sem eu lacus. Proin gravida orci id mi suscipit fermentum.</p>', '<p></p>\r\n<p><a href=\"http://www.impresscms.de/content/impresscms-demo-content-cms-module.png\" title=\"ACP of ImpressCMS\" rel=\"lightbox\"><img style=\"float: left; margin: 0px 10px 5px 0px;\" src=\"http://www.impresscms.de/content/impresscms-demo-content-cms-module_thumb.png\" /></a>Here, on the left site is a tumbnail image. If you click on it, will open it and load the right image with a \"lightbox\". The lightbox function is in ImpressCMS included, you can use it in all modules and blocks. Estibulum pretium blandit sem vel consequat. Phasellus convallis pellentesque ipsum ac lobortis. Suspendisse adipiscing magna non tellus ornare vel ullamcorper diam elementum. Nam dictum, diam ac porttitor pellentesque, eros ligula tempor urna, vel porttitor urna magna in sapien? Donec quis dolor risus. Vivamus rutrum tristique lorem, non rutrum 
felis porta quis. Pellentesque quis nisl et quam tempor ultrices eu in tortor. Aliquam ipsum dolor, vulputate vel semper nec, mollis quis augue. Nulla id mauris a enim pellentesque malesuada at eget nulla. Pellentesque molestie luctus laoreet. Ut lectus sem, accumsan sit amet laoreet sed, dapibus eu tortor. Etiam consectetur, quam a mattis dignissim, mi elit dignissim orci, ut adipiscing urna sem eu lacus. Proin gravida orci id mi suscipit fermentum. Paragraph end.<br /><br />Second paragraph. Nulla at venenatis leo. Sed nibh est, cursus vitae convallis at, convallis at felis. Sed adipiscing tincidunt elementum. Pellentesque viverra sem non massa tempus vel commodo mauris vulputate. Duis nec enim in velit consectetur ultrices. Proin a pretium ligula. Ut varius imperdiet rhoncus! Praesent sed neque at lacus vehicula placerat ut quis nunc. Vivamus dictum tristique tincidunt! Nulla facilisi. Maecenas lobortis vehicula velit, non bibendum ligula consequat nec. Maecenas ac nibh ligula. Cras risus nibh, 
ullamcorper ac pretium in, interdum et nunc. Nunc erat mauris, blandit quis suscipit molestie, eleifend nec quam. Ut blandit nunc sed turpis egestas a mollis nisi commodo. Etiam blandit sollicitudin nibh nec consectetur.</p>\r\n<p>Here some Typography examples. The output is&nbsp;heavily dependent from your used theme design. Enjoy it.</p>\r\n<h1></h1>\r\n<h1>Heading 1</h1>\r\n<h2>Heading 2</h2>\r\n<h3>Heading 3</h3>\r\n<h4>Heading 4</h4>\r\n<h5>Heading 5</h5>\r\n<h6>Heading 6</h6>\r\n<h1><br />Blockquote</h1>\r\n<div class=\"xoopsQuote\">Sed sit amet mauris erat. Fusce imperdiet euismod justo, ut faucibus augue placerat id. Proin eleifend vulputate consequat. Vestibulum nibh elit; interdum ac pulvinar ut; pulvinar eu dolor. Integer viverra posuere odio vel molestie! Morbi vel elementum enim. Mauris tristique mi in magna dictum ut viverra ipsum laoreet! Maecenas et elit et mi commodo aliquam non ac ipsum. Suspendisse potenti. Mauris eget urna eget nunc tincidunt dignissim.</div>\r\n<h1></h1>\r\n<h1>Blockcode</h1> \r\n<div class=\"xoopsCode\">function cms_notify_iteminfo(\$category, \$item_id){<br />&nbsp;&nbsp;&nbsp; global \$icmsModule, \$icmsModuleConfig, \$icmsConfig;<br /><br />&nbsp;&nbsp;&nbsp; if (\$category == ''global'') {<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; \$item[''name''] = '''';<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; \$item[''url''] = '''';<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; return \$item;<br />&nbsp;&nbsp;&nbsp; }<br />}</div>\r\n<h1><br />Lists</h1>\r\n<p>Some list examples here:</p>\r\n<h2>Unordered List</h2>\r\n<!-- Unordered List -->\r\n<ul>\r\n<li>tation ullamcorper suscipit lobortis</li>\r\n<li>Nam liber tempor cum soluta nobis</li>\r\n<li>imperdiet doming id quod mazim</li>\r\n<li>suscipit lobortis nisl ut aliquip ex</li>\r\n</ul>\r\n<h2><span class=\"com\">Ordered List</span></h2>\r\n<!-- Ordered List --><ol>\r\n<li>tation ullamcorper suscipit lobortis</li>\r\n<li>Nam liber tempor cum soluta nobis</li>\r\n<li>imperdiet doming id quod mazim</li>\r\n<li>suscipit lobortis 
nisl ut aliquip ex</li>\r\n</ol>\r\n<h1></h1>\r\n<h1>Tables</h1>\r\n<p>Some table examples here:</p>\r\n<h2>Easy Table</h2>\r\n<!-- Table -->\r\n<table style=\"width: 100%;\" cellpadding=\"0\" cellspacing=\"0\">\r\n<thead>\r\n<tr><th>Item1</th><th>Item2</th><th>Item3</th></tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td>Item1</td>\r\n<td>Item2</td>\r\n<td>Item3</td>\r\n</tr>\r\n<tr>\r\n<td>Item1</td>\r\n<td>Item2</td>\r\n<td>Item3</td>\r\n</tr>\r\n<tr>\r\n<td>Item1</td>\r\n<td>Item2</td>\r\n<td>Item3</td>\r\n</tr>\r\n<tr>\r\n<td>Item1</td>\r\n<td>Item2</td>\r\n<td>Item3</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<h2>ImpressCMS Table</h2>\r\n<!-- Table -->\r\n<table style=\"width: 100%;\" class=\"outer\" cellpadding=\"0\" cellspacing=\"0\">\r\n<thead>\r\n<tr><th>Item1</th><th>Item2</th><th>Item3</th></tr>\r\n</thead>\r\n<tbody>\r\n<tr class=\"even\">\r\n<td>Item1</td>\r\n<td>Item2</td>\r\n<td>Item3</td>\r\n</tr>\r\n<tr class=\"odd\">\r\n<td>Item1</td>\r\n<td>Item2</td>\r\n<td>Item3</td>\r\n</tr>\r\n<tr class=\"even\">\r\n<td>Item1
			</td>\r\n<td>Item2</td>\r\n<td>Item3</td>\r\n</tr>\r\n<tr class=\"odd\">\r\n<td>Item1</td>\r\n<td>Item2</td>\r\n<td>Item3</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<h1></h1>\r\n<h1>Horizontal Rules</h1>\r\n<p>HR</p>\r\n<!-- HR --><hr />\r\n<h1></h1>\r\n<h1>ImpressCMS Messages</h1>\r\n<p>confirmMsg</p>\r\n<div class=\"confirmMsg\"><b>Success</b> - This is a success message.</div>\r\n<p>&nbsp;</p>\r\n<p>errorMsg</p>\r\n<div class=\"errorMsg\"><b>Failure</b> - This is a failure message.</div>\r\n<p>&nbsp;</p>\r\n<p>resultMsg</p>\r\n<div class=\"resultMsg\"><b>Information</b> - This is an information message.</div>\r\n<p>&nbsp;</p>\r\n<h1>default Pagination</h1>\r\n<div class=\"pagination default\"><a href=\"#\">◄ Prev</a> <a href=\"#\">1</a> <span class=\"current\"><strong>(2)</strong></span> <a href=\"#\">3</a> <a href=\"#\">4</a> <a href=\"#\">Next ►</a></div>\r\n<p>&nbsp;</p>\r\n<p>END of Typo.</p>\r\n<p><img src=\"http://localhost/01-testing/impresscms/icms_13trunk/uploads/smil3dbd4d6422f04.gif\" alt=\"\" /></p>', '<p>- My first content 
from 2012-10-25</p>', 1, 1351144200, 1351144200, 0, 1, 0, 1, 1, 1, 'keyword', 'description', 'vestibulum-pretium-blandit-sem-vel-consequat');
	";
	foreach($queries as $query) {
	$result = icms::$xoopsDB->query($query);
	}
	return TRUE;
}

/**
 * Authorises some common audio (and image) mimetypes on install
 *
 * Helps reduce the need for post-install configuration, its just a convenience for the end user.
 * It grants the module permission to use some common audio (and image) mimetypes that will
 * probably be needed for audio tracks and programme cover art.
 */
function cms_authorise_mimetypes()
{
	$dirname = basename(dirname(dirname(__FILE__)));
	$extension_list = array('png', 'gif', 'jpg');
	$system_mimetype_handler = icms_getModuleHandler('mimetype', 'system');
	foreach ($extension_list as $extension)
	{
		$allowed_modules = array();
		$mimetypeObj = '';

		$criteria = new icms_db_criteria_Compo();
		$criteria->add(new icms_db_criteria_Item('extension', $extension));
		$mimetypeObj = array_shift($system_mimetype_handler->getObjects($criteria));

		if ($mimetypeObj)
		{
			$allowed_modules = $mimetypeObj->getVar('dirname');
			if (empty($allowed_modules))
			{
				$mimetypeObj->setVar('dirname', $dirname);
				$mimetypeObj->store();
			}
			else
			{
				if (!in_array($dirname, $allowed_modules))
				{
					$allowed_modules[] = $dirname;
					$mimetypeObj->setVar('dirname', $allowed_modules);
					$mimetypeObj->store();
				}
			}
		}
	}
}