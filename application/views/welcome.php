<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>PT. YMPI</title>

	<script type="text/javascript">
		var base_url = '<?php echo base_url();?>';
	</script>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/ext-4/resources/css/ext-neptune.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/ext-4/resources/css/sink.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/ext-4/src/ux/css/CheckHeader.css" />

	<script type="text/javascript" src="<?php echo base_url();?>assets/ext-4/ext-debug.js"></script>
	<!-- 
	#Jika Aplikasi SUDAH di Deployment menggunakan SDK Sencha, maka TAMBAHKAN file all-classes.js
	#Jika Aplikasi BELUM di Deployment menggunakan SDK Sencha, maka HILANGKAN file all-classes.js
	 -->
	<script type="text/javascript" src="<?php echo base_url();?>extympi/all-classes.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url();?>assets/ext-4/ext-neptune.js"></script>
	
	<!-- 
	#Jika Aplikasi SUDAH di Deployment menggunakan SDK Sencha, maka GANTI file app.js dengan app-all.js
	#Jika Aplikasi BELUM di Deployment menggunakan SDK Sencha, maka TETAP menggunakan file app.js
	 -->
    <script type="text/javascript" src="<?php echo base_url();?>extympi/app-all.js"></script>
	
	<style type="text/css">
        .employee-add {
            background-image: url('<?php echo base_url();?>assets/images/icons/fam/user_add.gif') !important;
        }

        .employee-remove {
            background-image: url('<?php echo base_url();?>assets/images/icons/fam/user_delete.gif') !important;
        }
        
        .icon-add {
            background-image: url('<?php echo base_url();?>assets/images/icons/fam/add.png') !important;
        }
        .icon-remove {
        	background-image: url('<?php echo base_url();?>assets/images/icons/fam/delete.png') !important;
        }
        .icon-form {
        	background-image: url('<?php echo base_url();?>assets/images/icons/fam/application_form.png') !important;
        }
        .icon-grid {
        	background-image: url('<?php echo base_url();?>assets/images/icons/fam/grid.png') !important;
        }
        .icon-save {
        	background-image: url('<?php echo base_url();?>assets/images/icons/fam/save.png') !important;
        }
        .icon-reset {
        	background-image: url('<?php echo base_url();?>assets/images/icons/fam/stop.png') !important;
        }
        .icon-excel {
        	background-image: url('<?php echo base_url();?>assets/images/icons/fam/page_excel.png') !important;
        }
        .icon-print {
        	background-image: url('<?php echo base_url();?>assets/images/icons/fam/printer.png') !important;
        }
        
        
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
<body background="<?php echo base_url();?>assets/images/background.png">
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