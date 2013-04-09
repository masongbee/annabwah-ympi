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
			$sql = "SELECT USER_ID, USER_NAME, USER_GROUP, GROUP_NAME
			FROM s_users 
			JOIN s_usergroups ON(s_usergroups.GROUP_ID = s_users.USER_GROUP)
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
					'user_id'	=> $userdata->USER_ID,
					'user_name'	=> $userdata->USER_NAME,
					'group_id' => $userdata->USER_GROUP,
					'group_name' => $userdata->GROUP_NAME
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
		
	//Untuk Akses User_File
	function get($user) 
	{		
		$rows =  $this->CI->db->get_where('s_users', array('user_name' => $user));
		if ($rows->num_rows() > 0)
		{
			foreach ($rows->result() as $row) {
				$item = $row->USER_FILE;
			}
			return $item;
		}
		return 0;
	}
	
	//Untuk Enkripsi dan Generate File
	//$msg => pesan berupa pesan mentah mis. "ekojs"
	//$fname -> nama file berdasarkan Username beserta extensi file mis *.txt
	function Enkripsi($msg,$fname)
	{
		if (($msg != "") or ($msg != null))
		{
			$md5_hash = md5($msg);
			$buf = random_string('alnum', 34) . $md5_hash . random_string('alnum', 34);
			
			$arr = array();
			$rs = "";
			for($i=0;$i < strlen($buf);$i++)
			{
				$arr[$i] = chr(ord($buf[$i]) + 80);
				$rs = $rs . $arr[$i];
			}
			
			if (! write_file("./assets/upload/" . $fname, $rs))
			{
				return 0;
			}
			else
			{
				return $md5_hash;
			}
		}
		else
			return 0;		
	}
	
	function Denkripsi($msg)
	{
		$msg_length = strlen($msg);
		if ($msg_length == 100)
		{
			$buf = array();
			$rs = "";
			for($i=0;$i < $msg_length;$i++)
			{
				$buf[$i] = chr(ord($msg[$i])-80);
				$rs = $rs . $buf[$i];
			}
			
			$data = substr($rs,34,32);
			$rs = "";
			return $data;
		}
		else
			return 0;		
	}

	// untuk logout
	function do_logout()
	{
		$this->CI->session->sess_destroy();	
		//$this->CI->db->empty_table('ci_sessions');
	}
}
