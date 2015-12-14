<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_lapkarlembur
 * 
 * Table	: lapkarlembur
 *  
 * @author masongbee
 *
 */
class M_lapkarlembur extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function genLemburPerBulan($bulan){
		$this->db->truncate('calendar');
		$sql_cal = "INSERT INTO calendar
			SELECT date
			FROM (
					SELECT curdate() - interval (a.a + (10 * b.a) + (100 * c.a) + (1000 * d.a) + (10000 * e.a)) day as date
					FROM (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
					CROSS JOIN (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
					CROSS JOIN (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
					CROSS JOIN (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as d
					CROSS JOIN (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as e
					 ) dates
			WHERE DATE_FORMAT(date,'%Y%m') = '".date('Ym', strtotime($bulan))."'
			ORDER BY date";
		$this->db->query($sql_cal);
		
		$gen_date= "";
		$qdate 	 = "SELECT date FROM calendar";
		$rs_date = $this->db->query($qdate)->result();
		foreach ($rs_date as $row) {
			$intdate = (int)date('d', strtotime($row->date));
			$gen_date .= ",";
			$gen_date .= "GROUP_CONCAT(if(DAY(calendar.date) = ".$intdate.", hitungpresensi.JAMLEMBUR, NULL)) AS 'jam_day".$intdate."'";
			$gen_date .= ",";
			$gen_date .= "GROUP_CONCAT(if(DAY(calendar.date) = ".$intdate.", hitungpresensi.SATLEMBUR, NULL)) AS 'sat_day".$intdate."'";
		}

		$select  = "SELECT karyawan.NIK,
				regex_replace('[^0-9]','',karyawan.NIK) AS UNIK,
				karyawan.NAMAKAR,
				karyawan.KODEUNIT,
				unitkerja.NAMAUNIT,
				/*DATE_FORMAT(calendar.date, '%Y%m') AS BULAN,*/
				SUM(if(hitungpresensi.JAMLEMBUR IS NOT NULL, hitungpresensi.JAMLEMBUR, NULL)) AS 'jam_total',
				SUM(if(hitungpresensi.SATLEMBUR IS NOT NULL, hitungpresensi.SATLEMBUR, NULL)) AS 'sat_total'
			  	".$gen_date;
		$from    = " FROM calendar
			CROSS JOIN karyawan
			LEFT JOIN hitungpresensi ON(hitungpresensi.NIK = karyawan.NIK AND hitungpresensi.TANGGAL = calendar.date)
			LEFT JOIN unitkerja ON(unitkerja.KODEUNIT = karyawan.KODEUNIT)
			WHERE DATE_FORMAT(date,'%Y%m') = '".date('Ym', strtotime($bulan))."'";
		$groupby = " GROUP BY karyawan.NIK, hitungpresensi.DATAKE";
		
		$sql     = $select.$from.$groupby;
		
		return $this->db->query($sql)->result();
	}

	function lapkarlembur($bulan){
		$this->db->truncate('calendar');
		$sql_cal = "INSERT INTO calendar
			SELECT date
			FROM (
					SELECT curdate() - interval (a.a + (10 * b.a) + (100 * c.a) + (1000 * d.a) + (10000 * e.a)) day as date
					FROM (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
					CROSS JOIN (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
					CROSS JOIN (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
					CROSS JOIN (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as d
					CROSS JOIN (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as e
					 ) dates
			WHERE DATE_FORMAT(date,'%Y%m') = '".date('Ym', strtotime($bulan))."'
			ORDER BY date";
		$this->db->query($sql_cal);
		
		$gen_date= "";
		$qdate 	 = "SELECT date FROM calendar";
		$rs_date = $this->db->query($qdate)->result();
		foreach ($rs_date as $row) {
			$intdate = (int)date('d', strtotime($row->date));
			$gen_date .= ",";
			$gen_date .= "GROUP_CONCAT(if(DAY(calendar.date) = ".$intdate.", hitungpresensi.JAMLEMBUR, NULL)) AS 'jam_day".$intdate."'";
			$gen_date .= ",";
			$gen_date .= "GROUP_CONCAT(if(DAY(calendar.date) = ".$intdate.", hitungpresensi.SATLEMBUR, NULL)) AS 'sat_day".$intdate."'";
		}

		$select  = "SELECT karyawan.NIK,
				regex_replace('[^0-9]','',karyawan.NIK) AS UNIK,
				karyawan.NAMAKAR,
				karyawan.KODEUNIT,
				unitkerja.NAMAUNIT,
				/*DATE_FORMAT(calendar.date, '%Y%m') AS BULAN,*/
				SUM(if(hitungpresensi.JAMLEMBUR IS NOT NULL, hitungpresensi.JAMLEMBUR, NULL)) AS 'jam_total',
				SUM(if(hitungpresensi.SATLEMBUR IS NOT NULL, hitungpresensi.SATLEMBUR, NULL)) AS 'sat_total'
			  	".$gen_date;
		$from    = " FROM calendar
			CROSS JOIN karyawan
			LEFT JOIN hitungpresensi ON(hitungpresensi.NIK = karyawan.NIK AND hitungpresensi.TANGGAL = calendar.date)
			LEFT JOIN unitkerja ON(unitkerja.KODEUNIT = karyawan.KODEUNIT)
			WHERE DATE_FORMAT(date,'%Y%m') = '".date('Ym', strtotime($bulan))."'";
		$groupby = " GROUP BY karyawan.NIK, hitungpresensi.DATAKE";
		
		$sql     = $select.$from.$groupby;

		$query = $this->db->query($sql);
		$results = $query->result();
		// $results = $this->genLemburPerBulan($bulan);

		$columns = array();
		$fields  = array();
		/*foreach ($results as $key => $value) {
			$objColumns = new stdClass();
			$objColumns->text      = $key;
			$objColumns->dataIndex = $key;
			array_push($columns, $objColumns);

			$objFields = new stdClass();
			$objFields->name      = $key;
			array_push($fields, $objFields);
		}*/

		foreach ($query->list_fields() as $field){
			$objColumns = new stdClass();
			$objColumns->text      = $field;
			$objColumns->dataIndex = $field;
			array_push($columns, $objColumns);

		   	$objFields = new stdClass();
			$objFields->name = $field;
			array_push($fields, $objFields);
		}

		$json	= array(
			'success' => TRUE,
			'message' => "Loaded data",
			'columns' => $columns,
			'fields'  => $fields,
			'data'    => $results
		);
		
		return $json;
	}
}
?>