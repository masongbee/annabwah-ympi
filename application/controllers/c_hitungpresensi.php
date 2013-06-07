<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_hitungpresensi extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('m_hitungpresensi', '', TRUE);
	}
	
	function JamKerja($bln){		
		/*
		 * Processing Data
		 */
		$result = $this->m_hitungpresensi->JamKerja($bln);
		echo json_encode($result);
	}
	
	function getAll(){
		/*
		 * Collect Data
		 */
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 10);
		$group_id = ($this->input->post('GROUP_ID', TRUE) ? $this->input->post('GROUP_ID', TRUE) : 0);
	
		/*
		 * Processing Data
		 */
		$result = $this->m_hitungpresensi->getAll($group_id, $start, $page, $limit);
		echo json_encode($result);
	}
	
	function save(){
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Collect Data ==> diambil dari [model.User]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_hitungpresensi->save($data);
		echo json_encode($result);
	}
	
	function delete(){
		/*
		 * Collect Data ==> diambil dari [model.User]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_hitungpresensi->delete($data);
		echo json_encode($result);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */