<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_lapgaji
 * 
 * Table	: lapgaji
 *  
 * @author masongbee
 *
 */
class M_lapgaji extends CI_Model{

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
	function getAll($start, $page, $limit, $datafilter){
		/**
		 * get data by $datafilter
		 * Jika TIDAK ADA ==> eksekusi gen_lapgaji()
		 * Jika ADA ==> Load Data
		 */
		$this->firephp->log($datafilter);
		foreach ($datafilter->grade as $row) {
			$this->firephp->log($row);
		}

		$sql = "SELECT @a:=@a+1 AS SERIAL_NUMBER, t1.NIK, t1.NAMAKAR, t1.TGLMASUK, t1.STATUS, t1.GRADE, t1.STATTUNKEL,
				t1.RPUPAHPOKOK AS RPUPAHPOKOK, t1.RPTLEMBUR AS RPTLEMBUR, 
				(t1.RPTJABATAN + t1.RPTISTRI + t1.RPTANAK + t1.RPTBHS + t1.RPUMSK) AS RPTUNJTETAP,
				(t1.RPTTRANSPORT + t1.RPTSHIFT + t1.RPTPEKERJAAN + t1.RPTQCP) AS RPTUNJTDKTTP,
				(t1.RPIDISIPLIN + t1.RPTHADIR + t1.RPBONUS + t1.RPKOMPEN + t1.RPTMAKAN + t1.RPTSIMPATI + t1.RPTKACAMATA) AS RPNONUPAH,
				t1.RPTHR AS RPTHR
			FROM (
				SELECT detilgaji.*, karyawan.NAMAKAR, karyawan.TGLMASUK, karyawan.STATUS, karyawan.STATTUNKEL
				FROM detilgaji
				JOIN karyawan ON(detilgaji.BULAN = '".$datafilter->bulangaji."' AND karyawan.NIK = detilgaji.NIK)
				WHERE detilgaji.GRADE IN (".implode(',', $datafilter->grade).")
			) AS t1,
			(SELECT @a:= 0) AS a";
		$query = $this->db->query($sql);
		$result= $query->result();
		$total = $query->num_rows();
		
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
}
?>