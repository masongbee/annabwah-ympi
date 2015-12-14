<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_lapkarlembur extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('m_lapkarlembur', '', TRUE);
	}

	function getColExcel($idx){
		$arrcolexcel = array(0=>"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ","BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ");
		return $arrcolexcel[$idx];
	}
	
	/**
	 * Fungsi	: export2Excel
	 * 
	 * Untuk menyimpan data yang didapat dari Grid ExtJS ke dalam file Excel.
	 * Tidak lagi mengakses database untuk mendapatkan data.
	 */
	function export2Excel(){
		$data = json_decode($this->input->post('data',TRUE));
		
		//load our new PHPExcel library
		$this->load->library('excel');
		$objPHPExcel = $this->excel;
		$sheet = 0;

		$objWorkSheet = new PHPExcel_Worksheet($objPHPExcel);
		$objPHPExcel->addSheet($objWorkSheet, $sheet);
		$objPHPExcel->setActiveSheetIndex(0);

		$objWorkSheet->setTitle('KARLEMBUR');

		$records = $this->m_lapkarlembur->genLemburPerBulan($data->MONTH);

		// judul sheet
		$objWorkSheet->mergeCells('A1:H1');
		$objWorkSheet->setCellValueByColumnAndRow(0, 1, "OVERTIME DATA BULAN: ".date('M-Y', strtotime($data->MONTH)));
		
		$col = 0;
		foreach ($records[0] as $key => $value){
			$objWorkSheet->setCellValueByColumnAndRow($col, 2, $key);
			$objWorkSheet->getStyleByColumnAndRow($col, 2)->getFont()->setBold(true);
			$col++;
		}
		
		// Fetching the table records
		$row = 3;
		foreach($records as $record)
		{
			$colrec = 0;//$col = ord("A");
			foreach ($record as $key => $value) {
				if (!is_null($value)) {
					$objWorkSheet->setCellValue($this->getColExcel($colrec).$row, $value);
				}
				
				$colrec++;
			}
		
			$row++;
		}
		
		$filename='lapkarlembur.xlsx'; //save our workbook as this file name
		//header('Content-Type: application/vnd.ms-excel'); //mime type for Excel5
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type for Excel2007
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save(APPPATH.'../temp/'.$filename);
		echo $filename;
	}

	function export2PDF(){
		$getdata = json_decode($this->input->post('data',TRUE));
		$rs_records = $this->m_lapkarlembur->genLemburPerBulan($getdata->MONTH);
		
		$data["records"] = $rs_records;
		$data["table"] = "hitungpresensi";
		
		//html2pdf
		//Load the library
		$this->load->library('html2pdf');
		
		//Set folder to save PDF to
		$this->html2pdf->folder('./temp/');
		
		//Set the filename to save/download as
		$this->html2pdf->filename('lapkarlembur.pdf');
		
		//Set the paper defaults
		$this->html2pdf->paper('a0', 'landscape');
		
		//Load html view
		$this->html2pdf->html($this->load->view('pdf_hitungpresensi', $data, true));
		
		if($path = $this->html2pdf->create('save')) {
			//PDF was successfully saved or downloaded
			echo 'PDF saved to: ' . $path;
		}
	}

	function lapkarlembur(){
		/*
		 * Collect Data
		 */
		$data = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_lapkarlembur->lapkarlembur($data->MONTH);
		echo json_encode($result);
	}
}