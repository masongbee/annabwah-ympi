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
		
		// melengkapi NIK
		$sql = "SELECT NIK
		FROM karyawan
		WHERE SUBSTR(NIK,2,LENGTH(NIK))=".$this->db->escape($data->NIK)." LIMIT 1";
		$nik = $this->db->query($sql)->row()->NIK;

		// mencari ke rencanalembur
		$date = (isset($data->TJMASUK) ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : date('Y-m-d H:i:s'));
		// $datekeluar = (isset($data->TJKELUAR) ? date('Y-m-d H:i:s', strtotime($data->TJKELUAR)) : date('Y-m-d H:i:s'));
		$sql = "SELECT sp.KODEUNIT, rl.NOLEMBUR, rl.NOURUT, sp.TANGGAL, rl.NIK, rl.TJMASUK, rl.TJKELUAR, sp.KEPERLUAN, rl.JENISLEMBUR
		FROM splembur sp
		RIGHT JOIN rencanalembur rl
		ON rl.NOLEMBUR=sp.NOLEMBUR
		WHERE rl.NIK='".$nik."' AND (DATE('".$date."') BETWEEN DATE(rl.TJMASUK) AND DATE(rl.TJKELUAR))";
		$query = $this->db->query($sql);
		$rs = $query->row();
		
		// jika ada di SPL / RencanaLembur
		if($query->num_rows() > 0){			
			// cek apa sudah presensi lembur sebelumnya?

			$pkey = array('NIK'=>$nik, 'NOLEMBUR'=>$rs->NOLEMBUR, 'NOURUT'=>$rs->NOURUT);
			$total  = $this->db->get_where('presensilembur', $pkey)->num_rows();

			if($total > 0){
				// jika sudah ada
				$json   = array(
					"success"   => TRUE,
					"message"   => 'Anda sudah presensi lembur sebelumnya!',
					'total'     => $total,
					"data"      => $last
				);
			}
			else {
				// jika belum ada, simpan
				$arrdatac = array('NIK'=>$nik,'TJMASUK'=>(strlen(trim($data->TJMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : mdate("%Y-%m-%d %H:%i:%s", time())),
					              'NOLEMBUR'=>$rs->NOLEMBUR,'NOURUT'=>$rs->NOURUT,'JENISLEMBUR'=>$rs->JENISLEMBUR);
				//$this->firephp->info(mdate("%Y-%m-%d %H:%i:%s", time()));
				
				$this->db->insert('presensilembur', $arrdatac);
				$last   = $this->db->where($pkey)->get('presensilembur')->row();
				
				// $total  = $this->db->get('presensilembur')->num_rows();
				
				$json   = array(
					"success"   => TRUE,
					"message"   => 'Anda ada jadwal lembur sesuai SPL!',
					'total'     => $total,
					"data"      => $last
				);
			}
		}
		else{
			$json   = array(
				"success"   => TRUE,
				"message"   => 'Anda tidak ada jadwal lembur sesuai SPL!',
				'total'     => 0,
				"data"      => $last
			);			
		}


		// remark lama

		// $pkey = array('NIK'=>$data->NIK,'TJMASUK'=>date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
		// $sql = "SELECT NIK
		// FROM karyawan
		// WHERE SUBSTR(NIK,2,LENGTH(NIK))=".$this->db->escape($data->NIK)."";
		// $nik = $this->db->query($sql)->result();
		
		// //$this->firephp->info($nik[0]->NIK);
		
		// $rs = $this->db->select('NIK')->where(array('NIK' => $nik[0]->NIK))->get('karyawan')->num_rows();
		
		// if($rs > 0)
		// {

		// 	if($this->db->get_where('presensilembur', $pkey)->num_rows() > 0){
		// 		/*
		// 		 * Data Exist
		// 		 */			 
					
				 
		// 		$arrdatau = array('NOLEMBUR'=>$data->NOLEMBUR,'NOURUT'=>$data->NOURUT,'JENISLEMBUR'=>$data->JENISLEMBUR);
				 
		// 		$this->db->where($pkey)->update('presensilembur', $arrdatau);
		// 		$last   = $data;
				
			
		// 		$total  = $this->db->get('presensilembur')->num_rows();
				
		// 		$json   = array(
		// 						"success"   => TRUE,
		// 						"message"   => 'Data berhasil disimpan',
		// 						'total'     => $total,
		// 						"data"      => $last
		// 		);
		// 	}
		// 	else{
		// 		/*
		// 		 * Data Not Exist
		// 		 * 
		// 		 * Process Insert
		// 		 */
		// 		$date = (isset($data->TJMASUK) ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : date('Y-m-d H:i:s'));
		// 		$datekeluar = (isset($data->TJKELUAR) ? date('Y-m-d H:i:s', strtotime($data->TJKELUAR)) : date('Y-m-d H:i:s'));
		// 		$sql = "SELECT sp.KODEUNIT, rl.NOLEMBUR, rl.NOURUT, sp.TANGGAL, rl.NIK, rl.TJMASUK, rl.TJKELUAR, sp.KEPERLUAN, rl.JENISLEMBUR
		// 		FROM splembur sp
		// 		RIGHT JOIN rencanalembur rl
		// 		ON rl.NOLEMBUR=sp.NOLEMBUR
		// 		WHERE rl.NIK=".$this->db->escape($nik[0]->NIK)." AND (DATE(rl.TJMASUK)<=DATE('".$date."') OR DATE(rl.TJKELUAR)=DATE('".$datekeluar."'))";
		// 		$query = $this->db->query($sql);
		// 		$rs = $query->result();
				
		// 		$this->firephp->info($sql);
				
		// 		if($query->num_rows() > 0){			
		// 			$arrdatac = array('NIK'=>$nik[0]->NIK,'TJMASUK'=>(strlen(trim($data->TJMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : mdate("%Y-%m-%d %H:%i:%s", time())),'NOLEMBUR'=>$rs[0]->NOLEMBUR,'NOURUT'=>$rs[0]->NOURUT,'JENISLEMBUR'=>$rs[0]->JENISLEMBUR);
		// 			//$this->firephp->info(mdate("%Y-%m-%d %H:%i:%s", time()));
					
		// 			$this->firephp->info("Ada Datanya di SPL");
		// 			$this->db->insert('presensilembur', $arrdatac);
		// 			$last   = $this->db->where($pkey)->get('presensilembur')->row();
					
		// 			$total  = $this->db->get('presensilembur')->num_rows();
					
		// 			$json   = array(
		// 				"success"   => TRUE,
		// 				"message"   => 'Anda ada jadwal lembur di SPL',
		// 				'total'     => $total,
		// 				"data"      => $last
		// 			);
		// 		}
		// 		else
		// 		{
		// 			$arrdatac = array('NIK'=>$nik[0]->NIK,'TJMASUK'=>(strlen(trim($data->TJMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : mdate("%Y-%m-%d %H:%i:%s", time())),'NOLEMBUR'=>$data->NOLEMBUR,'NOURUT'=>$data->NOURUT,'JENISLEMBUR'=>$data->JENISLEMBUR);
		// 			//$this->firephp->info(mdate("%Y-%m-%d %H:%i:%s", time()));
					
		// 			$this->firephp->info("Tak ada Datanya di SPL");
					
		// 			$total  = $this->db->get('presensilembur')->num_rows();
					
		// 			$json   = array(
		// 				"success"   => FALSE,
		// 				"message"   => 'Anda tidak ada jadwal lembur di SPL!',
		// 				'total'     => $total,
		// 				"data"      => $last
		// 			);
		// 		}
		// 	}
		// }
		// else
		// {
		// 	$total  = $this->db->get('presensilembur')->num_rows();
			
		// 	$json   = array(
		// 					"success"   => FALSE,
		// 					"message"   => 'Data gagal disimpan',
		// 					'total'     => $total,
		// 					"data"      => $last
		// 	);
		// }
		
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
	
	/**
	 * Fungsi	: do_upload
	 *
	 * Untuk menginjeksi data dari Excel ke Database
	 *
	 * @param array $data
	 * @return array
	 */
	function do_upload($data, $filename){
		if(sizeof($data) > 0){
			$p = 0;
			foreach($data->getWorksheetIterator() as $worksheet){
				if($p>0){
					break;
				}
				
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				$skeepdata = 0;
				for ($row = 1; $row <= $highestRow; ++ $row) {
					if($row>1){
						for ($col = 0; $col < $highestColumnIndex; ++ $col) {
							//$validfrom = PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCellByColumnAndRow(0, $row)->getValue());
							$nik = (trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()));
							$tjmasuk = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(1, $row)->getValue(), 'yyyy-mm-dd hh:ii:ss');
							$nolembur = (trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
							$nourut = (trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
							$jenislembur = (trim($worksheet->getCellByColumnAndRow(4, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
						}
						
						$data = array(
							'NIK'		=> $nik,
							'TJMASUK'	=> $tjmasuk,
							'NOLEMBUR'	=> $nolembur,
							'NOURUT'	=> $nourut,
							'JENISLEMBUR'=> $jenislembur
						);
						$key_presensilembur = array(
							'NIK'		=> $nik,
							'TJMASUK'	=> date('Y-m-d H:i:s', strtotime($tjmasuk)),
							'NOLEMBUR'	=> $nolembur,
							'NOURUT'	=> $nourut,
							'JENISLEMBUR'=> $jenislembur
						);
						if($this->db->get_where('presensilembur', $key_presensilembur)->num_rows() == 0){
							$this->db->insert('presensilembur', $data);
						}else{
							$skeepdata++;
						}
						
					}
				}
				
				$p++;
			}
			
			$success = array(
				'success'	=> true,
				'msg'		=> 'Data telah berhasil ditambahkan.',
				'filename'	=> $filename,
				'skeepdata'	=> $skeepdata
			);
			return $success;
		}else{
			$error = array(
				'success'	=> false,
				'msg'		=> 'Tidak ada proses, karena data kosong.',
				'filename'	=> $filename
			);
			return $error;
		}
	}
}
?>