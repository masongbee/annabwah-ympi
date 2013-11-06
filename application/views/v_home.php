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
            <table cellpadding="4px">
              <tr>
                <td>
					<form action="<?php echo base_url() ?>login" method="post">
					<input type="hidden" name="group" value="absensi">
					<a href="#" onclick="document.forms[0].submit();return false;"><img id="absensi" src="<?php echo base_url(); ?>assets/images/logoapp/absensi.png" width="120" height="120" alt="absensi"></a>
					</form>
                </td>
                <td>
					<form action="<?php echo base_url() ?>login" method="post">
					<input type="hidden" name="group" value="presensi">
					<a href="#" onclick="document.forms[1].submit();return false;"><img id="presensi" src="<?php echo base_url(); ?>assets/images/logoapp/presensi.png" width="120" height="120" alt="presensi"></a>
					</form>
                </td>
                <td>
					<form action="<?php echo base_url() ?>login" method="post">
					<input type="hidden" name="group" value="mnjjemput">
					<a href="#" onclick="document.forms[2].submit();return false;"><img id="mnjjemput" src="<?php echo base_url(); ?>assets/images/logoapp/mnjjemput.png" width="120" height="120" alt="mnjjemput"></a>
					</form>
                </td>
                <td>
					<form action="<?php echo base_url() ?>login" method="post">
					<input type="hidden" name="group" value="mnjkar">
					<a href="#" onclick="document.forms[3].submit();return false;"><img id="mnjkar" src="<?php echo base_url(); ?>assets/images/logoapp/mnjkar.png" width="120" height="120" alt="mnjkar"></a>
					</form>
                </td>
                <td>
					<form action="<?php echo base_url() ?>login" method="post">
					<input type="hidden" name="group" value="mnjrekrut">
					<a href="#" onclick="document.forms[4].submit();return false;"><img id="mnjrekrut" src="<?php echo base_url(); ?>assets/images/logoapp/mnjrekrut.png" width="120" height="120" alt="mnjrekrut"></a>
					</form>
                </td>    
                <td>
					<form action="<?php echo base_url() ?>login" method="post">
					<input type="hidden" name="group" value="sistemgaji">
					<a href="#" onclick="document.forms[5].submit();return false;"><img id="sistemgaji" src="<?php echo base_url(); ?>assets/images/logoapp/sistemgaji.png" width="120" height="120" alt="sistemgaji"></a>
					</form>
                </td>     
                <td>
					<form action="<?php echo base_url() ?>login" method="post">
					<input type="hidden" name="group" value="admlembur">
					<a href="#" onclick="document.forms[6].submit();return false;"><img id="admlembur" src="<?php echo base_url(); ?>assets/images/logoapp/admlembur.png" width="120" height="120" alt="admlembur"></a>
					</form>
                </td>            
              </tr>
              <tr>
                <td>
					<form action="<?php echo base_url() ?>login" method="post">
					<input type="hidden" name="group" value="mnjshift">
					<a href="#" onclick="document.forms[7].submit();return false;"><img id="mnjshift" src="<?php echo base_url(); ?>assets/images/logoapp/mnjshift.png" width="120" height="120" alt="mnjshift"></a>
					</form>
                </td>
                <td>
					<form action="<?php echo base_url() ?>login" method="post">
					<input type="hidden" name="group" value="mnjtugas">
					<a href="#" onclick="document.forms[8].submit();return false;"><img id="mnjtugas" src="<?php echo base_url(); ?>assets/images/logoapp/mnjtugas.png" width="120" height="120" alt="mnjtugas"></a>
					</form>
                </td>
                <td>
					<form action="<?php echo base_url() ?>login" method="post">
					<input type="hidden" name="group" value="mnjuser">
					<a href="#" onclick="document.forms[9].submit();return false;"><img id="mnjuser" src="<?php echo base_url(); ?>assets/images/logoapp/mnjuser.png" width="120" height="120" alt="mnjuser"></a>
					</form>
                </td>
                <td>
					<form action="<?php echo base_url() ?>login" method="post">
					<input type="hidden" name="group" value="nilaikinerja">
					<a href="#" onclick="document.forms[10].submit();return false;"><img id="nilaikinerja" src="<?php echo base_url(); ?>assets/images/logoapp/nilaikinerja.png" width="120" height="120" alt="nilaikinerja"></a>
					</form>
                </td>
                <td>
					<form action="<?php echo base_url() ?>login" method="post">
					<input type="hidden" name="group" value="spkk">
					<a href="#" onclick="document.forms[11].submit();return false;"><img id="spkk" src="<?php echo base_url(); ?>assets/images/logoapp/spkk.png" width="120" height="120" alt="spkk"></a>
					</form>
                </td>
                <td>
					<form action="<?php echo base_url() ?>login" method="post">
					<input type="hidden" name="group" value="trainingdev">
					<a href="#" onclick="document.forms[12].submit();return false;"><img id="trainingdev" src="<?php echo base_url(); ?>assets/images/logoapp/trainingdev.png" width="120" height="120" alt="trainingdev"></a>
					</form>
                </td>     
                <td>
					<form action="<?php echo base_url() ?>login" method="post">
					<input type="hidden" name="group" value="admabsensi">
					<a href="#" onclick="document.forms[13].submit();return false;"><img id="admabsensi" src="<?php echo base_url(); ?>assets/images/logoapp/admabsensi.png" width="120" height="120" alt="admabsensi"></a>
					</form>
                </td>   
              </tr>
            </table>        
        </div>
	</div>
</body>
</html>