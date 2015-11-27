<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_rekapjemputan extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('m_rekapjemputan', '', TRUE);
	}
	
	function getAll(){
		/*
		 * Collect Data
		 */
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 15);
		
		/*
		 * Processing Data
		 */
		$result = $this->m_rekapjemputan->getAll($start, $page, $limit);
		echo json_encode($result);
	}
	
	function save(){
		/*
		 * Collect Data ==> diambil dari [model.rekapjemputan]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_rekapjemputan->save($data);
		echo json_encode($result);
	}
	
	function delete(){
		/*
		 * Collect Data ==> diambil dari [model.rekapjemputan]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_rekapjemputan->delete($data);
		echo json_encode($result);
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
			
			echo json_encode($error);
		}
		else
		{
			$upload_data = $this->upload->data();
			
			$this->load->library('excel');
			$filename = $upload_data['file_name'];
			$objPHPExcel = PHPExcel_IOFactory::load(APPPATH.'../temp/'.$filename);
			
			$result = $this->m_rekapjemputan->do_upload($objPHPExcel, $filename);
			echo json_encode($result);
		}
	}

	function lapjempkar(){
		/*
		 * Collect Data
		 */
		$bulan  =   ($this->input->post('bulan', TRUE) ? $this->input->post('bulan', TRUE) : '');
		$nik  =   ($this->input->post('nik', TRUE) ? $this->input->post('nik', TRUE) : '');
		
		/*
		 * Processing Data
		 */
		$result = $this->m_rekapjemputan->lapjempkar($bulan, $nik);
		echo json_encode($result);
	}

	function lapjempkarExport2Excel(){
		$bulan  =   ($this->input->post('bulan', TRUE) ? $this->input->post('bulan', TRUE) : '');
		$nik  =   ($this->input->post('nik', TRUE) ? $this->input->post('nik', TRUE) : '');
		
		//load our new PHPExcel library
		$this->load->library('excel');
		$objPHPExcel = $this->excel;
		$sheet = 0;

		$objWorkSheet = new PHPExcel_Worksheet($objPHPExcel);
		$objPHPExcel->addSheet($objWorkSheet, $sheet);
		$objPHPExcel->setActiveSheetIndex(0);

		$objWorkSheet->setTitle('JEMPUTANKAR');

		$records = $this->m_rekapjemputan->lapjempkar($bulan, $nik);
		$records = $records["data"];
		
		// judul sheet
		$objWorkSheet->mergeCells('A1:H1');
		$objWorkSheet->setCellValueByColumnAndRow(0, 1, "DAFTAR JEMPUTAN KARYAWAN");
		
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

		$filename='daftarjemputankaryawan.xlsx'; //save our workbook as this file name
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
	
	function lapjempkarExport2PDF(){
		$bulan  =   ($this->input->post('bulan', TRUE) ? $this->input->post('bulan', TRUE) : '');
		$nik  =   ($this->input->post('nik', TRUE) ? $this->input->post('nik', TRUE) : '');

		$records = $this->m_rekapjemputan->lapjempkar($bulan, $nik);
		$records = $records["data"];
		
		$data["records"] = $records;
		$data["table"] = "rekapjemputan";
		
		//html2pdf
		//Load the library
		$this->load->library('html2pdf');
		
		//Set folder to save PDF to
		$this->html2pdf->folder('./temp/');
		
		//Set the filename to save/download as
		$this->html2pdf->filename('daftarjemputankaryawan.pdf');
		
		//Set the paper defaults
		$this->html2pdf->paper('a4', 'landscape');
		
		//Load html view
		$this->html2pdf->html($this->load->view('pdf_riwayattraining', $data, true));
		
		if($path = $this->html2pdf->create('save')) {
			//PDF was successfully saved or downloaded
			echo 'PDF saved to: ' . $path;
		}
	}
}