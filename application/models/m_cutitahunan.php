<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_cutitahunan
 * 
 * Table	: cutitahunan
 *  
 * @author masongbee
 *
 */
class M_cutitahunan extends CI_Model{

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
		//$query  = $this->db->limit($limit, $start)->order_by('TANGGAL', 'ASC')->get('cutitahunan')->result();
		$tglmulai = date('Y-m-d', strtotime('-1 years'));
		$tglsampai = date('Y-m-d');
		
		$query = "SELECT cutitahunan.NIK
				,cutitahunan.TAHUN
				,cutitahunan.TANGGAL
				,cutitahunan.JENISCUTI
				,cutitahunan.JMLCUTI
				,cutitahunan.SISACUTI
				,cutitahunan.DIKOMPENSASI
				,cutitahunan.USERNAME
				,CONCAT('[', cutitahunan.NIK,'] - ', karyawan.NAMAKAR) AS NIKDISPLAY
			FROM cutitahunan
			LEFT JOIN karyawan ON(karyawan.NIK = cutitahunan.NIK)
			WHERE TANGGAL >= STR_TO_DATE('".$tglmulai."','%Y-%m-%d')
				AND TANGGAL <= STR_TO_DATE('".$tglsampai."','%Y-%m-%d')";
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
					if($filter_row->type == 'date'){
						$query .= "CAST(DATE_FORMAT(STR_TO_DATE(".$filter_row->field.",'%Y-%m-%d'),'%Y%m%d') AS UNSIGNED)".($filter_row->comparison == 'lt' ? " < " : ($filter_row->comparison == 'gt' ? " > " : " = "))."CAST(DATE_FORMAT(STR_TO_DATE('".$filter_row->value."','%m/%d/%Y'),'%Y%m%d') AS UNSIGNED))";
					}elseif($filter_row->type == 'numeric'){
						$query .= $filter_row->field.($filter_row->comparison == 'lt' ? " < " : ($filter_row->comparison == 'gt' ? " > " : " = ")).$filter_row->value.")";
					}else{
						$query .= $filter_row->field." LIKE '%".$filter_row->value."%')";
					}
				}else{
					$field_tmp = $filter_row->field;
					
					$query .= preg_match("/WHERE/i",$query)? " AND ":" WHERE ";
					if($filter_row->type == 'date'){
						$query .= "(CAST(DATE_FORMAT(STR_TO_DATE(".$filter_row->field.",'%Y-%m-%d'),'%Y%m%d') AS UNSIGNED)".($filter_row->comparison == 'lt' ? " < " : ($filter_row->comparison == 'gt' ? " > " : " = "))."CAST(DATE_FORMAT(STR_TO_DATE('".$filter_row->value."','%m/%d/%Y'),'%Y%m%d') AS UNSIGNED))";
					}elseif($filter_row->type == 'numeric'){
						$query .= "(".$filter_row->field.($filter_row->comparison == 'lt' ? " < " : ($filter_row->comparison == 'gt' ? " > " : " = ")).$filter_row->value.")";
					}else{
						$query .= "(".$filter_row->field." LIKE '%".$filter_row->value."%')";
					}
					
				}
			}
			
		}
		$query .= " ORDER BY NIK, TAHUN, JENISCUTI, TANGGAL
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('cutitahunan')->num_rows();
		
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
		
		$pkey = array('NIK'=>$data->NIK,'TAHUN'=>$data->TAHUN,'TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)));
		
		if($this->db->get_where('cutitahunan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('JENISCUTI'=>$data->JENISCUTI,'JMLCUTI'=>$data->JMLCUTI,'SISACUTI'=>$data->SISACUTI,'DIKOMPENSASI'=>$data->DIKOMPENSASI,'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('cutitahunan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('NIK'=>$data->NIK,'TAHUN'=>$data->TAHUN,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'JENISCUTI'=>$data->JENISCUTI,'JMLCUTI'=>$data->JMLCUTI,'SISACUTI'=>$data->SISACUTI,'DIKOMPENSASI'=>$data->DIKOMPENSASI,'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('cutitahunan', $arrdatac);
			$last   = $this->db->where($pkey)->get('cutitahunan')->row();
			
		}
		
		$total  = $this->db->get('cutitahunan')->num_rows();
		
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
		$pkey = array('NIK'=>$data->NIK,'TAHUN'=>$data->TAHUN,'TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)));
		
		$this->db->where($pkey)->delete('cutitahunan');
		
		$total  = $this->db->get('cutitahunan')->num_rows();
		$last = $this->db->get('cutitahunan')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						"total"     => $total,
						"data"      => $last
		);				
		return $json;
	}
	
	function generate(){
		$datenow = date('Y-m-d');
		$yearnow = date('Y');
		$monthnow = date('m');
		$blnthn_now = date('Ym');
		$blnthn_prev = date('Ym', strtotime('-1 years'));
		
		$sql = "INSERT INTO cutitahunan (NIK, TAHUN, TANGGAL, JENISCUTI, JMLCUTI, SISACUTI, USERNAME)
			SELECT karyawan.NIK, CAST('".$yearnow."' AS UNSIGNED), STR_TO_DATE('".$datenow."','%Y-%m-%d'),
				'0', 0, 12, '".$this->session->userdata('user_name')."'
			FROM karyawan
			LEFT JOIN cutitahunan ON(cutitahunan.NIK = karyawan.NIK
				AND cutitahunan.TAHUN = CAST('".$yearnow."' AS UNSIGNED)
				AND JENISCUTI = '0')
			WHERE CAST(DATE_FORMAT(karyawan.TGLMASUK,'%Y%m') AS UNSIGNED) <= CAST('".$blnthn_prev."' AS UNSIGNED)
				AND CAST(DATE_FORMAT(karyawan.TGLMASUK,'%m') AS UNSIGNED) = CAST('".$monthnow."' AS UNSIGNED)
				AND cutitahunan.NIK IS NULL
				AND cutitahunan.TAHUN IS NULL
				AND cutitahunan.TANGGAL IS NULL";
		$this->db->query($sql);
		$total = $this->db->affected_rows();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Cuti Tahunan sejumlah '.$total.' data berhasil digenerate.',
						"total"     => $total
		);				
		return $json;
	}
	
	function hangusall(){
		$yearnow = date('Y');
		$monthnow = date('m');
		
		$sql = "UPDATE cutitahunan AS t1
			SET t1.DIKOMPENSASI = 'H'
			WHERE CAST(t1.TAHUN AS UNSIGNED) = (CAST('".$yearnow."' AS UNSIGNED) - 1)
				AND CAST(DATE_FORMAT(t1.TANGGAL, '%m') AS UNSIGNED) = CAST('".$monthnow."' AS UNSIGNED)
				AND t1.JENISCUTI = '0'
				AND t1.SISACUTI > 0
				AND (t1.DIKOMPENSASI IS NULL OR t1.DIKOMPENSASI = '')";
		$this->db->query($sql);
		$total = $this->db->affected_rows();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Cuti Tahunan sejumlah '.$total.', telah dihanguskan untuk dikompensasi.',
						"total"     => $total
		);				
		return $json;
	}
	
	function kompensasiall(){
		$datenow = date('Y-m-d');
		$yearnow = date('Y');
		$monthnow = date('m');
		$thnblnnow = date('Ym');
		
		$sql = "INSERT INTO kompensasicuti (NIK, TAHUN, TANGGAL, SISACUTI, RPKOMPEN, BULAN)
			SELECT t1.NIK, t1.TAHUN, t1.TANGGAL, t1.TOTALSISACUTI, t1.RPKOMPEN, t1.BULAN
			FROM (
				SELECT cutitahunan.NIK, cutitahunan.TAHUN, STR_TO_DATE('".$datenow."', '%Y-%m-%d') AS TANGGAL,
					SUM(cutitahunan.SISACUTI) AS TOTALSISACUTI,
					((SUM(cutitahunan.SISACUTI)) * 8/173 * (gajibulanan.RPUPAHPOKOK + gajibulanan.RPTUNJTETAP)) AS RPKOMPEN,
					'".$thnblnnow."' AS BULAN
				FROM cutitahunan
				JOIN karyawan ON(karyawan.NIK = cutitahunan.NIK
					AND CAST(cutitahunan.TAHUN AS UNSIGNED) = (CAST('".$yearnow."' AS UNSIGNED) - 1)
					AND CAST(DATE_FORMAT(cutitahunan.TANGGAL, '%m') AS UNSIGNED) = CAST('".$monthnow."' AS UNSIGNED)
					AND (cutitahunan.DIKOMPENSASI IS NULL OR cutitahunan.DIKOMPENSASI = '')
					AND cutitahunan.SISACUTI > 0)
				JOIN jabatan ON(jabatan.IDJAB = karyawan.IDJAB
					AND jabatan.KOMPENCUTI = 'Y')
				JOIN gajibulanan ON(gajibulanan.NIK = cutitahunan.NIK
					AND CAST(gajibulanan.BULAN AS UNSIGNED) = (CAST('".$thnblnnow."' AS UNSIGNED) - 1))
				GROUP BY cutitahunan.NIK, cutitahunan.TAHUN
			) AS t1
			LEFT JOIN kompensasicuti AS t2 ON(t2.NIK = t1.NIK AND t2.TAHUN = t1.TAHUN)
			WHERE t2.NIK IS NULL AND t2.TAHUN IS NULL";
		$this->db->query($sql);
		
		$sql = "UPDATE cutitahunan
			JOIN karyawan ON(karyawan.NIK = cutitahunan.NIK
				AND CAST(cutitahunan.TAHUN AS UNSIGNED) = (CAST('".$yearnow."' AS UNSIGNED) - 1)
				AND CAST(DATE_FORMAT(cutitahunan.TANGGAL, '%m') AS UNSIGNED) = CAST('".$monthnow."' AS UNSIGNED)
				AND (cutitahunan.DIKOMPENSASI IS NULL OR cutitahunan.DIKOMPENSASI = '')
				AND cutitahunan.SISACUTI > 0)
			JOIN jabatan ON(jabatan.IDJAB = karyawan.IDJAB
				AND jabatan.KOMPENCUTI = 'Y')
			JOIN gajibulanan ON(gajibulanan.NIK = cutitahunan.NIK
				AND CAST(gajibulanan.BULAN AS UNSIGNED) = (CAST('".$thnblnnow."' AS UNSIGNED) - 1))
			SET cutitahunan.DIKOMPENSASI = 'Y'";
		$this->db->query($sql);
		
		$total = $this->db->affected_rows();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Cuti Tahunan sejumlah '.$total.', telah dikompensasi.',
						"total"     => $total
		);				
		return $json;
	}
	
	function hangus($data){
		$yearnow = date('Y');
		$monthnow = date('m');
		
		$i = 0;
		foreach($data as $row){
			$sql = "UPDATE cutitahunan AS t1
				SET t1.DIKOMPENSASI = 'H'
				WHERE t1.NIK = '".$row->NIK."'
					AND CAST(t1.TAHUN AS UNSIGNED) = (CAST('".$yearnow."' AS UNSIGNED) - 1)
					AND CAST(t1.TANGGAL AS UNSIGNED) = CAST('".date('Ymd', strtotime($row->TANGGAL))."' AS UNSIGNED)
					AND t1.JENISCUTI = '".$row->JENISCUTI."'
					AND t1.SISACUTI > 0
					AND (t1.DIKOMPENSASI IS NULL OR t1.DIKOMPENSASI = '')";
			$this->db->query($sql);
			$i += $this->db->affected_rows();
		}
		
		$total = sizeof($data);
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Cuti Tahunan sejumlah '.$i.' dari '.$total.' data terpilih, telah dihanguskan.',
						"total"     => $i
		);				
		return $json;
	}
	
	function kompensasi($data){
		$datenow = date('Y-m-d');
		$yearnow = date('Y');
		$monthnow = date('m');
		$thnblnnow = date('Ym');
		
		$i = 0;
		foreach($data as $row){
			$sql = "INSERT INTO kompensasicuti (NIK, TAHUN, TANGGAL, SISACUTI, RPKOMPEN, BULAN)
				SELECT t1.NIK, t1.TAHUN, t1.TANGGAL, t1.TOTALSISACUTI, t1.RPKOMPEN, t1.BULAN
				FROM (
					SELECT cutitahunan.NIK, cutitahunan.TAHUN, STR_TO_DATE('".$datenow."', '%Y-%m-%d') AS TANGGAL,
						SUM(cutitahunan.SISACUTI) AS TOTALSISACUTI,
						((SUM(cutitahunan.SISACUTI)) * 8/173 * (gajibulanan.RPUPAHPOKOK + gajibulanan.RPTUNJTETAP)) AS RPKOMPEN,
						'".$thnblnnow."' AS BULAN
					FROM cutitahunan
					JOIN karyawan ON(cutitahunan.NIK = '".$row->NIK."'
						AND karyawan.NIK = cutitahunan.NIK
						AND CAST(cutitahunan.TAHUN AS UNSIGNED) = (CAST('".$yearnow."' AS UNSIGNED) - 1)
						AND CAST(cutitahunan.TANGGAL AS UNSIGNED) = CAST('".date('Ymd', strtotime($row->TANGGAL))."' AS UNSIGNED)
						AND (cutitahunan.DIKOMPENSASI IS NULL OR cutitahunan.DIKOMPENSASI = '')
						AND cutitahunan.SISACUTI > 0)
					JOIN jabatan ON(jabatan.IDJAB = karyawan.IDJAB
						AND jabatan.KOMPENCUTI = 'Y')
					JOIN gajibulanan ON(gajibulanan.NIK = cutitahunan.NIK
						AND CAST(gajibulanan.BULAN AS UNSIGNED) = (CAST('".$thnblnnow."' AS UNSIGNED) - 1))
					GROUP BY cutitahunan.NIK, cutitahunan.TAHUN
				) AS t1
				LEFT JOIN kompensasicuti AS t2 ON(t2.NIK = t1.NIK AND t2.TAHUN = t1.TAHUN)
				WHERE t2.NIK IS NULL AND t2.TAHUN IS NULL";
			$this->db->query($sql);
			
			$sql = "UPDATE cutitahunan
				JOIN karyawan ON(cutitahunan.NIK = '".$row->NIK."'
					AND karyawan.NIK = cutitahunan.NIK
					AND CAST(cutitahunan.TAHUN AS UNSIGNED) = (CAST('".$yearnow."' AS UNSIGNED) - 1)
					AND CAST(cutitahunan.TANGGAL AS UNSIGNED) = CAST('".date('Ymd', strtotime($row->TANGGAL))."' AS UNSIGNED)
					AND (cutitahunan.DIKOMPENSASI IS NULL OR cutitahunan.DIKOMPENSASI = '')
					AND cutitahunan.SISACUTI > 0)
				JOIN jabatan ON(jabatan.IDJAB = karyawan.IDJAB
					AND jabatan.KOMPENCUTI = 'Y')
				JOIN gajibulanan ON(gajibulanan.NIK = cutitahunan.NIK
					AND CAST(gajibulanan.BULAN AS UNSIGNED) = (CAST('".$thnblnnow."' AS UNSIGNED) - 1))
				SET cutitahunan.DIKOMPENSASI = 'Y'";
			$this->db->query($sql);
			
			$i += $this->db->affected_rows();
		}
		
		$total = sizeof($data);
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Cuti Tahunan sejumlah '.$i.' dari '.$total.' data, telah dikompensasi.',
						"total"     => $i
		);				
		return $json;
	}
}
?>