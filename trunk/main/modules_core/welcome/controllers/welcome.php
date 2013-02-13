<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
	
	public function index(){
		$this->load->view('welcome');
	}
	
	/*public function getAllUser(){
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 10);
	
		$query  = $this->db->limit($limit, $start)->get('user')->result();
		$total  = $this->db->get('user')->num_rows();
	
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
	
	public function saveUser(){
		$data   = json_decode($this->input->post('data',TRUE));
		$last   = NULL;
		if($data->id !== NULL){
			$this->db->where('id', $data->id)->update('user', $data);
			$last   = $data;
		}else{
			$this->db->insert('user', $data);
			$last   = $this->db->limit(1,0)->order_by('id', 'DESC')->get('user')->row();
		}
		$total  = $this->db->get('user')->num_rows();
	
		$json   = array(
				"success"   => TRUE,
				"message"   => 'Data berhasil disimpan',
				'total'     => $total,
				"data"      => $last
		);
	
		echo json_encode($json);
	}
	
	public function deleteUser(){
		$data   = json_decode($this->input->post('data',TRUE));
		$this->db->where('id', $data->id)->delete('user');
	
		$total  = $this->db->get('user')->num_rows();
		$last = $this->db->get('user')->result();
	
		$json   = array(
				"success"   => TRUE,
				"message"   => 'Data berhasil dihapus',
				'total'     => $total,
				"data"      => $last
		);
	
		echo json_encode($json);
	}*/
	
	/*
	 * GRADE
	 */
	public function getAllGrade(){
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 5);
	
		$query  = $this->db->limit($limit, $start)->order_by('id', 'desc')->get('grade')->result();
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
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */