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
		/*$this->firephp->log($datafilter);
		foreach ($datafilter->grade as $row) {
			$this->firephp->log($row);
		}*/

		$sql = "SELECT @a:=@a+1 AS SERIAL_NUMBER, t1.NIK, t1.NAMAKAR, t1.SINGKATAN, t1.TGLMASUK, t1.STATUS, t1.NAMALEVEL, 
				t1.GRADE, t1.STATTUNKEL,
				t1.RPUPAHPOKOK AS RPUPAHPOKOK, t1.RPTLEMBUR AS RPTLEMBUR, 
				(t1.RPTJABATAN + t1.RPTISTRI + t1.RPTANAK + t1.RPTBHS + t1.RPUMSK) AS RPTUNJTETAP,
				(t1.RPTTRANSPORT + t1.RPTSHIFT + t1.RPTPEKERJAAN + t1.RPTQCP) AS RPTUNJTDKTTP,
				(t1.RPIDISIPLIN + t1.RPTHADIR + t1.RPBONUS + t1.RPKOMPEN + t1.RPTMAKAN + t1.RPTSIMPATI + t1.RPTKACAMATA) AS RPNONUPAH,
				t1.RPTHR AS RPTHR,
				(t1.RPUPAHPOKOK + t1.RPTLEMBUR + t1.RPTJABATAN + t1.RPTISTRI + t1.RPTANAK + t1.RPTBHS + t1.RPUMSK
					+ t1.RPTTRANSPORT + t1.RPTSHIFT + t1.RPTPEKERJAAN + t1.RPTQCP
					+ t1.RPIDISIPLIN + t1.RPTHADIR + t1.RPBONUS + t1.RPKOMPEN + t1.RPTMAKAN + t1.RPTSIMPATI + t1.RPTKACAMATA
					+ t1.RPTHR) AS TOTALPENDAPATAN,
				IFNULL(t1.RPPUPAHPOKOK, 0) AS RPPUPAHPOKOK,
				(IFNULL(t1.RPPJAMSOSTEK, 0) + IFNULL(t1.RPPOTSP, 0)) AS IURAN,
				IFNULL(t1.RPPOTONGAN, 0) AS PINJAMAN,
				(IFNULL(t1.RPPUPAHPOKOK, 0) + (IFNULL(t1.RPPJAMSOSTEK, 0) + IFNULL(t1.RPPOTSP, 0)) + IFNULL(t1.RPPOTONGAN, 0)) AS TOTALPOTONGAN,
				((t1.RPUPAHPOKOK + t1.RPTLEMBUR + t1.RPTJABATAN + t1.RPTISTRI + t1.RPTANAK + t1.RPTBHS + t1.RPUMSK
					+ t1.RPTTRANSPORT + t1.RPTSHIFT + t1.RPTPEKERJAAN + t1.RPTQCP
					+ t1.RPIDISIPLIN + t1.RPTHADIR + t1.RPBONUS + t1.RPKOMPEN + t1.RPTMAKAN + t1.RPTSIMPATI + t1.RPTKACAMATA
					+ t1.RPTHR)
						- (IFNULL(t1.RPPUPAHPOKOK, 0) + (IFNULL(t1.RPPJAMSOSTEK, 0) + IFNULL(t1.RPPOTSP, 0)) + IFNULL(t1.RPPOTONGAN, 0))) AS PENDAPATANBERSIH
			FROM (
				SELECT detilgaji.*, karyawan.NAMAKAR, unitkerja.SINGKATAN, karyawan.TGLMASUK, karyawan.STATUS, leveljabatan.NAMALEVEL, 
					karyawan.STATTUNKEL, v_detilgajipotongan.RPPOTONGAN
				FROM detilgaji
				JOIN karyawan ON(detilgaji.BULAN = '".$datafilter->bulangaji."' AND karyawan.NIK = detilgaji.NIK)
				LEFT JOIN leveljabatan ON(leveljabatan.KODEJAB = karyawan.KODEJAB)
				LEFT JOIN unitkerja ON(unitkerja.KODEUNIT = karyawan.KODEUNIT)
				LEFT JOIN (
					SELECT NIK, SUM(RPPOTONGAN) AS RPPOTONGAN
					FROM detilgajipotongan 
					WHERE BULAN = '".$datafilter->bulangaji."'
					GROUP BY NIK
				) AS v_detilgajipotongan ON(v_detilgajipotongan.NIK = detilgaji.NIK)
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