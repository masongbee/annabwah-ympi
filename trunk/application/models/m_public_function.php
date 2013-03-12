<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_public_function extends CI_Model {
	
	function __construct() 
	{		
		parent::__construct();
	}
	
	function getTblHeader($tbl)
	{
		$col = $this->db->list_fields($tbl);
		$rs = $col->result_array();
		if (sizeof($rs) > 0) 
		{
			foreach ($rs as $row) {
				$item = array($row => $row);
				$items[] = $item;
			}
			$data = json_encode($items);
			return $data;
		}
		return NULL;
	}
	
	function get($user) 
	{		
		$rows =  $this->db->get_where('s_users', array('USER_NAME' => $user));
		if ($rows->num_rows() > 0)
		{
			foreach ($rows->result() as $row) {
				$item = $row->USER_FILE;
			}
			return $item;
		}
		return 0;
	}
}

/* End of file phonebook_model.php */
/* Location: ./application/models/phonebook_model.php */