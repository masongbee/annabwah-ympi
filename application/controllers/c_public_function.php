<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_public_function extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('m_public_function', '', TRUE);
	}
	
	function getJabatan(){
		/*
		 * Collect Data
		 */
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 15);
		$filter =   ($this->input->post('query', TRUE) ? $this->input->post('query', TRUE) : '');
		
		/*
		 * Processing Data
		 */
		$result = $this->m_public_function->getJabatan($start, $page, $limit, $filter);
		echo json_encode($result);
	}
	
	function permohonan_save(){
		/*
		 * Collect Data
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_public_function->permohonan_save($data);
		echo json_encode($result);
	}

	function getKaryawanByUnitKerja(){
		/*
		 * Processing Data
		 */
		$result = $this->m_public_function->getKaryawanByUnitKerja();
		echo json_encode($result);
	}

	function get_atasan_spl(){
		$result = $this->m_public_function->get_atasan_spl();
		echo json_encode($result);
	}

	function get_atasan_cuti(){
		$result = $this->m_public_function->get_atasan_cuti();
		echo json_encode($result);
	}

	function get_personalia(){
		$result = $this->m_public_function->get_personalia();
		echo json_encode($result);
	}

	function getPosisiLowonganByGellow(){
		$gellow =   ($this->input->post('gellow', TRUE) ? $this->input->post('gellow', TRUE) : '');

		$result = $this->m_public_function->getPosisiLowonganByGellow($gellow);
		echo json_encode($result);
	}

	function getLapSeleksiKar(){
		$gellow      =   ($this->input->post('gellow', TRUE) ? $this->input->post('gellow', TRUE) : '');
		$idjab       =   ($this->input->post('idjab', TRUE) ? $this->input->post('idjab', TRUE) : '');
		$kodejab     =   ($this->input->post('kodejab', TRUE) ? $this->input->post('kodejab', TRUE) : '');
		$kodeseleksi =   ($this->input->post('kodeseleksi', TRUE) ? $this->input->post('kodeseleksi', TRUE) : '');

		$result = $this->m_public_function->getLapSeleksiKar($gellow,$idjab,$kodejab,$kodeseleksi);
		echo json_encode($result);
	}

	function getLapLevelLowonganByGellowIdjab(){
		$gellow =   ($this->input->post('gellow', TRUE) ? $this->input->post('gellow', TRUE) : '');
		$idjab  =   ($this->input->post('idjab', TRUE) ? $this->input->post('idjab', TRUE) : '');

		$result = $this->m_public_function->getLapLevelLowonganByGellowIdjab($gellow,$idjab);
		echo json_encode($result);
	}

	function getLevelJabatan(){
		/*
		 * Collect Data
		 */
		$kodejab  =   ($this->input->post('kodejab', TRUE) ? $this->input->post('kodejab', TRUE) : 0);
		
		/*
		 * Processing Data
		 */
		$result = $this->m_public_function->getLevelJabatan($kodejab);
		echo json_encode($result);
	}

	function getJenisSeleksi(){
		/*
		 * Collect Data
		 */
		$kodeseleksi  =   ($this->input->post('kodeseleksi', TRUE) ? $this->input->post('kodeseleksi', TRUE) : 0);
		
		/*
		 * Processing Data
		 */
		$result = $this->m_public_function->getJenisSeleksi($kodeseleksi);
		echo json_encode($result);
	}
	
}