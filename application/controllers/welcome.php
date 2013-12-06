<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		//$this->load->view('welcome_message');
		/*
		
		$path = './application'; //ini adalah path application CI
		$nfile = 'mohoncuti'; // ini adalah namafile bisa berdasar nama tabel
		$tbl = 'PERMOHONANCUTI';   // ini adalah nama tabel
		$data['fields'] = $this->db->field_data('PERMOHONANCUTI');
		$data['pathjs'] = 'TRANSAKSI';		//ini adalah nama path View misal : Master,Proses,Aksess,dll
		//$this->egen->SingleGrid($path,$nfile,$tbl,$data);
		//$this->egen->SingleGridSF($path,$nfile,$tbl,$data);
		
		$key = array();
		foreach($data['fields'] as $val)
		{
			if($val->primary_key == "1")
			{
				$key[$val->name] = $val->name;
			}
			echo $val->name . "<br />";
		}
		var_dump($key);*/
		echo date('Y-m-d H:i:s');
		//$n = new DateTime('2013-10-18');
		//$m = new DateTime('2013-10-21');
		//$rs = $n->diff($m);
		echo "<br /><br />";
		//echo $rs->format('%d');
		
		//echo $this->auth->initialization()->MAX_KAR;
		//echo $this->auth->gid('admlembur');
		//$this->ImporPresensi('2013-10-01','2013-10-31');
		
		echo 'Preparing Data... \nPlease Wait...';
		
		/*$t = 60007;
		$n = 500;
		$p = intval($t/$n);
		
		echo $p."<br /><br />";
		
		$cnt = $n;
		$j=1;
		for($i=0;$i<=$t;$i++){
			if($i == $cnt){
				if($j<=$p){
					echo $j." ".$cnt."<br />";
					$cnt = $cnt + $n;
					$j++;
				}
			}
		}*/
		
	}
	
	function ImporPresensi($tglmulai,$tglsampai)
	{
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
		
		$sqld = "DELETE FROM absensi
			WHERE trans_pengenal NOT IN (SELECT NIK FROM karyawan WHERE (STATUS='T' OR STATUS='K' OR STATUS='C'))";
		$this->db->query($sqld);
		
		//echo "Sukses";
		
		/*Prosedur Import Presensi Page 8
		A      = 1 REC (MASUK, TANPA KELUAR) (tergantung data berikutnya)
		A -> B = 1 REC (KELUAR TERISI -> NORMAL) (proses sempurna)
		A -> A = 2 REC (TANPA KELUAR) (rec ke-2 tergantung data berikutnya)
		B      = 1 REC (KELUAR, TANPA MASUK) (tak tergantung data berikutnya)*/
		
		$ketemuA = false;
		$ketemuB = false;
		$range = $this->db->query("SELECT VALUE FROM INIT WHERE PARAMETER = 'Range'")->result();
		
		$sql = "SELECT a.id,a.trans_pengenal,a.trans_tgl,a.trans_jam,a.trans_status,a.trans_log
		FROM absensi a
		INNER JOIN karyawan k ON k.NIK=a.trans_pengenal
		WHERE (a.trans_tgl >= DATE('$tglmulai') AND a.trans_tgl <= DATE('$tglsampai')) AND (k.STATUS='T' OR k.STATUS='K' OR k.STATUS='C') AND a.import='0'
		order by a.trans_pengenal, a.trans_tgl, a.trans_jam, a.trans_status";
		$query_abs = $this->db->query($sql)->result();
		
		// Data Presensi --> NIK, TJMASUK, TANGGAL, TJKELUAR, ASALDATA, ABSENSI_ID, NAMASHIFT, SHIFTKE
		$namashift = $this->db->query("SELECT *
		FROM shift
		WHERE (VALIDFROM <= DATE('$tglmulai') AND VALIDTO >= DATE('$tglsampai'))")->result();
		
		$data = array();
		$absensi = array();
		
		$data_prev = new StdClass();
		$data_prev->NIK = NULL;
		$data_prev->TANGGAL = NULL;
		$data_prev->NAMASHIFT = $namashift[0]->NAMASHIFT;
		$data_prev->SHIFTKE = NULL;
		$data_prev->TJMASUK = NULL;
		$data_prev->TJKELUAR = NULL;
		$data_prev->ASALDATA = 'D';
		$data_prev->USERNAME = 'Admin';
		$data_prev->ABSENSI_ID = NULL;
		
		$data_next = new StdClass();
		$data_next->NIK = NULL;
		$data_next->TANGGAL = NULL;
		$data_next->NAMASHIFT = $namashift[0]->NAMASHIFT;
		$data_next->SHIFTKE = NULL;
		$data_next->TJMASUK = NULL;
		$data_next->TJKELUAR = NULL;
		$data_next->ASALDATA = 'D';
		$data_next->USERNAME = 'Admin';
		$data_next->ABSENSI_ID = NULL;
		
		$cnt = 0;
		foreach($query_abs as $val)
		{
			echo $val->id." ".$val->trans_pengenal." ".$val->trans_tgl." ".$val->trans_jam." ".$val->trans_status."<br />";
			
			$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
			sj.JAMDARI,sj.JAMSAMPAI,
			((DATE_SUB(STR_TO_DATE(CONCAT('".$val->trans_tgl."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
			((DATE_ADD(STR_TO_DATE(CONCAT('".$val->trans_tgl."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AKHIR,
			((DATE_SUB(STR_TO_DATE(CONCAT('".$val->trans_tgl."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
			((DATE_ADD(STR_TO_DATE(CONCAT('".$val->trans_tgl."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
			FROM shift s
			RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
			WHERE (s.VALIDFROM <= DATE('".$val->trans_tgl."') AND s.VALIDTO >= DATE('".$val->trans_tgl."')) AND
			(TIMESTAMP('".$val->trans_tgl."','".$val->trans_jam."') >= ((DATE_SUB(STR_TO_DATE(CONCAT('".$val->trans_tgl."',' ',".($val->trans_status == 'A' ? "sj.JAMDARI" : "sj.JAMSAMPAI")."),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AND
			TIMESTAMP('".$val->trans_tgl."','".$val->trans_jam."') <= ((DATE_ADD(STR_TO_DATE(CONCAT('".$val->trans_tgl."',' ',".($val->trans_status == 'A' ? "sj.JAMDARI" : "sj.JAMSAMPAI")."),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR)))) AND sJ.JENISHARI=IF(DAYNAME('".$val->trans_tgl."')='Friday','J','N')";
			$hasil = $this->db->query($sqlshift)->result();
			
			$jda = (isset($hasil[0]->JAMDARI_AWAL) ? $hasil[0]->JAMDARI_AWAL: NULL);
			$jdk = (isset($hasil[0]->JAMDARI_AKHIR) ? $hasil[0]->JAMDARI_AKHIR: NULL);
			$jsa = (isset($hasil[0]->JAMSAMPAI_AWAL) ? $hasil[0]->JAMSAMPAI_AWAL: NULL);
			$jsk = (isset($hasil[0]->JAMSAMPAI_AKHIR) ? $hasil[0]->JAMSAMPAI_AKHIR: NULL);
			$ns = (isset($hasil[0]->NAMASHIFT) ? $hasil[0]->NAMASHIFT: NULL);
			$s = (isset($hasil[0]->SHIFTKE) ? $hasil[0]->SHIFTKE: 3);
			
			$tjmasuk = ($val->trans_status == 'A' ? date('Y-m-d H:i:s', strtotime($val->trans_tgl." ".$val->trans_jam)) : NULL);
			$tjkeluar = ($val->trans_status == 'B' ? date('Y-m-d H:i:s', strtotime($val->trans_tgl." ".$val->trans_jam)) : NULL);
			//$namashift = (($tjmasuk >= $jda && $tjmasuk <= $jdk) || ($tjkeluar >= $jsa && $tjkeluar <= $jsk)? $ns: NULL);
			$shiftke = (($tjmasuk >= $jda && $tjmasuk <= $jdk) || ($tjkeluar >= $jsa && $tjkeluar <= $jsk)? $s: '3');
			
			$data_next->NIK = $val->trans_pengenal;
			$data_next->TANGGAL = $val->trans_tgl;
			//$data_next->NAMASHIFT = $namashift;
			$data_next->SHIFTKE = $shiftke;
			$data_next->TJMASUK = $tjmasuk;
			$data_next->TJKELUAR = $tjkeluar;
			
			if(!$ketemuA && !$ketemuB && $val->trans_status == "A")
			{
				//Record Baru A
				$data_prev->NIK = $val->trans_pengenal;
				$data_prev->TANGGAL = $val->trans_tgl;
				//$data_prev->NAMASHIFT = $namashift;
				$data_prev->SHIFTKE = $shiftke;
				$data_prev->TJMASUK = date('Y-m-d H:i:s', strtotime($val->trans_tgl." ".$val->trans_jam));
				$data_prev->TJKELUAR = NULL;
				
				array_push($absensi,array('id'=>$val->id,'import'=>1));
				//$this->db->where(array('id'=>$val->id))->update('absensi', array('import'=>1));
				
				$ketemuA = true;
				$ketemuB = false;
			}
			elseif($ketemuA && $val->trans_status == "B")
			{
				//Update Record A->B jika NIK sama
				if($data_prev->NIK == $data_next->NIK)
				{
					$data_prev->TJMASUK = $data_prev->TJMASUK;
					$data_prev->TJKELUAR = $data_next->TJKELUAR;
					
					echo "Data Presensi  -->  ";
					echo $data_prev->NAMASHIFT.$data_prev->SHIFTKE." ".$data_prev->TANGGAL." ".$data_prev->NIK." TJMASUK : <strong>".$data_prev->TJMASUK."</strong> TJKELUAR : <strong>".$data_prev->TJKELUAR."</strong><br /><br />";
					
					array_push($data,(array) $data_prev);
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					//$this->insert($data_prev);
					//$this->db->insert('presensi', $data_prev);
					//$this->db->where(array('id'=>$val->id))->update('absensi', array('import'=>1));
					
					$ketemuA = false;
					$ketemuB = false;
				}
				else
				{
					//Insert Record A->B jika NIK berbeda
					echo "A->B NIK berbeda SIMPAN 2 REC : <br />";					
					echo $data_prev->NAMASHIFT." ".$data_prev->SHIFTKE." ".$data_prev->TANGGAL." ".$data_prev->NIK." TJMASUK : ".$data_prev->TJMASUK." TJKELUAR : ".$data_prev->TJKELUAR."  <-->  ";
					echo $data_next->NAMASHIFT." ".$data_next->SHIFTKE." ".$data_next->TANGGAL." ".$data_next->NIK." TJMASUK : ".$data_next->TJMASUK." TJKELUAR : ".$data_next->TJKELUAR."<br /><br />";
					
					//$data_prev->NIK = $data_next->NIK;
					//$data_prev->TANGGAL = $data_next->TANGGAL;
					//$data_prev->TJMASUK = NULL;
					//$data_prev->TJKELUAR = $data_next->TJKELUAR
					
					array_push($data,(array) $data_prev,(array) $data_next);
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					//$this->insert_batch($data_prev,$data_next);
					//$data = array((array) $data_prev,(array) $data_next);		
					//$this->db->insert_batch('presensi', $data);
					//$this->db->insert('presensi', $data_prev);
					//$this->db->insert('presensi', $data_next);
					//$this->db->where(array('id'=>$val->id))->update('absensi', array('import'=>1));
					
					$ketemuA = false;
					$ketemuB = false;
				}
			}
			elseif($ketemuA && $val->trans_status == "A")
			{
				//Record Baru A->A
				if($data_prev->NIK != $data_next->NIK)
				{
					echo "A->A NIK berbeda    ";
					echo $data_prev->TANGGAL." ".$data_prev->NIK." TJMASUK : ".$data_prev->TJMASUK." TJKELUAR : ".$data_prev->TJKELUAR."<br /><br />";
					
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					//$this->db->where(array('id'=>$val->id))->update('absensi', array('import'=>1));
					
					$data_prev->NIK = $data_next->NIK;
					$data_prev->TANGGAL = $data_next->TANGGAL;
					//$data_prev->NAMASHIFT = $data_next->NAMASHIFT;
					$data_prev->SHIFTKE = $data_next->SHIFTKE;
					$data_prev->TJMASUK = $data_next->TJMASUK;
					$data_prev->TJKELUAR = NULL;
					
					$ketemuA = true;
					$ketemuB = false;
				}
				else
				{
					echo "A->A NIK SAMA SIMPAN 2 REC : <br />";
					echo $data_prev->NAMASHIFT." ".$data_prev->SHIFTKE." ".$data_prev->TANGGAL." ".$data_prev->NIK." TJMASUK : ".$data_prev->TJMASUK." TJKELUAR : ".$data_prev->TJKELUAR."  <-->  ";
					echo $data_next->NAMASHIFT." ".$data_next->SHIFTKE." ".$data_next->TANGGAL." ".$data_next->NIK." TJMASUK : ".$data_next->TJMASUK." TJKELUAR : ".$data_next->TJKELUAR."<br /><br />";
					
					array_push($data,(array) $data_prev,(array) $data_next);
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					//$this->insert_batch($data_prev,$data_next);
					//$data = array((array) $data_prev,(array) $data_next);		
					//$this->db->insert_batch('presensi', $data);
					//$this->db->insert('presensi', $data_prev);
					//$this->db->insert('presensi', $data_next);
					//$this->db->where(array('id'=>$val->id))->update('absensi', array('import'=>1));
					
					$data_prev->NIK = $data_next->NIK;
					$data_prev->TANGGAL = $data_next->TANGGAL;
					//$data_prev->NAMASHIFT = $data_next->NAMASHIFT;
					$data_prev->SHIFTKE = $data_next->SHIFTKE;
					$data_prev->TJMASUK = $data_next->TJMASUK;
					//$data_prev->TANGGAL = ($data_prev->TJMASUK != $data_next->TJMASUK ? $data_prev->TANGGAL : $data_next->TANGGAL);
					//$data_prev->TJMASUK = ($data_prev->TJMASUK != $data_next->TJMASUK ? $data_prev->TJMASUK : $data_next->TJMASUK);
					$data_prev->TJKELUAR = NULL;
					
					$ketemuA = true;
					$ketemuB = false;
				}
			}
			elseif(!$ketemuA && !$ketemuB && $val->trans_status == "B")
			{
				//Record Baru B
				$data_prev->NIK = $val->trans_pengenal;
				$data_prev->TANGGAL = $val->trans_tgl;
				//$data_prev->NAMASHIFT = $namashift;
				$data_prev->SHIFTKE = $shiftke;
				$data_prev->TJMASUK = NULL;
				$data_prev->TJKELUAR = date('Y-m-d H:i:s', strtotime($val->trans_tgl." ".$val->trans_jam));
				
				array_push($absensi,array('id'=>$val->id,'import'=>1));
				//$this->db->where(array('id'=>$val->id))->update('absensi', array('import'=>1));
				
				$ketemuA = false;
				$ketemuB = true;
			}
			elseif($ketemuB && $val->trans_status == "A")
			{
				//Record Baru B->A
				if($data_prev->NIK != $data_next->NIK)
				{
					echo "B->A NIK berbeda    ";
					echo $data_prev->TANGGAL." ".$data_prev->NIK." TJMASUK : ".$data_prev->TJMASUK." TJKELUAR : ".$data_prev->TJKELUAR."<br /><br />";
					
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					//$this->db->where(array('id'=>$val->id))->update('absensi', array('import'=>1));
					
					$data_prev->NIK = $data_next->NIK;
					$data_prev->TANGGAL = $data_next->TANGGAL;
					//$data_prev->NAMASHIFT = $data_next->NAMASHIFT;
					$data_prev->SHIFTKE = $data_next->SHIFTKE;
					$data_prev->TJMASUK = $data_next->TJMASUK;
					$data_prev->TJKELUAR = NULL;
					
					$ketemuA = true;
					$ketemuB = false;
				}
				else
				{
					echo $data_prev->NAMASHIFT." ".$data_prev->SHIFTKE." ".$data_prev->TANGGAL." ".$data_prev->NIK." TJMASUK : ".$data_prev->TJMASUK." TJKELUAR : ".$data_prev->TJKELUAR."  <-->  ";
					echo $data_next->NAMASHIFT." ".$data_next->SHIFTKE." ".$data_next->TANGGAL." ".$data_next->NIK." TJMASUK : ".$data_next->TJMASUK." TJKELUAR : ".$data_next->TJKELUAR."<br /><br />";
					
					array_push($data,(array) $data_prev,(array) $data_next);
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					//$this->insert_batch($data_prev,$data_next);
					//$data = array((array) $data_prev,(array) $data_next);		
					//$this->db->insert_batch('presensi', $data);
					//$this->db->insert('presensi', $data_prev);
					//$this->db->insert('presensi', $data_next);
					//$this->db->where(array('id'=>$val->id))->update('absensi', array('import'=>1));
					
					$ketemuA = false;
					$ketemuB = false;
				}
			}
			elseif($ketemuB && $val->trans_status == "B")
			{
				//Record Baru B->B
				if($data_prev->NIK != $data_next->NIK)
				{
					echo "B->B NIK berbeda SIMPAN 2 REC : <br />";
					echo $data_prev->NAMASHIFT." ".$data_prev->SHIFTKE." ".$data_prev->TANGGAL." ".$data_prev->NIK." TJMASUK : ".$data_prev->TJMASUK." TJKELUAR : ".$data_prev->TJKELUAR."  <-->  ";
					echo $data_next->NAMASHIFT." ".$data_next->SHIFTKE." ".$data_next->TANGGAL." ".$data_next->NIK." TJMASUK : ".$data_next->TJMASUK." TJKELUAR : ".$data_next->TJKELUAR."<br /><br />";
					
					//$data_prev->NIK = $data_next->NIK;
					//$data_prev->TANGGAL = $data_next->TANGGAL;
					//$data_prev->TJMASUK = NULL;
					//$data_prev->TJKELUAR = $data_next->TJKELUAR;
					
					array_push($data,(array) $data_prev,(array) $data_next);
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					//$this->insert_batch($data_prev,$data_next);
					//$data = array((array) $data_prev,(array) $data_next);		
					//$this->db->insert_batch('presensi', $data);
					//$this->db->insert('presensi', $data_prev);
					//$this->db->insert('presensi', $data_next);
					//$this->db->where(array('id'=>$val->id))->update('absensi', array('import'=>1));
					
					$ketemuA = false;
					$ketemuB = false;
				}
				else
				{
					echo $data_prev->NAMASHIFT." ".$data_prev->SHIFTKE." ".$data_prev->TANGGAL." ".$data_prev->NIK." TJMASUK : ".$data_prev->TJMASUK." TJKELUAR : ".$data_prev->TJKELUAR."  <-->  ";
					echo $data_next->NAMASHIFT." ".$data_next->SHIFTKE." ".$data_next->TANGGAL." ".$data_next->NIK." TJMASUK : ".$data_next->TJMASUK." TJKELUAR : ".$data_next->TJKELUAR."<br /><br />";
					
					array_push($data,(array) $data_prev,(array) $data_next);
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					//$this->insert_batch($data_prev,$data_next);
					//$data = array((array) $data_prev,(array) $data_next);		
					//$this->db->insert_batch('presensi', $data);
					//$this->db->insert('presensi', $data_prev);
					//$this->db->insert('presensi', $data_next);
					//$this->db->where(array('id'=>$val->id))->update('absensi', array('import'=>1));
					
					$ketemuA = false;
					$ketemuB = false;
				}
			}
			$cnt ++;
		}
		echo "Sukses : ".$cnt;
		var_dump($data);
		echo "<br /><br />";
		var_dump($absensi);
		//$this->db->update_batch('absensi', $absensi, 'id');
		//$this->db->insert_batch('presensi', $data);
	}
	
	
	function GenIjinCuti()
	{
		$rs = $this->db->get('tblabs')->result_array();
		$n = substr("A97070008",0,1);
		
		$sqlcuti = "SELECT MAX(NOCUTI) AS NOCUTI,NIKATASAN1,
		IF(ISNULL(MAX(NOCUTI)),'A000001',CONCAT(SUBSTR(NOCUTI,1,1), SUBSTR(CONCAT('000000',(SUBSTR(MAX(NOCUTI),2,8)+1)),-6))) AS GEN
		FROM permohonancuti
		WHERE NOCUTI LIKE '".$n."%';";
		$rscuti = $this->db->query($sqlcuti);
		$hcuti = $rscuti->result();
		$this->db->insert('permohonancuti', array(
			'NOCUTI'=>($rscuti->num_rows() > 0 && !(substr($hcuti[0]->NOCUTI,1,6) == '999999') ? $hcuti[0]->GEN : $hcuti[0]->GEN),
			'KODEUNIT'=> NULL,
			'NIKATASAN1'=>NULL,
			'STATUSCUTI'=>'T',
			'NIKATASAN2'=>NULL,
			'NIKHR'=>"M09061339",
			'TGLATASAN1'=>NULL,
			'TGLATASAN2'=>NULL,
			'TGLHR'=>"2013-09-01",
			'USERNAME'=>"Admin"));
		$ncuti = $hcuti[0]->GEN;
		
		//$this->firephp->info($ncuti);
		
		foreach($rs as $v)
		{			
			echo $v["NIK"] ." ";
			for($i=1;$i<=30;$i++)
			{
				$sql = "SELECT MAX(NOIJIN) AS NOIJIN,NIKATASAN1,
				CONCAT(SUBSTR(NOIJIN,1,1),
				SUBSTR(CONCAT('000000',(SUBSTR(MAX(NOIJIN),2,6)+1)),-6)) AS GEN
				FROM permohonanijin
				WHERE NOIJIN LIKE '".$n."%';";
				$rs = $this->db->query($sql);
				$hasil = $rs->result();
				
				$scuti = "SELECT NOCUTI,MAX(NOURUT) AS NOURUT,NIK,
				IF(ISNULL(MAX(NOURUT)),1,MAX(NOURUT) + 1) AS GEN
				FROM rinciancuti
				WHERE NOCUTI='".$ncuti."';";
				$rcuti = $this->db->query($scuti);
				$hasilcuti = $rcuti->result();
				
				$pkey = array('NIK'=>$v["NIK"],'TANGGAL'=>"2013-09-".$i);
				$row = $this->db->get_where('permohonanijin', $pkey)->row();
				$rowcuti = $this->db->get_where('rinciancuti', array('NIK'=>$v["NIK"],'TGLMULAI'=>"2013-09-".$i))->row();
				
				if($v["D".$i] != null)
				{
					if($v["D".$i] == 'M')
					{
						echo "CL" . " ";
						if(sizeof($rowcuti) <= 0)
						{
							$arrdatac = array(
							'NOCUTI'=>$ncuti,
							'NOURUT'=>$hasilcuti[0]->GEN,
							'NIK'=>$v["NIK"],
							'JENISABSEN'=>"CL",
							'LAMA'=>"1",
							'TGLMULAI'=>"2013-09-".$i,
							'TGLSAMPAI'=>"2013-09-".$i,
							'SISACUTI'=>NULL,
							'ALASAN'=>NULL,
							'STATUSCUTI'=>"T");
							
							$this->db->insert('rinciancuti', $arrdatac);
						}
					}
					elseif($v["D".$i] == 'I')
					{
						echo "IZ" . " ";
						if(sizeof($row) <= 0){
							//Data Not Exist
							$noijin = ($rs->num_rows() > 0 && !(substr($hasil[0]->NOIJIN,1,6) == '999999') ? $hasil[0]->GEN : $rs2[0]->GEN);
							$noijin = ($noijin === NULL ? $n.'000001' : $noijin);
							$arrdatac = array(
								'NOIJIN'=>$noijin,
								'NIK'=>$v["NIK"],
								'JENISABSEN'=>"IZ",
								'TANGGAL'=>"2013-09-".$i,
								'AMBILCUTI'=>"1",
								'NIKATASAN1'=>null,
								'STATUSIJIN'=>'T',
								'NIKPERSONALIA'=>"M09061339",
								'USERNAME'=>"Admin"
							);
							$this->db->insert('permohonanijin', $arrdatac);
						}
					}
					elseif($v["D".$i] == 'Km')
					{
						echo "CM" . " ";
						if(sizeof($rowcuti) <= 0)
						{
							$arrdatac = array(
							'NOCUTI'=>$ncuti,
							'NOURUT'=>$hasilcuti[0]->GEN,
							'NIK'=>$v["NIK"],
							'JENISABSEN'=>"CM",
							'LAMA'=>"1",
							'TGLMULAI'=>"2013-09-".$i,
							'TGLSAMPAI'=>"2013-09-".$i,
							'SISACUTI'=>NULL,
							'ALASAN'=>NULL,
							'STATUSCUTI'=>"T");
							
							$this->db->insert('rinciancuti', $arrdatac);
						}
					}
					elseif($v["D".$i] == 'Im')
					{
						echo "CI" . " ";
						if(sizeof($rowcuti) <= 0)
						{
							$arrdatac = array(
							'NOCUTI'=>$ncuti,
							'NOURUT'=>$hasilcuti[0]->GEN,
							'NIK'=>$v["NIK"],
							'JENISABSEN'=>"CI",
							'LAMA'=>"1",
							'TGLMULAI'=>"2013-09-".$i,
							'TGLSAMPAI'=>"2013-09-".$i,
							'SISACUTI'=>NULL,
							'ALASAN'=>NULL,
							'STATUSCUTI'=>"T");
							
							$this->db->insert('rinciancuti', $arrdatac);
						}
					}
					elseif($v["D".$i] == 'A')
					{
						echo "AL" . " ";
					}
					elseif($v["D".$i] == 'K')
					{
						echo "CH" . " ";
						if(sizeof($rowcuti) <= 0)
						{
							$arrdatac = array(
							'NOCUTI'=>$ncuti,
							'NOURUT'=>$hasilcuti[0]->GEN,
							'NIK'=>$v["NIK"],
							'JENISABSEN'=>"CH",
							'LAMA'=>"1",
							'TGLMULAI'=>"2013-09-".$i,
							'TGLSAMPAI'=>"2013-09-".$i,
							'SISACUTI'=>NULL,
							'ALASAN'=>NULL,
							'STATUSCUTI'=>"T");
							
							$this->db->insert('rinciancuti', $arrdatac);
						}
					}
					elseif($v["D".$i] == 'N' || $v["D".$i] == 'Sn')
					{
						echo "CN" . " ";
						if(sizeof($rowcuti) <= 0)
						{
							$arrdatac = array(
							'NOCUTI'=>$ncuti,
							'NOURUT'=>$hasilcuti[0]->GEN,
							'NIK'=>$v["NIK"],
							'JENISABSEN'=>"CN",
							'LAMA'=>"1",
							'TGLMULAI'=>"2013-09-".$i,
							'TGLSAMPAI'=>"2013-09-".$i,
							'SISACUTI'=>NULL,
							'ALASAN'=>NULL,
							'STATUSCUTI'=>"T");
							
							$this->db->insert('rinciancuti', $arrdatac);
						}
					}
					elseif($v["D".$i] == 'S')
					{
						echo "SK" . " ";
						if(sizeof($row) <= 0){
							//Data Not Exist
							$noijin = ($rs->num_rows() > 0 && !(substr($hasil[0]->NOIJIN,1,6) == '999999') ? $hasil[0]->GEN : $rs2[0]->GEN);
							$noijin = ($noijin === NULL ? $n.'000001' : $noijin);
							$arrdatac = array(
								'NOIJIN'=>$noijin,
								'NIK'=>$v["NIK"],
								'JENISABSEN'=>"SK",
								'TANGGAL'=>"2013-09-".$i,
								'AMBILCUTI'=>"1",
								'NIKATASAN1'=>null,
								'STATUSIJIN'=>'T',
								'NIKPERSONALIA'=>"M09061339",
								'USERNAME'=>"Admin"
							);
							$this->db->insert('permohonanijin', $arrdatac);
						}
					}
					elseif($v["D".$i] == 'CT')
					{
						echo "CT" . " ";
						if(sizeof($rowcuti) <= 0)
						{
							$arrdatac = array(
							'NOCUTI'=>$ncuti,
							'NOURUT'=>$hasilcuti[0]->GEN,
							'NIK'=>$v["NIK"],
							'JENISABSEN'=>"CT",
							'LAMA'=>"1",
							'TGLMULAI'=>"2013-09-".$i,
							'TGLSAMPAI'=>"2013-09-".$i,
							'SISACUTI'=>NULL,
							'ALASAN'=>NULL,
							'STATUSCUTI'=>"T");
							
							$this->db->insert('rinciancuti', $arrdatac);
						}
					}
					elseif($v["D".$i] == 'SD')
					{
						echo "SD" . " ";
						if(sizeof($row) <= 0){
							//Data Not Exist
							$noijin = ($rs->num_rows() > 0 && !(substr($hasil[0]->NOIJIN,1,6) == '999999') ? $hasil[0]->GEN : $rs2[0]->GEN);
							$noijin = ($noijin === NULL ? $n.'000001' : $noijin);
							$arrdatac = array(
								'NOIJIN'=>$noijin,
								'NIK'=>$v["NIK"],
								'JENISABSEN'=>"SD",
								'TANGGAL'=>"2013-09-".$i,
								'AMBILCUTI'=>"3",
								'NIKATASAN1'=>null,
								'STATUSIJIN'=>'T',
								'NIKPERSONALIA'=>"M09061339",
								'USERNAME'=>"Admin"
							);
							$this->db->insert('permohonanijin', $arrdatac);
						}
					}
					//else
						//echo $v["D".$i] . " ";
				}
			}
			
			echo "<br />";
		}
	}
	
	function GenSG($pathjs="", $table="")
	{
		$path = './application'; //ini adalah path application CI
		$nfile = $table; // ini adalah namafile bisa berdasar nama tabel
		//$tbl = 'cutitahunan';   // ini adalah nama tabel
		$data['fields'] = $this->db->field_data($table);
		$data['pathjs'] = $pathjs;		//ini adalah nama path View misal : Master,Proses,Aksess,dll
		$this->egen->SingleGrid($path,$nfile,$table,$data);  // ini adalah eksekusi Utama Generator
	}
	
	function GenSGSF($pathjs="", $table="")
	{
		$path = './application'; //ini adalah path application CI
		$nfile = $table; // ini adalah namafile bisa berdasar nama tabel
		//$tbl = 'cutitahunan';   // ini adalah nama tabel
		$data['fields'] = $this->db->field_data($table);
		$data['pathjs'] = $pathjs;		//ini adalah nama path View misal : Master,Proses,Aksess,dll
		$this->egen->SingleGridSF($path,$nfile,$table,$data);  // ini adalah eksekusi Utama Generator
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */