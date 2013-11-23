<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Tag Name</title>
	
	<style tyle="text/css">
	<!--
	@page { size:8.27in 5.83in; margin-left: 1cm; margin-right: 1cm; }
	-->
	</style>
	
	<style type="text/css">
	.judul{
		/*border-right: 1px solid #C1DAD7;
		border-bottom: 1px solid #C1DAD7;
		background: #fff;*/
		padding: 6px 6px 6px 12px;
		/*color: #4f6b72;*/
		font: normal 16px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	}
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
	$i = 1;
	foreach($records as $row){
		$modi = $i % 8;
	?>
	<?php
		if($modi == 1){
	?>
	<table>
	<?php
		}
	?>
	<?php
		if($modi >= 1 && $modi <= 4){
			//jika looping ke 1 s/d 4
	?>
	<?php
			if($modi == 1){
	?>
		<tr>
	<?php
			}
	?>
			<?php
				if($modi == 1){
			?>
			<td width="225px" height="339px" style="background-image: url('<?php print base_url();?>assets/images/<?php print $row->WARNATAGR.$row->WARNATAGG.$row->WARNATAGB;?>.png'); background-size: 6cm 9cm;">
				<div style="text-align: center;">
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold; text-decoration: underline;"><?php print $row->NAMAKAR?></span><br/>
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold;"><?php print $row->NIK?></span>
				</div>
			</td>
			<?php
				}
			?>
			<?php
				if($modi == 2){
			?>
			<td width="225px" height="339px" style="background-image: url('<?php print base_url();?>assets/images/<?php print $row->WARNATAGR.$row->WARNATAGG.$row->WARNATAGB;?>.png'); background-size: 6cm 9cm;">
				<div style="text-align: center;">
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold; text-decoration: underline;"><?php print $row->NAMAKAR?></span><br/>
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold;"><?php print $row->NIK?></span>
				</div>
			</td>
			<?php
				}
			?>
			<?php
				if($modi == 3){
			?>
			<td width="225px" height="339px" style="background-image: url('<?php print base_url();?>assets/images/<?php print $row->WARNATAGR.$row->WARNATAGG.$row->WARNATAGB;?>.png'); background-size: 6cm 9cm;">
				<div style="text-align: center;">
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold; text-decoration: underline;"><?php print $row->NAMAKAR?></span><br/>
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold;"><?php print $row->NIK?></span>
				</div>
			</td>
			<?php
				}
			?>
			<?php
				if($modi == 4){
			?>
			<td width="225px" height="339px" style="background-image: url('<?php print base_url();?>assets/images/<?php print $row->WARNATAGR.$row->WARNATAGG.$row->WARNATAGB;?>.png'); background-size: 6cm 9cm;">
				<div style="text-align: center;">
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold; text-decoration: underline;"><?php print $row->NAMAKAR?></span><br/>
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold;"><?php print $row->NIK?></span>
				</div>
			</td>
			<?php
				}
			?>
	<?php
			if($modi == 4){
	?>
		</tr>
	<?php
			}
	?>
	<?php
		}else{
			//jika looping ke 5 s/d 8
	?>
	<?php
			if($modi == 5){
	?>
		<tr>
	<?php
			}
	?>
			<?php
				if($modi == 5){
			?>
			<td width="225px" height="339px" style="background-image: url('<?php print base_url();?>assets/images/<?php print $row->WARNATAGR.$row->WARNATAGG.$row->WARNATAGB;?>.png'); background-size: 6cm 9cm;">
				<div style="text-align: center;">
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold; text-decoration: underline;"><?php print $row->NAMAKAR?></span><br/>
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold;"><?php print $row->NIK?></span>
				</div>
			</td>
			<?php
				}
			?>
			<?php
				if($modi == 6){
			?>
			<td width="225px" height="339px" style="background-image: url('<?php print base_url();?>assets/images/<?php print $row->WARNATAGR.$row->WARNATAGG.$row->WARNATAGB;?>.png'); background-size: 6cm 9cm;">
				<div style="text-align: center;">
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold; text-decoration: underline;"><?php print $row->NAMAKAR?></span><br/>
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold;"><?php print $row->NIK?></span>
				</div>
			</td>
			<?php
				}
			?>
			<?php
				if($modi == 7){
			?>
			<td width="225px" height="339px" style="background-image: url('<?php print base_url();?>assets/images/<?php print $row->WARNATAGR.$row->WARNATAGG.$row->WARNATAGB;?>.png'); background-size: 6cm 9cm;">
				<div style="text-align: center;">
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold; text-decoration: underline;"><?php print $row->NAMAKAR?></span><br/>
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold;"><?php print $row->NIK?></span>
				</div>
			</td>
			<?php
				}
			?>
			<?php
				if($modi == 0){
			?>
			<td width="225px" height="339px" style="background-image: url('<?php print base_url();?>assets/images/<?php print $row->WARNATAGR.$row->WARNATAGG.$row->WARNATAGB;?>.png'); background-size: 6cm 9cm;">
				<div style="text-align: center;">
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold; text-decoration: underline;"><?php print $row->NAMAKAR?></span><br/>
					<span style="font-size: 12pt; font-family: Arial; font-weight: bold;"><?php print $row->NIK?></span>
				</div>
			</td>
			<?php
				}
			?>
	<?php
			if($modi == 0){
	?>
		</tr>
	<?php
			}
	?>
	<?php
		}
	?>
	<?php
		if($modi == 0){
	?>
	</table>
	<?php
		}
	?>
	<?php
		$i++;
	}
	?>
</body>
</html>
