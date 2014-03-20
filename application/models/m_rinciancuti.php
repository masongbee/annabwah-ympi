<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_rinciancuti
 * 
 * Table	: rinciancuti
 *  
 * @author masongbee
 *
 */
class M_rinciancuti extends CI_Model{

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
	
	
	function getSisa($item){
		if($item['JENIS'] == "SISACUTI")
		{
			$sql = "SELECT SUM(SISACUTI) AS SISACUTI
			FROM cutitahunan
			WHERE NIK = ".$this->db->escape($item['KEY'])." AND DIKOMPENSASI = 'N'
			GROUP BY NIK";
			$query = $this->db->query($sql)->result();
		}
		
		$data   = '';
		foreach($query as $result){
			$data[] = $result;
		}
		
		$json	= array(
			'success'   => TRUE,
			'message'   => 'Loaded data',
			'data'      => $data
		);
		
		return $json;
	}
	
	function getAll($nocuti,$start, $page, $limit){
		//$query  = $this->db->where('NOCUTI',$nocuti)->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('rinciancuti')->result();
		//$total  = $this->db->get('rinciancuti')->num_rows();
		
		$sql = "SELECT rc.NOCUTI,rc.NOURUT,rc.NIK,k.NAMAKAR,rc.JENISABSEN,rc.LAMA,rc.TGLMULAI
		,rc.TGLSAMPAI,rc.SISACUTI,rc.ALASAN,rc.STATUSCUTI
		FROM rinciancuti rc
		INNER JOIN karyawan k ON k.NIK=rc.NIK
		WHERE rc.NOCUTI = '".$nocuti."'
		ORDER BY NOURUT
		LIMIT ".$start.",".$limit;
		
		
		$query = $this->db->query($sql)->result();
		$total  = $this->db->query("SELECT rc.NOCUTI,rc.NOURUT,rc.NIK,k.NAMAKAR,rc.JENISABSEN,rc.LAMA,rc.TGLMULAI
		,rc.TGLSAMPAI,rc.SISACUTI,rc.ALASAN,rc.STATUSCUTI
		FROM rinciancuti rc
		INNER JOIN karyawan k ON k.NIK=rc.NIK
		WHERE rc.NOCUTI = '".$nocuti."'")->num_rows();
		
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
		
		$pkey = array('NOCUTI'=>$data->NOCUTI,'NOURUT'=>$data->NOURUT);
		
		$row = $this->db->get_where('rinciancuti', $pkey)->row();
		
		if(sizeof($row) > 0){
			/*
			 * Data Exist
			 */
			 
			$n = new DateTime((strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL));
			$m = new DateTime((strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL));
			$rs = $n->diff($m);
			
			// 'LAMA'=>($rs->format('%d') > 0 ? ($rs->format('%d') + 1): 1),
			$arrdatau = array(
				'NIK'=>$data->NIK,
				'JENISABSEN'=>$data->JENISABSEN,
				'LAMA'=>($rs->days > 0 ? ($rs->days + 1): 1),
				'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),
				'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),
				'SISACUTI'=>$data->SISACUTI,
				'ALASAN'=>$data->ALASAN,
				'STATUSCUTI'=>$data->STATUSCUTI
			);
			
			$this->db->where($pkey)->update('rinciancuti', $arrdatau);
			
			if($this->db->affected_rows()){
				if($row->STATUSCUTI == 'T' && $data->STATUSCUTI == 'C'){
					$this->cutitahunan_return($data);
				}
			}
			
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 
			$sql = "SELECT NOCUTI,MAX(NOURUT) AS NOURUT,NIK,
			IF(ISNULL(MAX(NOURUT)),1,MAX(NOURUT) + 1) AS GEN
			FROM rinciancuti
			WHERE NOCUTI='".$data->NOCUTI."';";
			$rs = $this->db->query($sql);
			$hasil = $rs->result();
			
			$n = new DateTime((strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL));
			$m = new DateTime((strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL));
			$rs = $n->diff($m);
			
			// $arrdatac = array('NOCUTI'=>$data->NOCUTI,'NOURUT'=>$hasil[0]->GEN,'NIK'=>$data->NIK,'JENISABSEN'=>$data->JENISABSEN,'LAMA'=>($rs->format('%d') > 0 ? ($rs->format('%d') + 1): 1),'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),'SISACUTI'=>$data->SISACUTI,'ALASAN'=>$data->ALASAN,'STATUSCUTI'=>$data->STATUSCUTI);
			$arrdatac = array('NOCUTI'=>$data->NOCUTI,'NOURUT'=>$hasil[0]->GEN,'NIK'=>$data->NIK,'JENISABSEN'=>$data->JENISABSEN,'LAMA'=>($rs->days > 0 ? ($rs->days + 1): 1),'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),'SISACUTI'=>$data->SISACUTI,'ALASAN'=>$data->ALASAN,'STATUSCUTI'=>$data->STATUSCUTI);

			$this->db->insert('rinciancuti', $arrdatac);
			$last   = $this->db->where($pkey)->get('rinciancuti')->row();
			
		}
		
		$total  = $this->db->get('rinciancuti')->num_rows();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil disimpan',
						"total"     => $total,
						"data"      => $last
		);
		
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
		$pkey = array('NOCUTI'=>$data->NOCUTI,'NOURUT'=>$data->NOURUT);
		
		$this->db->where($pkey)->delete('rinciancuti');
		
		$total  = $this->db->get('rinciancuti')->num_rows();
		$last = $this->db->get('rinciancuti')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						"total"     => $total,
						"data"      => $last
		);				
		return $json;
	}
	
	/**
	 * Fungsi: cutitahunan_return
	 *
	 * Cuti Tahunan dikembalikan karena permohonan ijin di-Batalkan
	 */
	function cutitahunan_return($data){
		$this->db->where(array('NIK'=>$data->NIK, 'JENISCUTI'=>'1', 'DIKOMPENSASI'=>'N', 'JMLCUTI >'=>0))
			->set('JMLCUTI', 'JMLCUTI-'.$data->LAMA, FALSE)
			->set('SISACUTI', 'SISACUTI+'.$data->LAMA, FALSE)
			->update('cutitahunan');
		if($this->db->affected_rows() == 0){
			//update db.cutitahunan dengan JENISCUTI = '0' (Cuti Tahunan)
			$this->db->where(array('NIK'=>$data->NIK, 'JENISCUTI'=>'0', 'DIKOMPENSASI'=>'N'))
				->set('JMLCUTI', 'JMLCUTI-'.$data->LAMA, FALSE)
				->set('SISACUTI', 'SISACUTI+'.$data->LAMA, FALSE)
				->update('cutitahunan');
		}
	}
}
?>