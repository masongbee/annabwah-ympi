<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>SIMSDM-YMPI</title>

	<script type="text/javascript">
		var base_url = '<?php echo base_url();?>';
	</script>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/ext-4.2/lib/prettify/prettify.css"/>
	<link rel="stylesheet" href="<?php echo base_url();?>assets/ext-4.2/resources/KitchenSink-all.css"/>
	
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/icons.css"/>

	<script type="text/javascript" src="<?php echo base_url();?>assets/ext-4.2/ext-all-debug.js"></script>
	<!-- 
	#Jika Aplikasi SUDAH di Deployment menggunakan SDK Sencha, maka TAMBAHKAN file all-classes.js
	#Jika Aplikasi BELUM di Deployment menggunakan SDK Sencha, maka HILANGKAN file all-classes.js
	 -->
	<!-- <script type="text/javascript" src="<;?php echo base_url();?>extympi/all-classes.js"></script> -->
	
	<script src="<?php echo base_url();?>assets/ext-4.2/lib/prettify/prettify.js"></script>
	
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
	
</head>
<body background="<?php echo base_url();?>assets/images/backlogin.gif">
	<div id="loading-mask" style=""></div>
    <div id="loading">
        <div class="loading-indicator">
            <img src="<?php echo base_url();?>assets/ext-4/resources/images/loading.gif" style="margin-right:8px;float:left;vertical-align:top;"/>
        </div>
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