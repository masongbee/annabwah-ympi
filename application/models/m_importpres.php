<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_importpres
 * 
 * Table	: presensi
 *  
 * @author masongbee
 *
 */
class M_importpres extends CI_Model{

	function __construct(){
		parent::__construct();
		
		//$DB1 = $this->load->database('default', TRUE);
		//$DB2 = $this->load->database('mybase', TRUE); 
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
	 
	function ImportPresensi($tglmulai,$tglsampai){
		$sql = "INSERT INTO dbympi.presensi
		(NIK,TJMASUK,TJKELUAR,ASALDATA,USERNAME)
		SELECT t1.NIK AS NIK,
		t1.MASUK AS TJMASUK,
		t1.KELUAR AS TJKELUAR,
		'D' AS ASALDATA,
		'Super Admin' AS USERNAME
		FROM (
			SELECT k.NIK, j.trans_tgl, j.trans_jam, j.trans_keluar, j.trans_status, j.MASUK, j.KELUAR, j.jml as JRECORD
			FROM dbympi.karyawan k
			JOIN (
			select t.trans_pengenal,t.trans_tgl,t.trans_jam,MAX(t.trans_jam)as trans_keluar,t.trans_status, 
			TIMESTAMP(MIN(t.trans_tgl),MIN(t.trans_jam)) as MASUK, 
			TIMESTAMP(MAX(t.trans_tgl),MAX(t.trans_jam)) as KELUAR,count(t.trans_tgl) as jml
			from (
			SELECT DISTINCT trans_pengenal,trans_tgl,trans_jam,trans_status,trans_log
				FROM mybase.absensi ) as t
			WHERE t.trans_tgl >= DATE('$tglmulai') AND t.trans_tgl <= DATE('$tglsampai')
			group by t.trans_pengenal, t.trans_tgl) as j
			ON k.NIK=j.trans_pengenal) as t1
		WHERE t1.trans_tgl >= DATE('$tglmulai') AND t1.trans_tgl <= DATE('$tglsampai')";
		$query = $this->db->query($sql);
		$rs = $this->db->order_by('TJMASUK', 'ASC')->get('presensi')->result();
		//$firephp->info($query);
		$total = $this->db->get('presensi')->num_rows();
		$data   = array();
		foreach($rs as $result){
			$data[] = $result;
		}
		
		$json	= array(
					'success'   => TRUE,
					'message'   => "Loaded data",
					'total'     => $total,
					'data'      => $data
			);
			
		return $json;
		
		
		/*$DB1 = $this->load->database('default', TRUE);
		$DB2 = $this->load->database('mybase', TRUE); 
		
		//$sql = "SELECT DISTINCT trans_pengenal,trans_tgl,trans_jam,trans_status,trans_log from absensi WHERE trans_pengenal = 00030453 ORDER BY trans_pengenal,trans_log";
		
		$cp = intval(read_file("./assets/checkpoint/cp.txt"));
		$limit = 100;
		$query = $DB2->limit($limit, $cp)->distinct()->order_by('trans_pengenal','trans_log')->get('absensi');
		$total  = $query->num_rows();
		
		$TimeWork = 12; // misal jam kerja dalam 1hari adlah 9 jam
		
		/*Prosedur Import Presensi Page 8
		A    = 1 REC (MASUK, TANPA KELUAR) (tergantung data berikutnya)
		A -> B = 1 REC (KELUAR TERISI -> NORMAL) (proses sempurna)
		A -> A = 2 REC (TANPA KELUAR) (rec ke-2 tergantung data berikutnya)
		B      = 1 REC (KELUAR, TANPA MASUK) (tak tergantung data berikutnya)*/
		
		/*$ketemuA = false;
		$ketemuB = false;
		foreach($query->result_array() as $val)
		{
			if ($ketemuA)
			{				
				$this->id2 = $val['trans_pengenal'];
				$this->jam2 = new DateTime($val['trans_tgl']." ".$val['trans_jam']);			
				$interval = date_diff($this->TimeLimit,$this->jam2);
				//echo "Jam 2 A : ".$this->jam1."<br />";
				
				if(($val['trans_status'] == "A") && ($interval->h > $TimeWork))
				{					
					$array = array('NIK' => $this->id1, 'TJMASUK' => $this->jam1);

					if($DB1->get_where('presensi', $array)->num_rows() <= 0)
					{
						$data = array(
						   'NIK' => $val['trans_pengenal'] ,
						   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
						   'TJKELUAR' => null,
						   'ASALDATA' => 'D',
						   'POSTING' => null,
						   'USERNAME' => $this->session->userdata('user_name')
						);
						$DB1->insert('presensi', $data);
					}					
					
					$ketemuA = false;
					$ketemuB = false;
				}
				elseif (($val['trans_status'] == "B") && ($interval->h <= $TimeWork) && ($this->id2 == $this->id1))
				{
					$data = array(
					   'TJKELUAR' => $val['trans_tgl']." ".$val['trans_jam']
					);
					
					$array = array('NIK' => $this->id1, 'TJMASUK' => $this->jam1);

					$DB1->where($array);
					$DB1->update('presensi', $data);
					
					$ketemuB = true;
					$ketemuA = false;
				}
				elseif(($val['trans_status'] == "A") && ($interval->h <= $TimeWork))
				{
					$array = array('NIK' => $this->id1, 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);

					if($DB1->get_where('presensi', $array)->num_rows() <= 0)
					{
						$data = array(
						   'NIK' => $val['trans_pengenal'] ,
						   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
						   'TJKELUAR' => null,
						   'ASALDATA' => 'D' ,
						   'POSTING' => null ,
						   'USERNAME' => $this->session->userdata('user_name')
						);
						$DB1->insert('presensi', $data);
					}
				}
				else
				{
					$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);

					if($DB1->get_where('presensi', $array)->num_rows() <= 0)
					{
						$data = array(
						   'NIK' => $val['trans_pengenal'],
						   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
						   'TJKELUAR' => $val['trans_tgl']." ".$val['trans_jam'],
						   'ASALDATA' => 'D' ,
						   'POSTING' => null ,
						   'USERNAME' => $this->session->userdata('user_name')
						);
						$DB1->insert('presensi', $data);
					}
				}
				
			}
			
			if (!$ketemuA && $val['trans_status'] == "A")
			{				
				$ketemuA = true;				
				$array = array('NIK' => $this->id1, 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);

				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					$data = array(
					   'NIK' => $val['trans_pengenal'] ,
					   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
					   'TJKELUAR' => null,
					   'ASALDATA' => 'D' ,
					   'POSTING' => null ,
						   'USERNAME' => $this->session->userdata('user_name')
					);
					$DB1->insert('presensi', $data);
				}	
				$this->jam1 = $val['trans_tgl']." ".$val['trans_jam'];
							
				$waktu = new DateTime($this->jam1);
				$waktu->add(new DateInterval("PT".$TimeWork."H"));
				$this->TimeLimit = $waktu;
				$this->id1 = $val['trans_pengenal'];
				//echo "Jam 1 A : ".$this->jam1."<br />";
			}
			elseif (!$ketemuB && $val['trans_status'] == "B")
			{
				if($ketemuA == true)
					$ketemuA = false;
					
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);

				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					$data = array(
					   'NIK' => $val['trans_pengenal'] ,
					   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
					   'TJKELUAR' => $val['trans_tgl']." ".$val['trans_jam'],
					   'ASALDATA' => 'D' ,
					   'POSTING' => null ,
						'USERNAME' => $this->session->userdata('user_name')
					);
					$DB1->insert('presensi', $data);
				}
				$this->jam1 = $val['trans_tgl']." ".$val['trans_jam'];
				
				$waktu = new DateTime($this->jam1);
				$waktu->add(new DateInterval("PT".$TimeWork."H"));
				$this->TimeLimit = $waktu;
				$this->id1 = $val['trans_pengenal'];
				//echo "Jam 1 B : ".$this->jam1."<br />";
			}
		}
		
		if (write_file("./assets/checkpoint/cp.txt", $cp + $total))
		{
			//echo "Checkpoint telah dibuat....<br /><br />";
			$query  = $DB1->limit($limit, $start)->order_by('TJMASUK', 'ASC')->get('presensi')->result();
			$total = $DB1->get('presensi')->num_rows();
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
		}*/
	}
	 
	function FilterPresensi($start, $page, $limit){
		$this->db->where('TJKELUAR IS NULL', NULL);
		$this->db->or_where('TJMASUK = TJKELUAR', NULL); 
		$query  = $this->db->limit($limit, $start)->order_by('TJMASUK', 'ASC')->get('presensi')->result();
		$total  = $this->db->get('presensi')->num_rows();
		
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
	
	function getAll($filt,$start, $page, $limit){
		if($filt == "Filter")
		{
			$this->db->where('TJKELUAR IS NULL', NULL);
			$this->db->or_where('TJMASUK = TJKELUAR', NULL); 
			$query  = $this->db->limit($limit, $start)->order_by('TJMASUK', 'ASC')->get('presensi');
			$total  = $query->num_rows();
			
			$data   = array();
			foreach($query->result() as $result){
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
		else
		{
			$query  = $this->db->limit($limit, $start)->order_by('TJMASUK', 'ASC')->get('presensi')->result();
			$total  = $this->db->get('presensi')->num_rows();
			
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
		
		$pkey = array('NIK'=>$data->NIK,'TJMASUK'=>date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
		
		if($this->db->get_where('presensi', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('TJKELUAR'=>(strlen(trim($data->TJKELUAR)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJKELUAR)) : NULL),'ASALDATA'=>$data->ASALDATA,'POSTING'=>$data->POSTING,'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('presensi', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('NIK'=>$data->NIK,'TJMASUK'=>(strlen(trim($data->TJMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : NULL),'TJKELUAR'=>(strlen(trim($data->TJKELUAR)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJKELUAR)) : NULL),'ASALDATA'=>$data->ASALDATA,'POSTING'=>$data->POSTING,'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('presensi', $arrdatac);
			$last   = $this->db->where($pkey)->get('presensi')->row();
			
		}
		
		$total  = $this->db->get('presensi')->num_rows();
		
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
		$pkey = array('NIK'=>$data->NIK,'TJMASUK'=>date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
		
		$this->db->where($pkey)->delete('presensi');
		
		$total  = $this->db->get('presensi')->num_rows();
		$last = $this->db->get('presensi')->result();
		
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