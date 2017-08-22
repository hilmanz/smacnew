<?php
/*
	PDF Helper
	Author Babar
	21/12/2011
	
	Updates
	16/05/2012 - duf - implement method for inserting entry into smac_web.job_pdf_report 
*/
global $ENGINE_PATH;
require_once $ENGINE_PATH.'Utility/tcpdf/config/lang/eng.php';
require_once $ENGINE_PATH.'Utility/tcpdf/tcpdf.php';

class pdfHelper extends Application{
	function addJob($campaign_id,$label,$report_types,$report_filename,$report_start,$report_end,$market){
		$campaign_id = mysql_escape_string($campaign_id);
		$label = mysql_escape_string($label);
		$report_start = mysql_escape_string($report_start);
		$report_end = mysql_escape_string($report_end);
		$market = mysql_escape_string($market);
		$report_filename = mysql_escape_string($report_filename);
		$report_types = mysql_escape_string($report_types);
		$type = explode(",",$report_types);
		$task_count = sizeof($type);
		$sql0 = "SELECT campaign_id FROM smac_web.job_report_pdf 
				WHERE campaign_id={$campaign_id} 
				AND report_start='{$report_start}'
				AND report_end='{$report_end}'
				AND market='{$market}'
				AND n_status <> 2 LIMIT 1";
		$sql = "INSERT INTO smac_web.job_report_pdf 
				(campaign_id, label,report_types, report_filename, request_date, run_time, end_time, report_start, report_end, task_count,market, n_status)
				VALUES
				({$campaign_id}, '{$label}','{$report_types}', '{$report_filename}', NOW(), NOW(), '', '{$report_start}', '{$report_end}', {$task_count},'{$market}', 0)";
		
		$this->open(0);
		//make sure that only 1 report created at one time
		$rs = $this->fetch($sql0);
		if($rs['campaign_id']==$campaign_id){
			return false;
		}
		$q = $this->query($sql);
		$this->close();
		return $q;
	}
	function getReports($campaign_id,$start,$limit=20){
		$campaign_id = mysql_escape_string($campaign_id);
		$start = intval($start);
		$sql = "SELECT *,DATE_FORMAT(request_date,'%d/%m/%Y') as submit_date 
				FROM smac_web.job_report_pdf 
				WHERE 
				campaign_id={$campaign_id} 
				ORDER BY id DESC
				LIMIT {$start},{$limit}";
		
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	function writePDF($author,$title,$fontsize=10,$content){
		if(!empty($author) && !empty($title) && !empty($fontsize) && !empty($content)){
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			// set document information
			$pdf->SetAuthor($author);
			$pdf->SetTitle($title);
			// set header and footer fonts
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			
			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			
			//set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			
			//set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			
			//set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			
			//set some language-dependent strings
			$pdf->setLanguageArray(@$l);
			
			// ---------------------------------------------------------
			
			// set default font subsetting mode
			$pdf->setFontSubsetting(true);
			
			// Set font
			// dejavusans is a UTF-8 Unicode font, if you only need to
			// print standard ASCII chars, you can use core fonts like
			// helvetica or times to reduce file size.
			$pdf->SetFont('dejavusans', '', $fontsize, '', true);
			
			// Add a page
			// This method has several options, check the source code documentation for more information.
			$pdf->AddPage();
			$pdf->writeHTML($content, true, false, true, false, '');
			return $pdf->Output($title.".pdf","I");
		}
		else{
			echo '<script type="text/javascript">alert("Failed to create PDF!");</script>';
			return false;
		}
	}
}
?>