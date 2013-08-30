<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_upahpokok extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('m_upahpokok', '', TRUE);
		$this->load->helper(array('form', 'url'));
	}
	
	function getAll(){
		/*
		 * Collect Data
		 */
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 15);
		$filter = json_decode($this->input->post('filter', TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_upahpokok->getAll($start, $page, $limit, $filter);
		echo json_encode($result);
	}
	
	function save(){
		/*
		 * Collect Data ==> diambil dari [model.upahpokok]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_upahpokok->save($data);
		echo json_encode($result);
	}
	
	function delete(){
		/*
		 * Collect Data ==> diambil dari [model.upahpokok]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_upahpokok->delete($data);
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
				
				if($key == strtoupper('upahpokok')){
					$this->excel->getActiveSheet()->getCell(chr($col).$row)->setValueExplicit($cellvalue, PHPExcel_Cell_DataType::TYPE_STRING);
				}else{
					$this->excel->getActiveSheet()->setCellValue(chr($col).$row, $cellvalue);
				}
				
				$col++;
			}
		
			$row++;
		}		
		
		$filename='upahpokok.xlsx'; //save our workbook as this file name
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
		$data["table"] = "upahpokok";
		
		//html2pdf
		//Load the library
		$this->load->library('html2pdf');
		
		//Set folder to save PDF to
		$this->html2pdf->folder('./temp/');
		
		//Set the filename to save/download as
		$this->html2pdf->filename('upahpokok.pdf');
		
		//Set the paper defaults
		$this->html2pdf->paper('a4', 'portrait');
		
		//Load html view
		$this->html2pdf->html($this->load->view('pdf_upahpokok', $data, true));
		
		if($path = $this->html2pdf->create('save')) {
			//PDF was successfully saved or downloaded
			echo 'PDF saved to: ' . $path;
		}
	}
	
	function printRecords(){
		$getdata = json_decode($this->input->post('data',TRUE));
		$data["records"] = $getdata;
		$data["table"] = "upahpokok";
		$print_view=$this->load->view("p_upahpokok.php",$data,TRUE);
		if(!file_exists("temp")){
			mkdir("temp");
		}
		$print_file=fopen("temp/upahpokok.html","w+");
		fwrite($print_file, $print_view);
		echo '1';
	}
	
	function do_upload(){
		$config['upload_path'] = './temp/';
		$config['allowed_types'] = 'xlsx';
		$config['max_size']	= '200';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload())
		{
			$error = array(
				'success'	=> false,
				'msg' 		=> $this->upload->display_errors()
			);
			
			//$this->load->view('upload_form', $error);
			//$this->firephp->log($error);
			echo json_encode($error);
		}
		else
		{
			$upload_data = $this->upload->data();
			
			$this->load->library('excel');
			$filename = $upload_data['file_name'];
			$objPHPExcel = PHPExcel_IOFactory::load(APPPATH.'../temp/'.$filename);
			
			$result = $this->m_upahpokok->do_upload($objPHPExcel, $filename);
			echo json_encode($result);
		}
	}
}