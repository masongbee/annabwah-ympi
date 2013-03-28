<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class: C_menus
 * 
 * untuk mempersiapkan kontrol menu yang akan ditampilkan
 * 
 * @modul 	accounting
 * @author 	masongbee
 * @contact +62 852 3146 0022
 * @company ts.co.id
 */
class C_menus extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_menus', '', TRUE);
	}
	
	function getMenus(){
		$group_id	= $this->session->userdata('group_id');
		
		$result 	= $this->m_menus->getMenus($group_id);
		echo $result;
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */