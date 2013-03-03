<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usergroup extends CI_Controller {

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
		
		$query  = $this->db->limit($limit, $start)->get('s_usergroups')->result();
		$total  = $this->db->get('s_usergroups')->num_rows();
	
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
		
		if(($data->GROUP_ID !== NULL) && ($data->GROUP_ID != '') && ($data->GROUP_ID != 0)){
			$this->db->where('GROUP_ID', $data->GROUP_ID)->update('s_usergroups', $data);
			$last   = $data;
		}else{
			$this->db->insert('s_usergroups', $data);
			$create_affected = $this->db->affected_rows();
			$group_id = $this->db->insert_id();
			$last   = $this->db->limit(1,0)->order_by('GROUP_ID', 'DESC')->get('s_usergroups')->row();
			
			if($create_affected){
				/*
				 * INSERT db.s_permissions by GROUP_ID
				 */
				$sqlc = "INSERT INTO s_permissions (PERM_GROUP, PERM_MENU)
					SELECT ".$group_id.", MENU_ID FROM s_menus WHERE MENU_AKTIF = 'Y'";
				$this->db->query($sqlc);
			}
		}
		$total  = $this->db->get('s_usergroups')->num_rows();
	
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
		$this->db->where('GROUP_ID', $data->GROUP_ID)->delete('s_usergroups');
		
		if($this->db->affected_rows()){
			/*
			 * DELETE db.s_permissions
			 */
			$this->db->where('PERM_GROUP', $data->GROUP_ID)->delete('s_permissions');
		}
	
		$total  = $this->db->get('s_usergroups')->num_rows();
		$last = $this->db->get('s_usergroups')->result();
	
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