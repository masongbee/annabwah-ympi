<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_unitkerja
 * 
 * Table	: unitkerja
 *  
 * @author masongbee
 *
 */
class M_unitkerja extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	/**
	 * Fungsi	: getAll
	 * 
	 * Untuk mengambil all-data
	 * 
	 * @param number $start
	 * @param number $page
	 * @param number $limit
	 * @return json
	 */
	function getAll($start, $page, $limit){
		/*$query = "SELECT concat(REPEAT('&nbsp;&nbsp;&nbsp;',(count(parent.NAMAUNIT) - 1)),node.NAMAUNIT) AS NAMAUNIT_TREE,
				node.NAMAUNIT AS NAMAUNIT,
				node.KODEUNIT AS KODEUNIT,
				node.P_KODEUNIT AS P_KODEUNIT,
				node.SINGKATAN AS SINGKATAN,
				(count(parent.NAMAUNIT) - 1) AS depth
			FROM (unitkerja node JOIN unitkerja parent)
			WHERE (node.LFT BETWEEN parent.LFT AND parent.RGT)
			GROUP BY node.NAMAUNIT
			ORDER BY node.KODEUNIT
			LIMIT ".$start.",".$limit;*/
		$query = "SELECT concat(REPEAT('&nbsp;&nbsp;&nbsp;',(count(parent.NAMAUNIT) - 1)),node.NAMAUNIT) AS NAMAUNIT_TREE,
				node.NAMAUNIT AS NAMAUNIT,
				node.KODEUNIT AS KODEUNIT,
				node.P_KODEUNIT AS P_KODEUNIT,
				node.SINGKATAN AS SINGKATAN,
				(count(parent.NAMAUNIT) - 1) AS depth
			FROM (unitkerja node JOIN unitkerja parent)
			WHERE (node.LFT BETWEEN parent.LFT AND parent.RGT)
			GROUP BY node.NAMAUNIT
			ORDER BY /*node.LFT, */node.KODEUNIT";
		$result = $this->db->query($query)->result();
		$query_total = "SELECT COUNT(*) AS total
			FROM 
				(SELECT COUNT(*) AS total
				FROM (unitkerja node JOIN unitkerja parent)
				WHERE (node.LFT BETWEEN parent.LFT AND parent.RGT)
				GROUP BY node.NAMAUNIT) AS vu_total";
		$total  = $this->db->query($query_total)->row()->total;
		
		$data   = array();
		foreach($result as $row){
			$data[] = $row;
		}
		$json	= array(
						'success'   => TRUE,
						'message'   => "Loaded data",
						'total'     => $total,
						'data'      => $data
		);
		
		return $json;
	}
	
	/**
	 * Fungsi	: save
	 * 
	 * Untuk menambah data baru atau mengubah data lama
	 * 
	 * @param array $data
	 * @return json
	 */
	function save($data){
		$last   = NULL;
		
		/*
		 * KODEUNIT = "00000" <== "Presiden Direktur"
		 * KODEUNIT = "01000" <== "Direktur"
		 * Karakter 1=DIVISI
		 * Karakter 2=DEPARTEMEN
		 * Karakter 3=SECTION
		 * Karakter 4=SUBSECTION
		 * Karakter 5=GROUP
		 */
		$pkey = array('KODEUNIT'=>$data->KODEUNIT);
		
		if($this->db->get_where('unitkerja', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			  
			$arrdatau = array(
				'NAMAUNIT'=>$data->NAMAUNIT_TREE,
				'SINGKATAN'=>$data->SINGKATAN
			);
			
			$this->db->where($pkey)->update('unitkerja', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$lock_tbl = "LOCK TABLE unitkerja WRITE";
			$this->db->query($lock_tbl);
			
			if(($data->KODEUNIT == '00000') || ($data->KODEUNIT == '01000')){
				//"Presiden Direktur" / "Direktur" <== add root
				$sql = "SELECT IFNULL((MAX(RGT)+1),1) AS max_rgt FROM unitkerja";
				$record = $this->db->query($sql)->row();
				$myLeft = $record->max_rgt;
					
				$arrdatac = array(
					'KODEUNIT'=>$data->KODEUNIT,
					'NAMAUNIT'=>$data->NAMAUNIT_TREE,
					'SINGKATAN'=>$data->SINGKATAN,
					'LFT'=>$myLeft,
					'RGT'=>$myLeft+1
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
					
					$sql = "SELECT LFT FROM unitkerja WHERE KODEUNIT = '".$p_kodeunit."'";
					$record = $this->db->query($sql)->row();
					$myLeft = $record->LFT;
					
					$arrdatac = array(
						'KODEUNIT'=>$data->KODEUNIT,
						'P_KODEUNIT'=>$p_kodeunit,
						'NAMAUNIT'=>$data->NAMAUNIT_TREE,
						'SINGKATAN'=>$data->SINGKATAN,
						'LFT'=>$myLeft+1,
						'RGT'=>$myLeft+2
					);
				}else{
					//Digit 1=DIVISI <== add root
					$sql = "SELECT IFNULL((MAX(RGT)+1),1) AS max_rgt FROM unitkerja";
					$record = $this->db->query($sql)->row();
					$myLeft = $record->max_rgt;
					
					$arrdatac = array(
						'KODEUNIT'=>$data->KODEUNIT,
						'NAMAUNIT'=>$data->NAMAUNIT_TREE,
						'LFT'=>$myLeft,
						'RGT'=>$myLeft+1
					);
				}
			}
			
			$sqlu = "UPDATE unitkerja SET RGT = RGT + 2 WHERE RGT > ".$myLeft;
			$this->db->query($sqlu);
			$sqlu = "UPDATE unitkerja SET LFT = LFT + 2 WHERE LFT > ".$myLeft;
			$this->db->query($sqlu);
			
			$this->db->insert('unitkerja', $arrdatac);
			
			$unlock_tbl = "UNLOCK TABLES";
			$this->db->query($unlock_tbl);
			
		}
		
		$total  = $this->db->get('vu_unitkerja')->num_rows();
		$last   = $this->db->where($pkey)->get('vu_unitkerja')->row();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil disimpan',
						'total'     => $total,
						"data"      => $last
		);
		
		return $json;
	}
	
	/**
	 * Fungsi	: delete
	 * 
	 * Untuk menghapus satu node data <== Jika node tersebut memiliki child, maka semua child akan otomatis dihapus.
	 * db.unitkerja adalah master dari detail db.jabatan, 
	 * maka detail db.jabatan juga dihapus berdasarkan kodeunit yang telah dihapus <== dihandle oleh trigger di db.unitkerja
	 * 
	 * @param array $data
	 * @return json
	 */
	function delete($data){
		/*
		 * DELETE kodeunit beserta child yang dimilikinya
		 */
		$lock_tbl = "LOCK TABLE unitkerja WRITE";
		$this->db->query($lock_tbl);
		
		$sql = "SELECT LFT, RGT, (RGT - LFT + 1) AS mywidth
						FROM unitkerja
						WHERE KODEUNIT = ".$data->KODEUNIT;
		$record = $this->db->query($sql)->row();
		$myLeft = $record->LFT;
		$myRight = $record->RGT;
		$myWidth = $record->mywidth;
		
		$sqld = "DELETE FROM unitkerja WHERE LFT BETWEEN ".$myLeft." AND ".$myRight;
		$this->db->query($sqld);
		
		$sqlu = "UPDATE unitkerja SET RGT = RGT - ".$myWidth." WHERE RGT > ".$myRight;
		$this->db->query($sqlu);
		$sqlu = "UPDATE unitkerja SET LFT = LFT - ".$myWidth." WHERE LFT > ".$myRight;
		$this->db->query($sqlu);
		
		$unlock_tbl = "UNLOCK TABLES";
		$this->db->query($unlock_tbl);
		
		
		$total  = $this->db->get('vu_unitkerja')->num_rows();
		$last = $this->db->get('vu_unitkerja')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						'total'     => $total,
						"data"      => $last
		);
		
		return $json;
	}
}
?>