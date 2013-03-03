<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grade extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct(){
		parent::__construct();
	}
	
	/*
	 * GRADE
	 */
	public function getAllGrade(){
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 15);
	
		$query  = $this->db->where('id <', 10)->limit($limit, $start)->order_by('id', 'desc')->get('grade')->result();
		$total  = $this->db->get('grade')->num_rows();
	
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}
	
		$json   = array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total,
				'data'      => $data
		);
	
		echo json_encode($json);
	}
	
	public function saveGrade(){
		$data   = json_decode($this->input->post('data',TRUE));
		$last   = NULL;
		if(($data->ID !== NULL) && ($data->ID != '')){
			$this->db->where('id', $data->ID)->update('grade', $data);
			$last   = $data;
		}else{
			$this->db->insert('grade', $data);
			$last   = $this->db->limit(1,0)->order_by('id', 'DESC')->get('grade')->row();
		}
		$total  = $this->db->get('grade')->num_rows();
	
		$json   = array(
				"success"   => TRUE,
				"message"   => 'Data berhasil disimpan',
				'total'     => $total,
				"data"      => $last
		);
	
		echo json_encode($json);
	}
	
	public function deleteGrade(){
		$data   = json_decode($this->input->post('data',TRUE));
		$this->db->where('id', $data->ID)->delete('grade');
	
		$total  = $this->db->get('grade')->num_rows();
		$last = $this->db->get('grade')->result();
	
		$json   = array(
				"success"   => TRUE,
				"message"   => 'Data berhasil dihapus',
				'total'     => $total,
				"data"      => $last
		);
	
		echo json_encode($json);
	}
	
	public function export2Excel(){
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
	
	public function printRecords(){
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
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */