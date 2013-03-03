<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

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
	public function getAll(){
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 10);
		$group_id = ($this->input->post('GROUP_ID', TRUE) ? $this->input->post('GROUP_ID', TRUE) : 0);
		
		$this->db->where('GROUP_ID', $group_id);
		$query  = $this->db->limit($limit, $start)->get('vu_s_users')->result();
		
		$this->db->where('GROUP_ID', $group_id);
		$total  = $this->db->get('vu_s_users')->num_rows();
	
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
	
	public function save(){
		$data   = json_decode($this->input->post('data',TRUE));
		$last   = NULL;
		if(($data->USER_ID !== NULL) && ($data->USER_ID != '') && ($data->USER_ID != 0)){
			$this->db->where('USER_ID', $data->ID)->update('s_users', $data);
			$last   = $data;
		}else{
			$this->db->insert('s_users', $data);
			$last   = $this->db->limit(1,0)->order_by('USER_ID', 'DESC')->get('s_users')->row();
		}
		$total  = $this->db->get('s_users')->num_rows();
	
		$json   = array(
				"success"   => TRUE,
				"message"   => 'Data berhasil disimpan',
				'total'     => $total,
				"data"      => $last
		);
	
		echo json_encode($json);
	}
	
	public function delete(){
		$data   = json_decode($this->input->post('data',TRUE));
		$this->db->where('USER_ID', $data->ID)->delete('s_users');
	
		$total  = $this->db->get('s_users')->num_rows();
		$last = $this->db->get('s_users')->result();
	
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