<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_tahapseleksi extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('m_tahapseleksi', '', TRUE);
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
		$result = $this->m_tahapseleksi->getAll($start, $page, $limit);
		echo json_encode($result);
	}
	
	function save(){
		/*
		 * Collect Data ==> diambil dari [model.tahapseleksi]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_tahapseleksi->save($data);
		echo json_encode($result);
	}
	
	function delete(){
		/*
		 * Collect Data ==> diambil dari [model.tahapseleksi]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_tahapseleksi->delete($data);
		echo json_encode($result);
	}

	function lapseleksikarExport2Excel(){
		$gellow      = ($this->input->post('gellow', TRUE) ? $this->input->post('gellow', TRUE) : '');
		$idjab       = ($this->input->post('idjab', TRUE) ? $this->input->post('idjab', TRUE) : '');
		$kodejab     = ($this->input->post('kodejab', TRUE) ? $this->input->post('kodejab', TRUE) : '');
		$kodeseleksi = ($this->input->post('kodeseleksi', TRUE) ? $this->input->post('kodeseleksi', TRUE) : '');
		
		$rs_namajab     = $this->m_public_function->getJabatan(0, 1, 15, $idjab);
		$namajab        = $rs_namajab["data"][0]->NAMAJAB;
		$rs_namalevel   = $this->m_public_function->getLevelJabatan($kodejab);
		$namalevel      = $rs_namalevel->NAMALEVEL;
		$rs_namaseleksi = $this->m_public_function->getJenisSeleksi($kodeseleksi);
		$namaseleksi    = $rs_namaseleksi->NAMASELEKSI;

		//load our new PHPExcel library
		$this->load->library('excel');
		$objPHPExcel = $this->excel;
		$sheet = 0;

		$objWorkSheet = new PHPExcel_Worksheet($objPHPExcel);
		$objPHPExcel->addSheet($objWorkSheet, $sheet);
		$objPHPExcel->setActiveSheetIndex(0);

		$objWorkSheet->setTitle('SELEKSIKAR');

		$records = $this->m_public_function->getLapSeleksiKar($gellow,$idjab,$kodejab,$kodeseleksi);
		$records = $records["data"];
		
		// judul sheet
		$objWorkSheet->mergeCells('A1:H1');
		$objWorkSheet->setCellValueByColumnAndRow(0, 1, "DAFTAR SELEKSI KARYAWAN");
		$objWorkSheet->mergeCells('A2:H2');
		$objWorkSheet->setCellValueByColumnAndRow(0, 2, "GELOMBANG LOWONGAN: ".$gellow);
		$objWorkSheet->mergeCells('A3:H3');
		$objWorkSheet->setCellValueByColumnAndRow(0, 3, "NAMA JABATAN: ".$namajab);
		$objWorkSheet->mergeCells('A4:H4');
		$objWorkSheet->setCellValueByColumnAndRow(0, 4, "NAMA LEVEL JABATAN: ".$namalevel);
		$objWorkSheet->mergeCells('A5:H5');
		$objWorkSheet->setCellValueByColumnAndRow(0, 5, "NAMA SELEKSI: ".$namaseleksi);
		
		if (sizeof($records)) {
			$col = 0;
			foreach ($records[0] as $key => $value){
				$objWorkSheet->setCellValueByColumnAndRow($col, 6, $key);
				$objWorkSheet->getStyleByColumnAndRow($col, 6)->getFont()->setBold(true);
				$col++;
			}
			
			// Fetching the table records
			$row = 7;
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

		$filename='daftarseleksikaryawan.xlsx'; //save our workbook as this file name
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
	
	function lapseleksikarExport2PDF(){
		$gellow      = ($this->input->post('gellow', TRUE) ? $this->input->post('gellow', TRUE) : '');
		$idjab       = ($this->input->post('idjab', TRUE) ? $this->input->post('idjab', TRUE) : '');
		$kodejab     = ($this->input->post('kodejab', TRUE) ? $this->input->post('kodejab', TRUE) : '');
		$kodeseleksi = ($this->input->post('kodeseleksi', TRUE) ? $this->input->post('kodeseleksi', TRUE) : '');

		$records = $this->m_public_function->getLapSeleksiKar($gellow,$idjab,$kodejab,$kodeseleksi);
		$records = $records["data"];
		
		$data["records"] = $records;
		$data["table"] = "tahapseleksi";
		
		//html2pdf
		//Load the library
		$this->load->library('html2pdf');
		
		//Set folder to save PDF to
		$this->html2pdf->folder('./temp/');
		
		//Set the filename to save/download as
		$this->html2pdf->filename('daftarseleksikaryawan.pdf');
		
		//Set the paper defaults
		$this->html2pdf->paper('a4', 'landscape');
		
		//Load html view
		$this->html2pdf->html($this->load->view('pdf_all', $data, true));
		
		if($path = $this->html2pdf->create('save')) {
			//PDF was successfully saved or downloaded
			echo 'PDF saved to: ' . $path;
		}
	}
}