<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_rptpresensi
 * 
 * Table	: presensi
 *  
 * @author masongbee
 *
 */
class M_rptpresensi extends CI_Model{

	private $id1;
	private $id2;
	private $jam1;
	private $jam2;

	function __construct(){
		parent::__construct();
	}
	
	function getAllData($groups,$sorts,$filters,$start, $page, $limit)
	{
		if (is_array($groups)) {
			$encoded = false;
		} else {
			$encoded = true;
			$groups = json_decode($groups);
		}
		$dgroup = ' ';
		$kg = '';
		
		if (is_array($groups)) {
			for ($i=0;$i<count($groups);$i++){
				$group = $groups[$i];

				// assign group data (location depends if encoded or not)
				if ($encoded) {
					if($group->property == 'NIK')
						$prop = "p.".$group->property;
					elseif($group->property == 'NAMAKAR')
						$prop = "k.".$group->property;
					elseif($group->property == 'NAMAUNIT')
						$prop = "uk.".$group->property;
					elseif($group->property == 'NAMAKEL')
						$prop = "kk.".$group->property;
					else if($group->property == 'TANGGAL')
						$prop = "p.".$group->property;
					elseif($group->property == 'TJMASUK')
						$prop = "p.".$group->property;
					elseif($group->property == 'TJKELUAR')
						$prop = "p.".$group->property;
					else
						$prop = $group->property;
					
					$dir = $group->direction;					
				} else {
					$prop = $group['property'];
					$dir = $group['direction'];
				}
				$kg .= " GROUP BY ".$prop." ".$dir;
			}
			$dgroup .= $kg;
		}
		//$this->firephp->info($dsort);
	
		if (is_array($sorts)) {
			$encoded = false;
		} else {
			$encoded = true;
			$sorts = json_decode($sorts);
		}
		$dsort = ' p.SHIFTKE ASC';
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
					elseif($sort->property == 'SHIFTKE')
						$prop = "p.".$sort->property;
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
				$ks .= " ,".$prop." ".$dir;
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
						$field = "p.".$filter->field;
					elseif($filter->field == 'NAMAUNIT')
						$field = "uk.".$filter->field;
					elseif($filter->field == 'SINGKATAN')
						$field = "uk.".$filter->field;
					elseif($filter->field == 'NAMAKEL')
						$field = "kk.".$filter->field;
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
		
		$sql = "SELECT p.ID,p.NIK, k.NAMAKAR,uk.NAMAUNIT,uk.SINGKATAN,kk.NAMAKEL, p.TANGGAL,p.NAMASHIFT,p.SHIFTKE,p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
		FROM presensi p
		INNER JOIN karyawan k ON k.NIK=p.NIK
		INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
		INNER JOIN kelompok	kk ON kk.KODEKEL=uk.KODEKEL
		WHERE ".$where;
		
		//$sql .= " ORDER BY k.NAMAKAR ASC,p.TANGGAL ASC";
		//$sql .= " ORDER BY ".$sortProperty." ".$sortDirection."";
		//$sql .= " GROUP BY p.NIK,p.TANGGAL,p.TJMASUK,p.TJKELUAR ";
		//$sql .= $dgroup;
		//$sql .= $dsort;
		$sql .= " ORDER BY ".$dsort;
		$sql .= " LIMIT ".$start.",".$limit;
		$query = $this->db->query($sql)->result();
		//$total = $query->num_rows();
		
		$total  = $this->db->query("SELECT p.ID, COUNT(p.NIK) AS total, k.NAMAKAR,uk.NAMAUNIT,uk.SINGKATAN,kk.NAMAKEL, p.TANGGAL,p.NAMASHIFT,p.SHIFTKE, p.ASALDATA, p.POSTING, p.USERNAME
		FROM presensi p
		INNER JOIN karyawan k ON k.NIK=p.NIK
		INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
		INNER JOIN kelompok	kk ON kk.KODEKEL=uk.KODEKEL
		WHERE ".$where)->result();
		
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}
		//$this->firephp->info($sql);
		$json	= array(
			'success'   => TRUE,
			'message'   => 'Loaded data',
			'total'     => $total[0]->total,
			//'total'     => $total,
			'data'      => $data
		);
		
		return $json;
	}
}
?>