<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_upahpokok
 * 
 * Table	: upahpokok
 *  
 * @author masongbee
 *
 */
class M_upahpokok extends CI_Model{

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
		//$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('upahpokok')->result();
		$query = "SELECT VALIDFROM
				,VALIDTO
				,NOURUT
				,STR_TO_DATE(CONCAT(BULANMULAI,'01'),'%Y%m%d') AS BULANMULAI
				,STR_TO_DATE(CONCAT(BULANSAMPAI,'01'),'%Y%m%d') AS BULANSAMPAI
				,NIK
				,upahpokok.GRADE
				,upahpokok.KODEJAB
				,RPUPAHPOKOK
				,USERNAME
			FROM upahpokok
			LEFT JOIN grade ON(grade.GRADE = upahpokok.GRADE)
			LEFT JOIN jabatan ON(jabatan.KODEJAB = upahpokok.KODEJAB)";
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
					}elseif($filter_row->field == "GRADE"){
						$query .= "grade.".$filter_row->field." LIKE '%".$filter_row->value."%')";
					}elseif($filter_row->field == "KODEJAB"){
						$query .= "jabatan.".$filter_row->field." LIKE '%".$filter_row->value."%')";
					}elseif($filter_row->type == 'BULANMULAI' OR $filter_row->type == 'BULANSAMPAI'){
						$query .= "CAST(".$filter_row->field." AS UNSIGNED)".($filter_row->comparison == 'lt' ? " < " : ($filter_row->comparison == 'gt' ? " > " : " = ")).$filter_row->value;
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
					}elseif($filter_row->field == "GRADE"){
						$query .= "(grade.".$filter_row->field." LIKE '%".$filter_row->value."%')";
					}elseif($filter_row->field == "KODEJAB"){
						$query .= "jabatan.".$filter_row->field." LIKE '%".$filter_row->value."%')";
					}elseif($filter_row->type == 'BULANMULAI' OR $filter_row->type == 'BULANSAMPAI'){
						$query .= "CAST(".$filter_row->field." AS UNSIGNED)".($filter_row->comparison == 'lt' ? " < " : ($filter_row->comparison == 'gt' ? " > " : " = ")).$filter_row->value;
					}elseif($filter_row->type == 'numeric'){
						$query .= "(".$filter_row->field.($filter_row->comparison == 'lt' ? " < " : ($filter_row->comparison == 'gt' ? " > " : " = ")).$filter_row->value.")";
					}else{
						$query .= "(".$filter_row->field." LIKE '%".$filter_row->value."%')";
					}
					
				}
			}
			
		}
		$query .= " ORDER BY VALIDFROM, NOURUT
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('upahpokok')->num_rows();
		
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
		
		$pkey = array('VALIDFROM'=>date('Y-m-d', strtotime($data->VALIDFROM)),'NOURUT'=>$data->NOURUT);
		
		if($this->db->get_where('upahpokok', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL),
				'BULANMULAI'=>date('Ym', strtotime($data->BULANMULAI)),
				'BULANSAMPAI'=>date('Ym', strtotime($data->BULANSAMPAI)),
				'NIK'=>(trim($data->NIK) == '' ? NULL : $data->NIK),
				'GRADE'=>(trim($data->GRADE) == '' ? NULL : $data->GRADE),
				'KODEJAB'=>(trim($data->KODEJAB) == '' ? NULL : $data->KODEJAB),
				'RPUPAHPOKOK'=>(trim($data->RPUPAHPOKOK) == '' ? 0 : $data->RPUPAHPOKOK),
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->where($pkey)->update('upahpokok', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$nourut_last = $this->db->select('COUNT(*) AS total')->where('VALIDFROM', date('Y-m-d', strtotime($data->VALIDFROM)))->get('upahpokok')->row();
			$nourut = $nourut_last->total + 1;
			
			$arrdatac = array(
				'VALIDFROM'=>(strlen(trim($data->VALIDFROM)) > 0 ? date('Y-m-d', strtotime($data->VALIDFROM)) : NULL),
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL),
				'NOURUT'=>$nourut,
				'BULANMULAI'=>date('Ym', strtotime($data->BULANMULAI)),
				'BULANSAMPAI'=>date('Ym', strtotime($data->BULANSAMPAI)),
				'NIK'=>(trim($data->NIK) == '' ? NULL : $data->NIK),
				'GRADE'=>(trim($data->GRADE) == '' ? NULL : $data->GRADE),
				'KODEJAB'=>(trim($data->KODEJAB) == '' ? NULL : $data->KODEJAB),
				'RPUPAHPOKOK'=>(trim($data->RPUPAHPOKOK) == '' ? 0 : $data->RPUPAHPOKOK),
				'USERNAME'=>$data->USERNAME
			);
			 
			$this->db->insert('upahpokok', $arrdatac);
			$last   = $this->db->where($pkey)->get('upahpokok')->row();
			
		}
		
		$total  = $this->db->get('upahpokok')->num_rows();
		
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
		$pkey = array('VALIDFROM'=>date('Y-m-d', strtotime($data->VALIDFROM)),'NOURUT'=>$data->NOURUT);
		
		$this->db->where($pkey)->delete('upahpokok');
		
		$total  = $this->db->get('upahpokok')->num_rows();
		$last = $this->db->get('upahpokok')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						"total"     => $total,
						"data"      => $last
		);				
		return $json;
	}
	
	/**
	 * Fungsi	: do_upload
	 *
	 * Untuk menginjeksi data dari Excel ke Database
	 *
	 * @param array $data
	 * @return array
	 */
	function do_upload($data, $filename){
		if(sizeof($data) > 0){
			$p = 0;
			foreach($data->getWorksheetIterator() as $worksheet){
				if($p>0){
					break;
				}
				
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				$skeepdata = 0;
				for ($row = 1; $row <= $highestRow; ++ $row) {
					if($row>1){
						for ($col = 0; $col < $highestColumnIndex; ++ $col) {
							//$validfrom = PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCellByColumnAndRow(0, $row)->getValue());
							$validfrom = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(0, $row)->getValue(), 'yyyy-mm-dd');
							$nourut = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
							$validto = ($worksheet->getCellByColumnAndRow(2, $row)->getValue() == ''? NULL : PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
							$bulanmulai = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
							$bulansampai = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
							$nik = (trim($worksheet->getCellByColumnAndRow(5, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(5, $row)->getValue()));
							$grade = (trim($worksheet->getCellByColumnAndRow(6, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(6, $row)->getValue()));
							$kodejab = (trim($worksheet->getCellByColumnAndRow(7, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(7, $row)->getValue()));
							$rpupahpokok = ($worksheet->getCellByColumnAndRow(8, $row)->getValue() == ''? 0 : $worksheet->getCellByColumnAndRow(8, $row)->getValue());
						}
						
						$data = array(
							'VALIDFROM' => $validfrom,
							'NOURUT' => $nourut,
							'VALIDTO' => $validto,
							'BULANMULAI' => $bulanmulai,
							'BULANSAMPAI' => $bulansampai,
							'NIK' => $nik,
							'GRADE' => $grade,
							'KODEJAB' => $kodejab,
							'RPUPAHPOKOK' => $rpupahpokok
						);
						if($this->db->get_where('upahpokok', array('VALIDFROM'=>date('Y-m-d', strtotime($validfrom)),'NOURUT'=>$nourut))->num_rows() == 0){
							$this->db->insert('upahpokok', $data);
						}else{
							$skeepdata++;
						}
						
					}
				}
				
				$p++;
			}
			
			$success = array(
				'success'	=> true,
				'msg'		=> 'Data telah berhasil ditambahkan.',
				'filename'	=> $filename,
				'skeepdata'	=> $skeepdata
			);
			return $success;
		}else{
			$error = array(
				'success'	=> false,
				'msg'		=> 'Tidak ada proses, karena data kosong.',
				'filename'	=> $filename
			);
			return $error;
		}
	}
}
?>