<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_pelamar extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('m_pelamar', '', TRUE);
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
		$result = $this->m_pelamar->getAll($start, $page, $limit);
		echo json_encode($result);
	}
	
	function save(){
		/*
		 * Collect Data ==> diambil dari [model.pelamar]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_pelamar->save($data);
		echo json_encode($result);
	}
	
	function delete(){
		/*
		 * Collect Data ==> diambil dari [model.pelamar]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_pelamar->delete($data);
		echo json_encode($result);
	}
}