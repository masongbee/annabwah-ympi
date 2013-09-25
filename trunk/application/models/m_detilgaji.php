<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_detilgaji
 * 
 * Table	: detilgaji
 *  
 * @author masongbee
 *
 */
class M_detilgaji extends CI_Model{
	
	function __construct(){
		parent::__construct();
		$username = $this->session->userdata('user_name');
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
	function getAll($bulan, $nik, $start, $page, $limit){
		//$query  = $this->db->where(array('BULAN'=>$bulan, 'NIK'=>$nik))->order_by('NOREVISI', 'ASC')->get('detilgaji')->result();
		$sql = "SELECT t1.BULAN,t1.NIK,t1.NOREVISI,t1.GRADE,t1.KODEJAB,t1.KODESP,t1.MASA_KERJA_BLN,
				t1.MASA_KERJA_HARI,t1.RPUPAHPOKOK,t1.RPTJABATAN,t1.RPTANAK,t1.RPTISTRI,t1.RPTBHS,
				t1.RPTTRANSPORT,t1.RPTSHIFT,t1.RPTPEKERJAAN,t1.RPTQCP,t1.RPTLEMBUR,t1.RPIDISIPLIN,
				t1.RPTHADIR,t1.RPKOMPEN,t1.RPTMAKAN,t1.RPTSIMPATI,t1.RPTHR,t1.RPBONUS,
				t1.RPTKACAMATA,t1.RPPUPAHPOKOK,t1.RPPMAKAN,t1.RPPTRANSPORT,t1.RPPJAMSOSTEK,
				t1.CICILAN1,t1.RPCICILAN1,t1.CICILAN2,t1.RPCICILAN2,t1.RPPOTSP,t1.RPUMSK,
				t2.BULAN, t2.NIK, t2.NOREVISI,
				MAX(IF(t2.NOURUT = 1, t2.NAMAUPAH, NULL)) AS TAMBAHAN1,
				MAX(IF(t2.NOURUT = 1, t2.RPTAMBAHAN, NULL)) AS RPTAMBAHAN1,
				MAX(IF(t2.NOURUT = 2, t2.NAMAUPAH, NULL)) AS TAMBAHAN2,
				MAX(IF(t2.NOURUT = 2, t2.RPTAMBAHAN, NULL)) AS RPTAMBAHAN2,
				MAX(IF(t2.NOURUT = 3, t2.NAMAUPAH, NULL)) AS TAMBAHAN3,
				MAX(IF(t2.NOURUT = 3, t2.RPTAMBAHAN, NULL)) AS RPTAMBAHAN3,
				MAX(IF(t2.NOURUT = 4, t2.NAMAUPAH, NULL)) AS TAMBAHAN4,
				MAX(IF(t2.NOURUT = 4, t2.RPTAMBAHAN, NULL)) AS RPTAMBAHAN4,
				MAX(IF(t2.NOURUT = 5, t2.NAMAUPAH, NULL)) AS TAMBAHAN5,
				MAX(IF(t2.NOURUT = 5, t2.RPTAMBAHAN, NULL)) AS RPTAMBAHAN5,
				MAX(IF(t2.NOURUT > 5, 'Lain', NULL)) AS TAMBAHANLAIN,
				MAX(IF(t2.NOURUT > 5, t3.RPTAMBAHANLAIN, NULL)) AS RPTAMBAHANLAIN,
				MAX(IF(t4.NOURUT = 1, t4.NAMAPOTONGAN, NULL)) AS POTONGAN1,
				MAX(IF(t4.NOURUT = 1, t4.RPPOTONGAN, NULL)) AS RPPOTONGAN1,
				MAX(IF(t4.NOURUT = 2, t4.NAMAPOTONGAN, NULL)) AS POTONGAN2,
				MAX(IF(t4.NOURUT = 2, t4.RPPOTONGAN, NULL)) AS RPPOTONGAN2,
				MAX(IF(t4.NOURUT = 3, t4.NAMAPOTONGAN, NULL)) AS POTONGAN3,
				MAX(IF(t4.NOURUT = 3, t4.RPPOTONGAN, NULL)) AS RPPOTONGAN3,
				MAX(IF(t4.NOURUT = 4, t4.NAMAPOTONGAN, NULL)) AS POTONGAN4,
				MAX(IF(t4.NOURUT = 4, t4.RPPOTONGAN, NULL)) AS RPPOTONGAN4,
				MAX(IF(t4.NOURUT = 5, t4.NAMAPOTONGAN, NULL)) AS POTONGAN5,
				MAX(IF(t4.NOURUT = 5, t4.RPPOTONGAN, NULL)) AS RPPOTONGAN5,
				MAX(IF(t4.NOURUT > 5, 'Lain', NULL)) AS POTONGANLAIN,
				MAX(IF(t4.NOURUT > 5, t5.RPPOTONGANLAIN, NULL)) AS RPPOTONGANLAIN
			FROM detilgaji AS t1
			LEFT JOIN detilgajitambahan AS t2 ON(t2.BULAN = t1.BULAN AND t2.NIK = t1.NIK
				AND t2.NOREVISI = t1.NOREVISI
				AND t2.BULAN = '".$bulan."' AND t2.NIK = '".$nik."')
			LEFT JOIN (
				SELECT BULAN, NIK, NOREVISI, SUM(RPTAMBAHAN) AS RPTAMBAHANLAIN
				FROM detilgajitambahan
				WHERE BULAN = '".$bulan."' AND NIK = '".$nik."' AND NOURUT > 5
				GROUP BY BULAN, NIK, NOREVISI
			) AS t3 ON(t3.BULAN = t2.BULAN AND t3.NIK = t2.NIK AND t3.NOREVISI = t2.NOREVISI)
			LEFT JOIN detilgajipotongan AS t4 ON(t4.BULAN = t1.BULAN AND t4.NIK = t1.NIK
				AND t4.NOREVISI = t1.NOREVISI
				AND t4.BULAN = '".$bulan."' AND t4.NIK = '".$nik."')
			LEFT JOIN (
				SELECT BULAN, NIK, NOREVISI, SUM(RPPOTONGAN) AS RPPOTONGANLAIN
				FROM detilgajipotongan
				WHERE BULAN = '".$bulan."' AND NIK = '".$nik."' AND NOURUT > 5
				GROUP BY BULAN, NIK, NOREVISI
			) AS t5 ON(t5.BULAN = t4.BULAN AND t5.NIK = t4.NIK AND t5.NOREVISI = t4.NOREVISI)
			WHERE t1.BULAN = '".$bulan."' AND t1.NIK = '".$nik."'
			GROUP BY t1.BULAN, t1.NIK, t1.NOREVISI";
		$query = $this->db->query($sql)->result();
		$sql_total = "SELECT COUNT(*) AS total
			FROM detilgaji AS t1
			LEFT JOIN detilgajitambahan AS t2 ON(t2.BULAN = t1.BULAN AND t2.NIK = t1.NIK
				AND t2.NOREVISI = t1.NOREVISI
				AND t2.BULAN = '".$bulan."' AND t2.NIK = '".$nik."')
			LEFT JOIN (
				SELECT BULAN, NIK, NOREVISI, SUM(RPTAMBAHAN) AS RPTAMBAHANLAIN
				FROM detilgajitambahan
				WHERE BULAN = '".$bulan."' AND NIK = '".$nik."' AND NOURUT > 5
				GROUP BY BULAN, NIK, NOREVISI
			) AS t3 ON(t3.BULAN = t2.BULAN AND t3.NIK = t2.NIK AND t3.NOREVISI = t2.NOREVISI)
			LEFT JOIN detilgajipotongan AS t4 ON(t4.BULAN = t1.BULAN AND t4.NIK = t1.NIK
				AND t4.NOREVISI = t1.NOREVISI
				AND t4.BULAN = '".$bulan."' AND t4.NIK = '".$nik."')
			LEFT JOIN (
				SELECT BULAN, NIK, NOREVISI, SUM(RPPOTONGAN) AS RPPOTONGANLAIN
				FROM detilgajipotongan
				WHERE BULAN = '".$bulan."' AND NIK = '".$nik."' AND NOURUT > 5
				GROUP BY BULAN, NIK, NOREVISI
			) AS t5 ON(t5.BULAN = t4.BULAN AND t5.NIK = t4.NIK AND t5.NOREVISI = t4.NOREVISI)
			WHERE t1.BULAN = '".$bulan."' AND t1.NIK = '".$nik."'
			GROUP BY t1.BULAN, t1.NIK, t1.NOREVISI";
		$total  = $this->db->query($sql_total)->row()->total;
		
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
		
		$pkey = array('BULAN'=>$data->BULAN,'NIK'=>$data->NIK,'NOREVISI'=>$data->NOREVISI);
		
		if($this->db->get_where('detilgaji', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('RPUPAHPOKOK'=>$data->RPUPAHPOKOK,'RPTANAK'=>$data->RPTANAK,'RPTBHS'=>$data->RPTBHS,'RPTHR'=>$data->RPTHR,'RPTISTRI'=>$data->RPTISTRI,'RPTJABATAN'=>$data->RPTJABATAN,'RPTPEKERJAAN'=>$data->RPTPEKERJAAN,'RPTSHIFT'=>$data->RPTSHIFT,'RPTTRANSPORT'=>$data->RPTTRANSPORT,'RPBONUS'=>$data->RPBONUS,'RPIDISIPLIN'=>$data->RPIDISIPLIN,'RPTLEMBUR'=>$data->RPTLEMBUR,'RPTKACAMATA'=>$data->RPTKACAMATA,'RPTSIMPATI'=>$data->RPTSIMPATI,'RPTMAKAN'=>$data->RPTMAKAN,'RPPSKORSING'=>$data->RPPSKORSING,'RPPSAKITCUTI'=>$data->RPPSAKITCUTI,'RPPJAMSOSTEK'=>$data->RPPJAMSOSTEK,'RPPOTONGAN'=>$data->RPPOTONGAN,'RPTAMBAHAN'=>$data->RPTAMBAHAN);
			 
			$this->db->where($pkey)->update('detilgaji', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('BULAN'=>$data->BULAN,'NIK'=>$data->NIK,'NOREVISI'=>$data->NOREVISI,'RPUPAHPOKOK'=>$data->RPUPAHPOKOK,'RPTANAK'=>$data->RPTANAK,'RPTBHS'=>$data->RPTBHS,'RPTHR'=>$data->RPTHR,'RPTISTRI'=>$data->RPTISTRI,'RPTJABATAN'=>$data->RPTJABATAN,'RPTPEKERJAAN'=>$data->RPTPEKERJAAN,'RPTSHIFT'=>$data->RPTSHIFT,'RPTTRANSPORT'=>$data->RPTTRANSPORT,'RPBONUS'=>$data->RPBONUS,'RPIDISIPLIN'=>$data->RPIDISIPLIN,'RPTLEMBUR'=>$data->RPTLEMBUR,'RPTKACAMATA'=>$data->RPTKACAMATA,'RPTSIMPATI'=>$data->RPTSIMPATI,'RPTMAKAN'=>$data->RPTMAKAN,'RPPSKORSING'=>$data->RPPSKORSING,'RPPSAKITCUTI'=>$data->RPPSAKITCUTI,'RPPJAMSOSTEK'=>$data->RPPJAMSOSTEK,'RPPOTONGAN'=>$data->RPPOTONGAN,'RPTAMBAHAN'=>$data->RPTAMBAHAN);
			 
			$this->db->insert('detilgaji', $arrdatac);
			$last   = $this->db->where($pkey)->get('detilgaji')->row();
			
		}
		
		$total  = $this->db->get('detilgaji')->num_rows();
		
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
		$pkey = array('BULAN'=>$data->BULAN,'NIK'=>$data->NIK,'NOREVISI'=>$data->NOREVISI);
		
		$this->db->where($pkey)->delete('detilgaji');
		
		$total  = $this->db->get('detilgaji')->num_rows();
		$last = $this->db->get('detilgaji')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						"total"     => $total,
						"data"      => $last
		);				
		return $json;
	}
	
}
?>