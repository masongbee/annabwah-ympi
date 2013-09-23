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
		$total_pendapatan = ($row->RPUPAHPOKOK + $row->RPUMSK + $row->RPTISTRI + $row->RPTANAK
							 + $row->RPTJABATAN + $row->RPTBHS + $row->RPTTRANSPORT + $row->RPTSHIFT
							 + $row->RPTPEKERJAAN + $row->RPTQCP + $row->RPTHADIR + $row->RPTLEMBUR
							 + $row->RPIDISIPLIN + $row->RPKOMPEN + $row->RPTMAKAN + $row->RPTSIMPATI);
		
		$total_potongan = ($row->RPPUPAHPOKOK + $row->RPPMAKAN + $row->RPPTRANSPORT + $row->RPPJAMSOSTEK
						   + $row->RPCICILAN1 + $row->RPCICILAN2);
		
		$bank_transfer = $total_pendapatan - $total_potongan;
	?>
	<table cellpadding="0" cellspacing="0" style="border: 0px;" width="800px" height="500px">
		<tr>
			<td width="260"><img src="<?php echo base_url();?>assets/images/yamahalogo_bgwhite.png" width="200"></td>
			<td width="280">
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
			<td>Satuan Lembur &nbsp;&nbsp;: </td>
			<td>&nbsp;</td>
			<td align="right">NPWP : <?php print $row->NPWP;?></td>
		</tr>
		<tr>
			<td valign="top"><u><b>Pendapatan</b></u>
				<table width="200" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="120">Upah Pokok</td>
						<td width="10">:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPUPAHPOKOK, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Tunj. UMSK *)</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPUMSK, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Tunj. Jbtn / Keahlian *)</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPTJABATAN, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Tunj. Keluarga *)</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format(($row->RPTISTRI + $row->RPTANAK), 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Tunj. Bahasa *)</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPTBHS, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Tunj. Transport **)</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPTTRANSPORT, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Tunj. Shift **)</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPTSHIFT, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Tunj. Pekerjaan **)</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPTPEKERJAAN, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Tunj. QCP **)</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPTQCP, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Tunj. Kehadiran **)</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPTHADIR, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Upah Lembur</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPTLEMBUR, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Insentif Disiplin</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPIDISIPLIN, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Penggantian hak cuti</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPKOMPEN, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Penggantian makan</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPTMAKAN, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Uang Simpati</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPTSIMPATI, 0, ',', '.');?></td>
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
						<td align="right" style="border-top: 1px solid #000;"><?php print number_format($total_pendapatan, 0, ',', '.');?></td>
					</tr>
				</table>
				</td>
			<td valign="top"><u><b>Potongan</b></u>
				<table width="200" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="120">Pot. Upah Pokok</td>
						<td width="10">:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPPUPAHPOKOK, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Pot. Makan</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPPMAKAN, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Pot. Transport</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPPTRANSPORT, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Iuran Jamsostek</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPPJAMSOSTEK, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Iuran Serikat Pkj.</td>
						<td>:</td>
						<td>Rp</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Pinjaman Perush.</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format(($row->RPCICILAN1 + $row->RPCICILAN2), 0, ',', '.');?></td>
					</tr>
					<tr>
						<td align="right" style="border-top: 1px solid #000;"><b>Total</b></td>
						<td style="border-top: 1px solid #000;">:</td>
						<td style="border-top: 1px solid #000;">Rp</td>
						<td align="right" style="border-top: 1px solid #000;"><?php print number_format($total_potongan, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td colspan="2" style="border-left: 2px solid #000; border-top: 2px solid #000; border-right: 2px solid #000; padding-top: 3px; padding-left: 3px;">*) Tunj. Tetap</td>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" style="border-left: 2px solid #000; border-bottom: 2px solid #000; border-right: 2px solid #000; padding-top: 3px; padding-left: 3px; padding-bottom: 3px;">**) Tunj. Tidak Tetap</td>
						<td colspan="2">&nbsp;</td>
					</tr>
				</table>
				</td>
			<td valign="top">
				<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 40px;">
					<tr height="50">
						<td align="center" style="border-left: 2px solid #000; border-bottom: 2px solid #000; border-right: 2px solid #000; border-top: 2px solid #000; font-size: 11px; font-weight: bold;">Bank Transfer</td>
						<td align="center" style="border-bottom: 2px solid #000; border-right: 2px solid #000; border-top: 2px solid #000; font-size: 11px; font-weight: bold;" width="20">=</td>
						<td align="center" style="border-bottom: 2px solid #000; border-right: 2px solid #000; border-top: 2px solid #000; font-size: 11px; font-weight: bold;">Pendapatan</td>
						<td align="center" style="border-bottom: 2px solid #000; border-right: 2px solid #000; border-top: 2px solid #000; font-size: 11px; font-weight: bold;" width="20">-</td>
						<td align="center" style="border-bottom: 2px solid #000; border-right: 2px solid #000; border-top: 2px solid #000; font-size: 11px; font-weight: bold;">Potongan</td>
					</tr>
					<tr>
						<td align="right" style="border-left: 2px solid #000; border-bottom: 2px solid #000; border-right: 2px solid #000; padding-top: 3px; padding-right: 3px; padding-bottom: 3px; font-size: 11px; font-weight: bold;"><?php print number_format($bank_transfer, 0, ',', '.');?></td>
						<td align="center" style="border-bottom: 2px solid #000; border-right: 2px solid #000;">=</td>
						<td align="right" style="border-bottom: 2px solid #000; border-right: 2px solid #000; padding-top: 3px; padding-right: 3px; padding-bottom: 3px; font-size: 11px; font-weight: bold;"><?php print number_format($total_pendapatan, 0, ',', '.');?></td>
						<td align="center" style="border-bottom: 2px solid #000; border-right: 2px solid #000;">-</td>
						<td align="right" style="border-bottom: 2px solid #000; border-right: 2px solid #000; padding-top: 3px; padding-right: 3px; padding-bottom: 3px; font-size: 11px; font-weight: bold;"><?php print number_format($total_potongan, 0, ',', '.');?></td>
					</tr>
					<tr height="30">
						<td colspan="5" align="right">Pasuruan, <?php print date('d-M-y');?></td>
					</tr>
					<tr>
						<td colspan="5" style="font-size: 12px;"><i>Sisa Cuti per akhir Agustus 2013</i></td>
					</tr>
					<tr>
						<td colspan="5" style="font-size: 8px;">Revisi gaji lebih dari tgl.15 bulan berikutnya "tidak berlaku" (tanpa kecuali)</td>
					</tr>
				</table>
				</td>
		</tr>
	</table>
	<div style="page-break-after: always;"></div>
	<?php
		if($i==10){
			break;
		}
		$i++;
	}
	?>
</body>
</html>
