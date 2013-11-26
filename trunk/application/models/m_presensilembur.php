<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_presensilembur
 * 
 * Table	: presensilembur
 *  
 * @author masongbee
 *
 */
class M_presensilembur extends CI_Model{

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
		$sql = "SELECT pl.NIK, k.NAMAKAR AS NAMA, pl.TJMASUK, pl.NOLEMBUR, pl.NOURUT, pl.JENISLEMBUR
		FROM presensilembur pl
		INNER JOIN karyawan k ON k.NIK=pl.NIK
		ORDER BY TJMASUK DESC
		LIMIT $start,$limit";
		$query = $this->db->query($sql)->result();
		
		//$query  = $this->db->limit($limit, $start)->order_by('TJMASUK', 'ASC')->get('presensilembur')->result();
		$total  = $this->db->get('presensilembur')->num_rows();
		
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
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
		
		$pkey = array('NIK'=>$data->NIK,'TJMASUK'=>date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
		$sql = "SELECT NIK
		FROM karyawan
		WHERE SUBSTR(NIK,2,LENGTH(NIK))=".$this->db->escape($data->NIK)."";
		$nik = $this->db->query($sql)->result();
		
		//$this->firephp->info($nik[0]->NIK);
		
		$rs = $this->db->select('NIK')->where(array('NIK' => $nik[0]->NIK))->get('karyawan')->num_rows();
		
		if($rs > 0)
		{
			if($this->db->get_where('presensilembur', $pkey)->num_rows() > 0){
				/*
				 * Data Exist
				 */			 
					
				 
				$arrdatau = array('NOLEMBUR'=>$data->NOLEMBUR,'NOURUT'=>$data->NOURUT,'JENISLEMBUR'=>$data->JENISLEMBUR);
				 
				$this->db->where($pkey)->update('presensilembur', $arrdatau);
				$last   = $data;
				
			
				$total  = $this->db->get('presensilembur')->num_rows();
				
				$json   = array(
								"success"   => TRUE,
								"message"   => 'Data berhasil disimpan',
								'total'     => $total,
								"data"      => $last
				);
			}
			else{
				/*
				 * Data Not Exist
				 * 
				 * Process Insert
				 */
				$date = (isset($data->TJMASUK) ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : date('Y-m-d H:i:s'));
				$sql = "SELECT sp.KODEUNIT, rl.NOLEMBUR, rl.NOURUT, sp.TANGGAL, rl.NIK, rl.TJMASUK, rl.TJKELUAR, sp.KEPERLUAN, rl.JENISLEMBUR
				FROM splembur sp
				RIGHT JOIN rencanalembur rl
				ON rl.NOLEMBUR=sp.NOLEMBUR
				WHERE rl.NIK=".$this->db->escape($nik[0]->NIK)." AND DATE(rl.TJMASUK)=DATE('".$date."')";
				$query = $this->db->query($sql);
				$rs = $query->result();
				
				$this->firephp->info($sql);
				
				if($query->num_rows() > 0){			
					$arrdatac = array('NIK'=>$nik[0]->NIK,'TJMASUK'=>(strlen(trim($data->TJMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : mdate("%Y-%m-%d %H:%i:%s", time())),'NOLEMBUR'=>$rs[0]->NOLEMBUR,'NOURUT'=>$rs[0]->NOURUT,'JENISLEMBUR'=>$rs[0]->JENISLEMBUR);
					//$this->firephp->info(mdate("%Y-%m-%d %H:%i:%s", time()));
					
					$this->firephp->info("Ada Datanya di SPL");
					$this->db->insert('presensilembur', $arrdatac);
					$last   = $this->db->where($pkey)->get('presensilembur')->row();
					
					$total  = $this->db->get('presensilembur')->num_rows();
					
					$json   = array(
						"success"   => TRUE,
						"message"   => 'Ada Datanya di SPL',
						'total'     => $total,
						"data"      => $last
					);
				}
				else
				{
					$arrdatac = array('NIK'=>$nik[0]->NIK,'TJMASUK'=>(strlen(trim($data->TJMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : mdate("%Y-%m-%d %H:%i:%s", time())),'NOLEMBUR'=>$data->NOLEMBUR,'NOURUT'=>$data->NOURUT,'JENISLEMBUR'=>$data->JENISLEMBUR);
					//$this->firephp->info(mdate("%Y-%m-%d %H:%i:%s", time()));
					
					$this->firephp->info("Tak ada Datanya di SPL");
					
					$total  = $this->db->get('presensilembur')->num_rows();
					
					$json   = array(
						"success"   => FALSE,
						"message"   => 'Tak ada Datanya di SPL',
						'total'     => $total,
						"data"      => $last
					);
				}
			}
		}
		else
		{
			$total  = $this->db->get('presensilembur')->num_rows();
			
			$json   = array(
							"success"   => FALSE,
							"message"   => 'Data gagal disimpan',
							'total'     => $total,
							"data"      => $last
			);
		}
		
		//$this->firephp->info($data->NIK);
		return $json;
	}
	
	
	/**
	 * Fungsi	: delete
	 * 
	 * Untuk menghapus satu data
	 * 
	 * @param array $data
	 * @return json
	 */
	function delete($data){
		$pkey = array('NIK'=>$data->NIK,'TJMASUK'=>date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
		
		$this->db->where($pkey)->delete('presensilembur');
		
		$total  = $this->db->get('presensilembur')->num_rows();
		$last = $this->db->get('presensilembur')->result();
		
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