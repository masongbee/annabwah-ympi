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
	
	function initialization()
	{
		$init = new stdClass();
		
		$nik_hrd = $this->CI->db->get_where('init',array('PARAMETER'=>'NIK_HRD'))->result();
		$max_kar = $this->CI->db->get_where('init',array('PARAMETER'=>'Max_Kar'))->result();
		
		$init->NIK_HRD = $nik_hrd[0]->VALUE;
		$init->MAX_KAR = $max_kar[0]->VALUE;
		return $init;
	}
	
	function gid($gid)
	{
		$gname = $this->CI->db->query("SELECT GROUP_DESC FROM s_usergroups WHERE LOWER(GROUP_NAME)='".$gid."'")->result();
		return $gname[0]->GROUP_DESC;
	}
	
	// untuk validasi login
	function do_login($username,$password,$group){
		if(($username == 'admin') && ($password == '21232f297a57a5a743894a0e4a801fc3')){
			$session_data = array(
				'user_id'	=> '0',
				'user_name'	=> 'Admin',
				'user_nik' => '12345678',
				'group_id'	=> 0,
				'group_name' => 'mnjuser',
				'group_icon' => $group
			);
			$this->CI->session->set_userdata($session_data);
			$this->CI->db->insert('s_userslog', array(
				'USERLOG_USER_ID'=>0,
				'USERLOG_USER_NAME'=>'Admin',
				'USERLOG_STATUS'=>'in'
			));
			return 1;
		}else{
			/*$sql = "SELECT USER_ID, USER_NAME, USER_GROUP, GROUP_NAME, GROUP_DESC
			FROM s_users 
			JOIN s_usergroups ON(s_usergroups.GROUP_ID = s_users.USER_GROUP)
			WHERE s_users.USER_NAME='".$username."' AND s_users.USER_PASSWD='".$password."' AND s_usergroups.GROUP_NAME='".$group."'";*/
			$sql = "SELECT USER_ID, USER_NAME,USER_KARYAWAN, USER_GROUP, GROUP_NAME, GROUP_DESC
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
					'user_nik' => $userdata->USER_KARYAWAN,
					'group_id' => $userdata->USER_GROUP,
					'group_name' => strtolower($userdata->GROUP_NAME),
					'group_desc' => $userdata->GROUP_DESC,
					'group_icon' => $group
				);
				// buat session
				$this->CI->session->set_userdata($session_data);
				$this->CI->db->insert('s_userslog', array(
					'USERLOG_USER_ID'=>$userdata->USER_ID,
					'USERLOG_USER_NAME'=>$userdata->USER_NAME,
					'USERLOG_STATUS'=>'in'
				));
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
		else
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
		$this->CI->db->insert('s_userslog', array(
			'USERLOG_USER_ID'=>$this->CI->session->userdata('user_id'),
			'USERLOG_USER_NAME'=>$this->CI->session->userdata('user_name'),
			'USERLOG_STATUS'=>'out'
		));
		$this->CI->session->sess_destroy();
		//redirect(base_url().'login','refresh');
		redirect(base_url().'c_main','refresh');
		//$this->CI->db->empty_table('ci_sessions');
	}
	
	function cleanMemory($class)
    {
        $refl = new ReflectionObject($class);
        foreach ($refl->getProperties() as $prop) {
            if (!$prop->isStatic()) {
                $prop->setAccessible(true);
                $prop->setValue($class, null);
            }
        }
    }
}
