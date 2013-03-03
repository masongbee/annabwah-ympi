<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissiongroup extends CI_Controller {

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
		$group_id = ($this->input->post('GROUP_ID', TRUE) ? $this->input->post('GROUP_ID', TRUE) : 0);
		
		$sql = "SELECT vu_tree_menus.TREE_MENU_TITLE, vu_tree_menus.DEPTH, s_permissions.PERM_ID, s_permissions.PERM_GROUP, 
				IF(s_permissions.PERM_PRIV IS NULL,0,1) AS PERM_PRIV
			FROM vu_tree_menus
			LEFT JOIN s_permissions ON(s_permissions.PERM_MENU = vu_tree_menus.MENU_ID AND s_permissions.PERM_GROUP = ".$group_id.")";
		$query  = $this->db->query($sql)->result();
		$total  = $this->db->query($sql)->num_rows();
	
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
		/*if(($data->PERM_ID !== NULL) && ($data->PERM_ID != '') && ($data->PERM_ID != 0)){
			$this->db->where('PERM_ID', $data->ID)->update('s_permissions', $data);
			$last   = $data;
		}else{
			$this->db->insert('s_permissions', $data);
			$last   = $this->db->limit(1,0)->order_by('PERM_ID', 'DESC')->get('s_permissions')->row();
		}*/
		foreach ($data as $row){
			$this->firephp->log($row);
			if(($row->PERM_ID !== NULL) && ($row->PERM_ID != '') && ($row->PERM_ID != 0)){
				$datau = array();
				if($row->PERM_PRIV){
					$datau['PERM_PRIV'] = 'RCUD';
				}else {
					$datau['PERM_PRIV'] = null;
				}
				$this->db->where('PERM_ID', $row->PERM_ID);
				$this->db->update('s_permissions', $datau);
			}
		}
		$total  = $this->db->get('s_permissions')->num_rows();
	
		$json   = array(
				"success"   => TRUE,
				"message"   => 'Data berhasil disimpan',
				'total'     => $total,
				"data"      => $last
		);
	
		echo json_encode($json);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */