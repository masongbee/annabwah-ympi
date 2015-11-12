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

	function getLemburPerBulan($bulan){
		$this->db->query("SET @sql = NULL");
		$this->db->query("SELECT
			  	GROUP_CONCAT(DISTINCT
			    	CONCAT(
			      		'MAX(IF(pa.fieldname = ''',
			      		fieldname,
			      		''', pa.fieldvalue, NULL)) AS ',
			      		fieldname
			    	)
			  	) INTO @sql
			FROM product_additional");
		$this->db->query("SET @sql = CONCAT('SELECT p.id
                , p.name
                , p.description, ', @sql, ' 
         	FROM product p
           	LEFT JOIN product_additional AS pa ON p.id = pa.id
         	GROUP BY p.id')");
		$this->db->query("PREPARE stmt FROM @sql");
		$result = $this->db->query("EXECUTE stmt")->result();
		$this->db->query("DEALLOCATE PREPARE stmt");
		return $result;
	}
}
?>