<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>PDF Created</title>

	<style type="text/css">
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

<h1>Table: <?php print $table;?></h1>

<table class="gridtable" cellspacing="0" summary="YMPI - jenisabsen">
	<tr>
		<?php 
		foreach ($records[0] as $key => $value){
		?>
		<th><?php print $key;?></th>
		<?php 
		}
		?>
	</tr>
	<?php 
	for($i=0; $i<(sizeof($records)); $i++){
	?>
	<tr>
	<?php 
		foreach ($records[$i] as $key => $value){
	?>
		<td><?php print $value;?></td>
	<?php 
		}
	?>
	</tr>
	<?php 
	}
	?>
</table>

</body>
</html>