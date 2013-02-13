<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unitkerja extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct(){
		parent::__construct();
	}
	
	/*
	 * GRADE
	 */
	public function getAll(){
		/*$sql_hierarchy = "SELECT concat(REPEAT('---',(count(`parent`.`NAMAUNIT`)- 1)), `node`.`NAMAUNIT`)AS `NAMAUNIT`, `node`.`KODEUNIT` AS `KODEUNIT`,
					(count(`parent`.`NAMAUNIT`)- 1)AS `depth`
				FROM (`unitkerja_test` `node`JOIN `unitkerja_test` `parent`)
				WHERE (`node`.`LFT` BETWEEN `parent`.`LFT` AND `parent`.`RGT`)
				GROUP BY `node`.`NAMAUNIT`
				ORDER BY `node`.`LFT`";
		$first_depth=1;
		$depth = 1;
		$count_rows = sizeof($rows);
		
		$result = "[";
		
		$i = 1;
		foreach ($rows as $row){
			if($row['depth'] < $depth){
				$now_depth = $row['depth'];
				$gap_depth = $depth - $now_depth;
		
				for($g=1; $g<=$gap_depth; $g++){
					$result .= "</ul></li>";
				}
			}
				
			if($row['menu_rgt'] == ($row['menu_lft'] + 1)){
				$result .= "<li id='{$row['menu_link']}' title='{$row['menu_title']}'>{$row['menu_title']}</li>";
			}else{
				$result .= "<li>{$row['menu_title']}";
				$result .= "<ul  style='width: 250px;'>";
			}
				
			if($i == $count_rows){
				$now_depth = $row['depth'];
				$gap_depth = $now_depth - $first_depth;
		
				for($g=1; $g<=$gap_depth; $g++){
					$result .= "</ul></li>";
				}
			}
				
			$depth = $row['depth'];
			$i++;
		}
		$result.= "]";
		$this->firephp->log($result);
		return $result;
		============
		//generate list-menus
		$generate_list_menus = "[";
		//foreach
		$i = 0;
		foreach ($data_menus_parent as $menus_parent){
			if($i>0){
				$generate_list_menus .= ",";
			}
			$generate_list_menus .= "{";
				
			$data_menus_child = $this->m_main->get_menus_child($menus_parent['menu_id']);
			if(sizeof($data_menus_child) > 0){
				$generate_list_menus .= "text: '".$menus_parent['menu_title']."',iconCls: 'icon-".$menus_parent['menu_id']."',singleClickExpand: true,children: [";
				$c = 0;
				foreach ($data_menus_child as $menus_child){
					if($c>0){
						$generate_list_menus .= ",";
					}
					$generate_list_menus .= "{
	id: '".$menus_child['menu_title']."',
	text: '".$menus_child['menu_title']."',
	href: 'index.php".$menus_child['menu_link']."',
	iconCls: 'icon-".$menus_child['menu_id']."',
	leaf: true
}";
					$c++;
				}
				$generate_list_menus .= "]";
			}else{
				$generate_list_menus .= "id: '".$menus_parent['menu_title']."',
	text: '".$menus_parent['menu_title']."',
	href: 'index.php".$menus_parent['menu_link']."',
	iconCls: 'icon-".$menus_parent['menu_id']."',
	leaf: true";
			}
				
			$generate_list_menus .= "}";
				
			$i++;
		}
		$generate_list_menus .= "]";*/
		
		
		
		
		
		
		
		
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 10);
		
		$query  = $this->db->limit($limit, $start)->get('vu_unitkerja_hierarchy')->result();
		$total  = $this->db->get('vu_unitkerja_hierarchy')->num_rows();
	
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}
	
		$json   = array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total,
				'data'      => $data
		);
	
		echo json_encode($json);
	}
	
	public function save(){
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 10);
		
		$data   = json_decode($this->input->post('data',TRUE));
		$last   = NULL;
		
		/*if($data->P_KODEUNIT == ''){
			$this->firephp->log('undefined');
		}else{
			$this->firephp->log($data->P_KODEUNIT);
		}*/
		
		if(($data->ID !== NULL) && ($data->ID != '')){
			$this->db->where('kodeunit', $data->KODEUNIT)->update('unitkerja_test', $data);
			$last   = $data;
		}else{
			/*
			 * KODEUNIT = "00000" <== "Presiden Direktur"
			 * KODEUNIT = "01000" <== "Direktur"
			 * Digit 1=DIVISI
			 * Digit 2=DEPARTEMEN
			 * Digit 3=SECTION
			 * Digit 4=SUBSECTION
			 * Digit 5=GROUP
			 */
			$lock_tbl = "LOCK TABLE unitkerja_test WRITE";
			$this->db->query($lock_tbl);
			
			if(($data->KODEUNIT == '00000') || ($data->KODEUNIT == '01000')){
				//"Presiden Direktur" / "Direktur" <== add root
				$sql = "SELECT IFNULL((MAX(RGT)+1),1) AS max_rgt FROM unitkerja_test";
				$record = $this->db->query($sql)->row();
				$myLeft = $record->max_rgt;
				
				$datau = array(
					"KODEUNIT"=>$data->KODEUNIT,
					"NAMAUNIT"=>$data->NAMAUNIT,
					"LFT"=>$myLeft,
					"RGT"=>$myLeft+1
				);
			}else{
				$kodeunit = $data->KODEUNIT;
				$rtrim_kodeunit = rtrim($kodeunit, "0");
				$digit_kodeunit = strlen($rtrim_kodeunit);
				
				if($digit_kodeunit > 1){
					//add child
					$p_kodeunit = substr($rtrim_kodeunit, 0, ($digit_kodeunit-1));
					while (strlen($p_kodeunit) <= 4){
						$p_kodeunit.="0";
					}
					
					$sql = "SELECT LFT FROM unitkerja_test WHERE KODEUNIT = '".$p_kodeunit."'";
					$record = $this->db->query($sql)->row();
					$myLeft = $record->LFT;
					
					$datau = array(
						"KODEUNIT"=>$data->KODEUNIT,
						"P_KODEUNIT"=>$p_kodeunit,
						"NAMAUNIT"=>$data->NAMAUNIT,
						"LFT"=>$myLeft+1,
						"RGT"=>$myLeft+2
					);
				}else{
					//Digit 1=DIVISI <== add root
					$sql = "SELECT IFNULL((MAX(RGT)+1),1) AS max_rgt FROM unitkerja_test";
					$record = $this->db->query($sql)->row();
					$myLeft = $record->max_rgt;
					
					$datau = array(
						"KODEUNIT"=>$data->KODEUNIT,
						"NAMAUNIT"=>$data->NAMAUNIT,
						"LFT"=>$myLeft,
						"RGT"=>$myLeft+1
					);
				}
			}
			
			
			/*$this->firephp->log('proses insert');
			$lock_tbl = "LOCK TABLE unitkerja_test WRITE";
			$this->db->query($lock_tbl);
			$this->firephp->log($data->P_KODEUNIT);
			
			if($data->P_KODEUNIT != ''){
				//add new child
				//$this->db->select('LFT');
				//$this->db->where('P_KODEUNIT', $data->P_KODEUNIT);
				//$myLeft = $this->db->get('unitkerja_test')->row()->LFT;
				$sql = "SELECT LFT FROM unitkerja_test WHERE KODEUNIT = '".$data->P_KODEUNIT."'";
				$record = $this->db->query($sql)->row();
				$myLeft = $record->LFT;
				
				$datau = array(
						"KODEUNIT"=>$data->KODEUNIT,
						"P_KODEUNIT"=>$data->P_KODEUNIT,
						"NAMAUNIT"=>$data->NAMAUNIT,
						"LFT"=>$myLeft+1,
						"RGT"=>$myLeft+2
						);
			}else{
				//add new root
				//$this->db->select('IFNULL((MAX(RGT)+1), 1) AS max_rgt');
				//$myLeft  = $this->db->get('unitkerja_test')->row()->max_rgt; //next root
				$sql = "SELECT IFNULL((MAX(RGT)+1),1) AS max_rgt FROM unitkerja_test";
				$record = $this->db->query($sql)->row();
				$myLeft = $record->max_rgt;
				
				$datau = array(
						"KODEUNIT"=>$data->KODEUNIT,
						"NAMAUNIT"=>$data->NAMAUNIT,
						"LFT"=>$myLeft,
						"RGT"=>$myLeft+1
						);
			}*/
			
			$sqlu = "UPDATE unitkerja_test SET RGT = RGT + 2 WHERE RGT > ".$myLeft;
			$this->db->query($sqlu);
			$sqlu = "UPDATE unitkerja_test SET LFT = LFT + 2 WHERE LFT > ".$myLeft;
			$this->db->query($sqlu);
			
			$this->db->insert('unitkerja_test', $datau);
			$insert_id = $this->db->insert_id();
			
			$unlock_tbl = "UNLOCK TABLES";
			$this->db->query($unlock_tbl);
		}
		
		$total  = $this->db->get('vu_unitkerja_hierarchy')->num_rows();
		//$last   = $this->db->limit(1,0)->order_by('kodeunit', 'DESC')->get('vu_unitkerja_hierarchy')->row();
		$last   = $this->db->where('ID', $insert_id)->limit($limit, $start)->get('vu_unitkerja_hierarchy')->row();
		
		$json   = array(
				"success"   => TRUE,
				"message"   => 'Data berhasil disimpan',
				'total'     => $total,
				"data"      => $last
		);
		
		echo json_encode($json);
	}
	
	public function delete(){
		$data = json_decode($this->input->post('data',TRUE));
		
		$lock_tbl = "LOCK TABLE unitkerja_test WRITE";
		$this->db->query($lock_tbl);
		
		$sql = "SELECT LFT, RGT, (RGT - LFT + 1) AS mywidth
						FROM unitkerja_test
						WHERE ID = ".$data->ID;
		$record = $this->db->query($sql)->row();
		$myLeft = $record->LFT;
		$myRight = $record->RGT;
		$myWidth = $record->mywidth;
		
		$sqld = "DELETE FROM unitkerja_test WHERE LFT BETWEEN ".$myLeft." AND ".$myRight;
		$this->db->query($sqld);
		
		$sqlu = "UPDATE unitkerja_test SET RGT = RGT - ".$myWidth." WHERE RGT > ".$myRight;
		$this->db->query($sqlu);
		$sqlu = "UPDATE unitkerja_test SET LFT = LFT - ".$myWidth." WHERE LFT > ".$myRight;
		$this->db->query($sqlu);
		
		$unlock_tbl = "UNLOCK TABLES";
		$this->db->query($unlock_tbl);
		
		
		$total  = $this->db->get('vu_unitkerja_hierarchy')->num_rows();
		$last = $this->db->get('vu_unitkerja_hierarchy')->result();
	
		$json   = array(
				"success"   => TRUE,
				"message"   => 'Data berhasil dihapus',
				'total'     => $total,
				"data"      => $last
		);
	
		echo json_encode($json);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */