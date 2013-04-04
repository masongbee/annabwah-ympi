<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
    <title>SIMSDM-YMPI</title>
	
	<link rel="stylesheet" type="text/css" href="assets/ext-4/resources/css/ext-neptune.css" />

    <script type="text/javascript" src="assets/ext-4/ext.js"></script>
    <!--<script type="text/javascript" src="extympi/applogin/app-all.js"></script>-->
    <!--<script type="text/javascript" src="extympi/applogin/app.js"></script>-->
	

</head>

<body background="./assets/images/backlogin.png">

<?php	
	echo "<form id='F_Utama' name='F_Utama' method='post' action=" . site_url('c_utama/Send') . ">";
	echo "<table id='T_Utama' class='formku' align='center'>
			<tr class='formku'>
			<th colspan='3'>Pilih Menu</th>
			</tr>";	
			
	echo "<tr class='formku'>
			<td colspan='7'>" . form_submit('submit','Absensi') . form_submit('submit','Presensi') . form_submit('submit','Home') .
			"</tr>";
	echo "</table>";
	echo "</form>";
?>

</body>
</html>