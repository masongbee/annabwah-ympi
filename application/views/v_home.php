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

		function logout(){
			$.ajax({
				url: base_url+'c_action/logout',
				success: function(data){
					redirect = 'login';
            		window.location = redirect;
				}
			});
		}
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
			<!-- Memeriksa group apikasi apa saja yang username ini berhak -->

			<?php
				$app1=false;
				$app2=false;
				$app3=false;
				$app4=false;
				$app5=false;
				$app6=false;
				$app7=false;
				$app8=false;
				$app9=false;
				$app10=false;
				$app11=false;
				$app12=false;
				$app13=false;
				$app14=false;

				if (sizeof($rsgroup_name)>0) {
					foreach ($rsgroup_name as $row) {
						switch($row->GROUP_NAME) {
							case "mnjuser"     : $app1 =true; break;
							case "mnjkar"      : $app2 =true; break;
							case "presensi"    : $app3 =true; break;
							case "admlembur"   : $app4 =true; break;
							case "absensi"     : $app5 =true; break;
							case "admabsensi"  : $app6 =true; break;
							case "sistemgaji"  : $app7 =true; break;
						 	case "mnjrekrut"   : $app8 =true; break;
						 	case "trainingdev" : $app9 =true; break;
							// case "mnjshift"    : $app10=true; break;
							case "mnjjemput"   : $app10=true; break;
							case "mnjtugas"    : $app11=true; break;
						 	case "nilaikinerja": $app12=true; break;
						 	// case "spkk"        : $app14=true; break;
						}
					}
				}
			?>
                
            <table cellpadding="4px">
              <tr>
                <td>
					<form action="<?php echo base_url() ?>home" method="post">
					<input type="hidden" name="group" value="mnjuser">
					<?php if($app1) { ?>
						<a href="#" onclick="document.forms[0].submit();return false;"><img id="mnjuser" src="<?php echo base_url(); ?>assets/images/logoapp/mnjuser.png" width="120" height="120" alt="mnjuser"></a>
					<?php } else { ?>
						<img src="<?php echo base_url(); ?>assets/images/logoapp/mnjuser3.png" width="120" height="120" >
					<?php } ?>
					</form>
                </td>
                <td>
					<form action="<?php echo base_url() ?>home" method="post">
					<input type="hidden" name="group" value="mnjkar">
					<?php if($app2) { ?>
						<a href="#" onclick="document.forms[1].submit();return false;"><img id="mnjkar" src="<?php echo base_url(); ?>assets/images/logoapp/mnjkar.png" width="120" height="120" alt="mnjkar"></a>
					<?php } else { ?>
						<img src="<?php echo base_url(); ?>assets/images/logoapp/mnjkar3.png" width="120" height="120" >
					<?php } ?>
					</form>
                </td>				
                 <td>
					<form action="<?php echo base_url() ?>home" method="post">
					<input type="hidden" name="group" value="presensi">
					<?php if($app3) { ?>
						<a href="#" onclick="document.forms[2].submit();return false;"><img id="presensi" src="<?php echo base_url(); ?>assets/images/logoapp/presensi.png" width="120" height="120" alt="presensi"></a>
					<?php } else { ?>
						<img src="<?php echo base_url(); ?>assets/images/logoapp/presensi3.png" width="120" height="120" >
					<?php } ?>
					</form>
                </td>
                <td>
					<form action="<?php echo base_url() ?>home" method="post">
					<input type="hidden" name="group" value="admlembur">					
					<?php if($app4) { ?>
						<a href="#" onclick="document.forms[3].submit();return false;"><img id="admlembur" src="<?php echo base_url(); ?>assets/images/logoapp/admlembur.png" width="120" height="120" alt="admlembur"></a>
					<?php } else { ?>
						<img src="<?php echo base_url(); ?>assets/images/logoapp/admlembur3.png" width="120" height="120" >
					<?php } ?>
					</form>
                </td>            
                <td>
					<form action="<?php echo base_url() ?>home" method="post">
					<input type="hidden" name="group" value="absensi">
					<?php if($app5) { ?>
						<a href="#" onclick="document.forms[4].submit();return false;"><img id="absensi" src="<?php echo base_url(); ?>assets/images/logoapp/absensi.png" width="120" height="120" alt="absensi"></a>
					<?php } else { ?>
						<img src="<?php echo base_url(); ?>assets/images/logoapp/absensi3.png" width="120" height="120" >
					<?php } ?>
					</form>
                </td>
                <td>
					<form action="<?php echo base_url() ?>home" method="post">
					<input type="hidden" name="group" value="admabsensi">
					<?php if($app6) { ?>
						<a href="#" onclick="document.forms[5].submit();return false;"><img id="admabsensi" src="<?php echo base_url(); ?>assets/images/logoapp/admabsensi.png" width="120" height="120" alt="admabsensi"></a>
					<?php } else { ?>
						<img src="<?php echo base_url(); ?>assets/images/logoapp/admabsensi3.png" width="120" height="120" >
					<?php } ?>
					</form>
                </td>
              </tr>
              <tr>
              	<td>
					<form action="<?php echo base_url() ?>home" method="post">
					<input type="hidden" name="group" value="sistemgaji">
					<?php if($app7) { ?>
						<a href="#" onclick="document.forms[6].submit();return false;"><img id="sistemgaji" src="<?php echo base_url(); ?>assets/images/logoapp/sistemgaji.png" width="120" height="120" alt="sistemgaji"></a>
					<?php } else { ?>
						<img src="<?php echo base_url(); ?>assets/images/logoapp/sistemgaji3.png" width="120" height="120" >
					<?php } ?>
					</form>
                </td>
                <td>
					<form action="<?php echo base_url() ?>home" method="post">
					<input type="hidden" name="group" value="mnjrekrut">
					<?php if($app8) { ?>
						<a href="#" onclick="document.forms[7].submit();return false;"><img id="mnjrekrut" src="<?php echo base_url(); ?>assets/images/logoapp/mnjrekrut.png" width="120" height="120" alt="mnjrekrut"></a>
					<?php } else { ?>
						<img src="<?php echo base_url(); ?>assets/images/logoapp/mnjrekrut3.png" width="120" height="120" >
					<?php } ?>
					</form>
                </td>    
                <td>
					<form action="<?php echo base_url() ?>home" method="post">
					<input type="hidden" name="group" value="trainingdev">
					<?php if($app9) { ?>
						<a href="#" onclick="document.forms[8].submit();return false;"><img id="trainingdev" src="<?php echo base_url(); ?>assets/images/logoapp/trainingdev.png" width="120" height="120" alt="trainingdev"></a>
					<?php } else { ?>
						<img src="<?php echo base_url(); ?>assets/images/logoapp/trainingdev3.png" width="120" height="120" >
					<?php } ?>
					</form>
                </td><!--      
                <td>
					<form action="<;?php echo base_url() ?>home" method="post">
					<input type="hidden" name="group" value="mnjshift">
					<;?php if($app10) { ?>
						<a href="#" onclick="document.forms[9].submit();return false;"><img id="mnjshift" src="<;?php echo base_url(); ?>assets/images/logoapp/mnjshift.png" width="120" height="120" alt="mnjshift"></a>
					<;?php } else { ?>
						<img src="<;?php echo base_url(); ?>assets/images/logoapp/mnjshift3.png" width="120" height="120" >
					<;?php } ?>
					</form>
                </td> -->
                <td>
					<form action="<?php echo base_url() ?>home" method="post">
					<input type="hidden" name="group" value="mnjjemput">
					<?php if($app10) { ?>
						<a href="#" onclick="document.forms[9].submit();return false;"><img id="mnjjemput" src="<?php echo base_url(); ?>assets/images/logoapp/mnjjemput.png" width="120" height="120" alt="mnjjemput"></a>
					<?php } else { ?>
						<img src="<?php echo base_url(); ?>assets/images/logoapp/mnjjemput3.png" width="120" height="120" >
					<?php } ?>
					</form>
                </td>
                <td>
					<form action="<?php echo base_url() ?>home" method="post">
					<input type="hidden" name="group" value="mnjtugas">
					<?php if($app11) { ?>
						<a href="#" onclick="document.forms[10].submit();return false;"><img id="mnjtugas" src="<?php echo base_url(); ?>assets/images/logoapp/mnjtugas.png" width="120" height="120" alt="mnjtugas"></a>
					<?php } else { ?>
						<img src="<?php echo base_url(); ?>assets/images/logoapp/mnjtugas3.png" width="120" height="120" >
					<?php } ?>
					</form>
                </td>
                <td>
					<form action="<?php echo base_url() ?>home" method="post">
					<input type="hidden" name="group" value="nilaikinerja">
					<?php if($app12) { ?>
						<a href="#" onclick="document.forms[11].submit();return false;"><img id="nilaikinerja" src="<?php echo base_url(); ?>assets/images/logoapp/nilaikinerja.png" width="120" height="120" alt="nilaikinerja"></a>
					<?php } else { ?>
						<img src="<?php echo base_url(); ?>assets/images/logoapp/nilaikinerja3.png" width="120" height="120" >
					<?php } ?>
					</form>
                </td><!-- 
                <td>
					<form action="<;?php echo base_url() ?>home" method="post">
					<input type="hidden" name="group" value="spkk">
					<;?php if($app14) { ?>
						<a href="#" onclick="document.forms[13].submit();return false;"><img id="spkk" src="<;?php echo base_url(); ?>assets/images/logoapp/spkk.png" width="120" height="120" alt="spkk"></a>
					<;?php } else { ?>
						<img src="<;?php echo base_url(); ?>assets/images/logoapp/spkk3.png" width="120" height="120" >
					<;?php } ?>
					</form>
                </td> -->
              </tr>
            </table>        
			<div align="right">
				<a href="#" onclick="javascript:logout();">Logout</a>&nbsp;
			</div>	
        </div>
	</div>
</body>
</html>