<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {	
	private $username;
	private $gid;
	
	public function __construct(){
		parent::__construct();
		if($this->auth->is_logged_in() == false){
			redirect(base_url(),'refresh');
		}
		$this->gid = $this->session->userdata('group_id');
		$this->username = $this->session->userdata('user_name');
	}
	public function index()
	{
		$group = $this->input->post('group', TRUE);
		$session_data = array(
			'group_icon' => $group
		);
		$this->session->set_userdata($session_data);
		$this->db->query("CALL splitter('".$this->gid."', ',')");
		$rs_splitter = $this->db->query("SELECT * FROM splitResults")->result();
		$sqlcheck_group = "SELECT s_usergroups.GROUP_ID 
			FROM s_usergroups JOIN splitResults ON(splitResults.split_value = s_usergroups.GROUP_ID) 
			WHERE LOWER(s_usergroups.GROUP_NAME) = '".$group."'";
		$rowscheck_group = $this->db->query($sqlcheck_group)->row();
		
		if (sizeof($rowscheck_group) > 0) {
			$session_data = array(
				'group_select' => $rowscheck_group->GROUP_ID
			);
			$this->session->set_userdata($session_data);

			$this->load->view('welcome');
		} else {
			// print('alert("Anda Tidak Memiliki Hak Akses")');
			echo "<script type='text/javascript'>\n"; 
			echo "alert('Anda Tidak Memiliki Hak Akses');\n"; 
			echo "</script>";
			$this->load->view('v_home');
		}
		
		// $this->load->view('welcome');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */