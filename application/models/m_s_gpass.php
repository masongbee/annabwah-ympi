<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_s_gpass
 * 
 * Table	: s_gpass
 *  
 * @author masongbee
 *
 */
class M_s_gpass extends CI_Model{

	function __construct(){
		parent::__construct();
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
		
		$pkeyuser = array(
			'USER_NAME' => $this->session->userdata('user_name')
		);
		$pkeyuserpass = array(
			'USER_NAME' => $this->session->userdata('user_name'),
			'USER_PASSWD' => md5($data->OLD_PASSWORD)
		);
		
		if($this->db->get_where('s_users', $pkeyuser)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			if($this->db->get_where('s_users', $pkeyuserpass)->num_rows() > 0){
				$this->db->where($pkeyuserpass)->update('s_users', array('USER_PASSWD' => md5($data->NEW_PASSWORD)));
				if($this->db->affected_rows()){
					$json   = array(
						"success"   => TRUE,
						"message"   => 'Password berhasil diubah.'
					);
				}else{
					$json   = array(
						"success"   => TRUE,
						"message"   => 'Password tidak bisa diubah.'
					);
				}
				
			}else{
				$json   = array(
					"success"   => TRUE,
					"message"   => 'Password Lama salah.'
				);
			}
			
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$json   = array(
				"success"   => TRUE,
				"message"   => 'User salah.'
			);
			
		}
		
		return $json;
	}
}
?>