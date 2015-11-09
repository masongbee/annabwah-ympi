<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_td_pelatihan
 * 
 * Table	: td_pelatihan
 *  
 * @author masongbee
 *
 */
class M_td_pelatihan extends CI_Model{

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
	function getAll($start, $page, $limit, $filter){
		$select 	= "SELECT riwayattraining.NIK
			,karyawan.NAMAKAR
			,riwayattraining.KODETRAINING
			,riwayattraining.NAMATRAINING
			,realisasitraining.TAHUN
			,riwayattraining.TEMPAT
			,riwayattraining.TGLMULAI
			,riwayattraining.TGLSAMPAI
			,riwayattraining.PENYELENGGARA
			,riwayattraining.KETERANGAN";
		$from		= " FROM riwayattraining
			LEFT JOIN realisasitraining ON(realisasitraining.KODETRAINING = riwayattraining.KODETRAINING)
			LEFT JOIN karyawan ON(karyawan.NIK = riwayattraining.NIK)";

		if ($filter != '') {
			$from .=preg_match("/WHERE/i",$from)? " AND ":" WHERE ";
			$from .= " (riwayattraining.KODETRAINING = '".$filter."' OR riwayattraining.NAMATRAINING LIKE '%".$filter."%' OR LOWER(riwayattraining.NIK) LIKE '%".addslashes(strtolower($filter))."%' OR LOWER(karyawan.NAMAKAR) LIKE '%".addslashes(strtolower($filter))."%')";
		}
		$sql	= $select.$from;
		
		$result = $this->db->query($sql)->result();
		
		/*$data   = array();
		foreach($result as $row){
			$data[] = $row;
		}*/
		
		$json	= array(
			'success'   => TRUE,
			'message'   => "Loaded data",
			'data'      => $result
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
		
		$pkey = array('TDPELATIHAN_ID'=>$data->TDPELATIHAN_ID);
		$dataexists = $this->db->select('TDPELATIHAN_ID')->get_where('td_pelatihan', $pkey)->num_rows();

		$arrdatacu = array(
			'TDPELATIHAN_NO'                =>$data->TDPELATIHAN_NO,
			'TDPELATIHAN_TANGGAL'           =>(strlen(trim($data->TDPELATIHAN_TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TDPELATIHAN_TANGGAL)) : NULL),
			'TDPELATIHAN_DIBUAT'            =>$data->TDPELATIHAN_DIBUAT,
			'TDPELATIHAN_DIBUAT_NAMA'       =>$data->TDPELATIHAN_DIBUAT_NAMA,
			'TDPELATIHAN_DIPERIKSA'         =>$data->TDPELATIHAN_DIPERIKSA,
			'TDPELATIHAN_DIPERIKSA_NAMA'    =>$data->TDPELATIHAN_DIPERIKSA_NAMA,
			'TDPELATIHAN_DIKETAHUI'         =>$data->TDPELATIHAN_DIKETAHUI,
			'TDPELATIHAN_DIKETAHUI_NAMA'    =>$data->TDPELATIHAN_DIKETAHUI_NAMA,
			'TDPELATIHAN_DISETUJUI01'       =>$data->TDPELATIHAN_DISETUJUI01,
			'TDPELATIHAN_DISETUJUI01_NAMA'  =>$data->TDPELATIHAN_DISETUJUI01_NAMA,
			'TDPELATIHAN_DISETUJUI02'       =>$data->TDPELATIHAN_DISETUJUI02,
			'TDPELATIHAN_DISETUJUI02_NAMA'  =>$data->TDPELATIHAN_DISETUJUI02_NAMA,
			'TDPELATIHAN_DISETUJUI03'       =>$data->TDPELATIHAN_DISETUJUI03,
			'TDPELATIHAN_DISETUJUI03_NAMA'  =>$data->TDPELATIHAN_DISETUJUI03_NAMA,
			'TDPELATIHAN_TDTRAINING_ID'     =>$data->TDPELATIHAN_TDTRAINING_ID,
			'TDPELATIHAN_TDTRAINING_NAMA'   =>$data->TDPELATIHAN_TDTRAINING_NAMA,
			'TDPELATIHAN_TDKELOMPOK_ID'     =>$data->TDPELATIHAN_TDKELOMPOK_ID,
			'TDPELATIHAN_TDKELOMPOK_NAMA'   =>$data->TDPELATIHAN_TDKELOMPOK_NAMA,
			'TDPELATIHAN_TDTRAINING_TUJUAN' =>$data->TDPELATIHAN_TDTRAINING_TUJUAN,
			'TDPELATIHAN_TDTRAINING_JENIS'  =>$data->TDPELATIHAN_TDTRAINING_JENIS,
			'TDPELATIHAN_TDTRAINING_SIFAT'  =>$data->TDPELATIHAN_TDTRAINING_SIFAT,
			'TDPELATIHAN_PESERTA'           =>$data->TDPELATIHAN_PESERTA,
			'TDPELATIHAN_PESERTA_JUMLAH'    =>$data->TDPELATIHAN_PESERTA_JUMLAH,
			'TDPELATIHAN_DURASI'            =>$data->TDPELATIHAN_DURASI,
			'TDPELATIHAN_BIAYA_PLAN'        =>$data->TDPELATIHAN_BIAYA_PLAN,
			'TDPELATIHAN_BIAYA_AKTUAL'      =>$data->TDPELATIHAN_BIAYA_AKTUAL,
			'TDPELATIHAN_BIAYA_BALANCE'     =>$data->TDPELATIHAN_BIAYA_BALANCE,
			'TDPELATIHAN_TDTRAINER_ID'      =>$data->TDPELATIHAN_TDTRAINER_ID,
			'TDPELATIHAN_TDTRAINER_NAMA'    =>$data->TDPELATIHAN_TDTRAINER_NAMA,
			'TDPELATIHAN_EVREAKSI'          =>$data->TDPELATIHAN_EVREAKSI,
			'TDPELATIHAN_EVEFFECTIVITAS'    =>$data->TDPELATIHAN_EVEFFECTIVITAS
		);
		
		$arrdataupdated = array(
			'TDPELATIHAN_UPDATED_DATE' =>date(LONG_FORMATDATE)
		);
		
		$arrdatau = array_merge($arrdatacu, $arrdataupdated);
		
		$arrdatacreated = array(
			'TDPELATIHAN_CREATED_DATE'=>date(LONG_FORMATDATE)
		);
		
		$arrdatac = array_merge($arrdatacu, $arrdatacreated);
		
		if($dataexists > 0){
			/*
			 * Data Exist
			 */			 
			$this->db->where($pkey)->update('td_pelatihan', $arrdatau);
			if($this->db->affected_rows()){
				$this->db->where($pkey)->set('TDPELATIHAN_REVISED', 'TDPELATIHAN_REVISED+1', FALSE)->update('td_pelatihan');
			}

			$last   = $data;
			
			$json   = array(
				"success"   => TRUE,
				"message"   => 'Data berhasil diubah.',
				"data"      => $last
			);
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('td_pelatihan', $arrdatac);
			$masterid = $this->db->insert_id();

			if($masterid > 0){
				$this->rencana($masterid, $data->TDPELATIHAN_DATE_PLAN);
				$this->realisasi($masterid, $data->TDPELATIHAN_DATE_AKTUAL);

				$last   = $this->db->where('TDPELATIHAN_ID', $masterid)->get('td_pelatihan')->row();
			}
			
			$json   = array(
				"success"   => TRUE,
				"message"   => 'Data berhasil disimpan',
				"data"      => $last
			);
			
		}
		
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
		$pkey = array('TDPELATIHAN_ID'=>$data->TDPELATIHAN_ID);
		
		$this->db->where($pkey)->delete('td_pelatihan');
		
		$total  = $this->db->get('td_pelatihan')->num_rows();
		$last = $this->db->get('td_pelatihan')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						'total'     => $total,
						"data"      => $last
		);				
		return $json;
	}

	/**
	 * Fungsi : rencana
	 *
	 * Untuk menyimpan tanggal Rencana Pelaksanaan
	 * 
	 */
	function rencana($masterid, $arrdate){
		$this->db->where(array('TDRENCANA_TDPELATIHAN_ID'=>$masterid))->delete('td_rencana');

		foreach ($arrdate as $value) {
			$arrdatac = array(
				'TDRENCANA_TDPELATIHAN_ID' => $masterid,
				'TDRENCANA_TANGGAL' => date('Y-m-d', strtotime($value))
			);
			$this->db->insert('td_rencana', $arrdatac);
		}
	}

	/**
	 * Fungsi : realisasi
	 *
	 * Untuk menyimpan tanggal Rencana Pelaksanaan
	 * 
	 */
	function realisasi($masterid, $arrdate){
		$this->db->where(array('TDREALISASI_TDPELATIHAN_ID'=>$masterid))->delete('td_realisasi');

		foreach ($arrdate as $value) {
			$arrdatac = array(
				'TDREALISASI_TDPELATIHAN_ID' => $masterid,
				'TDREALISASI_TANGGAL' => date('Y-m-d', strtotime($value))
			);
			$this->db->insert('td_realisasi', $arrdatac);
		}
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
			// $p = 0;
			$skeepdata = 0;
			foreach($data->getWorksheetIterator() as $worksheet){
				$worksheetTitle     = $worksheet->getTitle();
				// if($p>0){
				// 	break;
				// }

				$worksheetTitle     = $worksheet->getTitle();
				if ($worksheetTitle == 'JENISTRAINING') {
					$this->importJenisTraining($worksheet);
				} else if ($worksheetTitle == 'REALISASITRAINING') {
					$this->importRealisasiTraining($worksheet);
				} else if ($worksheetTitle == 'RIWAYATTRAINING') {
					$this->importRiwayatTraining($worksheet);
				}
				
				// $p++;
			}
			
			$success = array(
				'success'	=> true,
				'msg'		=> 'Data telah berhasil ditambahkan.',
				'filename'	=> $filename,
				'skeepdata'	=> 0// $skeepdata
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

	function importJenisTraining($worksheet){
		$highestRow         = $worksheet->getHighestRow(); // e.g. 10
		$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$skeepdata = 0;
		for ($row = 1; $row <= $highestRow; ++ $row) {
			if($row>1){
				for ($col = 0; $col < $highestColumnIndex; ++ $col) {
					$kodetraining = (trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()));
					$namatraining = (trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
					$internal = (trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
					$sifat = (trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
					$penyelenggara = (trim($worksheet->getCellByColumnAndRow(4, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
				}

				if (!is_null($kodetraining)) {
					$data = array(
						'KODETRAINING'  => $kodetraining,
						'NAMATRAINING'  => $namatraining,
						'INTERNAL'      => $internal,
						'SIFAT'         => $sifat,
						'PENYELENGGARA' => $penyelenggara
					);
					if($this->db->get_where('jenistraining', array('KODETRAINING'=>$kodetraining))->num_rows() == 0){
						$this->db->insert('jenistraining', $data);
					}else{
						$skeepdata++;
					}
				}
			}
		}
	}

	function importRealisasiTraining($worksheet){
		$highestRow         = $worksheet->getHighestRow(); // e.g. 10
		$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$skeepdata = 0;
		for ($row = 1; $row <= $highestRow; ++ $row) {
			if($row>1){
				for ($col = 0; $col < $highestColumnIndex; ++ $col) {
					$kodetraining = (trim($worksheet->getCellByColumnAndRow(0, $row)->getCalculatedValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(0, $row)->getCalculatedValue()));
					$tahun = (trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
					// $norealisasi = (trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
					$tempat = (trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
					$tglmulai = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(3, $row)->getValue(), 'yyyy-mm-dd');
					$tglsampai = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(4, $row)->getValue(), 'yyyy-mm-dd');
					$jmlpeserta = (trim($worksheet->getCellByColumnAndRow(5, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(5, $row)->getValue()));
					$rpbiaya = (trim($worksheet->getCellByColumnAndRow(6, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(6, $row)->getValue()));
				}

				if (!is_null($kodetraining)) {
					$data = array(
						'KODETRAINING' => $kodetraining,
						'TAHUN'        => $tahun,
						// 'NOREALISASI'  => $norealisasi,
						'TEMPAT'       => $tempat,
						'TGLMULAI'     => $tglmulai,
						'TGLSAMPAI'    => $tglsampai,
						'JMLPESERTA'   => $jmlpeserta,
						'RPBIAYA'      => $rpbiaya
					);
					if($this->db->get_where('realisasitraining', array('KODETRAINING'=>$kodetraining, 'TAHUN'=>$tahun))->num_rows() == 0){
						$this->db->insert('realisasitraining', $data);
					}else{
						$skeepdata++;
					}
				}
			}
		}
	}

	function importRiwayatTraining($worksheet){
		$highestRow         = $worksheet->getHighestRow(); // e.g. 10
		$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$skeepdata = 0;
		for ($row = 1; $row <= $highestRow; ++ $row) {
			if($row>1){
				for ($col = 0; $col < $highestColumnIndex; ++ $col) {
					$nik = (trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()));
					$kodetraining = (trim($worksheet->getCellByColumnAndRow(1, $row)->getCalculatedValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(1, $row)->getCalculatedValue()));
					$namatraining = (trim($worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue()));
					$tempat = (trim($worksheet->getCellByColumnAndRow(3, $row)->getCalculatedValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(3, $row)->getCalculatedValue()));
					$penyelenggara = (trim($worksheet->getCellByColumnAndRow(4, $row)->getCalculatedValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(4, $row)->getCalculatedValue()));
					$tglmulai = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(5, $row)->getCalculatedValue(), 'yyyy-mm-dd');
					$tglsampai = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(6, $row)->getCalculatedValue(), 'yyyy-mm-dd');
					$keterangan = (trim($worksheet->getCellByColumnAndRow(7, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(7, $row)->getValue()));
				}
				
				if (!is_null($nik)) {
					$data = array(
						'NIK'           => $nik,
						'KODETRAINING'  => $kodetraining,
						'NAMATRAINING'  => $namatraining,
						'TEMPAT'        => $tempat,
						'PENYELENGGARA' => $penyelenggara,
						'TGLMULAI'      => $tglmulai,
						'TGLSAMPAI'     => $tglsampai,
						'KETERANGAN'    => $keterangan
					);
					if($this->db->get_where('riwayattraining', array('NIK'=>$nik, 'KODETRAINING'=>$kodetraining))->num_rows() == 0){
						$this->db->insert('riwayattraining', $data);
					}else{
						$skeepdata++;
					}
				}
			}
		}
	}
}
?>