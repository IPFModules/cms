<?php
/**
 * German language constants commonly used in the module
 *
 * @copyright	(c) by René Sato
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		佐藤レネー René Sato <rene.sato@gmx.de>
 * @package		cms
 * @version		$sato-san$
*/

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

// Module name for user side
define("_CO_CMS_CMS", "CMS");

// Start
define("_CO_CMS_START_TITLE", "Überschrift");
define("_CO_CMS_START_TITLE_DSC", "Eine kurze aber aussagekräftigen Überschrift eingeben.");
define("_CO_CMS_START_SUBTITLE", "Untertitel");
define("_CO_CMS_START_SUBTITLE_DSC", "Optional, einen Untertitel eingeben. Er wird unterhalb der Überschrift dargestellt.");
define("_CO_CMS_START_LOGO", "Bild");
define("_CO_CMS_START_LOGO_DSC", "Hochladen eines Bildes oder Foto. Die Größenänderung kann in den Modul Einstellungen vorgenommen werden.");
define("_CO_CMS_START_WEBSITE", "Website");
define("_CO_CMS_START_WEBSITE_DSC", "WICHTIG: Starten mit 'http://' - Wenn der Inhalt eine If the content has a bestimmte Internet Adresse haben soll, kann diese hier eingetragen werden. Wenn nicht, einfach leer lassen.");
define("_CO_CMS_START_BEENDET", "Archiviert");
define("_CO_CMS_START_BEENDET_DSC", "Inhalte können als 'Archiviert' markiert werden. Veraltete Inhalte sind über die Hauptseite nicht mehr aufrufbar, diese werden in das Untermenü 'Archiviert' verschoben und können dort wiedergefunden und geöffnet werden.");
define("_CO_CMS_START_TAG", "Tag");
define("_CO_CMS_START_TAG_DSC", "Es können Tags zugewiesen werden. Anschließend kann nach diesen gefiltert werden um beispielsweise Inhalte zu kategorisieren.");
define("_CO_CMS_START_DESCRIPTION", "Anreißer");
define("_CO_CMS_START_DESCRIPTION_DSC", "Eine zusammengefasste Beschreibung des Inhalts. Dieser Inhalt wird nur in der Hauptseite angezeigt.");
define("_CO_CMS_START_EXTENDED_TEXT", "erweiterter Inhalt");
define("_CO_CMS_START_EXTENDED_TEXT_DSC", "Eine volle Beschreibung des Inhalts (optional). Dieser Inhalt wird in der Detailseite dargestellt. Wird das Feld nicht ausgefüllt, wird der Anreißer in der Detailseite dargestellt.");
define("_CO_CMS_START_HISTORY", "Historie / Neuer Entwurf");
define("_CO_CMS_START_HISTORY_DSC", "Dieser Text ist nur fuer den Admin sichtbar. Daher kann dieses Feld als Historie oder auch als Feld für einen neuen Entwurf genutzt werden.");
define("_CO_CMS_START_CREATOR", "Autor");
define("_CO_CMS_START_CREATOR_DSC", "Die Person, welche den Inhalt erstellt hat.");
define("_CO_CMS_START_DATE", "Erstellt am");
define("_CO_CMS_START_DATE_DSC", "Datum eingeben, seit wann der Inhalt erstellt wurde.");
define("_CO_CMS_START_LAST_UPDATE", "Aktualisiert?");
define("_CO_CMS_START_LAST_UPDATE_DSC", "Wann wurde ein Inhalt aktualisiert. Das aktualisierte Datum kann in der Hauptseite und Detailseite dargestellt werden um darauf aufmerksam zu machen. Weitere Konfiguarationen dazu gibt es in den Modul Einstellungen.");
define("_CO_CMS_START_WEIGHT", "Sortierung");
define("_CO_CMS_START_WEIGHT_DSC", "Änderung der Sortierung. Lower weights are listed first.");
define("_CO_CMS_START_ONLINE_STATUS", "Online");
define("_CO_CMS_START_ONLINE_STATUS_DSC", "Den Inhalt Online (Ja) oder Offline (Nein) setzen. Auf Offline Inhalte können nicht zugegriffen werden. Nur der Admin kann diese bearbeiten.");
define("_CO_CMS_START_UPDATED", "Aktualisiert am");
define("_CO_CMS_START_VIEWS", "mal gelesen");
define("_CO_CMS_START_TAGS", "Tags:");
define("_CO_CMS_ARCHIVED_NO", "Inhalt ist NICHT archiviert");
define("_CO_CMS_ARCHIVED_YES", "Inhalt ist archiviert worden");
define("_CO_CMS_START_ONLINE", "Online, klicken um auf Offline zu wechseln.");
define("_CO_CMS_START_OFFLINE", "Offline, klicken um auf Online zu wechseln.");
define("_CO_CMS_READ_MORE", "weiterlesen...");
define("_CO_CMS_START_VIEW", "Vorschau: Zeige Formularfelder");
define("_CO_CMS_PREVIEW", "Zeige den Inhalt in der Webseite an");
define("_CO_CMS_START_CLONE", "Inhalt klonen");

define("_CO_CMS_COMMENTS_INFO", "%d");
define("_CO_CMS_COMMENTS_TITLE", "Kommentare zu");
define("_CO_CMS_NO_COMMENT", "0");


define("_CO_CMS_GUEST_LOGIN", "Login");
define("_CO_CMS_OR", "oder");
define("_CO_CMS_GUEST_REGISTER", "Neu anmelden");
define("_CO_CMS_TO_POST_COMMENTS", "um Kommentare zu schreiben");
// Tag select box
define("_CO_CMS_START_ALL_TAGS", "-- Tag Filter --");

// Page titles
define("_CO_CMS_BEENDET_CMS", "Archiviert");
define("_CO_CMS_ACTIVE_CMS", "CMS");

// Tag title (Mouse overview)
define("_CO_CMS_TAGS_ALL_CONTENTS_ON", "Zeige die Inhalte von");
define("_CO_CMS_TAGS_ALL_SHOW", ".");

// Category title (Mouse overview)
define("_CO_CMS_CATEGORIES_ALL_CONTENTS_ON", "Zeige die Inhalte von");
define("_CO_CMS_CATEGORIES_ALL_SHOW", ".");

// Category title
define("_CO_CMS_START_CATEGORY", "Kategorien");
define("_CO_CMS_START_CATEGORY_DSC", "Wählen Sie eine oder mehrere Kategorien aus, mit der dieser Inhalt verknüpft werden soll.");

define("_CO_CMS_START_RELATED_ITEMS", "Weitere Inhalte");
define("_CO_CMS_START_RELATED_CATEGORIES", "Kategorie");

// Content Permission
define("_CO_CMS_START_START_PERM_READ", "Berechtigungen - Inhalt sehen");
define("_CO_CMS_START_START_PERM_READ_DSC", "Wählen Sie welche Gruppe(n) aus, die berechtigt ist diesen Inhalt anzusehen. Nur Benutzer der ausgewählten Gruppe(n) werden den Inhalt sehen.");
define("_CO_CMS_START_START_PERM_EDIT", "Berechtigungen - Inhalt bearbeiten");
define("_CO_CMS_START_START_PERM_EDIT_DSC", "Wählen Sie welche Gruppe(n) aus, die berechtigt ist diesen Inhalt zu bearbeiten. Nur Benutzer der ausgewählten Gruppe(n) werden den Inhalt bearbeiten können.");
