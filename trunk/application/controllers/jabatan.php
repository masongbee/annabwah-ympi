<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jabatan extends CI_Controller {

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
		//$filter = json_decode($this->input->post('filter',TRUE));
		$kodeunit = $this->input->post('KODEUNIT',TRUE);
		
		/*if(sizeof($filter)){
			foreach ($filter as $row){
				$this->db->where($row->property, $row->value);
			}
		}*/
		$this->db->where('KODEUNIT', $kodeunit);
		$query  = $this->db->limit($limit, $start)->get('vu_jabatan_test')->result();
		
		/*if(sizeof($filter)){
			foreach ($filter as $row){
				$this->db->where($row->property, $row->value);
			}
		}*/
		$this->db->where('KODEUNIT', $kodeunit);
		$total  = $this->db->get('vu_jabatan_test')->num_rows();
	
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
		if(($data->ID !== NULL) && ($data->ID != '') && ($data->ID != 0)){
			$this->db->where('id', $data->ID)->update('jabatan_test', $data);
			$last   = $data;
		}else{
			$this->db->insert('jabatan_test', $data);
			$last   = $this->db->limit(1,0)->order_by('id', 'DESC')->get('jabatan_test')->row();
		}
		$total  = $this->db->get('jabatan_test')->num_rows();
	
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
		$this->db->where('id', $data->ID)->delete('jabatan_test');
	
		$total  = $this->db->get('jabatan_test')->num_rows();
		$last = $this->db->get('jabatan_test')->result();
	
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