<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * EGen library
 *
 * @author	Eko Junaidi Salam 2013
 */
class Egen{
	var $CI = NULL;
	function __construct()
	{
		$this->CI =& get_instance();
	}	
	
	//----------------------------------------------------- Generator Single Grid ---------------------------------------------------------------//
	
	function SingleGrid($path,$nfile,$tbl,$data){
		$this->CController($path,$nfile,$tbl,$data);
		$this->CModel($path,$nfile,$tbl,$data);
		$this->CPrint($path,$nfile,$tbl,$data);
		$this->CPDF($path,$nfile,$tbl,$data);
		$this->CControllerExtjs($path,$nfile,$tbl,$data);
		$this->CModelExtjs($path,$nfile,$tbl,$data);
		$this->CStoreExtjs($path,$nfile,$tbl,$data);
		$this->CViewExtjs($path,$nfile,$tbl,$data);
		$this->CViewport($path,$nfile,$tbl,$data);
	}
	
	//Generate Controller CI
	function CController($path,$nfile,$tbl,$data){
		$tulis = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_".$nfile." extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		\$this->load->model('m_".$nfile."', '', TRUE);
	}
	
	function getAll(){
		/*
		 * Collect Data
		 */
		\$start  =   (\$this->input->post('start', TRUE) ? \$this->input->post('start', TRUE) : 0);
		\$page   =   (\$this->input->post('page', TRUE) ? \$this->input->post('page', TRUE) : 1);
		\$limit  =   (\$this->input->post('limit', TRUE) ? \$this->input->post('limit', TRUE) : 15);
		
		/*
		 * Processing Data
		 */
		\$result = \$this->m_".$nfile."->getAll(\$start, \$page, \$limit);
		echo json_encode(\$result);
	}
	
	function save(){
		/*
		 * Collect Data ==> diambil dari [model.".$nfile."]
		 */
		\$data   = json_decode(\$this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		\$result = \$this->m_".$nfile."->save(\$data);
		echo json_encode(\$result);
	}
	
	function delete(){
		/*
		 * Collect Data ==> diambil dari [model.".$nfile."]
		 */
		\$data   = json_decode(\$this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		\$result = \$this->m_".$nfile."->delete(\$data);
		echo json_encode(\$result);
	}
	
	/**
	 * Fungsi	: export2Excel
	 * 
	 * Untuk menyimpan data yang didapat dari Grid ExtJS ke dalam file Excel.
	 * Tidak lagi mengakses database untuk mendapatkan data.
	 */
	function export2Excel(){
		\$data = json_decode(\$this->input->post('data',TRUE));
		
		//load our new PHPExcel library
		\$this->load->library('excel');
		//activate worksheet number 1
		\$this->excel->setActiveSheetIndex(0);
		//name the worksheet
		\$this->excel->getActiveSheet()->setTitle('test worksheet');
		
		\$col = 0;
		foreach (\$data[0] as \$key => \$value){
			\$this->excel->getActiveSheet()->setCellValueByColumnAndRow(\$col, 1, \$key);
			\$this->excel->getActiveSheet()->getStyleByColumnAndRow(\$col, 1)->getFont()->setBold(true);
			\$col++;
		}
		
		// Fetching the table data
		\$row = 2;
		foreach(\$data as \$record)
		{
			\$col = ord(\"A\");
			foreach (\$data[0] as \$key => \$value)
			{
				\$cellvalue = \$record->\$key;
				
				if(\$key == strtoupper('".$nfile."')){
					\$this->excel->getActiveSheet()->getCell(chr(\$col).\$row)->setValueExplicit(\$cellvalue, PHPExcel_Cell_DataType::TYPE_STRING);
				}else{
					\$this->excel->getActiveSheet()->setCellValue(chr(\$col).\$row, \$cellvalue);
				}
				
				\$col++;
			}
		
			\$row++;
		}		
		
		\$filename='".$nfile.".xlsx'; //save our workbook as this file name
		//header('Content-Type: application/vnd.ms-excel'); //mime type for Excel5
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type for Excel2007
		header('Content-Disposition: attachment;filename=\"'.\$filename.'\"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		\$objWriter = PHPExcel_IOFactory::createWriter(\$this->excel, 'Excel2007');
		//force user to download the Excel file without writing it to server's HD
		\$objWriter->save(APPPATH.'../temp/'.\$filename);
		echo \$filename;
	}
	
	function export2PDF(){
		\$getdata = json_decode(\$this->input->post('data',TRUE));
		\$data[\"records\"] = \$getdata;
		\$data[\"table\"] = \"".$tbl."\";
		
		//html2pdf
		//Load the library
		\$this->load->library('html2pdf');
		
		//Set folder to save PDF to
		\$this->html2pdf->folder('./temp/');
		
		//Set the filename to save/download as
		\$this->html2pdf->filename('".$nfile.".pdf');
		
		//Set the paper defaults
		\$this->html2pdf->paper('a4', 'portrait');
		
		//Load html view
		\$this->html2pdf->html(\$this->load->view('pdf_".$nfile."', \$data, true));
		
		if(\$path = \$this->html2pdf->create('save')) {
			//PDF was successfully saved or downloaded
			echo 'PDF saved to: ' . \$path;
		}
	}
	
	function printRecords(){
		\$getdata = json_decode(\$this->input->post('data',TRUE));
		\$data[\"records\"] = \$getdata;
		\$data[\"table\"] = \"".$tbl."\";
		\$print_view=\$this->load->view(\"p_".$nfile.".php\",\$data,TRUE);
		if(!file_exists(\"temp\")){
			mkdir(\"temp\");
		}
		\$print_file=fopen(\"temp/".$nfile.".html\",\"w+\");
		fwrite(\$print_file, \$print_view);
		echo '1';
	}	
}";
		
		if ( ! write_file($path."/controllers/c_".$nfile.".php", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Controller telah digenerate...!!!<br /> Lokasi : ".$path."/controllers/c_".$nfile.".php </strong><br />";			
			return 1;
		}
	}
	
	//Generate Model CI
	function CModel($path,$nfile,$tbl,$data){
		$tulis = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author masongbee
 *
 */
class M_".$nfile." extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	/**
	 * Fungsi	: getAll
	 * 
	 * Untuk mengambil all-data
	 * 
	 * @param number \$start
	 * @param number \$page
	 * @param number \$limit
	 * @return json
	 */
	function getAll(\$start, \$page, \$limit){";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$key = $field->name;
			}
		}
		$tulis .= "
		\$query  = \$this->db->limit(\$limit, \$start)->order_by('".$key."', 'ASC')->get('".$tbl."')->result();
		\$total  = \$this->db->get('".$tbl."')->num_rows();
		
		\$data   = array();
		foreach(\$query as \$result){
			\$data[] = \$result;
		}
		
		\$json	= array(
						'success'   => TRUE,
						'message'   => \"Loaded data\",
						'total'     => \$total,
						'data'      => \$data
		);
		
		return \$json;
	}
	
	/**
	 * Fungsi	: save
	 * 
	 * Untuk menambah data baru atau mengubah data lama
	 * 
	 * @param array \$data
	 * @return json
	 */
	function save(\$data){
		\$last   = NULL;
		
		\$pkey = array(";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				if($field->type == "date")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d', strtotime(\$data->".$field->name.")),";
				}
				elseif($field->type == "datetime")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")),";
				}
				else
					$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
		
		if(\$this->db->get_where('".$tbl."', \$pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			\$arrdatau = array(";
		foreach($data['fields'] as $field)
		{
			if(! $field->primary_key == "1")
			{
				if($field->type == "date")
				{
					$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d', strtotime(\$data->".$field->name.")) : NULL),";
				}
				elseif($field->type == "datetime")
				{
					$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")) : NULL),";
				}
				else
					$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
			 
			\$this->db->where(\$pkey)->update('".$tbl."', \$arrdatau);
			\$last   = \$data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			\$arrdatac = array(";
		foreach($data['fields'] as $field)
		{
			if($field->type == "date")
			{
				$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d', strtotime(\$data->".$field->name.")) : NULL),";
			}
			elseif($field->type == "datetime")
			{
				$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")) : NULL),";
			}
			else
				$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
			 
			\$this->db->insert('".$tbl."', \$arrdatac);
			\$last   = \$this->db->where(\$pkey)->get('".$tbl."')->row();
			
		}
		
		\$total  = \$this->db->get('".$tbl."')->num_rows();
		
		\$json   = array(
						\"success\"   => TRUE,
						\"message\"   => 'Data berhasil disimpan',
						\"total\"     => \$total,
						\"data\"      => \$last
		);
		
		return \$json;
	}
	
	/**
	 * Fungsi	: delete
	 * 
	 * Untuk menghapus satu data
	 * 
	 * @param array \$data
	 * @return json
	 */
	function delete(\$data){
		\$pkey = array(";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				if($field->type == "date")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d', strtotime(\$data->".$field->name.")),";
				}
				elseif($field->type == "datetime")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")),";
				}
				else
					$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
		
		\$this->db->where(\$pkey)->delete('".$tbl."');
		
		\$total  = \$this->db->get('".$tbl."')->num_rows();
		\$last = \$this->db->get('".$tbl."')->result();
		
		\$json   = array(
						\"success\"   => TRUE,
						\"message\"   => 'Data berhasil dihapus',
						\"total\"     => \$total,
						\"data\"      => \$last
		);				
		return \$json;
	}
}
?>";
		
		if ( ! write_file($path."/models/m_".$nfile.".php", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Model telah digenerate...!!!<br /> Lokasi : ".$path."/models/m_".$nfile.".php </strong><br />";
			return 1;
		}
	}
	
	//Generate Controller Extjs
	function CControllerExtjs($path,$nfile,$tbl,$data){
		$tulis = "Ext.define('YMPI.controller.".strtoupper($nfile)."',{
	extend: 'Ext.app.Controller',
	views: ['".$data['pathjs'].".v_".$nfile."'],
	models: ['m_".$nfile."'],
	stores: ['s_".$nfile."'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'List".$nfile."',
		selector: 'List".$nfile."'
	}],


	init: function(){
		this.control({
			'List".$nfile."': {
				'afterrender': this.".$nfile."AfterRender,
				'selectionchange': this.enableDelete
			},
			'List".$nfile." button[action=create]': {
				click: this.createRecord
			},
			'List".$nfile." button[action=delete]': {
				click: this.deleteRecord
			},
			'List".$nfile." button[action=xexcel]': {
				click: this.export2Excel
			},
			'List".$nfile." button[action=xpdf]': {
				click: this.export2PDF
			},
			'List".$nfile." button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	".$nfile."AfterRender: function(){
		var ".$nfile."Store = this.getList".$nfile."().getStore();
		".$nfile."Store.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_".$nfile."');
		var r = Ext.ModelManager.create({
		";
		foreach($data['fields'] as $field)
		{
			$tulis .= "".$field->name."		: '',";
			if($field->primary_key == "1")
			{
				$key = $field->name;
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= "}, model);
		this.getList".$nfile."().getStore().insert(0, r);
		this.getList".$nfile."().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getList".$nfile."().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getList".$nfile."().getStore();
		var selection = this.getList".$nfile."().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: ".$key." = \"'+selection.data.".$key."+'\"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getList".$nfile."().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_".$nfile."/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getList".$nfile."().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_".$nfile."/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/".$nfile.".pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getList".$nfile."().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_".$nfile."/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/".$nfile.".html','".$nfile."_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
					break;
				default:
					Ext.MessageBox.show({
						title: 'Warning',
						msg: 'Unable to print the grid!',
						buttons: Ext.MessageBox.OK,
						animEl: 'save',
						icon: Ext.MessageBox.WARNING
					});
					break;
				}  
			}
		});
	}
	
});";
		
		if ( ! write_file("./extympi/app/controller/".strtoupper($nfile).".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Controller Extjs telah digenerate...!!!<br /> Lokasi : "."./extympi/app/controller/".strtoupper($nfile).".js </strong><br />";
			return 1;
		}
	}
	
	function CPrint($path,$nfile,$tbl,$data){
		$tulis = "<!DOCTYPE html>
<html lang=\"en\">
<head>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
	<title>CSS Tables</title>
	
	<style type=\"text/css\">
	@media screen{
		body {
			font: normal 11px auto \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
			color: #4f6b72;
			background: #E6EAE9;
		}
	}
	@media print{
		body {
			font: normal 11px auto \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		}
	}
	
	a {
		color: #c75f3e;
	}
	
	#mytable {
		width: 700px;
		padding: 0;
		margin: 0;
	}
	
	caption {
		padding: 0 0 5px 0;
		width: 700px;	 
		font: italic 11px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		text-align: right;
	}
	
	th {
		font: bold 11px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		color: #4f6b72;
		border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		border-top: 1px solid #C1DAD7;
		letter-spacing: 2px;
		text-transform: uppercase;
		text-align: left;
		padding: 6px 6px 6px 12px;
		background: #CAE8EA url(./assets/images/bg_header.jpg) no-repeat;
	}
	
	th.nobg {
		border-top: 0;
		border-left: 0;
		border-right: 1px solid #C1DAD7;
		background: none;
	}
	
	td {
		border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		background: #fff;
		padding: 6px 6px 6px 12px;
		color: #4f6b72;
		font: normal 12px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
	}
	
	td.alt {
		background: #F5FAFA;
		color: #797268;
	}
	
	th.spec {
		border-left: 1px solid #C1DAD7;
		border-top: 0;
		background: #fff url(./assets/images/bullet1.gif) no-repeat;
		font: bold 10px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
	}
	
	th.specalt {
		border-left: 1px solid #C1DAD7;
		border-top: 0;
		background: #f5fafa url(./assets/images/bullet2.gif) no-repeat;
		font: bold 10px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		color: #797268;
	}
	</style>
</head>

<body>
<table id=\"mytable\" cellspacing=\"0\" summary=\"YMPI - ".$nfile."\">
<caption>Table: ".$tbl." </caption>
  <tr>
	<?php 
	\$i = 0;
	foreach (\$records[0] as \$key => \$value){
		if(\$i==0){
			echo '<th scope=\"col\" abbr=\"'.\$key.'\" class=\"nobg\">'.\$key.'</th>';
		}else {
			echo '<th scope=\"col\" abbr=\"'.\$key.'\">'.\$key.'</th>';
		}
		
		\$i++;
	}
	?>
  </tr>
  <?php 
	\$i = 0;
	for(\$i=0; \$i<(sizeof(\$records)); \$i++){
		echo '<tr>';
		\$j = 0;
		foreach (\$records[\$i] as \$key => \$value){
			if((\$j==0) && (\$i%2 == 0)){
				echo '<th scope=\"row\" abbr=\"'.\$value.'\" class=\"specalt\">'.\$value.'</th>';
			}elseif((\$j==0) && (\$i%2 != 0)){
				echo '<th scope=\"row\" abbr=\"'.\$value.'\" class=\"spec\">'.\$value.'</th>';
			}else{
				if(\$i%2 == 0){
					echo '<td class=\"alt\">'.\$value.'</td>';
				}else{
					echo '<td>'.\$value.'</td>';
				}
			}
			\$j++;
		}
		echo '<tr/>';
	}
  ?>
</table>
</body>
</html>
";
		
		if ( ! write_file($path."/views/p_".$nfile.".php", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Print Preview telah digenerate...!!!<br /> Lokasi : ".$path."/views/p_".$nfile.".php </strong><br />";
			return 1;
		}
	}
	
	function CPDF($path,$nfile,$tbl,$data){
		$tulis = "<!DOCTYPE html>
<html lang=\"en\">
<head>
	<meta charset=\"utf-8\">
	<title>PDF Created</title>

	<style type=\"text/css\">
	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 16px;
		font-weight: bold;
		margin: 24px 0 2px 0;
		padding: 5px 0 6px 0;
	}
	
	table.gridtable {
		font-family: verdana,arial,sans-serif;
		font-size:11px;
		color:#333333;
		border-width: 1px;
		border-color: #666666;
		border-collapse: collapse;
		margin-top: 5px;
	}
	table.gridtable th {
		border-width: 1px;
		padding: 8px;
		border-style: solid;
		border-color: #666666;
		background-color: #dedede;
	}
	table.gridtable td {
		border-width: 1px;
		padding: 8px;
		border-style: solid;
		border-color: #666666;
		background-color: #ffffff;
	}
	
	</style>
</head>
<body>

<h1>Table: <?php print \$table;?></h1>

<table class=\"gridtable\" cellspacing=\"0\" summary=\"YMPI - ".$nfile."\">
	<tr>
		<?php 
		foreach (\$records[0] as \$key => \$value){
		?>
		<th><?php print \$key;?></th>
		<?php 
		}
		?>
	</tr>
	<?php 
	for(\$i=0; \$i<(sizeof(\$records)); \$i++){
	?>
	<tr>
	<?php 
		foreach (\$records[\$i] as \$key => \$value){
	?>
		<td><?php print \$value;?></td>
	<?php 
		}
	?>
	</tr>
	<?php 
	}
	?>
</table>

</body>
</html>";
		
		if ( ! write_file($path."/views/pdf_".$nfile.".php", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Print PDF telah digenerate...!!!<br /> Lokasi : ".$path."/views/pdf_".$nfile.".php </strong><br />";
			return 1;
		}
	}
	
	//Generate Model Extjs
	function CModelExtjs($path,$nfile,$tbl,$data){
		$tulis = "Ext.define('YMPI.model.m_".$nfile."', {
	extend: 'Ext.data.Model',
	alias		: 'widget.".$nfile."Model',
	fields		: [";
	foreach($data['fields'] as $field)
	{
		$tulis .= "'".$field->name."',";		
	}
	
$tulis = substr($tulis,0,strlen($tulis) -1);
$tulis .= "],";
	$i=0;
	$idProperty = "";
	foreach($data['fields'] as $field)
	{
		if($field->primary_key == "1")
		{
			$idProperty = "
	idProperty	: '".$field->name."'";
			$i++;
		}
	}
	if($i == 1)
	{
		$tulis .= $idProperty;
	}else{
		$tulis = substr($tulis,0,strlen($tulis) -1);
	}
	
$tulis .= "	
});";
		
		if ( ! write_file("./extympi/app/model/m_".$nfile.".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Model Extjs telah digenerate...!!!<br /> Lokasi : "."./extympi/app/model/m_".$nfile.".js </strong><br />";
			return 1;
		}
	}
	
	//Generate Store Extjs
	function CStoreExtjs($path,$nfile,$tbl,$data){
		$tulis = "Ext.define('YMPI.store.s_".$nfile."', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.".$nfile."Store',
	model	: 'YMPI.model.m_".$nfile."',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: '".$nfile."',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_".$nfile."/getAll',
			create	: 'c_".$nfile."/save',
			update	: 'c_".$nfile."/save',
			destroy	: 'c_".$nfile."/delete'
		},
		actionMethods: {
			read    : 'POST',
			create	: 'POST',
			update	: 'POST',
			destroy	: 'POST'
		},
		reader: {
			type            : 'json',
			root            : 'data',
			rootProperty    : 'data',
			successProperty : 'success',
			messageProperty : 'message'
		},
		writer: {
			type            : 'json',
			writeAllFields  : true,
			root            : 'data',
			encode          : true
		},
		listeners: {
			exception: function(proxy, response, operation){
				Ext.MessageBox.show({
					title: 'REMOTE EXCEPTION',
					msg: operation.getError(),
					icon: Ext.MessageBox.ERROR,
					buttons: Ext.Msg.OK
				});
			}
		}
	},
	
	constructor: function(){
		this.callParent(arguments);
	}
	
});";
		
		if ( ! write_file("./extympi/app/store/s_".$nfile.".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Store Extjs telah digenerate...!!!<br /> Lokasi : "."./extympi/app/store/s_".$nfile.".js </strong><br />";
			return 1;
		}
	}
	
	//Generate Veiw Extjs
	function CViewExtjs($path,$nfile,$tbl,$data){
		$tulis = "Ext.define('YMPI.view.".$data['pathjs'].".v_".$nfile."', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_".$nfile."'],
	
	title		: '".$nfile."',
	itemId		: 'List".$nfile."',
	alias       : 'widget.List".$nfile."',
	store 		: 's_".$nfile."',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
	";
	
	foreach($data['fields'] as $field)
	{
		if($field->primary_key == "1")
		{
			if($field->type == "date" || $field->type == "datetime")
			{
				$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.Date', {
			allowBlank : false,
			format: 'Y-m-d'
		});";
			}
			elseif($field->type == "int" || $field->type == "decimal")
			{
				$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.Number', {
			allowBlank : false";
			if(isset($field->max_length)){
				$tulis .= ",
			maxLength: ".$field->max_length." /* length of column name */";
			}
		$tulis .= "
		});";
			}
			else
			{
				if($field->max_length > 20)
				{
					$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.TextArea', {
			allowBlank : false";
			if(isset($field->max_length)){
				$tulis .= ",
			maxLength: ".$field->max_length." /* length of column name */";
			}
		$tulis .= "
		});";
				}
				else
					$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false";
			if(isset($field->max_length)){
				$tulis .= ",
			maxLength: ".$field->max_length." /* length of column name */";
			}
		$tulis .= "
		});";
			}
		}
	}

		$tulis .= "
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
				if(";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$tulis .= "! (/^\s*$/).test(e.record.data.".$field->name.") || ";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -3);
					$tulis .= "){
					";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$tulis .= "".$field->name."_field.setReadOnly(true);";
			}
		}
		$tulis .= "}
					else
					{
						";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$tulis .= "".$field->name."_field.setReadOnly(false);";
			}
		}
		$tulis .= "
					}
					
				},
				'canceledit': function(editor, e){
					if(";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$tulis .= "(/^\s*$/).test(e.record.data.".$field->name.") || ";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -3);
					$tulis .= "){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if(";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$tulis .= "(/^\s*$/).test(e.record.data.".$field->name.") || ";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -3);
					$tulis .= "){
						Ext.Msg.alert('Peringatan', 'Kolom ";
						foreach($data['fields'] as $field)
						{
							if($field->primary_key == "1")
							{
								$tulis .= "\"".$field->name."\",";
							}
						}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= " tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_".$nfile."/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (";
											foreach($data['fields'] as $field)
											{
												if($field->primary_key == "1")
												{
													if($field->type == "date")
													{
														$tulis .= "(new Date(record.get('".$field->name."'))).format('yyyy-mm-dd') === (new Date(e.record.data.".$field->name.")).format('yyyy-mm-dd') && ";
													}
													elseif($field->type == "datetime")
													{
														$tulis .= "(new Date(record.get('".$field->name."'))).format('yyyy-mm-dd hh:nn:ss') === (new Date(e.record.data.".$field->name.")).format('yyyy-mm-dd hh:nn:ss') && ";
													}
													elseif($field->type == "int" || $field->type == "decimal")
													{
														$tulis .= "parseFloat(record.get('".$field->name."')) === e.record.data.".$field->name." && ";
													}
													else
														$tulis .= "record.get('".$field->name."') === e.record.data.".$field->name." && ";
												}
											}
											$tulis = substr($tulis,0,strlen($tulis) - 4);
											$tulis .= ") {
												return true;
											}
											return false;
										}
									);
									/* me.grid.getView().select(recordIndex); */
									me.grid.getSelectionModel().select(newRecordIndex);
								}
							});
						}
					});
					return true;
				}
			}
		});
		
		this.columns = [
			";
foreach($data['fields'] as $field)
{
	if(! $field->primary_key == "1")
	{
		if($field->type == "date" || $field->type == "datetime")
		{
			$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."', renderer: Ext.util.Format.dateRenderer('d M, Y'), field: {xtype: 'datefield',format: 'm-d-Y'}},";
		}
		elseif($field->type == "int")
		{
			$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."', field: {xtype: 'numberfield'}},";
		}
		elseif($field->type == "decimal")
		{
			$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."', align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				}, field: {xtype: 'numberfield'}},";
		}
		else
		{
			if($field->max_length > 20)
			{
				$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."', field: {xtype: 'textarea'}},";
			}
			else
				$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."', field: {xtype: 'textfield'} },";
		}
	}
	else
	{
		if($field->type == "date" || $field->type == "datetime")
		{
			$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."', renderer: Ext.util.Format.dateRenderer('d M, Y'), field: ".$field->name."_field},";
		}
		elseif($field->type == "int" || $field->type == "decimal")
		{
			$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."', field: ".$field->name."_field},";
		}
		else
		{
			if($field->max_length > 20)
			{
				$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."', field: ".$field->name."_field},";
			}
			else
				$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."', field: ".$field->name."_field},";
		}
			
	}		
}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= "];
		this.plugins = [this.rowEditing];
		this.dockedItems = [
			{
				xtype: 'toolbar',
				frame: true,
				items: [{
					text	: 'Add',
					iconCls	: 'icon-add',
					action	: 'create'
				}, {
					itemId	: 'btndelete',
					text	: 'Delete',
					iconCls	: 'icon-remove',
					action	: 'delete',
					disabled: true
				}, '-',{
					text	: 'Export Excel',
					iconCls	: 'icon-excel',
					action	: 'xexcel'
				}, {
					text	: 'Export PDF',
					iconCls	: 'icon-pdf',
					action	: 'xpdf'
				}, {
					text	: 'Cetak',
					iconCls	: 'icon-print',
					action	: 'print'
				}]
			},
			{
				xtype: 'pagingtoolbar',
				store: 's_".$nfile."',
				dock: 'bottom',
				displayInfo: true
			}
		];
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		this.getView().on('refresh', this.refreshSelection, this);
	},
	
	gridSelection: function(me, record, item, index, e, eOpts){
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);
    }

});";
		
		if ( ! write_file("./extympi/app/view/".$data['pathjs']."/v_".$nfile.".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>View Extjs telah digenerate...!!!<br /> Lokasi : "."./extympi/app/view/".$data['pathjs']."/v_".$nfile.".js </strong><br />";
			return 1;
		}
	}
	
	//Generate Viewport Extjs
	function CViewport($path,$nfile,$tbl,$data){
		$tulis = "Ext.define('YMPI.view.".$data['pathjs'].".".strtoupper($nfile)."', {
	extend: 'Ext.form.Panel',
	
	bodyPadding: 0,
	layout: 'border',
	initComponent: function(){
		this.items = [{
			region: 'center',
			layout: {
				type : 'hbox',
				align: 'stretch'
			},
			items: [{
				xtype	: 'List".$nfile."',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});";
		
		if ( ! write_file("./extympi/app/view/".$data['pathjs']."/".strtoupper($nfile).".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Viewport Extjs telah digenerate...!!!<br /> Lokasi : "."./extympi/app/view/".$data['pathjs']."/".strtoupper($nfile).".js </strong><br />";
			return 1;
		}
	}
	
	
	//----------------------------------------------------- Generator Single Grid Single Form ------------------------------------------------------------//
	
	function SingleGridSF($path,$nfile,$tbl,$data){
		$this->CControllerSF($path,$nfile,$tbl,$data);
		$this->CModelSF($path,$nfile,$tbl,$data);
		$this->CPrintSF($path,$nfile,$tbl,$data);
		$this->CPDFSF($path,$nfile,$tbl,$data);
		$this->CControllerExtjsSF($path,$nfile,$tbl,$data);
		$this->CModelExtjsSF($path,$nfile,$tbl,$data);
		$this->CStoreExtjsSF($path,$nfile,$tbl,$data);
		$this->CViewExtjsSF($path,$nfile,$tbl,$data);
		$this->CFormExtjs($path,$nfile,$tbl,$data);
		$this->CViewportSF($path,$nfile,$tbl,$data);
	}
	
	//Generate Controller CI
	function CControllerSF($path,$nfile,$tbl,$data){
		$tulis = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_".$nfile." extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		\$this->load->model('m_".$nfile."', '', TRUE);
	}
	
	function getAll(){
		/*
		 * Collect Data
		 */
		\$start  =   (\$this->input->post('start', TRUE) ? \$this->input->post('start', TRUE) : 0);
		\$page   =   (\$this->input->post('page', TRUE) ? \$this->input->post('page', TRUE) : 1);
		\$limit  =   (\$this->input->post('limit', TRUE) ? \$this->input->post('limit', TRUE) : 15);
		
		/*
		 * Processing Data
		 */
		\$result = \$this->m_".$nfile."->getAll(\$start, \$page, \$limit);
		echo json_encode(\$result);
	}
	
	function save(){
		/*
		 * Collect Data ==> diambil dari [model.".$nfile."]
		 */
		\$data   = json_decode(\$this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		\$result = \$this->m_".$nfile."->save(\$data);
		echo json_encode(\$result);
	}
	
	function delete(){
		/*
		 * Collect Data ==> diambil dari [model.".$nfile."]
		 */
		\$data   = json_decode(\$this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		\$result = \$this->m_".$nfile."->delete(\$data);
		echo json_encode(\$result);
	}
	
	/**
	 * Fungsi	: export2Excel
	 * 
	 * Untuk menyimpan data yang didapat dari Grid ExtJS ke dalam file Excel.
	 * Tidak lagi mengakses database untuk mendapatkan data.
	 */
	function export2Excel(){
		\$data = json_decode(\$this->input->post('data',TRUE));
		
		//load our new PHPExcel library
		\$this->load->library('excel');
		//activate worksheet number 1
		\$this->excel->setActiveSheetIndex(0);
		//name the worksheet
		\$this->excel->getActiveSheet()->setTitle('test worksheet');
		
		\$col = 0;
		foreach (\$data[0] as \$key => \$value){
			\$this->excel->getActiveSheet()->setCellValueByColumnAndRow(\$col, 1, \$key);
			\$this->excel->getActiveSheet()->getStyleByColumnAndRow(\$col, 1)->getFont()->setBold(true);
			\$col++;
		}
		
		// Fetching the table data
		\$row = 2;
		foreach(\$data as \$record)
		{
			\$col = ord(\"A\");
			foreach (\$data[0] as \$key => \$value)
			{
				\$cellvalue = \$record->\$key;
				
				if(\$key == strtoupper('".$nfile."')){
					\$this->excel->getActiveSheet()->getCell(chr(\$col).\$row)->setValueExplicit(\$cellvalue, PHPExcel_Cell_DataType::TYPE_STRING);
				}else{
					\$this->excel->getActiveSheet()->setCellValue(chr(\$col).\$row, \$cellvalue);
				}
				
				\$col++;
			}
		
			\$row++;
		}		
		
		\$filename='".$nfile.".xlsx'; //save our workbook as this file name
		//header('Content-Type: application/vnd.ms-excel'); //mime type for Excel5
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type for Excel2007
		header('Content-Disposition: attachment;filename=\"'.\$filename.'\"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		\$objWriter = PHPExcel_IOFactory::createWriter(\$this->excel, 'Excel2007');
		//force user to download the Excel file without writing it to server's HD
		\$objWriter->save(APPPATH.'../temp/'.\$filename);
		echo \$filename;
	}
	
	function export2PDF(){
		\$getdata = json_decode(\$this->input->post('data',TRUE));
		\$data[\"records\"] = \$getdata;
		\$data[\"table\"] = \"".$tbl."\";
		
		//html2pdf
		//Load the library
		\$this->load->library('html2pdf');
		
		//Set folder to save PDF to
		\$this->html2pdf->folder('./temp/');
		
		//Set the filename to save/download as
		\$this->html2pdf->filename('".$nfile.".pdf');
		
		//Set the paper defaults
		\$this->html2pdf->paper('a4', 'portrait');
		
		//Load html view
		\$this->html2pdf->html(\$this->load->view('pdf_".$nfile."', \$data, true));
		
		if(\$path = \$this->html2pdf->create('save')) {
			//PDF was successfully saved or downloaded
			echo 'PDF saved to: ' . \$path;
		}
	}
	
	function printRecords(){
		\$getdata = json_decode(\$this->input->post('data',TRUE));
		\$data[\"records\"] = \$getdata;
		\$data[\"table\"] = \"".$tbl."\";
		\$print_view=\$this->load->view(\"p_".$nfile.".php\",\$data,TRUE);
		if(!file_exists(\"temp\")){
			mkdir(\"temp\");
		}
		\$print_file=fopen(\"temp/".$nfile.".html\",\"w+\");
		fwrite(\$print_file, \$print_view);
		echo '1';
	}	
}";
		
		if ( ! write_file($path."/controllers/c_".$nfile.".php", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Controller telah digenerate...!!!<br /> Lokasi : ".$path."/controllers/c_".$nfile.".php </strong><br />";			
			return 1;
		}
	}
	
	//Generate Model CI
	function CModelSF($path,$nfile,$tbl,$data){
		$tulis = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_".$nfile."
 * 
 * Table	: ".$tbl."
 *  
 * @author masongbee
 *
 */
class M_".$nfile." extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	/**
	 * Fungsi	: getAll
	 * 
	 * Untuk mengambil all-data
	 * 
	 * @param number \$start
	 * @param number \$page
	 * @param number \$limit
	 * @return json
	 */
	function getAll(\$start, \$page, \$limit){";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$key = $field->name;
			}
		}
		$tulis .= "
		\$query  = \$this->db->limit(\$limit, \$start)->order_by('".$key."', 'ASC')->get('".$tbl."')->result();
		\$total  = \$this->db->get('".$tbl."')->num_rows();
		
		\$data   = array();
		foreach(\$query as \$result){
			\$data[] = \$result;
		}
		
		\$json	= array(
						'success'   => TRUE,
						'message'   => \"Loaded data\",
						'total'     => \$total,
						'data'      => \$data
		);
		
		return \$json;
	}
	
	/**
	 * Fungsi	: save
	 * 
	 * Untuk menambah data baru atau mengubah data lama
	 * 
	 * @param array \$data
	 * @return json
	 */
	function save(\$data){
		\$last   = NULL;
		
		\$pkey = array(";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				if($field->type == "date")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d', strtotime(\$data->".$field->name.")),";
				}
				elseif($field->type == "datetime")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")),";
				}
				else
					$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
		
		if(\$this->db->get_where('".$tbl."', \$pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */			 
			";
		foreach($data['fields'] as $field)
		{
			if($field->type == "decimal")
			{
				$tulis .= "\$tmp = substr(\$data->".$field->name.",3,strlen(\$data->".$field->name."));
			\$tmp = str_replace('.','',\$tmp);
			\$tmp = str_replace(',','.',\$tmp);
			\$data->".$field->name." = \$tmp;";
			}
		}
		$tulis .= "	
			 
			\$arrdatau = array(";
		foreach($data['fields'] as $field)
		{
			if(! $field->primary_key == "1")
			{
				if($field->type == "date")
				{
					$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d', strtotime(\$data->".$field->name.")) : NULL),";
				}
				elseif($field->type == "datetime")
				{
					$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")) : NULL),";
				}
				else
					$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
			}
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
			 
			\$this->db->where(\$pkey)->update('".$tbl."', \$arrdatau);
			\$last   = \$data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 ";
		foreach($data['fields'] as $field)
		{
			if($field->type == "decimal")
			{
				$tulis .= "\$tmp = substr(\$data->".$field->name.",3,strlen(\$data->".$field->name."));
			\$tmp = str_replace('.','',\$tmp);
			\$tmp = str_replace(',','.',\$tmp);
			\$data->".$field->name." = \$tmp;";
			}
		}
		$tulis .= "
			\$arrdatac = array(";
			 
		foreach($data['fields'] as $field)
		{
			if($field->type == "date")
			{
				$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d', strtotime(\$data->".$field->name.")) : NULL),";
			}
			elseif($field->type == "datetime")
			{
				$tulis .= "'".$field->name."'=>(strlen(trim(\$data->".$field->name.")) > 0 ? date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")) : NULL),";
			}
			else
				$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
		}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
			 
			\$this->db->insert('".$tbl."', \$arrdatac);
			\$last   = \$this->db->where(\$pkey)->get('".$tbl."')->row();
			
		}
		
		\$total  = \$this->db->get('".$tbl."')->num_rows();
		
		\$json   = array(
						\"success\"   => TRUE,
						\"message\"   => 'Data berhasil disimpan',
						'total'     => \$total,
						\"data\"      => \$last
		);
		
		return \$json;
	}
	
	/**
	 * Fungsi	: delete
	 * 
	 * Untuk menghapus satu data
	 * 
	 * @param array \$data
	 * @return json
	 */
	function delete(\$data){
		\$pkey = array(";
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				if($field->type == "date")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d', strtotime(\$data->".$field->name.")),";
				}
				elseif($field->type == "datetime")
				{
					$tulis .= "'".$field->name."'=>date('Y-m-d H:i:s', strtotime(\$data->".$field->name.")),";
				}
				else
					$tulis .= "'".$field->name."'=>\$data->".$field->name.",";
			}
		}	
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= ");
		
		\$this->db->where(\$pkey)->delete('".$tbl."');
		
		\$total  = \$this->db->get('".$tbl."')->num_rows();
		\$last = \$this->db->get('".$tbl."')->result();
		
		\$json   = array(
						\"success\"   => TRUE,
						\"message\"   => 'Data berhasil dihapus',
						'total'     => \$total,
						\"data\"      => \$last
		);				
		return \$json;
	}
}
?>";
		
		if ( ! write_file($path."/models/m_".$nfile.".php", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Model telah digenerate...!!!<br /> Lokasi : ".$path."/models/m_".$nfile.".php </strong><br />";
			return 1;
		}
	}
	
	//Generate Controller Extjs
	function CControllerExtjsSF($path,$nfile,$tbl,$data){
		$tulis = "Ext.define('YMPI.controller.".strtoupper($nfile)."',{
	extend: 'Ext.app.Controller',
	views: ['".$data['pathjs'].".v_".$nfile."','".$data['pathjs'].".v_".$nfile."_form'],
	models: ['m_".$nfile."'],
	stores: ['s_".$nfile."'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'List".$nfile."',
		selector: 'List".$nfile."'
	}, {
		ref: 'v_".$nfile."_form',
		selector: 'v_".$nfile."_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_".$nfile."_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_".$nfile."_form #create'
	}, {
		ref: '".strtoupper($nfile)."',
		selector: '".strtoupper($nfile)."'
	}],


	init: function(){
		this.control({
			'".strtoupper($nfile)."': {
				'afterrender': this.".$nfile."AfterRender
			},
			'List".$nfile."': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateList".$nfile."
			},
			'List".$nfile." button[action=create]': {
				click: this.createRecord
			},
			'List".$nfile." button[action=delete]': {
				click: this.deleteRecord
			},
			'List".$nfile." button[action=xexcel]': {
				click: this.export2Excel
			},
			'List".$nfile." button[action=xpdf]': {
				click: this.export2PDF
			},
			'List".$nfile." button[action=print]': {
				click: this.printRecords
			},
			'v_".$nfile."_form button[action=save]': {
				click: this.saveV_".$nfile."_form
			},
			'v_".$nfile."_form button[action=create]': {
				click: this.saveV_".$nfile."_form
			},
			'v_".$nfile."_form button[action=cancel]': {
				click: this.cancelV_".$nfile."_form
			}
		});
	},
	
	".$nfile."AfterRender: function(){
		var ".$nfile."Store = this.getList".$nfile."().getStore();
		".$nfile."Store.load();
	},
	
	createRecord: function(){
		var getList".$nfile."	= this.getList".$nfile."();
		var getV_".$nfile."_form= this.getV_".$nfile."_form(),
			form			= getV_".$nfile."_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getList".$nfile.".setDisabled(true);
        
		/* form-panel */
		form.reset();
		";
		
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$tulis .= "getV_".$nfile."_form.down('#".$field->name."_field').setReadOnly(false);";
			}
		}
		
		$tulis .= "
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_".$nfile."_form.setDisabled(false);
		
		this.get".strtoupper($nfile)."().setActiveTab(getV_".$nfile."_form);		
	},
	
	enableDelete: function(dataview, selections){
		this.getList".$nfile."().down('#btndelete').setDisabled(!selections.length);
	},
	
	updateList".$nfile.": function(me, record, item, index, e){
		var get".strtoupper($nfile)."		= this.get".strtoupper($nfile)."();
		var getList".$nfile."	= this.getList".$nfile."();
		var getV_".$nfile."_form= this.getV_".$nfile."_form(),
			form			= getV_".$nfile."_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		";
		
		foreach($data['fields'] as $field)
		{
			if($field->primary_key == "1")
			{
				$tulis .= "getV_".$nfile."_form.down('#".$field->name."_field').setReadOnly(true);";
			}
		}
		$tulis .= "		
		getV_".$nfile."_form.loadRecord(record);
		
		getList".$nfile.".setDisabled(true);
		getV_".$nfile."_form.setDisabled(false);
		get".strtoupper($nfile).".setActiveTab(getV_".$nfile."_form);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getList".$nfile."().getStore();
		var selection = this.getList".$nfile."().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: ";
						foreach($data['fields'] as $field)
						{
							if($field->primary_key == "1")
							{
								$tulis .= "\"".$field->name."\" = \"'+selection.data.".$field->name."+'\",";
							}
						}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= "?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getList".$nfile."().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_".$nfile."/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getList".$nfile."().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_".$nfile."/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/".$nfile.".pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getList".$nfile."().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_".$nfile."/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/".$nfile.".html','".$nfile."_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
					break;
				default:
					Ext.MessageBox.show({
						title: 'Warning',
						msg: 'Unable to print the grid!',
						buttons: Ext.MessageBox.OK,
						animEl: 'save',
						icon: Ext.MessageBox.WARNING
					});
					break;
				}  
			}
		});
	},
	
	saveV_".$nfile."_form: function(){
		var get".strtoupper($nfile)."		= this.get".strtoupper($nfile)."();
		var getList".$nfile." 	= this.getList".$nfile."();
		var getV_".$nfile."_form= this.getV_".$nfile."_form(),
			form			= getV_".$nfile."_form.getForm(),
			values			= getV_".$nfile."_form.getValues();
		var store 			= this.getStore('s_".$nfile."');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_".$nfile."/save',
				params: {data: jsonData},
				success: function(response){
					store.reload({
						callback: function(){
							var newRecordIndex = store.findBy(
								function(record, id) {
									if (";
									foreach($data['fields'] as $field)
									{
										if($field->primary_key == "1")
										{
											if($field->type == "date")
											{
												$tulis .= "(new Date(record.get('".$field->name."'))).format('yyyy-mm-dd') === (new Date(values.".$field->name.")).format('yyyy-mm-dd') && ";
											}
											elseif($field->type == "datetime")
											{
												$tulis .= "(new Date(record.get('".$field->name."'))).format('yyyy-mm-dd hh:nn:ss') === (new Date(values.".$field->name.")).format('yyyy-mm-dd hh:nn:ss') && ";
											}
											else
												$tulis .= "record.get('".$field->name."') === values.".$field->name." && ";
										}
									}
									$tulis = substr($tulis,0,strlen($tulis) - 4);
									$tulis .= ") {
										return true;
									}
									return false;
								}
							);
							/* getList".$nfile.".getView().select(recordIndex); */
							getList".$nfile.".getSelectionModel().select(newRecordIndex);
						}
					});
					
					getV_".$nfile."_form.setDisabled(true);
					getList".$nfile.".setDisabled(false);
					get".strtoupper($nfile).".setActiveTab(getList".$nfile.");
				}
			});
		}
	},
	
	createV_".$nfile."_form: function(){
		var get".strtoupper($nfile)."		= this.get".strtoupper($nfile)."();
		var getList".$nfile." 	= this.getList".$nfile."();
		var getV_".$nfile."_form= this.getV_".$nfile."_form(),
			form			= getV_".$nfile."_form.getForm(),
			values			= getV_".$nfile."_form.getValues();
		var store 			= this.getStore('s_".$nfile."');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_".$nfile."/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_".$nfile."_form.setDisabled(true);
					getList".$nfile.".setDisabled(false);
					get".strtoupper($nfile).".setActiveTab(getList".$nfile.");
				}
			});
		}
	},
	
	cancelV_".$nfile."_form: function(){
		var get".strtoupper($nfile)."		= this.get".strtoupper($nfile)."();
		var getList".$nfile."	= this.getList".$nfile."();
		var getV_".$nfile."_form= this.getV_".$nfile."_form(),
			form			= getV_".$nfile."_form.getForm();
			
		form.reset();
		getV_".$nfile."_form.setDisabled(true);
		getList".$nfile.".setDisabled(false);
		get".strtoupper($nfile).".setActiveTab(getList".$nfile.");
	}
	
});";
		
		if ( ! write_file("./extympi/app/controller/".strtoupper($nfile).".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Controller Extjs telah digenerate...!!!<br /> Lokasi : "."./extympi/app/controller/".strtoupper($nfile).".js </strong><br />";
			return 1;
		}
	}
	
	function CPrintSF($path,$nfile,$tbl,$data){
		$tulis = "<!DOCTYPE html>
<html lang=\"en\">
<head>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
	<title>CSS Tables</title>
	
	<style type=\"text/css\">
	@media screen{
		body {
			font: normal 11px auto \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
			color: #4f6b72;
			background: #E6EAE9;
		}
	}
	@media print{
		body {
			font: normal 11px auto \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		}
	}
	
	a {
		color: #c75f3e;
	}
	
	#mytable {
		width: 700px;
		padding: 0;
		margin: 0;
	}
	
	caption {
		padding: 0 0 5px 0;
		width: 700px;	 
		font: italic 11px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		text-align: right;
	}
	
	th {
		font: bold 11px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		color: #4f6b72;
		border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		border-top: 1px solid #C1DAD7;
		letter-spacing: 2px;
		text-transform: uppercase;
		text-align: left;
		padding: 6px 6px 6px 12px;
		background: #CAE8EA url(./assets/images/bg_header.jpg) no-repeat;
	}
	
	th.nobg {
		border-top: 0;
		border-left: 0;
		border-right: 1px solid #C1DAD7;
		background: none;
	}
	
	td {
		border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		background: #fff;
		padding: 6px 6px 6px 12px;
		color: #4f6b72;
		font: normal 12px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
	}
	
	td.alt {
		background: #F5FAFA;
		color: #797268;
	}
	
	th.spec {
		border-left: 1px solid #C1DAD7;
		border-top: 0;
		background: #fff url(./assets/images/bullet1.gif) no-repeat;
		font: bold 10px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
	}
	
	th.specalt {
		border-left: 1px solid #C1DAD7;
		border-top: 0;
		background: #f5fafa url(./assets/images/bullet2.gif) no-repeat;
		font: bold 10px \"Trebuchet MS\", Verdana, Arial, Helvetica, sans-serif;
		color: #797268;
	}
	</style>
</head>

<body>
<table id=\"mytable\" cellspacing=\"0\" summary=\"YMPI - ".$nfile."\">
<caption>Table: ".$tbl." </caption>
  <tr>
	<?php 
	\$i = 0;
	foreach (\$records[0] as \$key => \$value){
		if(\$i==0){
			echo '<th scope=\"col\" abbr=\"'.\$key.'\" class=\"nobg\">'.\$key.'</th>';
		}else {
			echo '<th scope=\"col\" abbr=\"'.\$key.'\">'.\$key.'</th>';
		}
		
		\$i++;
	}
	?>
  </tr>
  <?php 
	\$i = 0;
	for(\$i=0; \$i<(sizeof(\$records)); \$i++){
		echo '<tr>';
		\$j = 0;
		foreach (\$records[\$i] as \$key => \$value){
			if((\$j==0) && (\$i%2 == 0)){
				echo '<th scope=\"row\" abbr=\"'.\$value.'\" class=\"specalt\">'.\$value.'</th>';
			}elseif((\$j==0) && (\$i%2 != 0)){
				echo '<th scope=\"row\" abbr=\"'.\$value.'\" class=\"spec\">'.\$value.'</th>';
			}else{
				if(\$i%2 == 0){
					echo '<td class=\"alt\">'.\$value.'</td>';
				}else{
					echo '<td>'.\$value.'</td>';
				}
			}
			\$j++;
		}
		echo '<tr/>';
	}
  ?>
</table>
</body>
</html>
";
		
		if ( ! write_file($path."/views/p_".$nfile.".php", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Print Preview telah digenerate...!!!<br /> Lokasi : ".$path."/views/p_".$nfile.".php </strong><br />";
			return 1;
		}
	}
	
	function CPDFSF($path,$nfile,$tbl,$data){
		$tulis = "<!DOCTYPE html>
<html lang=\"en\">
<head>
	<meta charset=\"utf-8\">
	<title>PDF Created</title>

	<style type=\"text/css\">
	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 16px;
		font-weight: bold;
		margin: 24px 0 2px 0;
		padding: 5px 0 6px 0;
	}
	
	table.gridtable {
		font-family: verdana,arial,sans-serif;
		font-size:11px;
		color:#333333;
		border-width: 1px;
		border-color: #666666;
		border-collapse: collapse;
		margin-top: 5px;
	}
	table.gridtable th {
		border-width: 1px;
		padding: 8px;
		border-style: solid;
		border-color: #666666;
		background-color: #dedede;
	}
	table.gridtable td {
		border-width: 1px;
		padding: 8px;
		border-style: solid;
		border-color: #666666;
		background-color: #ffffff;
	}
	
	</style>
</head>
<body>

<h1>Table: <?php print \$table;?></h1>

<table class=\"gridtable\" cellspacing=\"0\" summary=\"YMPI - ".$nfile."\">
	<tr>
		<?php 
		foreach (\$records[0] as \$key => \$value){
		?>
		<th><?php print \$key;?></th>
		<?php 
		}
		?>
	</tr>
	<?php 
	for(\$i=0; \$i<(sizeof(\$records)); \$i++){
	?>
	<tr>
	<?php 
		foreach (\$records[\$i] as \$key => \$value){
	?>
		<td><?php print \$value;?></td>
	<?php 
		}
	?>
	</tr>
	<?php 
	}
	?>
</table>

</body>
</html>";
		
		if ( ! write_file($path."/views/pdf_".$nfile.".php", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Print PDF telah digenerate...!!!<br /> Lokasi : ".$path."/views/pdf_".$nfile.".php </strong><br />";
			return 1;
		}
	}
	
	//Generate Model Extjs
	function CModelExtjsSF($path,$nfile,$tbl,$data){
		$tulis = "Ext.define('YMPI.model.m_".$nfile."', {
	extend: 'Ext.data.Model',
	alias		: 'widget.".$nfile."Model',
	fields		: [";
	foreach($data['fields'] as $field)
	{
		$tulis .= "'".$field->name."',";		
	}
	
$tulis = substr($tulis,0,strlen($tulis) -1);
$tulis .= "],";
	$i=0;
	$idProperty = "";
	foreach($data['fields'] as $field)
	{
		if($field->primary_key == "1")
		{
			$idProperty = "
	idProperty	: '".$field->name."'";
			$i++;
		}
	}
	if($i == 1)
	{
		$tulis .= $idProperty;
	}else{
		$tulis = substr($tulis,0,strlen($tulis) -1);
	}
	
$tulis .= "	
});";
		
		if ( ! write_file("./extympi/app/model/m_".$nfile.".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Model Extjs telah digenerate...!!!<br /> Lokasi : "."./extympi/app/model/m_".$nfile.".js </strong><br />";
			return 1;
		}
	}
	
	//Generate Store Extjs
	function CStoreExtjsSF($path,$nfile,$tbl,$data){
		$tulis = "Ext.define('YMPI.store.s_".$nfile."', {
	extend	: 'Ext.data.Store',
	alias	: 'widget.".$nfile."Store',
	model	: 'YMPI.model.m_".$nfile."',
	
	autoLoad	: true,
	autoSync	: false,
	
	storeId		: '".$nfile."',
	
	pageSize	: 15, // number display per Grid
	
	proxy: {
		type: 'ajax',
		api: {
			read    : 'c_".$nfile."/getAll',
			create	: 'c_".$nfile."/save',
			update	: 'c_".$nfile."/save',
			destroy	: 'c_".$nfile."/delete'
		},
		actionMethods: {
			read    : 'POST',
			create	: 'POST',
			update	: 'POST',
			destroy	: 'POST'
		},
		reader: {
			type            : 'json',
			root            : 'data',
			rootProperty    : 'data',
			successProperty : 'success',
			messageProperty : 'message'
		},
		writer: {
			type            : 'json',
			writeAllFields  : true,
			root            : 'data',
			encode          : true
		},
		listeners: {
			exception: function(proxy, response, operation){
				Ext.MessageBox.show({
					title: 'REMOTE EXCEPTION',
					msg: operation.getError(),
					icon: Ext.MessageBox.ERROR,
					buttons: Ext.Msg.OK
				});
			}
		}
	},
	
	constructor: function(){
		this.callParent(arguments);
	}
	
});";
		
		if ( ! write_file("./extympi/app/store/s_".$nfile.".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Store Extjs telah digenerate...!!!<br /> Lokasi : "."./extympi/app/store/s_".$nfile.".js </strong><br />";
			return 1;
		}
	}
	
	//Generate Veiw Extjs
	function CViewExtjsSF($path,$nfile,$tbl,$data){
		$tulis = "Ext.define('YMPI.view.".$data['pathjs'].".v_".$nfile."', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_".$nfile."'],
	
	title		: '".$nfile."',
	itemId		: 'List".$nfile."',
	alias       : 'widget.List".$nfile."',
	store 		: 's_".$nfile."',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex : -1,
	
	initComponent: function(){		
		this.columns = [
			";
foreach($data['fields'] as $field)
{
	if(! $field->primary_key == "1")
	{
		if($field->type == "date" || $field->type == "datetime")
		{
			$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."', renderer: Ext.util.Format.dateRenderer('d M, Y')},";
		}
		elseif($field->type == "int")
		{
			$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."'},";
		}
		elseif($field->type == "decimal")
		{
			$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."', align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				}},";
		}
		else
		{
			if($field->max_length > 20)
			{
				$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."'},";
			}
			else
				$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."'},";
		}
	}
	else
	{
		if($field->type == "date" || $field->type == "datetime")
		{
			$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."', renderer: Ext.util.Format.dateRenderer('d M, Y')},";
		}
		elseif($field->type == "int" || $field->type == "decimal")
		{
			$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."'},";
		}
		else
		{
			if($field->max_length > 20)
			{
				$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."'},";
			}
			else
				$tulis .= "{ header: '".$field->name."', dataIndex: '".$field->name."'},";
		}
			
	}		
}
		$tulis = substr($tulis,0,strlen($tulis) -1);
		$tulis .= "];
		this.dockedItems = [
			{
				xtype: 'toolbar',
				frame: true,
				items: [{
					text	: 'Add',
					iconCls	: 'icon-add',
					action	: 'create'
				}, {
					itemId	: 'btndelete',
					text	: 'Delete',
					iconCls	: 'icon-remove',
					action	: 'delete',
					disabled: true
				}, '-',{
					text	: 'Export Excel',
					iconCls	: 'icon-excel',
					action	: 'xexcel'
				}, {
					text	: 'Export PDF',
					iconCls	: 'icon-pdf',
					action	: 'xpdf'
				}, {
					text	: 'Cetak',
					iconCls	: 'icon-print',
					action	: 'print'
				}]
			},
			{
				xtype: 'pagingtoolbar',
				store: 's_".$nfile."',
				dock: 'bottom',
				displayInfo: true
			}
		];
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		this.getView().on('refresh', this.refreshSelection, this);
	},	
	
	gridSelection: function(me, record, item, index, e, eOpts){
		//me.getSelectionModel().select(index);
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);   /*Ext.defer(this.setScrollTop, 30, this, [this.getView().scrollState.top]);*/
    }

});";
		
		if ( ! write_file("./extympi/app/view/".$data['pathjs']."/v_".$nfile.".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>View Extjs telah digenerate...!!!<br /> Lokasi : "."./extympi/app/view/".$data['pathjs']."/v_".$nfile.".js </strong><br />";
			return 1;
		}
	}
	
	//Generate Form Extjs
	function CFormExtjs($path,$nfile,$tbl,$data){
		$tulis = "Ext.define('YMPI.view.".$data['pathjs'].".v_".$nfile."_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_".$nfile."_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update ".$nfile."',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
    	/*
		 * Deklarasi variable setiap field
		 */
		 ";
		foreach($data['fields'] as $field)
		{
			if(! $field->primary_key == "1")
			{
				if($field->type == "date" || $field->type == "datetime")
				{
					$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.Date', {
			name: '".$field->name."', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: '".$field->name."'
		});";
				}
				elseif($field->type == "int")
				{
					$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.Number', {
			name: '".$field->name."', /* column name of table */
			fieldLabel: '".$field->name."'";
			if(isset($field->max_length)){
				$tulis .= ",
			maxLength: ".$field->max_length." /* length of column name */";
			}
		$tulis .= "
		});";
				}
				elseif($field->type == "decimal")
				{
					$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.ux.form.NumericField', {
			name: '".$field->name."', /* column name of table */
			fieldLabel: '".$field->name."',
			useThousandSeparator: true,
			decimalPrecision: 2,
			alwaysDisplayDecimals: true,
			currencySymbol: 'Rp',
			thousandSeparator: '.',
			decimalSeparator: ','
		});";
				}
				else
				{
					if($field->max_length > 20)
					{
						$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.TextArea', {
			name: '".$field->name."', /* column name of table */
			fieldLabel: '".$field->name."'";
			if(isset($field->max_length)){
				$tulis .= ",
			maxLength: ".$field->max_length." /* length of column name */";
			}
		$tulis .= "
		});";
					}
					else
					{
						$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.Text', {
			name: '".$field->name."', /* column name of table */
			fieldLabel: '".$field->name."'";
			if(isset($field->max_length)){
				$tulis .= ",
			maxLength: ".$field->max_length." /* length of column name */";
			}
		$tulis .= "
		});";
					}
				}
			}
			else
			{
				if($field->type == "date" || $field->type == "datetime")
				{
					$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.Date', {
			itemId: '".$field->name."_field',
			name: '".$field->name."', /* column name of table */
			fieldLabel: '".$field->name."',
			format: 'Y-m-d',
			allowBlank: false /* jika primary_key */
		});";
				}
				elseif($field->type == "int")
				{
					$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.Number', {
			itemId: '".$field->name."_field',
			name: '".$field->name."', /* column name of table */
			fieldLabel: '".$field->name."',
			allowBlank: false /* jika primary_key */";
			if(isset($field->max_length)){
				$tulis .= ",
			maxLength: ".$field->max_length." /* length of column name */";
			}
		$tulis .= "});";
				}
				elseif($field->type == "decimal")
				{
					$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.ux.form.NumericField', {
			itemId: '".$field->name."_field',
			name: '".$field->name."', /* column name of table */
			fieldLabel: '".$field->name."',
			useThousandSeparator: true,
			decimalPrecision: 2,
			alwaysDisplayDecimals: true,
			currencySymbol: 'Rp',
			thousandSeparator: '.',
			decimalSeparator: ','
		});";
				}
				else
				{
					if($field->max_length > 20)
					{
						$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.TextArea', {
			itemId: '".$field->name."_field',
			name: '".$field->name."', /* column name of table */
			fieldLabel: '".$field->name."',
			allowBlank: false /* jika primary_key */";
			if(isset($field->max_length)){
				$tulis .= "
			,maxLength: ".$field->max_length." /* length of column name */";
			}
			$tulis .= "
		});";
					}
					else
						$tulis .= "
		var ".$field->name."_field = Ext.create('Ext.form.field.Text', {
			itemId: '".$field->name."_field',
			name: '".$field->name."', /* column name of table */
			fieldLabel: '".$field->name."',
			allowBlank: false /* jika primary_key */";
			if(isset($field->max_length)){
				$tulis .= ",
			maxLength: ".$field->max_length." /* length of column name */";
			}
			$tulis .= "
		});";
				}
					
			}		
		}
		 
		$tulis .= "		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
            items: [";
			foreach($data['fields'] as $field)
			{
				$tulis .= "".$field->name."_field,";
			}
			
			$tulis = substr($tulis,0,strlen($tulis) -1);
			$tulis .= "],
			
	        buttons: [{
                iconCls: 'icon-save',
                itemId: 'save',
                text: 'Save',
                disabled: true,
                action: 'save'
            }, {
                iconCls: 'icon-add',
				itemId: 'create',
                text: 'Create',
                action: 'create'
            }, {
                iconCls: 'icon-reset',
                text: 'Cancel',
                action: 'cancel'
            }]
        });
        
        this.callParent();
    }
});";
		
		if ( ! write_file("./extympi/app/view/".$data['pathjs']."/v_".$nfile."_form.js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Form Extjs telah digenerate...!!!<br /> Lokasi : "."./extympi/app/view/".$data['pathjs']."/v_".$nfile."_form.js </strong><br />";
			return 1;
		}
	}
	
	//Generate Viewport Extjs
	function CViewportSF($path,$nfile,$tbl,$data){
		$tulis = "Ext.define('YMPI.view.".$data['pathjs'].".".strtoupper($nfile)."', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.".strtoupper($nfile)."',
	
	title	: '".$nfile."',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'List".$nfile."'
			}, {
				xtype: 'v_".$nfile."_form',
				disabled: true
			}]
        });
		this.callParent(arguments);
	}
	
});";
		
		if ( ! write_file("./extympi/app/view/".$data['pathjs']."/".strtoupper($nfile).".js", $tulis))
		{
			return 0;
		}
		else
		{
			echo "<strong>Viewport Extjs telah digenerate...!!!<br /> Lokasi : "."./extympi/app/view/".$data['pathjs']."/".strtoupper($nfile).".js </strong><br />";
			return 1;
		}
	}
	
	
}
