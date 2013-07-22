<?php
/**
 * PDF functions used by the cms module
 *
 * @copyright	(c) by René Sato
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		3.0
 * @author		佐藤レネー René Sato <rene.sato@gmx.de>
 * @package		cms
 * @version		$Id$
 */

include_once 'header.php';

$clean_start_id = isset($_GET['start_id']) ? filter_input(INPUT_GET, 'start_id', FILTER_SANITIZE_NUMBER_INT) : 0;
//$item_page_id = isset($_GET['page']) ? (int)($_GET['page']) : -1;

if ($clean_start_id == 0) {
	redirect_header(icms_getPreviousPage(), 3, _MD_CMS_NO_CONTENT);
}

$cms_start_handler = icms_getModuleHandler("start", CMS_DIRNAME, "start");
$startObj = $cms_start_handler->get($clean_start_id);

if (!$startObj || !is_object($startObj) || $startObj->isNew()) {
	redirect_header(icms_getPreviousPage(), 3, _MD_CMS_NO_CONTENT);
}


$start = $startObj->toArray();
$content = '<a href="' . ICMS_URL . '/modules/cms/start.php?start_id=' . $clean_start_id . '" title="' . $start['title'] . '">' . $start['title'] . '</a><br />';
$content .= $start['subtitle']. '<br />';
$content .= $start['extended_text'];
//$content .= icms_core_DataFilter::undoHtmlSpecialChars($cmsConfig['cms_print_footer'] . $powered_by . "&nbsp;" . $version);

require_once ICMS_PDF_LIB_PATH.'/tcpdf.php';
	icms_loadLanguageFile('core', 'pdf');
	
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);

	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor(PDF_AUTHOR);
	$pdf->SetTitle($title);
	$pdf->SetSubject($subtitle);
	$pdf->SetKeywords($keywords);
	$sitename = $icmsConfig['sitename'];
	$siteslogan = $icmsConfig['slogan'];
	$pdfheader = icms_core_DataFilter::undoHtmlSpecialChars($sitename.' - '.$siteslogan);
	$pdf->SetHeaderData($cmsConfig['cms_print_logo'], $sitename, $pdfheader, ICMS_URL);
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor

	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf->setLanguageArray($l); //set language items
	
	// set default font subsetting mode
	$pdf->setFontSubsetting(TRUE);

	// set font
	$TextFont = (@_PDF_LOCAL_FONT && file_exists(ICMS_PDF_LIB_PATH.'/fonts/'._PDF_LOCAL_FONT.'.php')) ? _PDF_LOCAL_FONT : 'arialunicid0';
	$pdf -> SetFont($TextFont);
	//$pdf->SetFont('arialunicid0', '', 14, '', true);


	//initialize document
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->writeHTML($content, TRUE, 0);
	return $pdf->Output();
