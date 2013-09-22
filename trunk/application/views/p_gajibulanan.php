<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>CSS Tables</title>
	
	<style type="text/css">
	/*@media screen{
		body {
			font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
			color: #000;
			background: #E6EAE9;
		}
	}
	@media print{
		body {
			font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
		}
	}*/
	
	/*a {
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
		font: italic 11px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
		text-align: right;
	}*/
	
	/*th {
		font: normal 11px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
		color: #4f6b72;
		border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		border-top: 1px solid #C1DAD7;
		letter-spacing: 2px;
		text-transform: uppercase;
		text-align: left;
		padding: 0px 0px 0px 0px;
		background: #CAE8EA url(./assets/images/bg_header.jpg) no-repeat;
	}*/
	
	/*th.nobg {
		border-top: 0;
		border-left: 0;
		border-right: 1px solid #C1DAD7;
		background: none;
	}*/
	
	/*td {
		border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		background: #fff;
		padding: 0px 6px 0px 6px;
		color: #4f6b72;
		font: normal 12px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	}*/
	
	.judul{
		/*border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		background: #fff;*/
		padding: 6px 6px 6px 12px;
		/*color: #4f6b72;*/
		font: normal 16px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	}
	
	/*td.alt {
		background: #F5FAFA;
		color: #797268;
	}
	
	th.spec {
		border-left: 1px solid #C1DAD7;
		border-top: 0;
		background: #fff url(./assets/images/bullet1.gif) no-repeat;
		font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	}
	
	th.specalt {
		border-left: 1px solid #C1DAD7;
		border-top: 0;
		background: #f5fafa url(./assets/images/bullet2.gif) no-repeat;
		font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
		color: #797268;
	}*/
	table {
		font: normal 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	}
	td {
		font: normal 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	}
	</style>
</head>

<body>
	<?php
	$i = 0;
	foreach($records as $row){
	?>
	<table cellpadding="0" cellspacing="0" style="border: 0px;" width="800px">
		<tr>
			<td><img src="<?php echo base_url();?>assets/images/yamahalogo02.png"></td>
			<td width="340">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr align="center">
						<td colspan="3" align="center">
							<b>PT. YAMAHA MUSICAL PRODUCTS INDONESIA (YMPI)</b><br/>
							Jl. Rembang Industri I/36, PIER-Pasuruan Tlp.(0343) 740290<br/>
							<span style="font-family: 'Times New Roman'; font-size: 14px; font-weight: bold;">SLIP UPAH<br/>
							KARYAWAN</span><br/>
							Periode : <?php print $bulangaji;?>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td width="150" style="text-align: center; border: 1px solid #000">C O N F I D E N T I A L</td>
						<td>&nbsp;</td>
					</tr>
				</table></td>
			<td width="260">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="70" align="left" style="border-left: 2px solid #000; border-top: 2px solid #000; padding-left: 5px; padding-top: 5px;"><span style="font-size: 12px;"><b>NAMA</b></span></td>
						<td style="border-top: 2px solid #000; padding-top: 5px;"><span style="font-size: 12px;"><b>:</b></span></td>
						<td style="border-top: 2px solid #000; border-right: 2px solid #000;  padding-top: 5px;"><span style="font-size: 12px;"><b><?php print $row->NAMAKAR;?></b></span></td>
					</tr>
					<tr>
						<td align="left" style="border-left: 2px solid #000; border-bottom: 2px solid #000; padding-left: 5px; padding-bottom: 5px;"><span style="font-size: 12px;"><b>NIK</b></span></td>
						<td style="border-bottom: 2px solid #000; padding-bottom: 5px;"><span style="font-size: 12px;"><b>:</b></span></td>
						<td style="border-bottom: 2px solid #000; border-right: 2px solid #000; padding-bottom: 5px;"><span style="font-size: 12px;"><b><?php print $row->NIK;?></b></span></td>
					</tr>
					<tr>
						<td align="left" style="border-left: 2px solid #000; padding-left: 5px; padding-top: 2px;">Dept. - Sect.</td>
						<td>:</td>
						<td style="border-right: 2px solid #000; padding-right: 2px;"><?php print $row->SINGKATAN;?></td>
					</tr>
					<tr>
						<td align="left" style="border-left: 2px solid #000; padding-left: 5px; padding-top: 2px;">Jbtn. - Grade.</td>
						<td>:</td>
						<td style="border-right: 2px solid #000; padding-right: 2px;"><?php print $row->NAMALEVEL." - ".$row->GRADE;?></td>
					</tr>
					<tr>
						<td align="left" style="border-left: 2px solid #000; padding-left: 5px; padding-top: 2px;">Status</td>
						<td>:</td>
						<td style="border-right: 2px solid #000; padding-right: 2px;"><?php print $row->STATUSKAR;?></td>
					</tr>
					<tr>
						<td align="left" style="border-left: 2px solid #000; border-bottom: 2px solid #000; padding-left: 5px; padding-bottom: 2px;">Tgl Masuk</td>
						<td style="border-bottom: 2px solid #000;">:</td>
						<td style="border-bottom: 2px solid #000; border-right: 2px solid #000;"><?php print date('d-M-Y', strtotime($row->TGLMASUK));?></td>
					</tr>
				</table></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Satuan Lembur &nbsp;&nbsp;: </td>
			<td>&nbsp;</td>
			<td align="right">NPWP : <?php print $row->NPWP;?></td>
		</tr>
		<tr>
			<td><u><b>Pendapatan</b></u>
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td>Upah Pokok</td>
						<td>:</td>
						<td>Rp</td>
						<td>2.000.000</td>
					</tr>
					<tr>
						<td>Tunj. UMSK *)</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Tunj. Jbtn / Keahlian *)</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Tunj. Keluarga *)</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Tunj. Bahasa *)</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Tunj. Transport **)</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Tunj. Shift **)</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Tunj. Pekerjaan **)</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Tunj. QCP **)</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Tunj. Kehadiran **)</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Upah Lembur</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Insentif Disiplin</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Penggantian hak cuti</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Penggantian makan</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Uang Simpati</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Revisi upah</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td align="right" style="border-top: 1px solid #000;"><b>Total</b></td>
						<td style="border-top: 1px solid #000;">:</td>
						<td style="border-top: 1px solid #000;">Rp</td>
						<td style="border-top: 1px solid #000;">&nbsp;</td>
					</tr>
				</table>
				</td>
			<td><u><b>Potongan</b></u>
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td>Pot. Upah Pokok</td>
						<td>:</td>
						<td>Rp</td>
						<td>2.000.000</td>
					</tr>
					<tr>
						<td>Pot. Makan</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Pot. Transport</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Iuran Jamsostek</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Iuran Serikat Pkj.</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Tunj. Transport **)</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Tunj. Shift **)</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Tunj. Pekerjaan **)</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Tunj. QCP **)</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Tunj. Kehadiran **)</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Upah Lembur</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Insentif Disiplin</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Penggantian hak cuti</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Penggantian makan</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Uang Simpati</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Revisi upah</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td align="right" style="border-top: 1px solid #000;"><b>Total</b></td>
						<td style="border-top: 1px solid #000;">:</td>
						<td style="border-top: 1px solid #000;">Rp</td>
						<td style="border-top: 1px solid #000;">&nbsp;</td>
					</tr>
				</table>
				</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Nama</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<?php
		if($i==10){
			break;
		}
		$i++;
	}
	?>
</body>
</html>
