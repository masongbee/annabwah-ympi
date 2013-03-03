<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_info extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('m_info', 'info');	
	}

	function index()
	{		
		$this->load->view('v_info');
	}
	
	function task() 
	{
		$tbl = $this->input->post('tbl');
		switch ($this->input->post('task')) 
		{
			case 'get': 
				$this->pb_get(); 
			break;
			case 'tbl': 
				$this->pb_header($tbl); 
			break;
			case 'create': 
				$this->pb_create(); 
			break;
			case 'update': 
				$this->pb_update(); 
			break;
			case 'delete': 
				$this->pb_delete(); 
			break;
			default:
			break;
		}
	}
	
	private function pb_get() 
	{
		$start = 0;
		$limit = 10;
		
		if ($this->input->post('start') && $this->input->post('limit')) 
		{
			$start = $this->input->post('start');
			$limit = $this->input->post('limit');
		}
		$cnt = $this->info->count();
		if ($cnt > 0) 
		{
			$data = $this->info->get($start, $limit);
			echo '({"total":"'.$cnt.'", "results":'.$data.'})';
		}
		else 
		{
			echo '({"total":"0", "results":""})';
		}
		
	}
	
	private function pb_header($tbl) 
	{
		if ($tbl <> "") 
		{
			$data = $this->info->getTblHeader($tbl);
			$cnt = sizeof($data);
			echo '({"total":"'.$cnt.'", "results":'.$data.'})';
		}
		else 
		{
			echo '({"total":"0", "results":""})';
		}
		
	}
	
	private function pb_create() 
	{
		$data = array ("NAME" => $this->input->post('NAME'), 
					  "ADDRESS" => $this->input->post('ADDRESS'),					  
					  "PHONE" => $this->input->post('PHONE'),
					  "TYPE" => $this->input->post('TYPE'),
					  "STATUS" => $this->input->post('STATUS')					  
				);		
		if (!empty($data)) 
		{						
			$this->info->insert($data);
			echo '({"status":1})';
		}
		else 
		{
			echo '({"status":0})';
		}
	}
	
	private function pb_update() 
	{
		$id = $this->input->post('ID');		
		$data = array ("NAME" => $this->input->post('NAME'), 
					  "ADDRESS" => $this->input->post('ADDRESS'),					  
					  "PHONE" => $this->input->post('PHONE'),
					  "TYPE" => $this->input->post('TYPE'),
					  "STATUS" => $this->input->post('STATUS')					  
				);		
		if (!is_null($id) && !empty($data)) 
		{						
			$this->info->update($id, $data);
			echo '({"status":1})';
		}
		else 
		{
			echo '({"status":0})';
		}
	}
	
	private function pb_delete() 
	{
		$id = $this->input->post('id');
		if (!is_null($id)) 
		{
			$this->info->delete($id);
			echo '({"status":1})';
		}
		else
		{
			echo '({"status":0})';
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */