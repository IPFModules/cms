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

/**  General Information  */
$modversion = array(
	"name"						=> _MI_CMS_MD_NAME,
	"version"					=> 3.0,
	"description"				=> _MI_CMS_MD_DESC,
	"author"					=> "@Madfish (Simon Wilkinson) and @sato-san (Rene Sato)",
	"credits"					=> "Thanks to QM-B and Lotus for your help.",
	"help"						=> "",
	"license"					=> "GNU General Public License (GPL)",
	"official"					=> 0,
	"dirname"					=> basename(dirname(__FILE__)),
	"modname"					=> "cms",

/**  Images information  */
	"iconsmall"					=> "images/icon_small.png",
	"iconbig"					=> "images/icon_big.png",
	"image"						=> "images/icon_big.png", /* for backward compatibility */

/**  Development information */
	"status_version"			=> "3.0",
	"status"					=> "Final",
	"date"						=> "23.10.2012",
	"author_word"				=> "For ICMS 1.3+ only.",
	"warning"					=> "trunk",

/** Contributors */
	"developer_website_url"		=> "",
	"developer_website_name"	=> "",
	"developer_email"			=> "",

/** Administrative information */
	"hasAdmin"					=> 1,
	"adminindex"				=> "admin/index.php",
	"adminmenu"					=> "admin/menu.php",

/** Install and update informations */
	"onInstall"					=> "include/onupdate.inc.php",
	"onUpdate"					=> "include/onupdate.inc.php",

/** Search information */
	"hasSearch"					=> 1,
	"search"					=> array("file" => "include/search.inc.php", "func" => "cms_search"),

/** Comments information */
	"hasComments"				=> 1,
	"comments"					=> array(
									"itemName" => "start_id",
									"pageName" => "start.php",
									"callbackFile" => "include/comment.inc.php",
									"callback" => array("approve" => "cms_com_approve",
									"update" => "cms_com_update")));

/** Menu information */
$modversion["hasMain"] = 1;
$modversion["sub"][0]["name"] = _MI_CMS_ARCHIVIERT;
$modversion["sub"][0]["url"] = "archiviert_start.php";

/** other possible types: testers, translators, documenters and other */
$modversion['people']['developers'][] = "<a href='http://community.impresscms.org/userinfo.php?uid=91' target='_blank'>Madfish</a> | Simon Wilkinson | <a href='https://www.isengard.biz'>isengard.biz</a> | simon@isengard.biz";
$modversion['people']['developers'][] = "<a href='http://community.impresscms.org/userinfo.php?uid=10' target='_blank'>sato-san</a> | René Sato| 佐藤レネー | <a href='http://www.impresscms.de'>impresscms.de</a> | sato-san@impresscms.org";

/** Manual */
$modversion['manual']['wiki'][] = "<a href='http://wiki.impresscms.org/index.php?title=cms' target='_blank'>English</a>";

/** Database information */
$modversion['object_items'][1] = 'start';

$modversion["tables"] = icms_getTablesArray($modversion['dirname'], $modversion['object_items']);

/** Templates information */
$modversion['templates'] = array(
	array("file" => "cms_admin_start.html", "description" => "Start admin index."),
	array("file" => "cms_start.html", "description" => "Start index."),
	array("file" => "cms_header.html", "description" => "Module header."),
	array("file" => "cms_footer.html", "description" => "Module footer."),
	array("file" => "cms_print.html", "description" => "Module print template."),
	array("file" => "cms_requirements.html", "description" => "Alert if module requirements not met.")
);


/** Notification information */
$modversion['hasNotification'] = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'cms_notify_iteminfo';

$modversion['notification']['category'][1] = array (
	'name'				=> 'global',
	'title'				=> _MI_CMS_GLOBAL_NOTIFY,
	'description'		=> _MI_CMS_GLOBAL_NOTIFY_DSC,
	'subscribe_from'	=> array('start.php')
);
$modversion['notification']['event'][2] = array(
	'name'				=> 'new_filling',
	'category'			=> 'global',
	'title'				=> _MI_CMS_GLOBAL_NEW_FILLING_NOTIFY,
	'caption'			=> _MI_CMS_GLOBAL_NEW_FILLING_NOTIFY_CAP,
	'description'		=> _MI_CMS_GLOBAL_NEW_FILLING_NOTIFY_DSC,
	'mail_template'		=> 'new_filling',  //create a mail template
	'mail_subject'		=> _MI_CMS_GLOBAL_NEW_FILLING_NOTIFY_SBJ
);
$modversion['notification']['event'][3] = array(
	'name'				=> 'filling_modified',
	'category'			=> 'global',
	'title'				=> _MI_CMS_GLOBAL_FILLING_NOTIFY,
	'caption'			=> _MI_CMS_GLOBAL_FILLING_NOTIFY_CAP,
	'description'		=> _MI_CMS_GLOBAL_FILLING_NOTIFY_DSC,
	'mail_template'		=> 'filling_modified',  //create a mail template
	'mail_subject'		=> _MI_CMS_GLOBAL_FILLING_NOTIFY_SBJ
);

/** Blocks */
$modversion['blocks'][1] = array(
	'file' => 'list_cms.php',
	'name' => _MI_CMS_LIST,
	'description' => _MI_CMS_LISTDSC,
	'show_func' => 'show_list_cms',
	'edit_func' => 'edit_list_cms',
	'options' => '5|0|0|0',
	'template' => 'cms_block_list.html'
);

$modversion['blocks'][2] = array(
	'file' => 'random_cms.php',
	'name' => _MI_CMS_RANDOM,
	'description' => _MI_CMS_RANDOMDSC,
	'show_func' => 'show_random_cms',
	'edit_func' => 'edit_random_cms',
	'options' => '5|1|1|0',
	'template' => 'cms_block_random.html'
);

$modversion['blocks'][3] = array(
	'file' => 'slider_cms.php',
	'name' => _MI_CMS_SLIDER,
	'description' => _MI_CMS_SLIDERDSC,
	'show_func' => 'show_slider_cms',
	'edit_func' => 'edit_slider_cms',
	'options' => '3|0|1|0',
	'template' => 'cms_block_slider.html'
);

$modversion['blocks'][4] = array(
	'file' => 'ticker_cms.php',
	'name' => _MI_CMS_TICKER,
	'description' => _MI_CMS_TICKERDSC,
	'show_func' => 'show_ticker_cms',
	'edit_func' => 'edit_ticker_cms',
	'options' => '5|0|0|0',
	'template' => 'cms_block_ticker.html'
);

$modversion['blocks'][5] = array(
	'file' => 'select_content_cms.php',
	'name' => _MI_CMS_SELECT_CONTENT,
	'description' => _MI_CMS_SELECT_CONTENTDSC,
	'show_func' => 'show_select_content_cms',
	'edit_func' => 'edit_select_content_cms',
	'options' => '1|1|0',
	'template' => 'cms_block_select_content.html'
);

/** Preferences */
$modversion['config'][1] = array(
	'name' => 'index_display_mode',
	'title' => '_MI_CMS_INDEX_DISPLAY_MODE',
	'description' => '_MI_CMS_INDEX_DISPLAY_MODE_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' =>  '1');

$modversion['config'][] = array(
	'name' => 'number_of_cms_per_page',
	'title' => '_MI_CMS_NUMBER_CMS_PER_PAGE',
	'description' => '_MI_CMS_NUMBER_CMS_PER_PAGE_DSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '5');

$modversion['config'][] = array(
	'name' => 'show_breadcrumb',
	'title' => '_MI_CMS_SHOW_BREADCRUMB',
	'description' => '_MI_CMS_SHOW_BREADCRUMB_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '0');

$modversion['config'][] = array(
	'name' => 'show_extension_print',
	'title' => '_MI_CMS_EXTENSION_PRINT',
	'description' => '_MI_CMS_EXTENSION_PRINT_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '0');

$modversion['config'][] = array(
	'name' => 'show_view_counter',
	'title' => '_MI_CMS_SHOW_VIEW_COUNTER',
	'description' => '_MI_CMS_SHOW_VIEW_COUNTER_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '0');

$modversion['config'][] = array(
	'name' => 'show_tag_select_box',
	'title' => '_MI_CMS_SHOW_TAG_SELECT_BOX',
	'description' => '_MI_CMS_SHOW_TAG_SELECT_BOX_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '0');

$modversion['config'][] = array(
	'name' => 'display_start_logos',
	'title' => '_MI_CMS_DISPLAY_START_LOGOS',
	'description' => '_MI_CMS_DISPLAY_START_LOGOS_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '1');

$modversion['config'][] = array(
	'name' => 'start_logo_position',
	'title' => '_MI_CMS_START_LOGO_POSITION',
	'description' => '_MI_CMS_START_LOGO_POSITION_DSC',
	'formtype' => 'select',
	'valuetype' => 'int',
	'options' => array('_MI_CMS_LEFT' => 0, '_MI_CMS_RIGHT' => 1),
	'default' => 0);

$modversion['config'][] = array(
	'name' => 'freestyle_logo_dimensions',
	'title' => '_MI_CMS_FREESTYLE_LOGO_DIMENSIONS',
	'description' => '_MI_CMS_FREESTYLE_LOGO_DIMENSIONS_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '0');

$modversion['config'][] = array(
	'name' => 'logo_index_display_width',
	'title' => '_MI_CMS_LOGO_INDEX_DISPLAY_WIDTH',
	'description' => '_MI_CMS_LOGO_INDEX_DISPLAY_WIDTH_DSC',
	'formtype' => 'text',
	'valuetype' => 'int',
	'default' =>  '150');

$modversion['config'][] = array(
	'name' => 'logo_single_display_width',
	'title' => '_MI_CMS_LOGO_SINGLE_DISPLAY_WIDTH',
	'description' => '_MI_CMS_LOGO_SINGLE_DISPLAY_WIDTH_DSC',
	'formtype' => 'text',
	'valuetype' => 'int',
	'default' =>  '300');

$modversion['config'][] = array(
	'name' => 'logo_block_display_width',
	'title' => '_MI_CMS_LOGO_BLOCK_DISPLAY_WIDTH',
	'description' => '_MI_CMS_LOGO_BLOCK_DISPLAY_WIDTH_DSC',
	'formtype' => 'text',
	'valuetype' => 'int',
	'default' =>  '100');

$modversion['config'][] = array(
	'name' => 'logo_upload_height',
	'title' => '_MI_CMS_LOGO_UPLOAD_HEIGHT',
	'description' => '_MI_CMS_LOGO_UPLOAD_HEIGHT_DSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '500');

$modversion['config'][] = array(
	'name' => 'logo_upload_width',
	'title' => '_MI_CMS_LOGO_UPLOAD_WIDTH',
	'description' => '_MI_CMS_LOGO_UPLOAD_WIDTH_DSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '500');

$modversion['config'][] = array(
	'name' => 'logo_file_size',
	'title' => '_MI_CMS_LOGO_FILE_SIZE',
	'description' => '_MI_CMS_LOGO_FILE_SIZE_DSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '2097152'); // 2MB default max upload size

$modversion['config'][] = array(
	'name' => 'show_last_updated',
	'title' => '_MI_CMS_SHOW_LAST_UPDATED',
	'description' => '_MI_CMS_SHOW_LAST_UPDATED_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '0');

$modversion['config'][] = array(
	'name' => 'updated_notice_period',
	'title' => '_MI_CMS_UPDATED_NOTICE_PERIOD',
	'description' => '_MI_CMS_UPDATED_NOTICE_PERIOD_DSC',
	'formtype' => 'select',
	'valuetype' => 'int',
	'options' => array(
		'_MI_CMS_ONE_DAY' => 1,
		'_MI_CMS_THREE_DAYS' => 2,
		'_MI_CMS_ONE_WEEK' => 3,
		'_MI_CMS_TWO_WEEKS' => 4,
		'_MI_CMS_THREE_WEEKS' => 5,
		'_MI_CMS_FOUR_WEEKS' => 6),
		'default' => 3);

$modversion['config'][] = array(
	'name' => 'date_format',
	'title' => '_MI_CMS_DATE_FORMAT',
	'description' => '_MI_CMS_DATE_FORMAT_DSC',
	'formtype' => 'textbox',
 	'valuetype' => 'text',
 	'default' =>  'j/n/Y');
