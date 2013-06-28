<?php
/**
 * Necessary module:	Sitemap 1.40+
 * This plugin is for the sitemap module only and is an option (the URL is without SEO).
 *
 * @copyright	(c) by René Sato
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		3.0
 * @author		佐藤レネー René Sato <rene.sato@gmx.de>
 * @package		cms
 * @version		$Id$
 */

function b_sitemap_cms() {
	$block = sitemap_get_articles_map( icms::$xoopsDB -> prefix( 'cms_start' ), 'start_id', 'title', 'start.php?start_id=', 'start_id');
	return $block;
}
