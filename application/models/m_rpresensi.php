<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_rpresensi
 * 
 * Table	: rpresensi
 *  
 * @author masongbee
 *
 */
class M_rpresensi extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function gen_rpresensi($tglmulai, $tglsampai, $kdkel, $kdunit){
		/*
		/* delete isi table */
		$this->db->truncate('rpresensi');
		
		/* isi nik dan nama 
		   rpresensi_bulan = sesuai pilihan
		   kodekel = sesuai pilihan (atau all)
		   kodeunit = sesuai pilihan (atau all) 
		*/
		$bulan = date('Ym', strtotime($tglmulai));
		
		$filterkdkel= '';
		if($kdkel <> ''){
		   $filterkdkel= "KODEKEL = '".$kdkel."' and ";
		}
		
		$filterkdunit= '';
		if($kdunit <> ''){
		   $filterkdunit= "KODEUNIT = '".$kdunit."' and ";
		}
		
		$sqli = "INSERT INTO rpresensi (RPRESENSI_NIK, RPRESENSI_NAMA, RPRESENSI_BULAN, RPRESENSI_KODEKEL, RPRESENSI_KODEUNIT)
			SELECT NIK, NAMAKAR, $bulan, KODEKEL, KODEUNIT
			FROM karyawan
			WHERE ".$filterkdkel."".$filterkdunit."
				(STATUS='T' OR STATUS='K' OR STATUS='C')";
		$this->db->query($sqli);
		
		/* ambil presensi */
		for ($tgl = (ltrim(date('d', strtotime($tglmulai)) ,'0') - 1); $tgl<ltrim(date('d', strtotime($tglsampai)) ,'0'); $tgl++) {
			$col_presensi = "d".($tgl+1);
			$sqlu_presensi = "UPDATE rpresensi AS t1
				JOIN (
					SELECT NIK, SHIFTKE
					FROM presensi
					WHERE TANGGAL = DATE_ADD('".$tglmulai."', INTERVAL ".$tgl." DAY)
				) AS t2 ON(t1.RPRESENSI_NIK = t2.NIK)
				SET t1.".$col_presensi." = t2.SHIFTKE";
			$this->db->query($sqlu_presensi);
		}
		
		/* ambil cuti */
		for ($tgl = (ltrim(date('d', strtotime($tglmulai)) ,'0') - 1); $tgl<ltrim(date('d', strtotime($tglsampai)) ,'0'); $tgl++) {
			$col_cuti = "d".($tgl+1);
			$sqlu_cuti = "UPDATE rpresensi AS t1
				JOIN (
					SELECT NIK, JENISABSEN
					FROM rinciancuti
					WHERE TGLMULAI <= DATE_ADD('".$tglmulai."', INTERVAL ".$tgl." DAY)
						AND TGLSAMPAI >= DATE_ADD('".$tglmulai."', INTERVAL ".$tgl." DAY)
						AND STATUSCUTI='T'
				) AS t2 ON(t1.RPRESENSI_NIK = t2.NIK)
				SET t1.".$col_cuti." = t2.JENISABSEN";
			$this->db->query($sqlu_cuti);
		}
		
		/* ambil ijin */
		for ($tgl = (ltrim(date('d', strtotime($tglmulai)) ,'0') - 1); $tgl<ltrim(date('d', strtotime($tglsampai)) ,'0'); $tgl++) {
			$col_ijin = "d".($tgl+1);
			$sqlu_ijin = "UPDATE rpresensi AS t1
				JOIN (
					SELECT NIK, JENISABSEN
					FROM permohonanijin
					WHERE TANGGAL = DATE_ADD('".$tglmulai."', INTERVAL ".$tgl." DAY)
						AND JENISABSEN != 'IP'
						AND STATUSIJIN='T'
				) AS t2 ON(t1.RPRESENSI_NIK = t2.NIK)
				SET t1.".$col_ijin." = t2.JENISABSEN";
			$this->db->query($sqlu_ijin);
		}
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
		/*$query  = $this->db->select("RPRESENSI_ID,RPRESENSI_NIK,RPRESENSI_NAMA
				,STR_TO_DATE(CONCAT(RPRESENSI_BULAN,'01'),'%Y%m%d') AS RPRESENSI_BULAN
				,NAMAKEL,NAMAUNIT
				,d1,d2,d3,d4,d5,d6,d7,d8,d9,d10,d11,d12,d13,d14,d15,d16,d17,d18,d19,d20,d21,d22,d23,d24,d25,d26,d27,d28,d29,d30,d31")
			->limit($limit, $start)
			->from('rpresensi')
			->join('kelompok', 'kelompok.KODEKEL = rpresensi.RPRESENSI_KODEKEL', 'left')
			->join('unitkerja', 'unitkerja.KODEUNIT = rpresensi.RPRESENSI_KODEUNIT', 'left')
			->order_by('RPRESENSI_ID', 'ASC')->get('rpresensi')->result();
		$total  = $this->db->get('rpresensi')->num_rows();
		
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}*/
		$query = "SELECT RPRESENSI_ID,RPRESENSI_NIK,RPRESENSI_NAMA
				,STR_TO_DATE(CONCAT(RPRESENSI_BULAN,'01'),'%Y%m%d') AS RPRESENSI_BULAN
				,NAMAKEL,NAMAUNIT
				,d1,d2,d3,d4,d5,d6,d7,d8,d9,d10,d11,d12,d13,d14,d15,d16,d17,d18,d19,d20
				,d21,d22,d23,d24,d25,d26,d27,d28,d29,d30,d31
			FROM rpresensi
			JOIN kelompok ON(kelompok.KODEKEL = rpresensi.RPRESENSI_KODEKEL)
			JOIN unitkerja ON(unitkerja.KODEUNIT = rpresensi.RPRESENSI_KODEUNIT)";
		/* filter */
		if(sizeof($filter) > 0){
			/*sorting by field of filter*/
			$tmp = array(); 
			foreach($filter as $row) 
				$tmp[] = $row->field;
			array_multisort($tmp, $filter);
			
			$filter_arr = array();
			$field_tmp = "";
			foreach($filter as $filter_row){
				if($field_tmp == $filter_row->field){
					/* Satu Field memiliki lebih dari satu kondisi */
					$query = substr($query, 0, -1);
					$query .= " OR ";
					if($filter_row->field == "NAMAKEL"){
						$query .= "lower(kelompok.".$filter_row->field.") = '".strtolower($filter_row->value)."')";
					}elseif($filter_row->field == "NAMAUNIT"){
						$query .= "lower(unitkerja.".$filter_row->field.") = '".strtolower($filter_row->value)."')";
					}else{
						$query .= "lower(".$filter_row->field.") LIKE '%".strtolower($filter_row->value)."%')";
					}
				}else{
					$field_tmp = $filter_row->field;
					
					$query .= preg_match("/WHERE/i",$query)? " AND ":" WHERE ";
					if($filter_row->field == "NAMAKEL"){
						$query .= "(lower(kelompok.".$filter_row->field.") = '".strtolower($filter_row->value)."')";
					}elseif($filter_row->field == "NAMAUNIT"){
						$query .= "(lower(unitkerja.".$filter_row->field.") = '".strtolower($filter_row->value)."')";
					}else{
						$query .= "(lower(".$filter_row->field.") LIKE '%".strtolower($filter_row->value)."%')";
					}
					
				}
			}
			
		}
		$query .= " ORDER BY RPRESENSI_ID";
		$query_total = $query;
		$limit = " LIMIT ".$start.",".$limit;
		$query = $query.$limit;
		
		$result = $this->db->query($query)->result();
		$rstotal  = $this->db->query($query_total)->result();
		$total = sizeof($rstotal);
		
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
		
		$pkey = array('RPRESENSI_ID'=>$data->RPRESENSI_ID);
		
		if($this->db->get_where('rpresensi', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('RPRESENSI_NIK'=>$data->RPRESENSI_NIK,'RPRESENSI_NAMA'=>$data->RPRESENSI_NAMA,'RPRESENSI_BULAN'=>$data->RPRESENSI_BULAN,'d1'=>$data->d1,'d2'=>$data->d2,'d3'=>$data->d3,'d4'=>$data->d4,'d5'=>$data->d5,'d6'=>$data->d6,'d7'=>$data->d7,'d8'=>$data->d8,'d9'=>$data->d9,'d10'=>$data->d10,'d11'=>$data->d11,'d12'=>$data->d12,'d13'=>$data->d13,'d14'=>$data->d14,'d15'=>$data->d15,'d16'=>$data->d16,'d17'=>$data->d17,'d18'=>$data->d18,'d19'=>$data->d19,'d20'=>$data->d20,'d21'=>$data->d21,'d22'=>$data->d22,'d23'=>$data->d23,'d24'=>$data->d24,'d25'=>$data->d25,'d26'=>$data->d26,'d27'=>$data->d27,'d28'=>$data->d28,'d29'=>$data->d29,'d30'=>$data->d30,'d31'=>$data->d31);
			 
			$this->db->where($pkey)->update('rpresensi', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('RPRESENSI_ID'=>$data->RPRESENSI_ID,'RPRESENSI_NIK'=>$data->RPRESENSI_NIK,'RPRESENSI_NAMA'=>$data->RPRESENSI_NAMA,'RPRESENSI_BULAN'=>$data->RPRESENSI_BULAN,'d1'=>$data->d1,'d2'=>$data->d2,'d3'=>$data->d3,'d4'=>$data->d4,'d5'=>$data->d5,'d6'=>$data->d6,'d7'=>$data->d7,'d8'=>$data->d8,'d9'=>$data->d9,'d10'=>$data->d10,'d11'=>$data->d11,'d12'=>$data->d12,'d13'=>$data->d13,'d14'=>$data->d14,'d15'=>$data->d15,'d16'=>$data->d16,'d17'=>$data->d17,'d18'=>$data->d18,'d19'=>$data->d19,'d20'=>$data->d20,'d21'=>$data->d21,'d22'=>$data->d22,'d23'=>$data->d23,'d24'=>$data->d24,'d25'=>$data->d25,'d26'=>$data->d26,'d27'=>$data->d27,'d28'=>$data->d28,'d29'=>$data->d29,'d30'=>$data->d30,'d31'=>$data->d31);
			 
			$this->db->insert('rpresensi', $arrdatac);
			$last   = $this->db->where($pkey)->get('rpresensi')->row();
			
		}
		
		$total  = $this->db->get('rpresensi')->num_rows();
		
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
		$pkey = array('RPRESENSI_ID'=>$data->RPRESENSI_ID);
		
		$this->db->where($pkey)->delete('rpresensi');
		
		$total  = $this->db->get('rpresensi')->num_rows();
		$last = $this->db->get('rpresensi')->result();
		
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