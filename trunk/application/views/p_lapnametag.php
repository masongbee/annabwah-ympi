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
	?>
	<?php
		if($i % 8 == 1){
	?>
	<table>
	<?php
		}
	?>
	<?php
		if($i % 4 >= 0){
			//jika looping ke 1 s/d 4
	?>
	<?php
			if($i % 4 == 1){
	?>
		<tr>
	<?php
			}
	?>
			<?php
				if(){
			?>
			<td>&nbsp;</td>
			<?php
				}
			?>
			<?php
			
			?>
			<td>&nbsp;</td>
			<?php
			?>
			<?php
			?>
			<td>&nbsp;</td>
			<?php
			?>
			<?php
			?>
			<td>&nbsp;</td>
			<?php
			?>
	<?php
			if($i % 4 == 0){
	?>
		</tr>
	<?php
			}
	?>
	<?php
		}elseif(($i % 8 >= 5) && ($i % 8 <= 8)){
			//jika looping ke 5 s/d 8
	?>
	<?php
			if(){
	?>
		<tr>
	<?php
			}
	?>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	<?php
		}
	?>
	<?php
		if($i % 8 == 0){
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
