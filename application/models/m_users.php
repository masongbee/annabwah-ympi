<?php

/**
 * Class	: M_users
 * 
 * Table	: s_users
 *  
 * @author masongbee
 *
 */
class M_users extends CI_Model{

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
	function getAll($group_id, $start, $page, $limit, $filter){
		// $query  = $this->db->select('USER_ID, USER_NAME, "[hidden]" AS USER_PASSWD, GROUP_ID, IF(USER_FILE IS NULL, 0, 1) AS VIP_USER,USER_KARYAWAN')
		// 		->where('GROUP_ID', $group_id)->limit($limit, $start)->get('vu_s_users')->result();
		$this->db->select('USER_ID, USER_NAME, "[hidden]" AS USER_PASSWD, GROUP_ID, IF(USER_FILE IS NULL, 0, 1) AS VIP_USER,USER_KARYAWAN,NAMAKAR');
		if ($filter != '') {
			$this->db->like('NAMAKAR', $filter)->or_like('NIK', $filter);
		}
		/*$query  = ->limit($limit, $start)->get('vu_s_users')->result();*/
		$query = $this->db->get('vu_s_users')->result();
		$total = $this->db->where('GROUP_ID', $group_id)->get('vu_s_users')->num_rows();
		
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
		
		if($this->db->get_where('s_users', array('USER_NAME'=>$data->USER_NAME))->num_rows() > 0){
			/*
			 * Data Exist
			 * 
			 * Process Update	==> update berdasarkan db.s_users.USER_NAME = $data->USER_NAME
			 */
			if($data->USER_PASSWD != ''){
				$this->db->where('USER_NAME', $data->USER_NAME)->update('s_users', array('USER_PASSWD'=>md5($data->USER_PASSWD),'USER_KARYAWAN'=>$data->USER_KARYAWAN));
				if($this->db->affected_rows()){
					$last   = $this->db->select('USER_ID, USER_NAME, "[hidden]" AS USER_PASSWD, GROUP_ID, IF(USER_FILE IS NULL, 0, 1) AS VIP_USER,USER_KARYAWAN')->get('vu_s_users')->row();
				}
			}
			
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('s_users', array('USER_NAME'=>$data->USER_NAME, 'USER_PASSWD'=>md5($data->USER_PASSWD), 'USER_KARYAWAN'=>$data->USER_KARYAWAN));
			$insert_id = $this->db->insert_id();
			if ($data->VIP_USER){
				$user_file = $this->auth->Enkripsi($data->USER_PASSWD,$data->USER_NAME.'.txt');
				$this->db->where('USER_ID', $insert_id)->update('s_users', array('USER_FILE'=>$user_file));
			}
			$last   = $this->db->select('USER_ID, USER_NAME, "[hidden]" AS USER_PASSWD, GROUP_ID, IF(USER_FILE IS NULL, 0, 1) AS VIP_USER')
					->where('USER_ID', $insert_id)->get('vu_s_users')->row();
			
		}
		$total  = $this->db->get('vu_s_users')->num_rows();
		
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
		$this->db->where('USER_NAME', $data->USER_NAME)->delete('s_users');
		
		$total  = $this->db->get('vu_s_users')->num_rows();
		$last = $this->db->get('vu_s_users')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						'total'     => $total,
						"data"      => $last
		);
		
		return $json;
	}
	
	/**
	 * Fungsi	: do_upload
	 *
	 * Untuk menginjeksi data dari Excel ke Database
	 *
	 * @param array $data
	 * @return array
	 */
	function do_upload($data, $filename){
		if(sizeof($data) > 0){
			$this->db->truncate('s_users'); 
			$p = 0;
			$skeepdata = 0;
			foreach($data->getWorksheetIterator() as $worksheet){
				if($p>0){
					break;
				}
				
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				// $skeepdata = 0;
				for ($row = 1; $row <= $highestRow; ++ $row) {
					if($row>1){
						for ($col = 0; $col < $highestColumnIndex; ++ $col) {
							$nik = (trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
							$username = (trim($worksheet->getCellByColumnAndRow(7, $row)->getCalculatedValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(7, $row)->getCalculatedValue()));
							$password = (trim($worksheet->getCellByColumnAndRow(8, $row)->getCalculatedValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(8, $row)->getCalculatedValue()));
						}
						
						$data = array(
							'USER_NAME' => $username,
							'USER_PASSWD' => md5($password),
							'USER_KARYAWAN' => $nik
						);
						if($this->db->get_where('s_users', array('USER_NAME'=>$username))->num_rows() == 0){
							$this->db->insert('s_users', $data);
						}else{
							$skeepdata++;
						}
						
					}
				}
				
				$p++;
			}
			
			$success = array(
				'success'	=> true,
				'msg'		=> 'Data telah berhasil ditambahkan.',
				'filename'	=> $filename,
				'skeepdata'	=> $skeepdata
			);
			return $success;
		}else{
			$error = array(
				'success'	=> false,
				'msg'		=> 'Tidak ada proses, karena data kosong.',
				'filename'	=> $filename
			);
			return $error;
		}
	}

}


?>