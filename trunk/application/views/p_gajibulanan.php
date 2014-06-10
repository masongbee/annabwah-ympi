<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Cetak Slip Gaji</title>
	
	<style tyle="text/css">
	<!--
	@page { size:8.5in 11in; margin-left: 1cm; margin-right: 1cm; }
	body {
		margin-top: 0cm;
	}
	/*@media print{
		body {
			font-family: sans-serif;
			font-size: 10px;
		}
	}*/
	-->
	</style>
	
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
	
	/*.judul{*/
		/*border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		background: #fff;*/
		/*padding: 6px 6px 6px 12px;*/
		/*color: #4f6b72;*/
		/*font: normal 16px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	}*/
	
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
		font-family: Calibri;
		font-size: 9pt;
	}
	td {
		font-family: Calibri;
		font-size: 9pt;
	}
	</style>
</head>

<body>
	<?php
	$i = 1;
	foreach($records as $row){
		/**
		 * RPTAMBAHAN yang POSCETAK = 'B' (Berdiri Sendiri)
		 */
		$arr_brptambahan = explode(',', $row->BTAMBAHAN_RPTAMBAHAN);
		$btambahan_rptambahan = 0;
		foreach($arr_brptambahan as $key => $value){
			$btambahan_rptambahan+=$value;
		}
		
		/**
		 * RPTAMBAHAN yang POSCETAK = 'L' (di Luar Tambahan)
		 */
		$arr_lrptambahan = explode(',', $row->LTAMBAHAN_RPTAMBAHAN);
		$ltambahan_rptambahan = 0;
		foreach($arr_lrptambahan as $key => $value){
			$ltambahan_rptambahan+=$value;
		}
		
		$total_pendapatan = ($row->RPUPAHPOKOK + $row->RPUMSK + $row->RPTISTRI + $row->RPTANAK
							 + $row->RPTJABATAN + $row->RPTBHS + $row->RPTTRANSPORT + $row->RPTSHIFT
							 + $row->RPTPEKERJAAN + $row->RPTQCP + $row->RPTHADIR + $row->RPTLEMBUR
							 + $row->RPIDISIPLIN + $row->RPKOMPEN + $row->RPTMAKAN + $row->RPTSIMPATI
							 + $row->JTAMBAHAN_RPTAMBAHAN + $btambahan_rptambahan);
		$grandtotal_pendapatan = $total_pendapatan + $ltambahan_rptambahan;
		
		
		/**
		 * RPPOTONGAN yang POSCETAK = 'B' (Berdiri Sendiri)
		 */
		$arr_brppotongan = explode(',', $row->BPOTONGAN_RPPOTONGAN);
		$bpotongan_rppotongan = 0;
		foreach($arr_brppotongan as $key => $value){
			$bpotongan_rppotongan+=$value;
		}
		
		/**
		 * RPPOTONGAN yang POSCETAK = 'L' (di Luar Potongan)
		 */
		$arr_lrppotongan = explode(',', $row->LPOTONGAN_RPPOTONGAN);
		$lpotongan_rppotongan = 0;
		foreach($arr_lrppotongan as $key => $value){
			$lpotongan_rppotongan+=$value;
		}
		
		$total_potongan = ($row->RPPUPAHPOKOK + $row->RPPMAKAN + $row->RPPTRANSPORT + $row->RPPJAMSOSTEK
						   + $row->RPCICILAN1 + $row->RPCICILAN2 + $row->JPOTONGAN_RPPOTONGAN
						   + $row->RPPOTSP + $bpotongan_rppotongan);
		$grandtotal_potongan = $total_potongan + $lpotongan_rppotongan;
		
		$bank_transfer = $grandtotal_pendapatan - $grandtotal_potongan;
	?>
	<table cellpadding="0" cellspacing="0" style="border: 0px; padding-top: 40px;" height="180px">
		<tr>
			<td style="width: 2.1in;">&nbsp;</td>
			<td style="width: 4in; vertical-align: top;">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr align="center">
						<td colspan="3" align="center">
							<span style="font-size: 10pt;"><b>PT. YAMAHA MUSICAL PRODUCTS INDONESIA (YMPI)</b><br/></span>
							Jl. Rembang Industri I/36, PIER-Pasuruan Tlp.(0343) 740290<br/>
							<span style="font-size: 11pt; font-weight: bold;">SLIP UPAH<br/>
							KARYAWAN</span><br/>
							<span style="font-size: 10pt; font-weight: bold;">Periode : <?php print date('F-Y', strtotime($bulangaji.'01'));?></span>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td width="150" style="text-align: center; border: 1px solid #000">C O N F I D E N T I A L</td>
						<td>&nbsp;</td>
					</tr>
				</table></td>
			<td style="width: 2.8in; vertical-align: top;" align="right">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="70" align="left" style="border-left: 1px solid #000; border-top: 2px solid #000; padding-left: 5px; padding-top: 5px;"><span style="font-size: 12px;"><b>NAMA</b></span></td>
						<td style="border-top: 2px solid #000; padding-top: 5px;"><span style="font-size: 12px;"><b>:</b></span></td>
						<td style="border-top: 2px solid #000; border-right: 1px solid #000;  padding-top: 5px;"><span style="font-size: 12px;"><b><?php print $row->NAMAKAR;?></b></span></td>
					</tr>
					<tr>
						<td align="left" style="border-left: 1px solid #000; border-bottom: 1px solid #000; padding-left: 5px; padding-bottom: 5px;"><span style="font-size: 12px;"><b>NIK</b></span></td>
						<td style="border-bottom: 1px solid #000; padding-bottom: 5px;"><span style="font-size: 12px;"><b>:</b></span></td>
						<td style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding-bottom: 5px;"><span style="font-size: 12px;"><b><?php print $row->NIK;?></b></span></td>
					</tr>
					<tr>
						<td align="left" style="border-left: 1px solid #000; padding-left: 5px; padding-top: 2px;">Dept. - Sect.</td>
						<td>:</td>
						<td style="border-right: 1px solid #000; padding-right: 2px;"><?php print $row->SINGKATAN;?></td>
					</tr>
					<tr>
						<td align="left" style="border-left: 1px solid #000; padding-left: 5px; padding-top: 2px;">Jbtn. - Grade.</td>
						<td>:</td>
						<td style="border-right: 1px solid #000; padding-right: 2px;"><?php print $row->NAMALEVEL." - ".$row->GRADE;?></td>
					</tr>
					<tr>
						<td align="left" style="border-left: 1px solid #000; padding-left: 5px; padding-top: 2px;">Status</td>
						<td>:</td>
						<td style="border-right: 1px solid #000; padding-right: 2px;"><?php print $row->STATUSKAR;?></td>
					</tr>
					<tr>
						<td align="left" style="border-left: 1px solid #000; border-bottom: 1px solid #000; padding-left: 5px; padding-bottom: 2px;">Tgl Masuk</td>
						<td style="border-bottom: 1px solid #000;">:</td>
						<td style="border-bottom: 1px solid #000; border-right: 1px solid #000;"><?php print date('d-M-Y', strtotime($row->TGLMASUK));?></td>
					</tr>
				</table></td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" style="border: 0px;" height="440px">
		<tr>
			<td>Satuan Lembur x &nbsp;&nbsp;: <?php print $row->SATLEMBUR; /*print number_format($row->SATLEMBUR, 2, ',', '.');*/?></td>
			<td>&nbsp;</td>
			<td style="padding-left: 0.1in;">
				<table cellpadding="0" cellspacing="0" style="border: 0px;">
					<tr>
						<td width="40px">NPWP</td>
						<td>: <?php print $row->NPWP;?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign="top" style="width: 2.85in; padding-right: 20px;" align="left"><u><b>Pendapatan</b></u>
				<table style="width: 2.85in;" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td style="width: 1.5in">Upah Pokok</td>
						<td style="width: 0.1in">:</td>
						<td style="width: 0.1in">Rp</td>
						<td style="width: 1in" align="right"><?php print number_format($row->RPUPAHPOKOK, 0, ',', '.');?></td>
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
					<?php
					$arr_bnamaupah = explode(',', $row->BTAMBAHAN_NAMAUPAH);
					$arr_brptambahan = explode(',', $row->BTAMBAHAN_RPTAMBAHAN);
					foreach($arr_brptambahan as $key => $value){
						if($value > 0){
					?>
					<tr>
						<td><?php print $arr_bnamaupah[$key];?></td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($value, 0, ',', '.');?></td>
					</tr>
					<?php
						}
					}
					?>
					<?php
					if($row->JTAMBAHAN_RPTAMBAHAN > 0){
					?>
					<tr>
						<td>Lain-lain</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->JTAMBAHAN_RPTAMBAHAN, 0, ',', '.');?></td>
					</tr>
					<?php
					}
					?>
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
					<?php
					if($row->RPTHR > 0){
					?>
					<tr>
						<td>THR</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->RPTHR, 0, ',', '.');?></td>
					</tr>
					<?php
					}
					?>
					<?php
					$lrptambahan_count = 0;
					$lnamaupah = '';
					$lrptambahan = 0;
					$arr_lnamaupah = explode(',', $row->LTAMBAHAN_NAMAUPAH);
					$arr_lrptambahan = explode(',', $row->LTAMBAHAN_RPTAMBAHAN);
					foreach($arr_lrptambahan as $key => $value){
						if($value > 0){
							$lrptambahan_count++;
							$lnamaupah = $arr_lnamaupah[$key];
							$lrptambahan += $value;
						}
					}
					?>
					<?php
					if($lrptambahan_count > 0){
					?>
					<tr>
						<td><?php print $lnamaupah;?></td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($lrptambahan, 0, ',', '.');?></td>
					</tr>
					<?php
					}
					?>
				</table>
				</td>
			<td valign="top" style="width: 2.85in; padding-right: 20px;"><u><b>Potongan</b></u>
				<table style="width: 2.85in;" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td style="width: 1.5in;">Pot. Upah Pokok</td>
						<td style="width: 0.1in;">:</td>
						<td style="width: 0.1in;">Rp</td>
						<td style="width: 0.7in;" align="right"><?php print number_format($row->RPPUPAHPOKOK, 0, ',', '.');?></td>
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
						<td align="right"><?php print number_format($row->RPPOTSP, 0, ',', '.');?></td>
					</tr>
					<tr>
						<td>Pinjaman Perush.</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format(($row->RPCICILAN1 + $row->RPCICILAN2), 0, ',', '.');?></td>
					</tr>
					<?php
					$arr_bnamapotongan = explode(',', $row->BPOTONGAN_NAMAPOTONGAN);
					$arr_brppotongan = explode(',', $row->BPOTONGAN_RPPOTONGAN);
					foreach($arr_brppotongan as $key => $value){
						if($value > 0){
					?>
					<tr>
						<td><?php print $arr_bnamapotongan[$key];?></td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($value, 0, ',', '.');?></td>
					</tr>
					<?php
						}
					}
					?>
					<?php
					if($row->JPOTONGAN_RPPOTONGAN > 0){
					?>
					<tr>
						<td>Lain-lain</td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($row->JPOTONGAN_RPPOTONGAN, 0, ',', '.');?></td>
					</tr>
					<?php
					}
					?>
					<tr>
						<td align="right" style="border-top: 1px solid #000;"><b>Total</b></td>
						<td style="border-top: 1px solid #000;">:</td>
						<td style="border-top: 1px solid #000;">Rp</td>
						<td align="right" style="border-top: 1px solid #000;"><?php print number_format($total_potongan, 0, ',', '.');?></td>
					</tr>
					<?php
					$lrppotongan_count = 0;
					$lnamapotongan = '';
					$lrppotongan = 0;
					$arr_lnamapotongan = explode(',', $row->LPOTONGAN_NAMAPOTONGAN);
					$arr_lrppotongan = explode(',', $row->LPOTONGAN_RPPOTONGAN);
					foreach($arr_lrppotongan as $key => $value){
						if($value > 0){
							$lrppotongan_count++;
							$lnamapotongan = $arr_lnamapotongan[$key];
							$lrppotongan += $value;
						}
					}
					?>
					<?php
					if($lrppotongan_count > 0){
					?>
					<tr>
						<td><?php print $lnamapotongan;?></td>
						<td>:</td>
						<td>Rp</td>
						<td align="right"><?php print number_format($lrppotongan, 0, ',', '.');?></td>
					</tr>
					<?php
					}
					?>
					<tr>
						<td colspan="2" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000; padding-top: 3px; padding-left: 3px;">*) Tunj. Tetap</td>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" style="border-left: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; padding-top: 3px; padding-left: 3px; padding-bottom: 3px;">**) Tunj. Tidak Tetap</td>
						<td colspan="2">&nbsp;</td>
					</tr>
				</table>
				</td>
			<td valign="top" style="width: 2.8in;" align="right">
				<table style="width: 2.8in; margin-top: 40px;" cellpadding="0" cellspacing="0" border="0">
					<tr height="50">
						<td align="center" style="border-left: 1px solid #000; border-bottom: 1px solid #000; border-right: 1px solid #000; border-top: 1px solid #000; font-size: 11px; font-weight: bold;">Bank Transfer</td>
						<td align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000; border-top: 1px solid #000; font-size: 11px; font-weight: bold;" width="20">=</td>
						<td align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000; border-top: 1px solid #000; font-size: 11px; font-weight: bold;">Pendapatan</td>
						<td align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000; border-top: 1px solid #000; font-size: 11px; font-weight: bold;" width="20">-</td>
						<td align="center" style="border-bottom: 1px solid #000; border-right: 1px solid #000; border-top: 1px solid #000; font-size: 11px; font-weight: bold;">Potongan</td>
					</tr>
					<tr>
						<td align="right" style="border-left: 1px solid #000; border-bottom: 2px solid #000; border-right: 1px solid #000; padding-top: 3px; padding-right: 3px; padding-bottom: 3px; font-size: 11px; font-weight: bold;"><?php print number_format($bank_transfer, 0, ',', '.');?></td>
						<td align="center" style="border-bottom: 2px solid #000; border-right: 1px solid #000;">=</td>
						<td align="right" style="border-bottom: 2px solid #000; border-right: 1px solid #000; padding-top: 3px; padding-right: 3px; padding-bottom: 3px; font-size: 11px; font-weight: bold;"><?php print number_format($grandtotal_pendapatan, 0, ',', '.');?></td>
						<td align="center" style="border-bottom: 2px solid #000; border-right: 1px solid #000;">-</td>
						<td align="right" style="border-bottom: 2px solid #000; border-right: 1px solid #000; padding-top: 3px; padding-right: 3px; padding-bottom: 3px; font-size: 11px; font-weight: bold;"><?php print number_format($grandtotal_potongan, 0, ',', '.');?></td>
					</tr>
					<tr height="30">
						<td colspan="5" align="right">Pasuruan, <?php print date('d-F-y');?></td>
					</tr>
					<tr>
						<td colspan="4" style="font-size: 12px;"><i>Sisa Cuti per akhir <?php print date('F Y', strtotime($bulangaji.'01'));?></i></td>
						<td align="left" style="font-size: 12px;">= <i><?php print (strlen($row->SISACUTI) > 0 ? $row->SISACUTI : 0);?></i></td>
					</tr>
					<tr>
						<td colspan="5" style="font-size: 8px;">Revisi gaji lebih dari tgl.15 bulan berikutnya "tidak berlaku" (tanpa kecuali)</td>
					</tr>
				</table>
				</td>
		</tr>
	</table>
	<?php
		if($i==(sizeof($records) - 2)){
			break;
		}
	?>
	<?php
		if($i > 0 && $i % 2 == 0){
	?>
	<div style="page-break-after: always;"></div>
	<?php
		}
	?>
	<?php
		$i++;
	}
	?>
</body>
</html>
