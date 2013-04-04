<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ELibDB library
 *
 * @author	Eko Junaidi Salam
 */
class Elibdb{
	var $CI = NULL;
	function __construct()
	{
		// get CI's object
		$this->CI =& get_instance();
	}
	
	// untuk store SQL dari File	
	function StoreSQL($file)
	{		
		//$dir = "./assets/upload/";
		//$file = $dir . $fname;
		$isi = read_file($file);
		
		if(sizeof($isi) > 0 and $isi != "")
		{
			$mysql_username = "ekojs";
			$mysql_password = null;
			$mysql_database = "dbympi";
			//exec('%mysql% -u '.$mysql_username.' -p '.$mysql_password.' --database '.$mysql_database.' < ' . $file);
			exec('%mysql% -u '.$mysql_username.' --database '.$mysql_database.' < ' . $file);
			return 1;
		}
		else
			return 0;
	}
}
