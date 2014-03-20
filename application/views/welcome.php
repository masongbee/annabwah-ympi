<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>SIMSDM-YMPI</title>

	<script type="text/javascript">
		var base_url = '<?php echo base_url();?>';
		var group_icon = '<?php echo $this->session->userdata('group_icon');?>';
		var username = '<?php echo $this->session->userdata('user_name');?>';
		var user_nik = '<?php echo $this->session->userdata('user_nik');?>';
		var nik_hrd = '<?php echo $this->auth->initialization()->NIK_HRD ?>';
		var max_kar = '<?php echo $this->auth->initialization()->MAX_KAR ?>';
	</script>

	<!-- <link rel="stylesheet" type="text/css" href="<;?php echo base_url();?>assets/ext-4.2/lib/prettify/prettify.css"/> -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/ext-4.2/resources/KitchenSink-all.css"/>
	
	<!--<link rel="stylesheet" type="text/css" href="<;?php echo base_url();?>assets/ext-4.2/src/ux/css/CheckHeader.css" />-->
	
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/icons.css"/>
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/ext-4.2/src/ux/grid/css/GridFilters.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/ext-4.2/src/ux/grid/css/RangeMenu.css" />

	<script type="text/javascript" src="<?php echo base_url();?>assets/ext-4.2/ext-all-debug.js"></script>
	<!-- 
	#Jika Aplikasi SUDAH di Deployment menggunakan SDK Sencha, maka TAMBAHKAN file all-classes.js
	#Jika Aplikasi BELUM di Deployment menggunakan SDK Sencha, maka HILANGKAN file all-classes.js
	 -->
	<!-- <script type="text/javascript" src="<;?php echo base_url();?>extympi/all-classes.js"></script> -->
	
	<!-- <script src="<;?php echo base_url();?>assets/ext-4.2/lib/prettify/prettify.js"></script> -->
	
	<!-- 
	#Jika Aplikasi SUDAH di Deployment menggunakan SDK Sencha, maka GANTI file app.js dengan app-all.js
	#Jika Aplikasi BELUM di Deployment menggunakan SDK Sencha, maka TETAP menggunakan file app.js
	 -->
    <script type="text/javascript" src="<?php echo base_url();?>extympi/app/app.js"></script>
	
	<style type="text/css">
		#loading-mask{
	        background-color:white;
	        height:100%;
	        position:absolute;
	        left:0;
	        top:0;
	        width:100%;
	        z-index:20000;
	    }
	    #loading{
	        height:auto;
	        position:absolute;
	        left:45%;
	        top:40%;
	        padding:2px;
	        z-index:20001;
	    }
	    #loading a {
	        color:#225588;
	    }
	    #loading .loading-indicator{
	        background:white;
	        color:#444;
	        font:bold 13px Helvetica, Arial, sans-serif;
	        height:auto;
	        margin:0;
	        padding:10px;
	    }
	    #loading-msg {
	        font-size: 10px;
	        font-weight: normal;
	    }
    </style>
	
	<script type="text/javascript">
		function show(){
			var DaysOfWeek = new Array(7);
				DaysOfWeek[0] = "Minggu";
				DaysOfWeek[1] = "Senin";
				DaysOfWeek[2] = "Selasa";
				DaysOfWeek[3] = "Rabu";
				DaysOfWeek[4] = "Kamis";
				DaysOfWeek[5] = "Jum'at";
				DaysOfWeek[6] = "Sabtu";

			var MonthsOfYear = new Array(12);
				MonthsOfYear[0] = "Januari";
				MonthsOfYear[1] = "Februari";
				MonthsOfYear[2] = "Maret";
				MonthsOfYear[3] = "April";
				MonthsOfYear[4] = "Mei";
				MonthsOfYear[5] = "Juni";
				MonthsOfYear[6] = "Juli";
				MonthsOfYear[7] = "Agustus";
				MonthsOfYear[8] = "September";
				MonthsOfYear[9] = "Oktober";
				MonthsOfYear[10] = "November";
				MonthsOfYear[11] = "Desember";
				
			if (!document.all&&!document.getElementById)
				return
			thelement=document.getElementById? document.getElementById("tick2"): document.all.tick2;
			var Digital=new Date();
			
			var day = Digital.getDay();
			var mday = Digital.getDate();
			var month = Digital.getMonth();
			var year = Digital.getFullYear();
			
			var hours=Digital.getHours();
			var minutes=Digital.getMinutes();
			var seconds=Digital.getSeconds();
			/*var dn="PM";
			if (hours<12)
			dn="AM";
			if (hours>12)
			hours=hours-12;
			if (hours==0)
			hours=12;*/
			if (minutes<=9)
			minutes="0"+minutes;
			if (seconds<=9)
			seconds="0"+seconds;
			//var ctime=DaysOfWeek[day]+", "+mday+" "+MonthsOfYear[month]+" "+year+" - "+hours+":"+minutes+":"+seconds+" "+dn;
			var ctime=DaysOfWeek[day]+", "+mday+" "+MonthsOfYear[month]+" "+year+" - "+hours+":"+minutes+":"+seconds;
			thelement.innerHTML=ctime;
			setTimeout("show()",1000);
		}
		window.onload=show;
	</script>
	
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/date.js"></script>
	
</head>
<body>
	<div id="loading-mask" style=""></div>
    <div id="loading">
        <div class="loading-indicator">
            <img src="<?php echo base_url();?>assets/ext-4/resources/images/loading.gif" style="margin-right:8px;float:left;vertical-align:top;"/>
        </div>
    </div>
	
	<div id="info">
		<span id="tick2"></span> | 
	</div>
    
    <script type="text/javascript">
    Ext.onReady(function() {

        (Ext.defer(function() {
            var hideMask = function () {
                Ext.get('loading').remove();
                Ext.fly('loading-mask').animate({
                    opacity:0,
                    remove:true
                });
            };

            Ext.defer(hideMask, 250);

        },500));
    });
    </script>
</body>
</html>