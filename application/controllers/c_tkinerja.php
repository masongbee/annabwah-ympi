<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_tkinerja extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('m_tkinerja', '', TRUE);
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
		$result = $this->m_tkinerja->getAll($start, $page, $limit);
		echo json_encode($result);
	}
	
	function save(){
		/*
		 * Collect Data ==> diambil dari [model.tkinerja]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_tkinerja->save($data);
		echo json_encode($result);
	}
	
	function delete(){
		/*
		 * Collect Data ==> diambil dari [model.tkinerja]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_tkinerja->delete($data);
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
			
			$result = $this->m_tkinerja->do_upload($objPHPExcel, $filename);
			echo json_encode($result);
		}
	}

	function lapkrjkar(){
		/*
		 * Collect Data
		 */
		$data = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_tkinerja->lapkrjkar($data->TAHUN1, $data->TAHUN2, $data->NIK);
		echo json_encode($result);
	}
}