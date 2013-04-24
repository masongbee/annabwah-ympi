<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class	: C_grade
 * 
 * @author masongbee
 *
 */
class C_grade extends CI_Controller {

	function __construct(){
		parent::__construct();
		//$this->load->library('Cezpdf');
		
		$this->load->model('m_grade', '', TRUE);
		
		
		//$this->load->helper('dompdf');
		//$this->load->helper('file');
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
		$result = $this->m_grade->getAll($start, $page, $limit);
		echo json_encode($result);
	}
	
	function save(){
		/*
		 * Collect Data ==> diambil dari [model.Grade]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_grade->save($data);
		echo json_encode($result);
	}
	
	function delete(){
		/*
		 * Collect Data ==> diambil dari [model.Grade]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_grade->delete($data);
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
		/*$this->firephp->log($data[0]);
		foreach ($data[0] as $key => $value){
			$this->firephp->log($key);
		}*/
		
		//load our new PHPExcel library
		$this->load->library('excel');
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$this->excel->getActiveSheet()->setTitle('test worksheet');
		
		$col = 0;
		foreach ($data[0] as $key => $value){
			//$this->firephp->log($key);
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $key);
			$this->excel->getActiveSheet()->getStyleByColumnAndRow($col, 1)->getFont()->setBold(true);
			$col++;
		}
		
		// Fetching the table data
		$row = 2;
		foreach($data as $record)
		{
			//$col = 0;
			$col = ord("A");
			foreach ($data[0] as $key => $value)
			{
				$cellvalue = $record->$key;
				
				if($key == 'GRADE'){
					$this->excel->getActiveSheet()->getCell(chr($col).$row)->setValueExplicit($cellvalue, PHPExcel_Cell_DataType::TYPE_STRING);
					//$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $cellvalue);
					//$this->excel->getActiveSheet()->setCellValue(chr($col).$row, $cellvalue);
				}else{
					//$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $cellvalue);
					$this->excel->getActiveSheet()->setCellValue(chr($col).$row, $cellvalue);
				}
				
				$col++;
			}
		
			$row++;
		}
		
		
		
		
		$filename='grade.xlsx'; //save our workbook as this file name
		//header('Content-Type: application/vnd.ms-excel'); //mime type for Excel5
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type for Excel2007
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		//force user to download the Excel file without writing it to server's HD
		//$objWriter->save('php://output');
		$objWriter->save(APPPATH.'../temp/'.$filename);
		echo $filename;
	}
	
	function export2PDF(){
		$getdata = json_decode($this->input->post('data',TRUE));
		$data["records"] = $getdata;
		
		//html2pdf
		//Load the library
		$this->load->library('html2pdf');
		
		//Set folder to save PDF to
		$this->html2pdf->folder('./temp/');
		
		//Set the filename to save/download as
		$this->html2pdf->filename('grade.pdf');
		
		//Set the paper defaults
		$this->html2pdf->paper('a4', 'portrait');
		
		//Load html view
		/*$data = array(
						'title' => 'PDF Created',
						'message' => 'Hello World!'
		);*/
		
		//Load html view
		$this->html2pdf->html($this->load->view('pdf', $data, true));
		
		if($path = $this->html2pdf->create('save')) {
			//PDF was successfully saved or downloaded
			echo 'PDF saved to: ' . $path;
		}
	}
	
	//function export2PDF(){
		/*$this->load->library('cezpdf');
		$pdf = new Cezpdf();
		$pdf->selectFont('./assets/fonts/Helvetica');
		//$pdf->ezText('Hello World!',50);
		$data = array(
						array('num'=>1,'name'=>'gandalf','type'=>'wizard')
						,array('num'=>2,'name'=>'bilbo','type'=>'hobbit','url'=>'http://www.ros.co.nz/pdf/')
						,array('num'=>3,'name'=>'frodo','type'=>'hobbit')
						,array('num'=>4,'name'=>'saruman','type'=>'baddude','url'=>'http://sourceforge.net/projects/pdf-php')
						,array('num'=>5,'name'=>'sauron','type'=>'really bad dude')
		);
		$pdf->ezTable($data);
		$pdf->ezOutput();*/
		
		
		// Load all views as normal
		/*$getdata = json_decode($this->input->post('data',TRUE));
		$data["records"] = $getdata;
		$this->load->view("welcome_message",$data,TRUE);
		// Get output html
		$html = $this->output->get_output();
		
		// Load library
		$this->load->library('dompdf_gen');
		
		// Convert to PDF
		$this->dompdf->load_html($html);
		$this->dompdf->render();
		//$this->dompdf->stream("welcome.pdf");
		$output = $this->dompdf->output();
		file_put_contents('Brochure.pdf', $output);
		exit;*/
		
		/*$getdata = json_decode($this->input->post('data',TRUE));
		$data["records"] = $getdata;
		
		$html = $this->load->view("p_grade.php",$data,TRUE);
		//$html = $this->output->get_output();
		
		// Load library
		$this->load->library('dompdf_gen');
		
		// Convert to PDF
		$this->dompdf->load_html($html);
		$this->dompdf->render();
		
		$output = $this->dompdf->output();
		if(!file_exists("temp")){
			mkdir("temp");
		}
		file_put_contents('temp/grade.pdf', $output);
		//pdf_create($html, 'grade', FALSE);
		echo '1';*/
		
		
		
		/*$getdata = json_decode($this->input->post('data',TRUE));
		$data["records"] = $getdata;
		//$this->load->view("p_grade.php",$data,TRUE);
		
		$rs = $this->printRecordsTest($getdata);
		if($rs == 1){
			$html = fopen('./temp/grade.html', 'r');
		}
		
		// Load library
		$this->load->library('dompdf_gen');
		//$html = $this->output->get_output();
		// Convert to PDF
		$this->dompdf->load_html($html);
		$this->dompdf->render();
		
		$output = $this->dompdf->output();
		
		file_put_contents('./temp/grade.pdf', $output);
		
		echo '1';*/
		
		
		
		
		/*$getdata = json_decode($this->input->post('data',TRUE));
		$data["records"] = $getdata;
		$html = $this->load->view("p_grade_pdf.php",$data,TRUE);
		//echo $html;
		pdf_create($html, 'grade', FALSE);
		//redirect(base_url()."print/grade.pdf");
		echo '1';*/
		
		
		
		
		
		
		
		/*
		//Load the library
		$this->load->library('html2pdf');
	
		//$htmlFile = "./temp/grade.html";
		//$buffer = file_get_contents($htmlFile);
		//Set folder to save PDF to
		$this->html2pdf->folder('./temp/');
		
		//Set the filename to save/download as
		$this->html2pdf->filename('test.pdf');
		
		//Set the paper defaults
		$this->html2pdf->paper('a4', 'portrait');
		
		//Load html view
		//$records = json_decode($this->input->post('data',TRUE));
		//$data["records"] = $getdata;
		//$this->firephp->log($getdata);
		
		//$print_view=$this->load->view("p_grade_pdf.php",$data,TRUE);
		$print_view=fopen('temp/grade.html', 'r');
		
		$this->html2pdf->html($print_view);
		$this->html2pdf->create('save');
		echo '1';*/
		
		
		
		
		
		//$this->load->library('html2fpdf');
		/*require(base_url().'application/libraries/html2fpdf/html2fpdf.php');
		$htmlFile = "temp/grade.html";
		$file = fopen($htmlFile,"r");
		$size_of_file = filesize($htmlFile);
		$buffer = fread($file, $size_of_file);
		fclose($file);
		$pdf=new HTML2FPDF();
		$pdf->AddPage();
		$pdf->WriteHTML($buffer);
		$pdf->Output('doc.pdf','I');*/
	//}
	
	/**
	 * Fungsi 	: printRecords
	 * 
	 * Untuk proses mencetak data yang didapat dari Grid ExtJS.
	 * Tidak lagi mengakses database untuk mendapatkan data.
	 */
	function printRecords(){
		$getdata = json_decode($this->input->post('data',TRUE));
		$data["records"] = $getdata;
		$print_view=$this->load->view("p_grade.php",$data,TRUE);
		if(!file_exists("temp")){
			mkdir("temp");
		}
		$print_file=fopen("temp/grade.html","w+");
		fwrite($print_file, $print_view);
		echo '1';
	}
	
	
	function printRecordsTest($getdata){
		//$getdata = json_decode($this->input->post('data',TRUE));
		$data["records"] = $getdata;
		$print_view=$this->load->view("p_grade.php",$data,TRUE);
		if(!file_exists("temp")){
			mkdir("temp");
		}
		$print_file=fopen("temp/grade.html","w+");
		fwrite($print_file, $print_view);
		return 1;
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */