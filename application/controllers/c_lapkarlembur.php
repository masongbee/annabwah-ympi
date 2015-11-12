<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_lapkarlembur extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('m_lapkarlembur', '', TRUE);
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

		$records = $this->m_lapkarlembur->getLemburPerBulan($data->MONTH);
		$this->firephp->log($records);

		// judul sheet
		$objWorkSheet->mergeCells('A1:H1');
		$objWorkSheet->setCellValueByColumnAndRow(0, 1, "OVERTIME DATA BULAN: ".date('M-Y', strtotime($data->MONTH)));
		
		/*$col = 0;
		foreach ($data[0] as $key => $value){
			$objWorkSheet->setCellValueByColumnAndRow($col, 1, $key);
			$objWorkSheet->getStyleByColumnAndRow($col, 1)->getFont()->setBold(true);
			$col++;
		}
		
		// Fetching the table data
		$row = 2;
		foreach($data as $record)
		{
			$col = ord("A");
			foreach ($data[0] as $key => $value)
			{
				$cellvalue = $record->$key;
				
				if($key == strtoupper('lapkarlembur')){
					$objWorkSheet->getCell(chr($col).$row)->setValueExplicit($cellvalue, PHPExcel_Cell_DataType::TYPE_STRING);
				}else{
					$objWorkSheet->setCellValue(chr($col).$row, $cellvalue);
				}
				
				$col++;
			}
		
			$row++;
		}*/
		
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
}