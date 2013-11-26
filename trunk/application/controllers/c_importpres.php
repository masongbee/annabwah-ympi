<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_importpres extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('m_importpres', '', TRUE);
	}
	
	function ImportPresensi($tglmulai,$tglsampai){
		/*
		 * Processing Data
		 */
		$result = $this->m_importpres->ImportPresensi($tglmulai,$tglsampai);
		echo json_encode($result);
	}
	
	function killProsesImport()
	{
		$rs = $this->db->query("SHOW FULL PROCESSLIST");
		foreach ($rs->result() as $val) {
			$pid=$val->Id;
			$userId = $val->User;
			$cmd = $val->Command;
			if($userId == 'ekojs' && $cmd == 'Query')
			{
				$this->db->query("KILL QUERY $pid");
			}
		}
		
		$json	= array(
			'success'   => TRUE,
			'message'   => 'Process Killed'
		);
		
		return json_encode($json);
	}
	
	function getProsesImport(){
		$result = $this->m_importpres->getProsesImport();
		echo json_encode($result);
	}
	
	function setTukarShift(){
		$data   = json_decode($this->input->post('data',TRUE));
		
		$result = $this->m_importpres->setTukarShift($data);
		echo json_encode($result);
	}
	
	function getShift(){
		$nshift 	= ($this->input->post('nshift', TRUE) ? $this->input->post('nshift', TRUE) : '');
		$tgls		= ($this->input->post('tgls', TRUE) ? $this->input->post('tgls', TRUE) : '');
		
		$result = $this->m_importpres->getShift($nshift,$tgls);
		echo json_encode($result);
	}
	
	function getAll(){
		/*
		 * Collect Data
		 */
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 20);
		
		/* Collect Filter */
		$saring 	= ($this->input->post('saring', TRUE) ? $this->input->post('saring', TRUE) : '');
		$sort   = ($this->input->post('sort', TRUE) ? $this->input->post('sort', TRUE) : null);
		$filters 	= ($this->input->post('filter', TRUE) ? $this->input->post('filter', TRUE) : null);
		
		$tglmulai 	= ($this->input->post('tglmulai', TRUE) ? $this->input->post('tglmulai', TRUE) : '');
		$tglsampai 	= ($this->input->post('tglsampai', TRUE) ? $this->input->post('tglsampai', TRUE) : '');
		//$this->firephp->info($this->input->post());
		
		/*
		 * Processing Data
		 */
		$result = $this->m_importpres->getAllData($tglmulai, $tglsampai,$saring,$sort,$filters,$start, $page, $limit);
		echo json_encode($result);
	}
	
	function save(){
		/*
		 * Collect Data ==> diambil dari [model.importpres]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_importpres->save($data);
		echo json_encode($result);
	}
	
	function delete(){
		/*
		 * Collect Data ==> diambil dari [model.importpres]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_importpres->delete($data);
		echo json_encode($result);
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
				$cellvalue = $record->$key;
				
				if($key == strtoupper('importpres')){
					$this->excel->getActiveSheet()->getCell(chr($col).$row)->setValueExplicit($cellvalue, PHPExcel_Cell_DataType::TYPE_STRING);
				}else{
					$this->excel->getActiveSheet()->setCellValue(chr($col).$row, $cellvalue);
				}
				
				$col++;
			}
		
			$row++;
		}		
		
		$filename='importpres.xlsx'; //save our workbook as this file name
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
		$data["records"] = $getdata;
		$data["table"] = "presensi";
		
		//html2pdf
		//Load the library
		$this->load->library('html2pdf');
		
		//Set folder to save PDF to
		$this->html2pdf->folder('./temp/');
		
		//Set the filename to save/download as
		$this->html2pdf->filename('importpres.pdf');
		
		//Set the paper defaults
		$this->html2pdf->paper('a4', 'portrait');
		
		//Load html view
		$this->html2pdf->html($this->load->view('pdf_importpres', $data, true));
		
		if($path = $this->html2pdf->create('save')) {
			//PDF was successfully saved or downloaded
			echo 'PDF saved to: ' . $path;
		}
	}
	
	function printRecords(){
		$getdata = json_decode($this->input->post('data',TRUE));
		$data["records"] = $getdata;
		$data["table"] = "presensi";
		$print_view=$this->load->view("p_importpres.php",$data,TRUE);
		if(!file_exists("temp")){
			mkdir("temp");
		}
		$print_file=fopen("temp/importpres.html","w+");
		fwrite($print_file, $print_view);
		echo '1';
	}
	
	function setMasuk(){
		$tglmulai 	= ($this->input->post('tglmulai', TRUE) ? $this->input->post('tglmulai', TRUE) : '');
		$tglsampai 	= ($this->input->post('tglsampai', TRUE) ? $this->input->post('tglsampai', TRUE) : '');
		/*
		 * Processing Data
		 */
		$result = $this->m_importpres->setMasuk($tglmulai, $tglsampai);
		echo json_encode($result);
	}
	
	function setKeluar(){
		$tglmulai 	= ($this->input->post('tglmulai', TRUE) ? $this->input->post('tglmulai', TRUE) : '');
		$tglsampai 	= ($this->input->post('tglsampai', TRUE) ? $this->input->post('tglsampai', TRUE) : '');
		
		$result = $this->m_importpres->setKeluar($tglmulai, $tglsampai);
		echo json_encode($result);
	}
	
	function get_shift(){
		/*
		 * Collect Data ==> diambil dari [model.importpres]
		 */
		$datetime   = ($this->input->post('tjmasuk', TRUE) ? $this->input->post('tjmasuk', TRUE) : NULL);
		
		/*
		 * Processing Data
		 */
		$result = $this->m_importpres->get_shift($datetime);
		echo json_encode($result);
	}
}