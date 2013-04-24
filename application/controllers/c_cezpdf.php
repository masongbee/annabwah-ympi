<?php
class C_cezpdf extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('Cezpdf');
	}
	
	function index()
	{
		$pdf = new Cezpdf();
		$pdf->selectFont('./assets/fonts/Helvetica');
		//$pdf->ezText('Hello World!',50);
		$data = array(
						array('num'=>1,'name'=>'gandalf','type'=>'wizard')
						,array('num'=>2,'name'=>'bilbo','type'=>'hobbit','url'=>'http://www.ros.co.nz/pdf/')
						,array('num'=>3,'name'=>'frodo','type'=>'hobbit')
						,array('num'=>4,'name'=>'saruman','type'=>'baddude','url'=>'http://sourceforge.net/projects/pdf-php')
						,array('num'=>5,'name'=>'sauron','type'=>'really bad dude')
		);
		$pdf->ezTable($data);
		$pdf->ezStream(array('download'=>1));
	}
	
}
?>