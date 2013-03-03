<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_info extends CI_Model {
	
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
	
	function get($start, $limit) 
	{		
		$rows = $this->db->get('s_info', $limit, $start);
		if ($this->count() > 0) 
		{
			foreach ($rows->result() as $row) {
				$item = array('info_id' => $row->info_id,
							  'info_nama' => $row->info_nama,
							  'info_cabang' => $row->info_cabang,
							  'info_alamat' => $row->info_alamat,
							  'info_notelp' => $row->info_notelp,
							  'info_nofax' => $row->info_nofax,
							  'info_email' => $row->info_email,
							  'info_website' => $row->info_website,
							  'info_slogan' => $row->info_slogan,
							  'info_logo' => $row->info_logo,
							  'info_icon' => $row->info_icon,
							  'info_background' => $row->info_background,
							  'info_theme' => $row->info_theme
						);
				$items[] = $item;
			}
			$data = json_encode($items);
			return $data;
		}
		return NULL;
	}
	
	function insert($data) 
	{
		$this->db->insert("s_info", $data);
	}
	
	function update($id, $data) 
	{
		$this->db->where('info_id', $id);
		$this->db->update('s_info', $data);
	}
	
	function delete($id) 
	{
		$this->db->where('info_id', $id);
		$this->db->delete('s_info');
	}
	
	function count() 
	{
		return $this->db->count_all('s_info');
	}
}

/* End of file phonebook_model.php */
/* Location: ./application/models/phonebook_model.php */