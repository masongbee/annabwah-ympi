<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_permohonanijin extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('m_permohonanijin', '', TRUE);
	}
	
	function get_jenisabsen(){
		$result = $this->m_permohonanijin->get_jenisabsen();
		echo json_encode($result);
	}
	
	function get_personalia(){
		$result = $this->m_permohonanijin->get_personalia();
		echo json_encode($result);
	}
	
	function getNIK(){
		$pos = $this->input->post();
		if(! empty($pos))
		{
			$data['NIK'] = $pos['NIK'];
			$result = $this->m_permohonanijin->getNIK($data);
			echo json_encode($result);
		}
	}
	
	function getSisa(){
		/*
		 * Collect Data
		 */
		$nik 	= ($this->input->post('nik', TRUE) ? $this->input->post('nik', TRUE) : '');
		
		/*
		 * Processing Data
		 */
		$result = $this->m_permohonanijin->getSisa($nik);
		echo json_encode($result);
		/*$pos = $this->input->post();
		if(! empty($pos))
		{
			$data['JENIS'] = $pos['JENIS'];
			$data['KOLOM'] = $pos['KOLOM'];
			$data['KEY'] = $pos['KEY'];
			$result = $this->m_permohonanijin->getSisa($data);
			echo json_encode($result);
		}*/
	}
	
	function getAll(){
		/*
		 * Collect Data
		 */
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 15);
		$nik 	= ($this->input->post('nik', TRUE) ? $this->input->post('nik', TRUE) : '');
		
		$tglabsen 	= ($this->input->post('tglabsen', TRUE) ? $this->input->post('tglabsen', TRUE) : '');
		$allunit 	= ($this->input->post('allunit', TRUE) ? $this->input->post('allunit', TRUE) : '');
		
		/*
		 * Processing Data
		 */
		$result = $this->m_permohonanijin->getAll($nik,$start, $page, $limit, $tglabsen, $allunit);
		echo json_encode($result);
	}
	
	function save(){
		/*
		 * Collect Data ==> diambil dari [model.permohonanijin]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_permohonanijin->save($data);
		echo json_encode($result);
	}
	
	function delete(){
		/*
		 * Collect Data ==> diambil dari [model.permohonanijin]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_permohonanijin->delete($data);
		echo json_encode($result);
	}
	
	/**
	 * Fungsi	: export2Excel
	 * 
	 * Untuk menyimpan data yang didapat dari Grid ExtJS ke dalam file Excel.
	 * Tidak lagi mengakses database untuk mendapatkan data.
	 */
	function export2Excel(){
		$data     = json_decode($this->input->post('data',TRUE));
		$tglabsen = ($this->input->post('tglabsen', TRUE) ? $this->input->post('tglabsen', TRUE) : '');
		$allunit  = ($this->input->post('allunit', TRUE) ? $this->input->post('allunit', TRUE) : '');
		/*
		//load our new PHPExcel library
		$this->load->library('excel');
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$this->excel->getActiveSheet()->setTitle('test worksheet');
		
		$col = 0;
		foreach ($data[0] as $key => $value){
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $key);
			$this->excel->getActiveSheet()->getStyleByColumnAndRow($col, 1)->getFont()->setBold(true);
			$col++;
		}
		
		// Fetching the table data
		$row = 2;
		foreach($data as $record)
		{
			$col = ord("A");
			foreach ($data[0] as $key => $value)
			{
				// $this->excel->getActiveSheet()->setCellValue(chr($col).$row, $value);
				
				$col++;
			}
		
			$row++;
		}		
		*/
		//load our new PHPExcel library
		$this->load->library('excel');
		$objPHPExcel = $this->excel;
		$sheet = 0;

		$objWorkSheet = new PHPExcel_Worksheet($objPHPExcel);
		$objPHPExcel->addSheet($objWorkSheet, $sheet);
		$objPHPExcel->setActiveSheetIndex(0);

		$objWorkSheet->setTitle('KARIJIN');

		$records = $this->m_permohonanijin->getIjinPerTanggal($this->session->userdata('user_nik'),$tglabsen,$allunit);

		// judul sheet
		$objWorkSheet->mergeCells('A1:H1');
		$objWorkSheet->setCellValueByColumnAndRow(0, 1, "KARYAWAN IJIN PER TANGGAL: ".date('d-M-Y', strtotime($tglabsen)));
		
		if (sizeof($records)) {
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
				$col = ord("A");
				foreach ($record as $key => $value) {
					if (!is_null($value)) {
						$objWorkSheet->setCellValue(chr($col).$row, $value);
					}
					
					$col++;
				}
			
				$row++;
			}
		}

		$filename='permohonanijin.xlsx'; //save our workbook as this file name
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
		$getdata  = json_decode($this->input->post('data',TRUE));
		$tglabsen = ($this->input->post('tglabsen', TRUE) ? $this->input->post('tglabsen', TRUE) : '');
		$allunit  = ($this->input->post('allunit', TRUE) ? $this->input->post('allunit', TRUE) : '');

		$records = $this->m_permohonanijin->getIjinPerTanggal($this->session->userdata('user_nik'),$tglabsen,$allunit);
		
		$data["records"] = $records;
		$data["table"] = "permohonanijin";
		
		//html2pdf
		//Load the library
		$this->load->library('html2pdf');
		
		//Set folder to save PDF to
		$this->html2pdf->folder('./temp/');
		
		//Set the filename to save/download as
		$this->html2pdf->filename('permohonanijin.pdf');
		
		//Set the paper defaults
		$this->html2pdf->paper('a4', 'landscape');
		
		//Load html view
		$this->html2pdf->html($this->load->view('pdf_permohonanijin', $data, true));
		
		if($path = $this->html2pdf->create('save')) {
			//PDF was successfully saved or downloaded
			echo 'PDF saved to: ' . $path;
		}
	}
	
	function printRecords(){
		$getdata = json_decode($this->input->post('data',TRUE));
		$data["records"] = $getdata;
		$data["table"] = "permohonanijin";
		$print_view=$this->load->view("p_permohonanijin.php",$data,TRUE);
		if(!file_exists("temp")){
			mkdir("temp");
		}
		$print_file=fopen("temp/permohonanijin.html","w+");
		fwrite($print_file, $print_view);
		echo '1';
	}	
}