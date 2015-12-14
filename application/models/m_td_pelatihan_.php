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
	function getAll($start, $page, $limit){
		$query  = $this->db->limit($limit, $start)->order_by('TDPELATIHAN_ID', 'ASC')->get('td_pelatihan')->result();
		$total  = $this->db->get('td_pelatihan')->num_rows();
		
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





		$select 	= "SELECT TDPELATIHAN_ID
			,TDPELATIHAN_NO
			,TDPELATIHAN_TANGGAL
			,TDPELATIHAN_DIBUAT
			,TDPELATIHAN_DIBUAT_NAMA
			,TDPELATIHAN_DIPERIKSA
			,TDPELATIHAN_DIPERIKSA_NAMA
			,TDPELATIHAN_DIKETAHUI
			,TDPELATIHAN_DIKETAHUI_NAMA
			,TDPELATIHAN_DISETUJUI01
			,TDPELATIHAN_DISETUJUI01_NAMA
			,TDPELATIHAN_DISETUJUI02
			,TDPELATIHAN_DISETUJUI02_NAMA
			,TDPELATIHAN_DISETUJUI03
			,TDPELATIHAN_DISETUJUI03_NAMA
			,TDPELATIHAN_TDTRAINING_ID
			,TDPELATIHAN_TDTRAINING_NAMA
			,TDPELATIHAN_TDKELOMPOK_ID
			,TDPELATIHAN_TDKELOMPOK_NAMA
			,TDPELATIHAN_TDTRAINING_TUJUAN
			,TDPELATIHAN_TDTRAINING_JENIS
			,TDPELATIHAN_TDTRAINING_SIFAT
			,TDPELATIHAN_PESERTA
			,TDPELATIHAN_PESERTA_JUMLAH
			,TDPELATIHAN_DURASI
			,TDPELATIHAN_BIAYA_PLAN
			,TDPELATIHAN_BIAYA_AKTUAL
			,TDPELATIHAN_BIAYA_BALANCE
			,TDPELATIHAN_TDTRAINER_ID
			,TDPELATIHAN_TDTRAINER_NAMA
			,TDPELATIHAN_EVREAKSI
			,TDPELATIHAN_EVEFFECTIVITAS
			,GROUP_CONCAT(TDRENCANA_TANGGAL) AS TDRENCANA_TANGGAL
			,GROUP_CONCAT(TDREALISASI_TANGGAL) AS TDREALISASI_TANGGAL";
		$from		= " FROM td_pelatihan
			LEFT JOIN td_rencana ON (TDRENCANA_TDPELATIHAN_ID = TDPELATIHAN_ID)
			LEFT JOIN td_realisasi ON (TDREALISASI_TDPELATIHAN_ID = TDPELATIHAN_ID)";
		$groupby	= " GROUP BY TDPELATIHAN_ID";

		$sql	= $select.$from.$groupby;
		
		$result = $this->db->query($sql)->result();
		
		$data   = array();
		foreach($result as $row){
			$data[] = $row;
		}
		
		$json	= array(
			'success'   => TRUE,
			'message'   => "Loaded data",
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
}
?>