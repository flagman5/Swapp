<?php

/************* Membership V1.0 *******************/
/*
Released by AwesomePHP.com, under the GPL License, a
copy of it should be attached to the zip file, or
you can view it on http://AwesomePHP.com/gpl.txt
*/
/************* Membership V1.0 *******************/	

//Call Database & Connect
require_once('headers/database.php');
connect();

//We need functions
require_once('headers/functions.php');


//Get Admin Values
$get_cd = mysql_query("SELECT * FROM `memb_config`");
while($each = mysql_fetch_assoc($get_cd)){$$each['config_name'] = $each['config_value'];}

//If retrieving password
if($_POST['Submit'] == 'Login'){
	//Check standard fields
	if(is_valid_email($_POST['user_email']) == false){$message = 'Please enter a valid email.';}
	if($message == NULL && $_POST['user_password'] == NULL){$message = 'Please enter password.';}
	
	//Check Cpatcha
	if($message == NULL){
		$realanswer = trim(encode_decode(hex2bin($_POST['registration_id']),$CF_ENCDEC));
		if($CF_CAPTHCA == 'IMAGE'){
			if($_POST['answer'] != $realanswer){
				$message = 'Please enter the correct verification code.';
			}
		} else {
			if($realanswer != '*' AND $realanswer != $_POST['answer']){
				$message = 'Please answer the question correctly.';
			}
		}
	}
	
	//Everything OK, procceed
	if($message == NULL){
		$_POST['user_email'] = mysql_escape_string($_POST['user_email']);
		$_POST['user_password'] = mysql_escape_string($_POST['user_password']);
		$get_user = mysql_fetch_assoc(mysql_query("SELECT * FROM `memb_userlist` WHERE `user_email`='$_POST[user_email]' AND
			`user_password`='$_POST[user_password]'"));
		if($get_user['user_email'] == $_POST['user_email']){
			@session_start();
			$usersession = generate_session(100);
			$host_name = '.'.str_replace('www.','',$_SERVER['HTTP_HOST']);
			setcookie("usersession", $usersession, time()+31104000000, "/", $host_name, 0);
			$_SESSION['usersession'] = $usersession;
			$user_ip = get_ip();
			$insert_session = @mysql_query("INSERT INTO `memb_usersessions` 
				(`session_id`,`user_id`,`session_date`) VALUES ('$usersession','$get_user[user_id]',NOW())");
			$update_acces = @mysql_query("UPDATE `user_list` SET `last_access`= NOW(),`last_ip`='$user_ip'
				WHERE `user_id`='$get_user[user_id]' LIMIT 1");	
			//die('Please proceed to <a href="profile.php">profile.php</a>');
			header("Location: ../index/userhome.php");
		} else{ 
			$message = 'Invalid login credentials.';
		}			
	}
}

//Disconnect Database
disconnect_data();

include ("../common/header.php");
?>
<form name="login" id="login" method="post" action="<?=$_SERVER['PHP_SELF'];?>" style="display:inline;">
 
    <h1>User Login </h1>
    
	<?php
	if($message != NULL){
	?>
  <tr bgcolor="#FFDDDD">
    <td colspan="2"><strong><font color="#FF0000"><?=$message;?></font></strong></td>
  </tr>
  <?php } ?>
  Please enter Email: <br/>
    <input name="user_email" type="text" id="user_email" value="<?=$_POST['user_email'];?>"> 
<br/>
  Please enter Password: <br/>
    <input name="user_password" type="password" id="user_password">
<br/><br/>
    <input type="submit" name="Submit" id="Submit" value="Login">
<br/><br/>
    <a href="forgotpass.php">Forgot Password </a> - <a href="register.php">Register</a>

</form>

<!-- all required -->
</div>

<?include ("../common/sidenav.php"); ?>	

		<div class="clearer"><span></span></div>

	</div>
	
<?include ("../common/footer.php");?>