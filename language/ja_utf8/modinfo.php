<?php
/**
 * English language constants related to module information
 *
 * @copyright	Copyright Madfish (Simon Wilkinson) 2012
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
 * @package		cms
 * @version		$sato-san$
*/

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

// Module name
define("_MI_CMS_MD_NAME", "CMS");
define("_MI_CMS_MD_DESC", "ImpressCMS Simple Content");
define("_MI_CMS_CMS", "コンテンツ");
define("_MI_CMS_TEMPLATES", "モジュールのテンプレート");
define("_MI_CMS_COMMENTS", "モジュールのコメント");
define("_MI_CMS_BLOCKS", "モジュールのブロック");

// Blocks
define("_MI_CMS_RANDOM", "(Random) content");
define("_MI_CMS_RANDOMDSC", "Display (random) content");
define("_MI_CMS_LIST", "(List) content");
define("_MI_CMS_LISTDSC", "Display (listed) content");
define("_MI_CMS_SLIDER", "(Slide) content");
define("_MI_CMS_SLIDERDSC", "Display (slide) content");
define("_MI_CMS_TICKER", "(Ticker) content");
define("_MI_CMS_TICKERDSC", "Display (ticked) content");
define("_MI_CMS_SELECT_CONTENT", "(select) content");
define("_MI_CMS_SELECT_CONTENTDSC", "Display (selected) content");

// Preferences
define("_MI_CMS_INDEX_DISPLAY_MODE", "Display teaser text within the index page?");
define("_MI_CMS_INDEX_DISPLAY_MODE_DSC", "Toggles the display of the index page between a list of summaries (yes) and a compact list of tables (no).");
define("_MI_CMS_NUMBER_CMS_PER_PAGE", "Number of contents per page");
define("_MI_CMS_NUMBER_CMS_PER_PAGE_DSC", "Controls how many contens are shown on the index page, sane value is 5-10.");
define("_MI_CMS_SHOW_TAG_SELECT_BOX", "Show tag select box");
define("_MI_CMS_SHOW_TAG_SELECT_BOX_DSC", "Toggles the tag select box on/off for the index page (only if Sprockets module installed).");
define("_MI_CMS_SHOW_BREADCRUMB", "Show breadcrumb");
define("_MI_CMS_SHOW_BREADCRUMB_DSC", "Toggles the module breadcrumb on/off");
define("_MI_CMS_SHOW_VIEW_COUNTER", "Show views counter?");
define("_MI_CMS_SHOW_VIEW_COUNTER_DSC", "Toggles the visibility of the views counter field.");
define("_MI_CMS_SHOW_LAST_UPDATED", "Show date last updated?");
define("_MI_CMS_SHOW_LAST_UPDATED_DSC", "Labels a content as updated. Labels are good for one month");
define("_MI_CMS_DISPLAY_START_LOGOS", "Display content image");
define("_MI_CMS_DISPLAY_START_LOGOS_DSC", "Toggles uploaded images on or off.");
define("_MI_CMS_START_LOGO_POSITION", "画像の位置");
define("_MI_CMS_START_LOGO_POSITION_DSC", "Display content images on the left or right side of the page.");
define("_MI_CMS_FREESTYLE_LOGO_DIMENSIONS", "Freestyle image dimensions");
define("_MI_CMS_FREESTYLE_LOGO_DIMENSIONS_DSC", "If enabled, images will NOT be automatically resized. This setting is useful if your content images vary in shape and want to manually resize your images yourself.");
define("_MI_CMS_LOGO_INDEX_DISPLAY_WIDTH", "Image display width on the INDEX page (pixels)");
define("_MI_CMS_LOGO_INDEX_DISPLAY_WIDTH_DSC", "Content images will be dynamically resized according to this value. You can change the value any time you like. However, you should upload images who are slightly LARGER than the maximum desired display size to avoid pixelation due to upscaling.");
define("_MI_CMS_LOGO_SINGLE_DISPLAY_WIDTH", "Image display width in SINGLE view (pixels)");
define("_MI_CMS_LOGO_SINGLE_DISPLAY_WIDTH_DSC", "Content images will be dynamically resized according to this value. You can change the value any time you like. However, you should upload images who are slightly LARGER than the maximum desired display size to avoid pixelation due to upscaling.");
define("_MI_CMS_LOGO_BLOCK_DISPLAY_WIDTH", "Image display width in the Random block (pixels)");
define("_MI_CMS_LOGO_BLOCK_DISPLAY_WIDTH_DSC", "Content images will be dynamically resized according to this value. You can change the value any time you like. However, you should upload images who are slightly LARGER than the maximum desired display size to avoid pixelation due to upscaling.");
define("_MI_CMS_LOGO_UPLOAD_HEIGHT", "Maximum height of image files (pixels)");
define("_MI_CMS_LOGO_UPLOAD_HEIGHT_DSC", "Image files may not exceed this value.");
define("_MI_CMS_LOGO_UPLOAD_WIDTH", "Maximum width of image files (pixels)");
define("_MI_CMS_LOGO_UPLOAD_WIDTH_DSC", "Image files may not exceed this value.");
define("_MI_CMS_LOGO_FILE_SIZE", "Maximum file size of image files (bytes)");
define("_MI_CMS_LOGO_FILE_SIZE_DSC", "Image files may not exceed this value.");
define("_MI_CMS_DATE_FORMAT", "日付の形式");
define("_MI_CMS_DATE_FORMAT_DSC", "Controls the format of the date in content 'updated' notices. See the <a href='http://php.net/manual/en/function.date.php'>PHP manual</a> for formatting options.");
define("_MI_CMS_UPDATED_NOTICE_PERIOD", "Display 'updated' notice time");
define("_MI_CMS_UPDATED_NOTICE_PERIOD_DSC", "How long do you want to display 'updated' notices?");

// Preference options
define("_MI_CMS_LEFT", "左");
define("_MI_CMS_RIGHT", "右");
define("_MI_CMS_ONE_DAY", "一日");
define("_MI_CMS_THREE_DAYS", "三日間");
define("_MI_CMS_ONE_WEEK", "一週間");
define("_MI_CMS_TWO_WEEKS", "二週間");
define("_MI_CMS_THREE_WEEKS", "三週間");
define("_MI_CMS_FOUR_WEEKS", "四週間");

// Submenu
define("_MI_CMS_ARCHIVIERT", "アーカイブ");

// Manual
define("_MI_CMS_MANUAL", "マニュアル");

// Categories
define("_MI_CMS_CATEGORIES", "カテゴリ");