<?php
/**
 * German language constants related to module information
 *
 * @copyright	(c) by René Sato
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		佐藤レネー René Sato <rene.sato@gmx.de>
 * @package		cms
 * @version		$sato-san$
*/

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

// Module name
define("_MI_CMS_MD_NAME", "CMS");
define("_MI_CMS_MD_DESC", "Ein einfaches Modul für Inhalte");
define("_MI_CMS_CMS", "Inhalte");
define("_MI_CMS_TEMPLATES", "Modul Templates");
define("_MI_CMS_BLOCKS", "Modul Blöcke");

// Blocks
define("_MI_CMS_RANDOM", "(Random) content");
define("_MI_CMS_RANDOMDSC", "Display (random) content");
define("_MI_CMS_LIST", "(List) content");
define("_MI_CMS_LISTDSC", "Display (listed) content");
define("_MI_CMS_SLIDER", "(Slide) content");
define("_MI_CMS_SLIDERDSC", "Display (slide) content");
define("_MI_CMS_TICKER", "(Ticker) content");
define("_MI_CMS_TICKERDSC", "Display (ticked) content");

// Preferences
define("_MI_CMS_INDEX_DISPLAY_MODE", "Anreißer in der Hauptseite anzeigen?");
define("_MI_CMS_INDEX_DISPLAY_MODE_DSC", "Wechseln der Darstellung für die Hauptseite. Es können Inhalte mit Anreißer angezeigt werden (Ja) oder eine einfache tabellarische Übersicht (Nein).");
define("_MI_CMS_NUMBER_CMS_PER_PAGE", "Anzahl der Inhalte pro Seite");
define("_MI_CMS_NUMBER_CMS_PER_PAGE_DSC", "Die Anzahl wieviele Inhalte in der Hauptseite dargestellt werden sollen. Ein gesunder Wert liegt zwischen 5 und 10.");
define("_MI_CMS_SHOW_TAG_SELECT_BOX", "Zeige eine Auswahlbox mit Tags");
define("_MI_CMS_SHOW_TAG_SELECT_BOX_DSC", "Wechseln zwischen Ja und Nein. Wenn das Modul 'Sprockets' installiert wurde, kann eine Auswahlbox in der Hauptseite für die Tags angezeigt werden.");
define("_MI_CMS_SHOW_BREADCRUMB", "Zeige Navigation");
define("_MI_CMS_SHOW_BREADCRUMB_DSC", "Die Navigation (breadcrumb) kann ein- und ausgeschaltet werden.");
define("_MI_CMS_SHOW_VIEW_COUNTER", "Zeige die Anzahl der Zugriffe?");
define("_MI_CMS_SHOW_VIEW_COUNTER_DSC", "Es kann in der Detailseite des Inhalts angezeigt werden, wie oft ein Inhalt bereits gelesen wurde.");
define("_MI_CMS_SHOW_LAST_UPDATED", "Zeige das Datum der Aktualisierung?");
define("_MI_CMS_SHOW_LAST_UPDATED_DSC", "Beschriften eines Inhaltes als Aktualisiert. Wir empfehlen die Kennzeichnung für einen Monat");
define("_MI_CMS_DISPLAY_START_LOGOS", "Zeige Bild zum Inhalt");
define("_MI_CMS_DISPLAY_START_LOGOS_DSC", "Sollen hochgeladene Bilder zum Inhalt angezeigt werden?");
define("_MI_CMS_START_LOGO_POSITION", "Bild Position");
define("_MI_CMS_START_LOGO_POSITION_DSC", "Zeige Bilder zum Inhalt auf der rechten oder linken Seite an.");
define("_MI_CMS_FREESTYLE_LOGO_DIMENSIONS", "Bildgröße im Freistiel");
define("_MI_CMS_FREESTYLE_LOGO_DIMENSIONS_DSC", "Wenn dies aktiviert wird, werden hochgeladene Bilder NICHT automatisch in der Bildgöße begrenzt. Diese Einstellung ist nützlich, wenn die Bilder in der Form variiren sollen und schon verkleiner wurden.");
define("_MI_CMS_LOGO_INDEX_DISPLAY_WIDTH", "Bildgröße innerhalb der HAUPTSEITE (Pixel)");
define("_MI_CMS_LOGO_INDEX_DISPLAY_WIDTH_DSC", "Content images will be dynamically resized according to this value. You can change the value any time you like. However, you should upload images who are slightly LARGER than the maximum desired display size to avoid pixelation due to upscaling.");
define("_MI_CMS_LOGO_SINGLE_DISPLAY_WIDTH", "Bildgröße innerhalb einer DETAILSEITE (Pixel)");
define("_MI_CMS_LOGO_SINGLE_DISPLAY_WIDTH_DSC", "Content images will be dynamically resized according to this value. You can change the value any time you like. However, you should upload images who are slightly LARGER than the maximum desired display size to avoid pixelation due to upscaling.");
define("_MI_CMS_LOGO_BLOCK_DISPLAY_WIDTH", "Bildgröße innerhalb des RANDOM BLOCKS (Pixel)");
define("_MI_CMS_LOGO_BLOCK_DISPLAY_WIDTH_DSC", "Content images will be dynamically resized according to this value. You can change the value any time you like. However, you should upload images who are slightly LARGER than the maximum desired display size to avoid pixelation due to upscaling.");
define("_MI_CMS_LOGO_UPLOAD_HEIGHT", "Maximale Höhe eines Bildes (Pixel)");
define("_MI_CMS_LOGO_UPLOAD_HEIGHT_DSC", "Bilddateien können diesen Wert nicht überschreiben.");
define("_MI_CMS_LOGO_UPLOAD_WIDTH", "Maximale Breite eines Bildes (Pixel)");
define("_MI_CMS_LOGO_UPLOAD_WIDTH_DSC", "Bilddateien können diesen Wert nicht überschreiben.");
define("_MI_CMS_LOGO_FILE_SIZE", "Maximale Dateigröße eines Bildes (Bytes)");
define("_MI_CMS_LOGO_FILE_SIZE_DSC", "Bilddateien können diesen Wert nicht überschreiben. Die Dateigröße ist auch durch den Server oft auf 2MB begrenzt.");
define("_MI_CMS_DATE_FORMAT", "Datumsformat");
define("_MI_CMS_DATE_FORMAT_DSC", "Controls the format of the date in content 'updated' notices. See the <a href='http://php.net/manual/en/function.date.php'>PHP manual</a> for formatting options.");
define("_MI_CMS_UPDATED_NOTICE_PERIOD", "Zeige Hinweis 'Aktualisiert'");
define("_MI_CMS_UPDATED_NOTICE_PERIOD_DSC", "Wie lange soll der Hinweis 'Aktualisiert' angezeigt werden?");

// Preference options
define("_MI_CMS_LEFT", "Links");
define("_MI_CMS_RIGHT", "Rechts");
define("_MI_CMS_ONE_DAY", "Ein Tag");
define("_MI_CMS_THREE_DAYS", "Drei Tage");
define("_MI_CMS_ONE_WEEK", "Eine Woche");
define("_MI_CMS_TWO_WEEKS", "Zwei Wochen");
define("_MI_CMS_THREE_WEEKS", "Drei Wochen");
define("_MI_CMS_FOUR_WEEKS", "Fünf Wochen");

// Submenu
define("_MI_CMS_ARCHIVIERT", "Archiviert");

// Manual
define("_MI_CMS_MANUAL", "Anleitung");