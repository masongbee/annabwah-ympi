<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to YMPI</title>
	<link href = "<?php echo base_url(); ?>assets/css/login.style.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo base_url(); ?>assets/js/jquery-2.0.3.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/script.js"></script>
	
	<script type="text/javascript">
		var base_url = '<?php echo base_url();?>';
		var url_now = window.location.href;
	</script>
    <style type="text/css">
		img:hover{
			position:relative;
			right:2px;
			}
		img:active{
			position:relative;
			top:2px;
			}
    </style>
</head>
<body background="<?php echo base_url();?>assets/images/bg.png">
    <div id="container_home" align="center">
        <div id="logo"></div> 
        <div id="menu" align="center"> 
            <table cellpadding="15px">
              <tr>
                <td>
                  <a href="<?php echo base_url() ?>login"><img id="absensi" src="<?php echo base_url(); ?>assets/images/logoapp/absensi.png" width="120" height="120" alt="absensi"></a>
                </td>
                <td>
                  <a href="<?php echo base_url() ?>login"><img id="presensi" src="<?php echo base_url(); ?>assets/images/logoapp/presensi.png" width="120" height="120" alt="presensi"></a>
                </td>
                <td>
                  <a href="<?php echo base_url() ?>login"><img id="mnjjemput" src="<?php echo base_url(); ?>assets/images/logoapp/mnjjemput.png" width="120" height="120" alt="mnjjemput"></a>
                </td>
                <td>
                  <a href="<?php echo base_url() ?>login"><img id="mnjkar" src="<?php echo base_url(); ?>assets/images/logoapp/mnjkar.png" width="120" height="120" alt="mnjkar"></a>
                </td>
                <td>
                  <a href="<?php echo base_url() ?>login"><img id="mnjrekrut" src="<?php echo base_url(); ?>assets/images/logoapp/mnjrekrut.png" width="120" height="120" alt="mnjrekrut"></a>
                </td>    
                <td>
                  <a href="<?php echo base_url() ?>login"><img id="sistemgaji" src="<?php echo base_url(); ?>assets/images/logoapp/sistemgaji.png" width="120" height="120" alt="sistemgaji"></a>
                </td>            
              </tr>
              <tr>
                <td>
                  <a href="<?php echo base_url() ?>login"><img id="mnjshift" src="<?php echo base_url(); ?>assets/images/logoapp/mnjshift.png" width="120" height="120" alt="mnjshift"></a>
                </td>
                <td>
                  <a href="<?php echo base_url() ?>login"><img id="mnjtugas" src="<?php echo base_url(); ?>assets/images/logoapp/mnjtugas.png" width="120" height="120" alt="mnjtugas"></a>
                </td>
                <td>
                  <a href="<?php echo base_url() ?>login"><img id="mnjuser" src="<?php echo base_url(); ?>assets/images/logoapp/mnjuser.png" width="120" height="120" alt="mnjuser"></a>
                </td>
                <td>
                  <a href="<?php echo base_url() ?>login"><img id="nilaikinerja" src="<?php echo base_url(); ?>assets/images/logoapp/nilaikinerja.png" width="120" height="120" alt="nilaikinerja"></a>
                </td>
                <td>
                  <a href="<?php echo base_url() ?>login"><img id="spkk" src="<?php echo base_url(); ?>assets/images/logoapp/spkk.png" width="120" height="120" alt="spkk"></a>
                </td>
                <td>
                  <a href="<?php echo base_url() ?>login"><img id="trainingdev" src="<?php echo base_url(); ?>assets/images/logoapp/trainingdev.png" width="120" height="120" alt="trainingdev"></a>
                </td>
              </tr>
            </table>        
        </div>
	</div>
</body>
</html>