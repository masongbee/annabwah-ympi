<?php

/**
 * Class	: M_permohonanijin
 * 
 * Table	: permohonanijin
 *  
 * @author masongbee
 *
 */
class M_permohonanijin extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	/**
	 * Fungsi	: getAll
	 * 
	 * Untuk mengambil all-data
	 * 
	 * @param number $group_id
	 * @param number $start
	 * @param number $page
	 * @param number $limit
	 * @return json
	 */
	function getAll($group_id, $start, $page, $limit){
		$query  = $this->db->get('permohonanijin')->result();
		$total  = $this->db->get('permohonanijin')->num_rows();
		
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}
		
		$json	= array(
						'success'   => TRUE,
						'message'   => "Loaded data",
						'total'     => $total,
						'data'      => $data
		);
		
		return $json;
	}
	
	/**
	 * Fungsi	: save
	 * 
	 * Untuk menambah data baru atau mengubah data lama
	 * 
	 * @param array $data
	 * @return json
	 */
	function save($data){
		$last   = NULL;
		
		if($this->db->get_where('permohonanijin', array('NOIJIN'=>$data->NOIJIN))->num_rows() > 0){
			/*
			 * Data Exist
			 * 
			 * Process Update	==> update berdasarkan db.permohonanijin.NOIJIN = $data->NOIJIN
			 */
			if($data->NOIJIN != ''){
				$this->db->where('NOIJIN', $data->NOIJIN)->update('permohonanijin', array('USER_PASSWD'=>md5($data->USER_PASSWD)));
				if($this->db->affected_rows()){
					$last   = $this->db->select('USER_ID, NOIJIN, "[hidden]" AS USER_PASSWD, GROUP_ID')->get('permohonanijin')->row();
				}
			}
			
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('permohonanijin', array('NOIJIN'=>$data->NOIJIN, 'USER_PASSWD'=>md5($data->USER_PASSWD), 'USER_GROUP'=>$data->GROUP_ID));
			$last   = $this->db->select('USER_ID, NOIJIN, "[hidden]" AS USER_PASSWD, GROUP_ID')
					->order_by('NOIJIN', 'ASC')->get('permohonanijin')->row();
			
		}
		$total  = $this->db->get('permohonanijin')->num_rows();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil disimpan',
						'total'     => $total,
						"data"      => $last
		);
		
		return $json;
	}
	
	/**
	 * Fungsi	: delete
	 * 
	 * Untuk menghapus satu data
	 * 
	 * @param array $data
	 * @return json
	 */
	function delete($data){
		$this->db->where('NOIJIN', $data->NOIJIN)->delete('permohonanijin');
		
		$total  = $this->db->get('permohonanijin')->num_rows();
		$last = $this->db->get('permohonanijin')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						'total'     => $total,
						"data"      => $last
		);
		
		return $json;
	}

}


?>