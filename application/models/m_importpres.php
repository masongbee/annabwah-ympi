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

	private $id1;
	private $id2;
	private $jam1;
	private $jam2;

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
	 
	function ImportPresensi_MK($tglmulai,$tglsampai){
		/**
		 * Proses INSERT dari database mybase.absensi ke dbympi.absensi,
		 * dimana data mybase.absensi belum diimport ke dbympi.absensi
		 */
		$sql = "INSERT INTO absensi (trans_pengenal
			,trans_tgl
			,trans_jam
			,trans_status
			,trans_log
			,`import`)
			SELECT IF((SUBSTR(t2.trans_pengenal,1,2) >= 97)
				AND (SUBSTR(t2.trans_pengenal,1,2)<=99),
				CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)-32),t2.trans_pengenal),
				CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)+68),t2.trans_pengenal)) AS trans_pengenal, t2.trans_tgl, t2.trans_jam, t2.trans_status, t2.trans_log, '0'
			FROM mybase.absensi AS t2 
			LEFT JOIN absensi AS t1 ON(t1.trans_pengenal = (IF((SUBSTR(t2.trans_pengenal,1,2) >= 97)
					AND (SUBSTR(t2.trans_pengenal,1,2)<=99),
					CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)-32),t2.trans_pengenal),
					CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)+68),t2.trans_pengenal))) 
				AND t1.trans_tgl = t2.trans_tgl
				AND t1.trans_jam = t2.trans_jam AND t1.trans_status = t2.trans_status)
			WHERE t1.trans_pengenal IS NULL 
				AND t1.trans_tgl IS NULL 
				AND t1.trans_jam IS NULL
				AND t1.trans_status IS NULL
				AND TO_DAYS(t2.trans_tgl) >= TO_DAYS('".$tglmulai."') AND TO_DAYS(t2.trans_tgl) <= TO_DAYS('".$tglsampai."')
			GROUP BY t2.trans_pengenal, t2.trans_tgl, t2.trans_jam, t2.trans_status";
		$this->db->query($sql);
		
		/**
		 * DELETE absensi WHERE dbympi.absensi.trans_pengenal tidak ada di karyawan.NIK
		 */
		$sqld = "DELETE FROM absensi
			WHERE trans_pengenal NOT IN (SELECT NIK FROM karyawan)";
		$this->db->query($sqld);
		
		/**
		 * DELETE db.presensi WHERE TANGGAL diantara $tglmulai dan $tglsampai
		 */
		$sqld = "DELETE FROM presensi
			WHERE TO_DAYS(TANGGAL) >= TO_DAYS('".$tglmulai."')
				AND TO_DAYS(TANGGAL) <= TO_DAYS('".$tglsampai."')";
		$this->db->query($sqld);
		
		/**
		 * INSERT into db.presensi dari dbympi.absensi yang kolom import = '0' dan trans_status = A
		 */
		$sql = "INSERT INTO presensi (NIK, TJMASUK, TANGGAL, TJKELUAR, ASALDATA, POSTING)
			SELECT trans_pengenal, STR_TO_DATE(CONCAT(trans_tgl,' ',trans_jam),'%Y-%m-%d %H:%i:%s'),
				trans_tgl, null, 'D', null
			FROM absensi
			WHERE TO_DAYS(trans_tgl) >= TO_DAYS('".$tglmulai."')
				AND TO_DAYS(trans_tgl) <= TO_DAYS('".$tglsampai."')
				AND import = '0'
				AND trans_status = 'A'
			ORDER BY trans_pengenal, trans_tgl, trans_jam";
		$this->db->query($sql);
		
		/**
		 * UPDATE kolom absensi.import = '1' yang telah diimport ke db.presensi
		 */
		$sqlu = "UPDATE absensi
			SET import = '1'
			WHERE TO_DAYS(trans_tgl) >= TO_DAYS('".$tglmulai."')
				AND TO_DAYS(trans_tgl) <= TO_DAYS('".$tglsampai."')
				AND import = '0'
				AND trans_status = 'A'
			ORDER BY trans_pengenal, trans_tgl, trans_jam";
		$this->db->query($sqlu);
		
		/**
		 * GET Data dari db.absensi yang import = '0' dan trans_status = 'B'
		 * Untuk di LOOPing dan meng-Update db.presensi atau create baru
		 */
		$sql = "SELECT id, trans_pengenal, trans_tgl, trans_jam, trans_status, trans_log
			FROM absensi
			WHERE TO_DAYS(trans_tgl) >= TO_DAYS('".$tglmulai."')
				AND TO_DAYS(trans_tgl) <= TO_DAYS('".$tglsampai."')
				AND import = '0'
				AND trans_status = 'B'
			ORDER BY trans_pengenal, trans_tgl, trans_jam";
		$query = $this->db->query($sql);
		
		foreach($query->result() as $row){
			/**
			 * GET Data paling akhir sebelom NIK dan trans_tgl+trans_jam
			 */
			$sql = "SELECT *
				FROM presensi
				WHERE NIK = '".$row->trans_pengenal."'
					AND UNIX_TIMESTAMP(TJMASUK) < UNIX_TIMESTAMP('".$row->trans_tgl." ".$row->trans_jam."')
				ORDER BY TJMASUK DESC
				LIMIT 1";
			$rs = $this->db->query($sql)->result();
			if(sizeof($rs) > 0){
				foreach($rs as $rowb){
					if(is_null($rowb->TJKELUAR)){
						//db.presensi.TJKELUAR IS NULL ==> UPDATE record di db.presensi dengan db.presensi.TJKELUAR = trans_tgl + trans_jam
						$datau = array(
							'TJKELUAR' => date('Y-m-d H:i:s', strtotime($row->trans_tgl.' '.$row->trans_jam))
						);
						$this->db->where('ID', $rowb->ID);
						$this->db->update('presensi', $datau);
						
						//update db.absensi.import = 1
						$this->db->where('id', $row->id);
						$this->db->update('absensi', array('import'=>'1'));
					}else{
						//db.presensi.TJKELUAR IS NOT NULL ==> CREATE record ke db.presensi dengan db.presensi.TJMASUK = null, db.presensi.TJKELUAR = trans_tgl + trans_jam, db.presensi.TANGGAL = trans_tgl
						$data = array(
							'NIK'		=> $row->trans_pengenal,
							'TJMASUK'	=> null,
							'TANGGAL'	=> $row->trans_tgl,
							'TJKELUAR'	=> date('Y-m-d H:i:s', strtotime($row->trans_tgl.' '.$row->trans_jam)),
							'ASALDATA'	=> 'D',
							'POSTING'	=> null
						);
						$this->db->insert('presensi', $data);
						
						//update db.absensi.import = 1
						$this->db->where('id', $row->id);
						$this->db->update('absensi', array('import'=>'1'));
					}
				}
			}else{
				//CREATE record ke db.presensi dengan db.presensi.TJMASUK = null, db.presensi.TJKELUAR = trans_tgl + trans_jam, db.presensi.TANGGAL =  trans_tgl
				$data = array(
					'NIK'		=> $row->trans_pengenal,
					'TJMASUK'	=> null,
					'TANGGAL'	=> $row->trans_tgl,
					'TJKELUAR'	=> date('Y-m-d H:i:s', strtotime($row->trans_tgl.' '.$row->trans_jam)),
					'ASALDATA'	=> 'D',
					'POSTING'	=> null
				);
				$this->db->insert('presensi', $data);
				
				//update db.absensi.import = 1
				$this->db->where('id', $row->id);
				$this->db->update('absensi', array('import'=>'1'));
			}
		}
	}
	 
	function ImportPresensi($tglmulai,$tglsampai){
		$DB1 = $this->load->database('default', TRUE);
		$DB2 = $this->load->database('mybase', TRUE); 
		
		$sql = "INSERT INTO absensi (SELECT distinct (IF((SUBSTR(t1.trans_pengenal,1,2) >= 97)AND(SUBSTR(t1.trans_pengenal,1,2)<=99),CONCAT(CHAR(SUBSTR(t1.trans_pengenal,1,2)-32),t1.trans_pengenal),CONCAT(CHAR(SUBSTR(t1.trans_pengenal,1,2)+68),t1.trans_pengenal))) AS trans_pengenal,
		t1.trans_tgl,t1.trans_jam,t1.trans_status,t1.trans_log, '0' AS import
		FROM mybase.absensi AS t1
		WHERE t1.trans_tgl >= DATE('$tglmulai') AND t1.trans_tgl <= DATE('$tglsampai')
		ORDER BY t1.trans_pengenal,t1.trans_tgl, t1.trans_jam)";
		$query = $this->db->query($sql);
		//$total  = $query->num_rows();
		
		//DELETE absensi WHERE dbympi.absensi.trans_pengenal tidak ada di karyawan.NIK
		$sqld = "DELETE FROM absensi
			WHERE trans_pengenal NOT IN (SELECT NIK FROM karyawan)";
		$this->db->query($sqld);
		
		
		$sql = "SELECT a.trans_pengenal,a.trans_tgl,a.trans_jam,a.trans_status,a.trans_log
		FROM absensi a
		INNER JOIN karyawan k ON k.NIK=a.trans_pengenal
		WHERE a.trans_tgl >= DATE('$tglmulai') AND a.trans_tgl <= DATE('$tglsampai') AND (k.STATUS='T' OR k.STATUS='K' OR k.STATUS='C') AND a.import='0'
		order by a.trans_pengenal, a.trans_tgl, a.trans_jam, a.trans_status";
		$query = $this->db->query($sql);
		//$total  = $query->num_rows();
		
		//$TimeWork = 12; // misal jam kerja dalam 1hari adlah 9 jam
		
		/*Prosedur Import Presensi Page 8
		A      = 1 REC (MASUK, TANPA KELUAR) (tergantung data berikutnya)
		A -> B = 1 REC (KELUAR TERISI -> NORMAL) (proses sempurna)
		A -> A = 2 REC (TANPA KELUAR) (rec ke-2 tergantung data berikutnya)
		B      = 1 REC (KELUAR, TANPA MASUK) (tak tergantung data berikutnya)*/
		
		$ketemuA = false;
		$ketemuB = false;
		
		foreach($query->result_array() as $val)
		{
		
			if(!$ketemuA && $val['trans_status'] == "A")
			{
				//Record Baru A simpan NIK ke $id1
				$this->id1 = $val['trans_pengenal'];
				$this->jam1 = $val['trans_tgl']." ".$val['trans_jam'];
				//$this->firephp->info($this->jam1);
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
					   'TJKELUAR' => null,
					   'ASALDATA' => 'D' ,
					   'POSTING' => null ,
					   'USERNAME' => $this->session->userdata('user_name')
					);
					$DB1->insert('presensi', $data);
					
					$DB1->where(array('trans_pengenal' => $val['trans_pengenal'], 'trans_tgl' => $val['trans_tgl'],'trans_jam' => $val['trans_jam']))->update('absensi', array('import' => '1'));
				}
				
				$ketemuA = true;
				$ketemuB = false;
			}
			elseif($ketemuA && $val['trans_status'] == "B")
			{
				//Update Record A->B
				$this->id2 = $val['trans_pengenal'];
				
				if($this->id2 == $this->id1)
				{
					$data = array(
					   'TJKELUAR' => $val['trans_tgl']." ".$val['trans_jam']
					);
					
					$array = array('NIK' => $this->id1, 'TJMASUK' => $this->jam1);

					$DB1->where($array);
					$DB1->update('presensi', $data);
					
					$DB1->where(array('trans_pengenal' => $val['trans_pengenal'], 'trans_tgl' => $val['trans_tgl'],'trans_jam' => $val['trans_jam']))->update('absensi', array('import' => '1'));
					
					$ketemuA = false;
					$ketemuB = true;
				}
				else
				{
					//Insert Record A->B jika NIK berbeda
					$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);				
					if($DB1->get_where('presensi', $array)->num_rows() <= 0)
					{
						$data = array(
						   'NIK' => $val['trans_pengenal'],
						   'TANGGAL' => $val['trans_tgl'],
						   'TJMASUK' => null,
						   //'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
						   'TJKELUAR' => $val['trans_tgl']." ".$val['trans_jam'],
						   'ASALDATA' => 'D' ,
						   'POSTING' => null ,
						   'USERNAME' => $this->session->userdata('user_name')
						);
						$DB1->insert('presensi', $data);
						
						
						$DB1->where(array('trans_pengenal' => $val['trans_pengenal'], 'trans_tgl' => $val['trans_tgl'],'trans_jam' => $val['trans_jam']))->update('absensi', array('import' => '1'));
				
						$ketemuA = false;
						$ketemuB = false;
					}
				}
			}
			elseif($ketemuA && $val['trans_status'] == "A")
			{
				//Record Baru A->A simpan NIK $id2 ke $id1
				$this->id1 = $val['trans_pengenal'];
				$this->jam1 = $val['trans_tgl']." ".$val['trans_jam'];
				
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
					   'TJKELUAR' => null,
					   'ASALDATA' => 'D' ,
					   'POSTING' => null ,
					   'USERNAME' => $this->session->userdata('user_name')
					);
					$DB1->insert('presensi', $data);
					
					$DB1->where(array('trans_pengenal' => $val['trans_pengenal'], 'trans_tgl' => $val['trans_tgl'],'trans_jam' => $val['trans_jam']))->update('absensi', array('import' => '1'));
				}
				
				$ketemuA = true;
				$ketemuB = false;				
			}
			elseif(!$ketemuB && $val['trans_status'] == "B")
			{
				//Record Baru B				
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
					   'TJKELUAR' => $val['trans_tgl']." ".$val['trans_jam'],
					   'ASALDATA' => 'D' ,
					   'POSTING' => null ,
					   'USERNAME' => $this->session->userdata('user_name')
					);
					$DB1->insert('presensi', $data);
					
					$DB1->where(array('trans_pengenal' => $val['trans_pengenal'], 'trans_tgl' => $val['trans_tgl'],'trans_jam' => $val['trans_jam']))->update('absensi', array('import' => '1'));
				}
				$ketemuA = false;
				$ketemuB = true;
			}
			elseif($ketemuB && $val['trans_status'] == "A")
			{
				//Record Baru B->A simpan NIK $id2 ke $id1
				$this->id1 = $val['trans_pengenal'];
				$this->jam1 = $val['trans_tgl']." ".$val['trans_jam'];
				
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
					   'TJKELUAR' => null,
					   'ASALDATA' => 'D' ,
					   'POSTING' => null ,
					   'USERNAME' => $this->session->userdata('user_name')
					);
					$DB1->insert('presensi', $data);
					
					
					$DB1->where(array('trans_pengenal' => $val['trans_pengenal'], 'trans_tgl' => $val['trans_tgl'],'trans_jam' => $val['trans_jam']))->update('absensi', array('import' => '1'));
				}
				$ketemuA = true;
				$ketemuB = false;
			}
			elseif($ketemuB && $val['trans_status'] == "B")
			{
				//Record Baru B->B
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'TJMASUK' => null,
					   //'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
					   'TJKELUAR' => $val['trans_tgl']." ".$val['trans_jam'],
					   'ASALDATA' => 'D' ,
					   'POSTING' => null ,
					   'USERNAME' => $this->session->userdata('user_name')
					);
					$DB1->insert('presensi', $data);
					
					
					$DB1->where(array('trans_pengenal' => $val['trans_pengenal'], 'trans_tgl' => $val['trans_tgl'],'trans_jam' => $val['trans_jam']))->update('absensi', array('import' => '1'));
				}
				$ketemuA = false;
				$ketemuB = true;
			}
		}
		
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
			$sql = "SELECT p.NIK, k.NAMAKAR as NAMA, p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			ORDER BY p.TJMASUK ASC
			LIMIT $start,$limit";
			$query = $this->db->query($sql)->result();
			//$query  = $this->db->limit($limit, $start)->order_by('TJMASUK', 'ASC')->get('presensi')->result();
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
		
		//$pkey = array('ID'=>$data->ID,'NIK'=>$data->NIK,'TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)),'TJMASUK'=>date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
		
		$pkey = array('ID'=>$data->ID);
	
		//$this->firephp->info($data->TJMASUK);
		//$this->firephp->info(date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
		
		if($this->db->get_where('presensi', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('TANGGAL'=>trim($data->TANGGAL),'TJMASUK'=>(strlen(trim($data->TJMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : NULL),'TJKELUAR'=>(strlen(trim($data->TJKELUAR)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJKELUAR)) : NULL),'ASALDATA'=>$data->ASALDATA,'POSTING'=>$data->POSTING,'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('presensi', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('NIK'=>$data->NIK,'TANGGAL'=>trim($data->TANGGAL),'TJMASUK'=>(strlen(trim($data->TJMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : NULL),'TJKELUAR'=>(strlen(trim($data->TJKELUAR)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJKELUAR)) : NULL),'ASALDATA'=>$data->ASALDATA,'POSTING'=>$data->POSTING,'USERNAME'=>$data->USERNAME);
			 
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
	
		$pkey = array('ID'=>$data->ID);
		//$pkey = array('NIK'=>$data->NIK,'TJMASUK'=>date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
		
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
	
	function setTukarShift($data){
		$last   = NULL;
		$pkey = array('NIK'=>$data->NIK,'TGLMULAI'=>$data->TANGGAL);
			
		/*
		 * Process Insert
		 */
		
		$arrdatac = array('NIK'=>$data->NIK,'TGLMULAI'=>trim($data->TANGGAL),'TGLSAMPAI'=>trim($data->TANGGAL),'NAMASHIFT'=>trim($data->NAMASHIFT),'NAMASHIFT2'=>trim($data->NAMASHIFT2),'SHIFTKE'=>trim($data->SHIFTKE),'SHIFTKE2'=>trim($data->SHIFTKE2));
		 
		$this->db->insert('tukarshift', $arrdatac);
		$last   = $this->db->where($pkey)->get('tukarshift')->row();
			
		$total  = $this->db->get('tukarshift')->num_rows();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil disimpan',
						"total"     => $total,
						"data"      => $last
		);
		
		return $json;
	}
	
	function setMasuk(){
		$sql = "UPDATE presensi p
		INNER JOIN (
			SELECT p.NIK,p.TANGGAL,p.TJMASUK,p.TJKELUAR,t1.KODESHIFT,t1.NAMASHIFT,t1.SHIFTKE,t1.JENISHARI,t1.JAMDARI,t1.JAMSAMPAI,
			t2.SHIFTKE2,t2.JENISHARI AS JENISHARI2,t2.JAMDARI AS JAMDARI2,t2.JAMSAMPAI AS JAMSAMPAI2,
			t1.TGLMULAI,t1.TGLSAMPAI
			FROM presensi p
			INNER JOIN (
				SELECT ks.KODESHIFT,ps.NAMASHIFT,ps.SHIFTKE,ks.NIK,ps.TGLMULAI,ps.TGLSAMPAI,
				sk.JENISHARI,sk.JAMDARI,sk.JAMSAMPAI
				FROM karyawanshift ks
				INNER JOIN pembagianshift ps ON ps.KODESHIFT=ks.KODESHIFT
				INNER JOIN shiftjamkerja sk ON sk.NAMASHIFT=ps.NAMASHIFT AND sk.SHIFTKE=ps.SHIFTKE
			) AS t1 ON t1.NIK=p.NIK
			LEFT JOIN (
				SELECT ts.NAMASHIFT,ts.SHIFTKE,ts.NAMASHIFT2,ts.SHIFTKE2,ts.NIK,ts.NIK2,ts.TGLMULAI,ts.TGLSAMPAI,
				sjk.JENISHARI,sjk.JAMDARI,sjk.JAMSAMPAI
				FROM tukarshift ts
				INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=ts.NAMASHIFT2 AND sjk.SHIFTKE=ts.SHIFTKE2
			) AS t2 ON t2.NIK=p.NIK AND (p.TANGGAL >= t2.TGLMULAI AND p.TANGGAL <= t2.TGLSAMPAI) AND (t2.JENISHARI = (IF(DAYNAME(DATE(p.TANGGAL)) = 'Friday','J','N')))
			WHERE (p.TJMASUK IS NULL OR p.TJKELUAR IS NULL) AND (t1.JENISHARI = (IF(DAYNAME(DATE(p.TANGGAL)) = 'Friday','J','N')))
		) AS t3 ON t3.NIK=p.NIK AND t3.TANGGAL=p.TANGGAL
		SET 
			p.TJMASUK = IF(t3.SHIFTKE2 IS NULL,TIMESTAMP(p.TANGGAL,t3.JAMDARI),TIMESTAMP(P.TANGGAL,t3.JAMDARI2))";
		$query  = $this->db->query($sql);
		
		$json	= array(
			'success'   => TRUE,
			'message'   => "Data has been updated"
		);
		
		return $json;
	}
	
	function setKeluar(){
		$sql = "UPDATE presensi p
		INNER JOIN (
			SELECT p.NIK,p.TANGGAL,p.TJMASUK,p.TJKELUAR,t1.KODESHIFT,t1.NAMASHIFT,t1.SHIFTKE,t1.JENISHARI,t1.JAMDARI,t1.JAMSAMPAI,
			t2.SHIFTKE2,t2.JENISHARI AS JENISHARI2,t2.JAMDARI AS JAMDARI2,t2.JAMSAMPAI AS JAMSAMPAI2,
			t1.TGLMULAI,t1.TGLSAMPAI
			FROM presensi p
			INNER JOIN (
				SELECT ks.KODESHIFT,ps.NAMASHIFT,ps.SHIFTKE,ks.NIK,ps.TGLMULAI,ps.TGLSAMPAI,
				sk.JENISHARI,sk.JAMDARI,sk.JAMSAMPAI
				FROM karyawanshift ks
				INNER JOIN pembagianshift ps ON ps.KODESHIFT=ks.KODESHIFT
				INNER JOIN shiftjamkerja sk ON sk.NAMASHIFT=ps.NAMASHIFT AND sk.SHIFTKE=ps.SHIFTKE
			) AS t1 ON t1.NIK=p.NIK
			LEFT JOIN (
				SELECT ts.NAMASHIFT,ts.SHIFTKE,ts.NAMASHIFT2,ts.SHIFTKE2,ts.NIK,ts.NIK2,ts.TGLMULAI,ts.TGLSAMPAI,
				sjk.JENISHARI,sjk.JAMDARI,sjk.JAMSAMPAI
				FROM tukarshift ts
				INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=ts.NAMASHIFT2 AND sjk.SHIFTKE=ts.SHIFTKE2
			) AS t2 ON t2.NIK=p.NIK AND (p.TANGGAL >= t2.TGLMULAI AND p.TANGGAL <= t2.TGLSAMPAI) AND (t2.JENISHARI = (IF(DAYNAME(DATE(p.TANGGAL)) = 'Friday','J','N')))
			WHERE (p.TJMASUK IS NULL OR p.TJKELUAR IS NULL) AND (t1.JENISHARI = (IF(DAYNAME(DATE(p.TANGGAL)) = 'Friday','J','N')))
		) AS t3 ON t3.NIK=p.NIK AND t3.TANGGAL=p.TANGGAL
		SET 
			p.TJKELUAR = IF(t3.SHIFTKE2 IS NULL,TIMESTAMP(p.TANGGAL,t3.JAMSAMPAI),TIMESTAMP(P.TANGGAL,t3.JAMSAMPAI2))

		WHERE (p.NIK NOT IN (SELECT NIK FROM presensilembur)) AND (DATE(p.TJMASUK) NOT IN (SELECT DATE(TJMASUK) FROM presensilembur))";
		$query  = $this->db->query($sql);
		
		$json	= array(
			'success'   => TRUE,
			'message'   => "Data has been updated"
		);
		
		return $json;
	}
	
	function getShift($nshift,$tgls){
		$sql = "SELECT NAMASHIFT,SHIFTKE,JENISHARI,JAMDARI,JAMSAMPAI
		FROM shiftjamkerja
		WHERE NAMASHIFT='".$nshift."' AND JENISHARI=(IF(DAYNAME('".$tgls."')= 'Friday','J','N'))
		ORDER BY NAMASHIFT,SHIFTKE";
		$query  = $this->db->query($sql)->result();
		$total  = $this->db->query($sql)->num_rows();
		
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}
		
		$json	= array(
			'success'   => TRUE,
			'message'   => "Data Shift Loaded",
			'total'     => $total,
			'data'      => $data
		);
		
		return $json;
	}
	
	function getAllData($tglmulai, $tglsampai,$saring,$sorts,$filters,$start, $page, $limit)
	{
		if($saring == "Log Kosong")
		{
			if (is_array($sorts)) {
				$encoded = false;
			} else {
				$encoded = true;
				$sorts = json_decode($sorts);
			}
			$dsort = ' p.NIK ASC';
			$ks = '';
			
			if (is_array($sorts)) {
				for ($i=0;$i<count($sorts);$i++){
					$sort = $sorts[$i];

					// assign Sort data (location depends if encoded or not)
					if ($encoded) {
						if($sort->property == 'NIK')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'NAMAKAR')
							$prop = "k.".$sort->property;
						elseif($sort->property == 'NAMAUNIT')
							$prop = "uk.".$sort->property;
						elseif($sort->property == 'NAMAKEL')
							$prop = "kk.".$sort->property;
						else if($sort->property == 'TANGGAL')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'TJMASUK')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'TJKELUAR')
							$prop = "p.".$sort->property;
						else
							$prop = $sort->property;
						
						$dir = $sort->direction;					
					} else {
						$prop = $sort['property'];
						$dir = $sort['direction'];
					}
					$ks .= ",".$prop." ".$dir;
				}
				$dsort .= $ks;
			}
			//$this->firephp->info($dsort);

			// GridFilters sends filters as an Array if not json encoded
			if (is_array($filters)) {
				$encoded = false;
			} else {
				$encoded = true;
				$filters = json_decode($filters);
			}

			$where = ' (p.TJKELUAR IS NULL OR p.TJMASUK IS NULL) ';
			$qs = '';

			// loop through filters sent by client
			if (is_array($filters)) {
				for ($i=0;$i<count($filters);$i++){
					$filter = $filters[$i];

					// assign filter data (location depends if encoded or not)
					if ($encoded) {
						if($filter->field == 'NIK')
							$field = "p.".$filter->field;
						elseif($filter->field == 'TANGGAL')
							$field = "p.".$filter->field;
						elseif($filter->field == 'SHIFTKE')
							$field = "t1.".$filter->field;
						else
							$field = $filter->field;
							
						$value = $filter->value;
						$compare = isset($filter->comparison) ? $filter->comparison : null;
						$filterType = $filter->type;
					} else {
						$field = $filter['field'];
						$value = $filter['data']['value'];
						$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
						$filterType = $filter['data']['type'];
					}

					switch($filterType){
						case 'string' : $qs .= " AND ".$field." LIKE '%".$value."%'"; Break;
						case 'list' :
							if (strstr($value,',')){
								$fi = explode(',',$value);
								for ($q=0;$q<count($fi);$q++){
									$fi[$q] = "'".$fi[$q]."'";
								}
								$value = implode(',',$fi);
								$qs .= " AND ".$field." IN (".$value.")";
							}else{
								$qs .= " AND ".$field." = '".$value."'";
							}
						Break;
						case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
						case 'numeric' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
								case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
								case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
							}
						Break;
						case 'date' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
								case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
								case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
							}
						Break;
						case 'datetime' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
								case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
								case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
							}
						Break;
					}
				}
				$where .= $qs;
			}
			

			$sql = "SELECT p.ID,p.NIK, k.NAMAKAR,uk.NAMAUNIT,uk.SINGKATAN,kk.NAMAKEL, p.TANGGAL,t1.NAMASHIFT,t1.SHIFTKE,
			t1.JAMDARI,t1.JAMSAMPAI,p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=uk.KODEKEL
			LEFT JOIN (
			SELECT ks.KODESHIFT,ks.NIK,ps.NAMASHIFT,PS.SHIFTKE,ps.TGLMULAI,PS.TGLSAMPAI,sjk.JAMDARI,sjk.JAMSAMPAI,sjk.JENISHARI,ps.MAKAN
			FROM karyawanshift ks 
			INNER JOIN pembagianshift ps ON ps.KODESHIFT=ks.KODESHIFT
			INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=ps.NAMASHIFT AND sjk.SHIFTKE=ps.SHIFTKE
			) AS t1 ON t1.NIK=p.NIK AND t1.JENISHARI=(IF(DAYNAME(p.TANGGAL) = 'Friday','J','N'))
			WHERE ".$where;
			
			$sql .= " ORDER BY ".$dsort;
			$sql .= " LIMIT ".$start.",".$limit;	
			$query = $this->db->query($sql);
			
			
			$total  = $this->db->query("SELECT p.ID,COUNT(p.NIK) AS total, k.NAMAKAR,uk.NAMAUNIT,uk.SINGKATAN,kk.NAMAKEL, p.TANGGAL,t1.NAMASHIFT,t1.SHIFTKE,
			t1.JAMDARI,t1.JAMSAMPAI,p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=uk.KODEKEL
			LEFT JOIN (
			SELECT ks.KODESHIFT,ks.NIK,ps.NAMASHIFT,PS.SHIFTKE,ps.TGLMULAI,PS.TGLSAMPAI,sjk.JAMDARI,sjk.JAMSAMPAI,sjk.JENISHARI,ps.MAKAN
			FROM karyawanshift ks 
			INNER JOIN pembagianshift ps ON ps.KODESHIFT=ks.KODESHIFT
			INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=ps.NAMASHIFT AND sjk.SHIFTKE=ps.SHIFTKE
			) AS t1 ON t1.NIK=p.NIK AND t1.JENISHARI=(IF(DAYNAME(p.TANGGAL) = 'Friday','J','N'))
			WHERE ".$where)->result();
			
			$data   = array();
			foreach($query->result() as $result){
				$data[] = $result;
			}
			
			$json	= array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total[0]->total,
				'data'      => $data
			);
			
			return $json;
		}
		elseif($saring == "Log Dobel")
		{
			if (is_array($sorts)) {
				$encoded = false;
			} else {
				$encoded = true;
				$sorts = json_decode($sorts);
			}
			$dsort = ' p.NIK,p.TANGGAL ASC';
			$ks = '';
			
			if (is_array($sorts)) {
				for ($i=0;$i<count($sorts);$i++){
					$sort = $sorts[$i];

					// assign Sort data (location depends if encoded or not)
					if ($encoded) {
						if($sort->property == 'NIK')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'NAMAKAR')
							$prop = "k.".$sort->property;
						elseif($sort->property == 'NAMAUNIT')
							$prop = "uk.".$sort->property;
						elseif($sort->property == 'NAMAKEL')
							$prop = "kk.".$sort->property;
						else if($sort->property == 'TANGGAL')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'TJMASUK')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'TJKELUAR')
							$prop = "p.".$sort->property;
						else
							$prop = $sort->property;
						
						$dir = $sort->direction;					
					} else {
						$prop = $sort['property'];
						$dir = $sort['direction'];
					}
					$ks .= ",".$prop." ".$dir;
				}
				$dsort .= $ks;
			}
			//$this->firephp->info($dsort);

			// GridFilters sends filters as an Array if not json encoded
			if (is_array($filters)) {
				$encoded = false;
			} else {
				$encoded = true;
				$filters = json_decode($filters);
			}

			$where = ' 0=0 ';
			$qs = '';

			// loop through filters sent by client
			if (is_array($filters)) {
				for ($i=0;$i<count($filters);$i++){
					$filter = $filters[$i];

					// assign filter data (location depends if encoded or not)
					if ($encoded) {
						if($filter->field == 'NIK')
							$field = "p.".$filter->field;
						elseif($filter->field == 'TANGGAL')
							$field = "p.".$filter->field;
						elseif($filter->field == 'SHIFTKE')
							$field = "t10.".$filter->field;
						else
							$field = $filter->field;
							
						$value = $filter->value;
						$compare = isset($filter->comparison) ? $filter->comparison : null;
						$filterType = $filter->type;
					} else {
						$field = $filter['field'];
						$value = $filter['data']['value'];
						$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
						$filterType = $filter['data']['type'];
					}

					switch($filterType){
						case 'string' : $qs .= " AND ".$field." LIKE '%".$value."%'"; Break;
						case 'list' :
							if (strstr($value,',')){
								$fi = explode(',',$value);
								for ($q=0;$q<count($fi);$q++){
									$fi[$q] = "'".$fi[$q]."'";
								}
								$value = implode(',',$fi);
								$qs .= " AND ".$field." IN (".$value.")";
							}else{
								$qs .= " AND ".$field." = '".$value."'";
							}
						Break;
						case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
						case 'numeric' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
								case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
								case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
							}
						Break;
						case 'date' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
								case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
								case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
							}
						Break;
						case 'datetime' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
								case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
								case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
							}
						Break;
					}
				}
				$where .= $qs;
			}
			
			
			$sql = "select p.ID,p.NIK, p.TANGGAL, k.NAMAKAR,u.NAMAUNIT, u.SINGKATAN, t10.NAMASHIFT, t10.SHIFTKE, t10.JAMDARI, t10.JAMSAMPAI, p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			from presensi p
			RIGHT JOIN 
			(
				select t1.TANGGAL, t1.NIK
				from 
				(
				select TANGGAL, NIK, count(*) as jml
				from presensi
				group by TANGGAL, NIK
				) as t1
				where t1.jml > 1
			) as t9
			on p.NIK=t9.NIK AND p.TANGGAL=t9.TANGGAL
			INNER JOIN karyawan k on k.NIK=p.NIK
			INNER JOIN unitkerja u on u.KODEUNIT=k.KODEUNIT
			LEFT JOIN 
			(
				select ks.KODESHIFT, ks.NIK, ps.SHIFTKE, sj.JENISHARI, ps.NAMASHIFT, sj.JAMDARI, sj.JAMSAMPAI, ps.TGLMULAI, ps.TGLSAMPAI
				from karyawanshift ks
				INNER JOIN pembagianshift ps on ps.KODESHIFT=ks.KODESHIFT
				INNER JOIN shiftjamkerja sj on sj.SHIFTKE=ps.SHIFTKE and sj.NAMASHIFT=ps.NAMASHIFT
				INNER JOIN detilshift ds on ds.NAMASHIFT=sj.NAMASHIFT and ds.SHIFTKE=ps.SHIFTKE
			) as t10 on t10.NIK=p.NIK and p.TANGGAL >= t10.TGLMULAI and p.TANGGAL <= t10.TGLSAMPAI
			WHERE ".$where;
			
			$sql .= " GROUP BY p.NIK,p.TANGGAL,p.TJMASUK,p.TJKELUAR ";
			$sql .= " ORDER BY ".$dsort;
			$sql .= " LIMIT ".$start.",".$limit;	
			$query = $this->db->query($sql);
			
			$total  = $this->db->query("select p.ID,COUNT(p.NIK)AS total, p.TANGGAL, k.NAMAKAR,u.NAMAUNIT, u.SINGKATAN, t10.NAMASHIFT, t10.SHIFTKE, t10.JAMDARI, t10.JAMSAMPAI, p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			from presensi p
			RIGHT JOIN 
			(
				select t1.TANGGAL, t1.NIK
				from 
				(
				select TANGGAL, NIK, count(*) as jml
				from presensi
				group by TANGGAL, NIK
				) as t1
				where t1.jml > 1
			) as t9
			on p.NIK=t9.NIK AND p.TANGGAL=t9.TANGGAL
			INNER JOIN karyawan k on k.NIK=p.NIK
			INNER JOIN unitkerja u on u.KODEUNIT=k.KODEUNIT
			LEFT JOIN 
			(
				select ks.KODESHIFT, ks.NIK, ps.SHIFTKE, sj.JENISHARI, ps.NAMASHIFT, sj.JAMDARI, sj.JAMSAMPAI, ps.TGLMULAI, ps.TGLSAMPAI
				from karyawanshift ks
				INNER JOIN pembagianshift ps on ps.KODESHIFT=ks.KODESHIFT
				INNER JOIN shiftjamkerja sj on sj.SHIFTKE=ps.SHIFTKE and sj.NAMASHIFT=ps.NAMASHIFT
				INNER JOIN detilshift ds on ds.NAMASHIFT=sj.NAMASHIFT and ds.SHIFTKE=ps.SHIFTKE
			) as t10 on t10.NIK=p.NIK and p.TANGGAL >= t10.TGLMULAI and p.TANGGAL <= t10.TGLSAMPAI
			WHERE ".$where)->result();
			
			$this->firephp->info($sql);
			
			$data   = array();
			foreach($query->result() as $result){
				$data[] = $result;
			}
			
			$json	= array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total[0]->total,
				'data'      => $data
			);
			
			return $json;
		}
		elseif($saring == "Salah Shift")
		{
			if (is_array($sorts)) {
				$encoded = false;
			} else {
				$encoded = true;
				$sorts = json_decode($sorts);
			}
			$dsort = ' p.NIK,p.TANGGAL ASC';
			$ks = '';
			
			if (is_array($sorts)) {
				for ($i=0;$i<count($sorts);$i++){
					$sort = $sorts[$i];

					// assign Sort data (location depends if encoded or not)
					if ($encoded) {
						if($sort->property == 'NIK')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'NAMAKAR')
							$prop = "k.".$sort->property;
						elseif($sort->property == 'NAMAUNIT')
							$prop = "uk.".$sort->property;
						elseif($sort->property == 'NAMAKEL')
							$prop = "kk.".$sort->property;
						else if($sort->property == 'TANGGAL')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'TJMASUK')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'TJKELUAR')
							$prop = "p.".$sort->property;
						else
							$prop = $sort->property;
						
						$dir = $sort->direction;					
					} else {
						$prop = $sort['property'];
						$dir = $sort['direction'];
					}
					$ks .= ",".$prop." ".$dir;
				}
				$dsort .= $ks;
			}
			//$this->firephp->info($dsort);

			// GridFilters sends filters as an Array if not json encoded
			if (is_array($filters)) {
				$encoded = false;
			} else {
				$encoded = true;
				$filters = json_decode($filters);
			}

			$where = ' (TIMESTAMPDIFF(MINUTE,TIMESTAMP(p.TANGGAL,t10.JAMDARI),p.TJMASUK) >= 300) ';
			$qs = '';

			// loop through filters sent by client
			if (is_array($filters)) {
				for ($i=0;$i<count($filters);$i++){
					$filter = $filters[$i];

					// assign filter data (location depends if encoded or not)
					if ($encoded) {
						if($filter->field == 'NIK')
							$field = "p.".$filter->field;
						elseif($filter->field == 'TANGGAL')
							$field = "p.".$filter->field;
						elseif($filter->field == 'SHIFTKE')
							$field = "t10.".$filter->field;
						else
							$field = $filter->field;
							
						$value = $filter->value;
						$compare = isset($filter->comparison) ? $filter->comparison : null;
						$filterType = $filter->type;
					} else {
						$field = $filter['field'];
						$value = $filter['data']['value'];
						$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
						$filterType = $filter['data']['type'];
					}

					switch($filterType){
						case 'string' : $qs .= " AND ".$field." LIKE '%".$value."%'"; Break;
						case 'list' :
							if (strstr($value,',')){
								$fi = explode(',',$value);
								for ($q=0;$q<count($fi);$q++){
									$fi[$q] = "'".$fi[$q]."'";
								}
								$value = implode(',',$fi);
								$qs .= " AND ".$field." IN (".$value.")";
							}else{
								$qs .= " AND ".$field." = '".$value."'";
							}
						Break;
						case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
						case 'numeric' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
								case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
								case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
							}
						Break;
						case 'date' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
								case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
								case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
							}
						Break;
						case 'datetime' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
								case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
								case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
							}
						Break;
					}
				}
				$where .= $qs;
			}
			
			
			$sql = "select p.ID,p.NIK, p.TANGGAL, k.NAMAKAR,u.NAMAUNIT, u.SINGKATAN, t10.NAMASHIFT, t10.SHIFTKE, t10.JAMDARI, t10.JAMSAMPAI, p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			from presensi p
			INNER JOIN karyawan k on k.NIK=p.NIK
			INNER JOIN unitkerja u on u.KODEUNIT=k.KODEUNIT
			LEFT JOIN 
			(
				select ks.KODESHIFT, ks.NIK, ps.SHIFTKE, sj.JENISHARI, ps.NAMASHIFT, sj.JAMDARI, sj.JAMSAMPAI, ps.TGLMULAI, ps.TGLSAMPAI
				from karyawanshift ks
				INNER JOIN pembagianshift ps on ps.KODESHIFT=ks.KODESHIFT
				INNER JOIN shiftjamkerja sj on sj.SHIFTKE=ps.SHIFTKE and sj.NAMASHIFT=ps.NAMASHIFT
				INNER JOIN detilshift ds on ds.NAMASHIFT=sj.NAMASHIFT and ds.SHIFTKE=ps.SHIFTKE
			) as t10 on t10.NIK=p.NIK and p.TANGGAL >= t10.TGLMULAI and p.TANGGAL <= t10.TGLSAMPAI
			WHERE ".$where;
			
			$sql .= " GROUP BY p.NIK,p.TANGGAL,p.TJMASUK,p.TJKELUAR ";
			$sql .= " ORDER BY ".$dsort;
			$sql .= " LIMIT ".$start.",".$limit;	
			$query = $this->db->query($sql);
			
			$total  = $this->db->query("select p.ID,count(p.NIK)as total, p.TANGGAL, k.NAMAKAR,u.NAMAUNIT, u.SINGKATAN, t10.NAMASHIFT, t10.SHIFTKE, t10.JAMDARI, t10.JAMSAMPAI, p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			from presensi p
			INNER JOIN karyawan k on k.NIK=p.NIK
			INNER JOIN unitkerja u on u.KODEUNIT=k.KODEUNIT
			LEFT JOIN 
			(
				select ks.KODESHIFT, ks.NIK, ps.SHIFTKE, sj.JENISHARI, ps.NAMASHIFT, sj.JAMDARI, sj.JAMSAMPAI, ps.TGLMULAI, ps.TGLSAMPAI
				from karyawanshift ks
				INNER JOIN pembagianshift ps on ps.KODESHIFT=ks.KODESHIFT
				INNER JOIN shiftjamkerja sj on sj.SHIFTKE=ps.SHIFTKE and sj.NAMASHIFT=ps.NAMASHIFT
				INNER JOIN detilshift ds on ds.NAMASHIFT=sj.NAMASHIFT and ds.SHIFTKE=ps.SHIFTKE
			) as t10 on t10.NIK=p.NIK and p.TANGGAL >= t10.TGLMULAI and p.TANGGAL <= t10.TGLSAMPAI
			WHERE ".$where)->result();
			
			$data   = array();
			foreach($query->result() as $result){
				$data[] = $result;
			}
			
			$json	= array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total[0]->total,
				'data'      => $data
			);
			
			return $json;
		}
		elseif($saring == "Range" && $filters == null)
		{
			if (is_array($sorts)) {
				$encoded = false;
			} else {
				$encoded = true;
				$sorts = json_decode($sorts);
			}
			$dsort = ' p.NIK ASC';
			$ks = '';
			
			if (is_array($sorts)) {
				for ($i=0;$i<count($sorts);$i++){
					$sort = $sorts[$i];

					// assign Sort data (location depends if encoded or not)
					if ($encoded) {
						if($sort->property == 'NIK')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'NAMAKAR')
							$prop = "k.".$sort->property;
						elseif($sort->property == 'NAMAUNIT')
							$prop = "uk.".$sort->property;
						elseif($sort->property == 'NAMAKEL')
							$prop = "kk.".$sort->property;
						else if($sort->property == 'TANGGAL')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'TJMASUK')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'TJKELUAR')
							$prop = "p.".$sort->property;
						else
							$prop = $sort->property;
						
						$dir = $sort->direction;					
					} else {
						$prop = $sort['property'];
						$dir = $sort['direction'];
					}
					$ks .= ",".$prop." ".$dir;
				}
				$dsort .= $ks;
			}
			//$this->firephp->info($dsort);

			// GridFilters sends filters as an Array if not json encoded
			if (is_array($filters)) {
				$encoded = false;
			} else {
				$encoded = true;
				$filters = json_decode($filters);
			}

			$where = " (p.TANGGAL >= DATE('$tglmulai') AND p.TANGGAL <= DATE('$tglsampai')) ";
			$qs = '';

			// loop through filters sent by client
			if (is_array($filters)) {
				for ($i=0;$i<count($filters);$i++){
					$filter = $filters[$i];

					// assign filter data (location depends if encoded or not)
					if ($encoded) {
						if($filter->field == 'NIK')
							$field = "p.".$filter->field;
						elseif($filter->field == 'SHIFTKE')
							$field = "t1.".$filter->field;
						else
							$field = $filter->field;
							
						$value = $filter->value;
						$compare = isset($filter->comparison) ? $filter->comparison : null;
						$filterType = $filter->type;
					} else {
						$field = $filter['field'];
						$value = $filter['data']['value'];
						$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
						$filterType = $filter['data']['type'];
					}

					switch($filterType){
						case 'string' : $qs .= " AND ".$field." LIKE '%".$value."%'"; Break;
						case 'list' :
							if (strstr($value,',')){
								$fi = explode(',',$value);
								for ($q=0;$q<count($fi);$q++){
									$fi[$q] = "'".$fi[$q]."'";
								}
								$value = implode(',',$fi);
								$qs .= " AND ".$field." IN (".$value.")";
							}else{
								$qs .= " AND ".$field." = '".$value."'";
							}
						Break;
						case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
						case 'numeric' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
								case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
								case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
							}
						Break;
						case 'date' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
								case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
								case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
							}
						Break;
						case 'datetime' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
								case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
								case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
							}
						Break;
					}
				}
				$where .= $qs;
			}
			
			$sql = "SELECT p.ID,p.NIK, k.NAMAKAR,uk.NAMAUNIT,uk.SINGKATAN, p.TANGGAL, p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			WHERE ".$where;
			$sql .= " ORDER BY ".$dsort;
			$sql .= " LIMIT ".$start.",".$limit;
			$query = $this->db->query($sql)->result();
			
			$total  = $this->db->query("SELECT p.ID,count(p.NIK) as total, k.NAMAKAR,uk.NAMAUNIT,uk.SINGKATAN, p.TANGGAL, p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			WHERE ".$where)->result();
			
			$data   = array();
			foreach($query as $result){
				$data[] = $result;
			}
			
			$json	= array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total[0]->total,
				'data'      => $data
			);
			
			return $json;
		}
		else
		{			
			if (is_array($sorts)) {
				$encoded = false;
			} else {
				$encoded = true;
				$sorts = json_decode($sorts);
			}
			$dsort = ' p.NIK ASC';
			$ks = '';
			
			if (is_array($sorts)) {
				for ($i=0;$i<count($sorts);$i++){
					$sort = $sorts[$i];

					// assign Sort data (location depends if encoded or not)
					if ($encoded) {
						if($sort->property == 'NIK')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'NAMAKAR')
							$prop = "k.".$sort->property;
						elseif($sort->property == 'NAMAUNIT')
							$prop = "uk.".$sort->property;
						elseif($sort->property == 'NAMAKEL')
							$prop = "kk.".$sort->property;
						else if($sort->property == 'TANGGAL')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'TJMASUK')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'TJKELUAR')
							$prop = "p.".$sort->property;
						else
							$prop = $sort->property;
						
						$dir = $sort->direction;					
					} else {
						$prop = $sort['property'];
						$dir = $sort['direction'];
					}
					$ks .= ",".$prop." ".$dir;
				}
				$dsort .= $ks;
			}
			//$this->firephp->info($dsort);

			// GridFilters sends filters as an Array if not json encoded
			if (is_array($filters)) {
				$encoded = false;
			} else {
				$encoded = true;
				$filters = json_decode($filters);
			}

			$where = ' 0 = 0 ';
			$qs = '';

			// loop through filters sent by client
			if (is_array($filters)) {
				for ($i=0;$i<count($filters);$i++){
					$filter = $filters[$i];

					// assign filter data (location depends if encoded or not)
					if ($encoded) {
						if($filter->field == 'NIK')
							$field = "p.".$filter->field;
						elseif($filter->field == 'SHIFTKE')
							$field = "t1.".$filter->field;
						else
							$field = $filter->field;
							
						$value = $filter->value;
						$compare = isset($filter->comparison) ? $filter->comparison : null;
						$filterType = $filter->type;
					} else {
						$field = $filter['field'];
						$value = $filter['data']['value'];
						$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
						$filterType = $filter['data']['type'];
					}

					switch($filterType){
						case 'string' : $qs .= " AND ".$field." LIKE '%".$value."%'"; Break;
						case 'list' :
							if (strstr($value,',')){
								$fi = explode(',',$value);
								for ($q=0;$q<count($fi);$q++){
									$fi[$q] = "'".$fi[$q]."'";
								}
								$value = implode(',',$fi);
								$qs .= " AND ".$field." IN (".$value.")";
							}else{
								$qs .= " AND ".$field." = '".$value."'";
							}
						Break;
						case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
						case 'numeric' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
								case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
								case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
							}
						Break;
						case 'date' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
								case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
								case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
							}
						Break;
						case 'datetime' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
								case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
								case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
							}
						Break;
					}
				}
				$where .= $qs;
			}
			
			$sql = "SELECT p.ID,p.NIK, k.NAMAKAR,uk.NAMAUNIT,uk.SINGKATAN,kk.NAMAKEL, p.TANGGAL,t1.NAMASHIFT,t1.SHIFTKE,
			t1.JAMDARI,t1.JAMSAMPAI,ts.SHIFTKE2,p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=uk.KODEKEL
			LEFT JOIN (
			SELECT ks.KODESHIFT,ks.NIK,ps.NAMASHIFT,PS.SHIFTKE,ps.TGLMULAI,PS.TGLSAMPAI,sjk.JAMDARI,sjk.JAMSAMPAI,sjk.JENISHARI,ps.MAKAN
			FROM karyawanshift ks 
			INNER JOIN pembagianshift ps ON ps.KODESHIFT=ks.KODESHIFT
			INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=ps.NAMASHIFT AND sjk.SHIFTKE=ps.SHIFTKE
			) AS t1 ON t1.NIK=p.NIK AND t1.JENISHARI=(IF(DAYNAME(p.TANGGAL) = 'Friday','J','N'))
			LEFT JOIN tukarshift ts ON ts.NIK = p.NIK AND p.TANGGAL >= ts.TGLMULAI AND p.TANGGAL <= ts.TGLSAMPAI
			WHERE ".$where;
			
			//$sql .= " ORDER BY k.NAMAKAR ASC,p.TANGGAL ASC";
			//$sql .= " ORDER BY ".$sortProperty." ".$sortDirection."";
			$sql .= " GROUP BY p.NIK,p.TANGGAL,p.TJMASUK,p.TJKELUAR ";
			$sql .= " ORDER BY ".$dsort;
			$sql .= " LIMIT ".$start.",".$limit;
			$query = $this->db->query($sql)->result();
			//$total = $query->num_rows();
			
			$total  = $this->db->query("SELECT p.ID, COUNT(p.NIK) AS total, k.NAMAKAR,uk.NAMAUNIT,uk.SINGKATAN,kk.NAMAKEL, p.TANGGAL,t1.NAMASHIFT,t1.SHIFTKE,
			t1.JAMDARI,t1.JAMSAMPAI,ts.SHIFTKE2,p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=uk.KODEKEL
			LEFT JOIN (
			SELECT ks.KODESHIFT,ks.NIK,ps.NAMASHIFT,PS.SHIFTKE,ps.TGLMULAI,PS.TGLSAMPAI,sjk.JAMDARI,sjk.JAMSAMPAI,sjk.JENISHARI,ps.MAKAN
			FROM karyawanshift ks 
			INNER JOIN pembagianshift ps ON ps.KODESHIFT=ks.KODESHIFT
			INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=ps.NAMASHIFT AND sjk.SHIFTKE=ps.SHIFTKE
			) AS t1 ON t1.NIK=p.NIK AND t1.JENISHARI=(IF(DAYNAME(p.TANGGAL) = 'Friday','J','N'))
			LEFT JOIN tukarshift ts ON ts.NIK = p.NIK AND p.TANGGAL >= ts.TGLMULAI AND p.TANGGAL <= ts.TGLSAMPAI
			WHERE ".$where)->result();
			
			$data   = array();
			foreach($query as $result){
				$data[] = $result;
			}
			//$this->firephp->info($sql);
			$json	= array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total[0]->total,
				//'total'     => $total,
				'data'      => $data
			);
			
			return $json;
		}
	}
}
?>