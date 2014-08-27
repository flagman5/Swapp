
<?php
include ("../common/header.php");
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

//Get user count
$user_count = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `memb_userlist`"));

//Is registartion enabled?
if($CF_E_REG == 2){ echo 'Registration is currently disabled.';return;}

//Did the limit exceed allowed registrations?
if($CF_USER_LIMIT > 0 && $CF_USER_LIMIT <= $user_count[0]){ echo 'User registration limit of '.$user_count[0].' was exceeded.';return;}

//Get custom fields
$array_custom = array();
$get_fields = mysql_query("SELECT * FROM `memb_customfds`");
while($each = mysql_fetch_assoc($get_fields)){
	array_push($array_custom,$each);
}

//If verifying registration
if($_GET['ver'] != NULL){
	$user_status = mysql_escape_string($_GET['ver']);
	$get_inf = mysql_fetch_assoc(mysql_query("SELECT `user_id`,`user_email`,`user_password` 
		FROM `memb_userlist` WHERE `user_status`='$user_status'"));
	$update = mysql_query("UPDATE `memb_userlist` SET `user_status`='1' WHERE `user_status`='$user_status' LIMIT 1");
	if($update){
		$message = 'Your email has been confirmed. Thanks.';
		editfule($CF_FDACCESS,'Add',$get_inf['user_email'],$get_inf['user_password'],'');
	}else{$message = 'Unable to confirm email. Internal Error.';}
	
	//put a new row in feedback totals
	$usersid = $get_inf['user_id'];
	$insert = mysql_query("INSERT INTO feedback_totals VALUES(NULL, '".$usersid."', 0, 0, '',0)") or die(mysql_error());;
}

//If registering
if($_POST['Submit'] == 'Register'){
	//Check standard fields
	if(validate_username($_POST['user_name']) == false){$message = 'Please enter a valid user name (numbers/letters only).';}
	if($message == NULL && is_valid_email($_POST['user_email']) == false){$message = 'Please enter a valid email.';}
	if($message == NULL && $_POST['user_password2'] == NULL){$message = 'Please enter password twice.';}
	if($message == NULL && $_POST['user_password2'] != $_POST['user_password']){$message = 'Passwords do not match.';}
	
	//Check Email existence
	if($message == NULL){
		$_POST['user_email'] = mysql_escape_string($_POST['user_email']);
		$get_email = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `memb_userlist` 
			WHERE `user_email`='$_POST[user_email]'"));
		if($get_email[0] > 0){ $message = 'This email is already registered. Please enter a different email, or use forgot password link.';}	
	}	
	
	//Check username existence
	if($message == NULL){
		$_POST['user_name'] = mysql_escape_string($_POST['user_name']);
		$get_name = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `memb_userlist` 
			WHERE `user_name`='$_POST[user_name]'"));
		if($get_name[0] > 0){ $message = 'This username is already registered. Please enter a different email, or use forgot password link.';}	
	}	
	
	
	//Check Custom Fields
	if($message == NULL){
		foreach($array_custom as $field){
			if($_POST['fieldxy_'.$field['field_id']] == NULL && $field['is_required'] == 1){
				$message .= 'Please enter the '.$field['field_name'].' field<br/>';
			}
		}
	}
	
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
		// Default user settings
		$_POST['allow_delete'] = $CF_E_DEL;
		
		$remove_array = array('Submit','answer','registration_id','user_password2');
		$array_fields = array();
		$array_values = array();
		
		//Compose needed info
		$random_string = generate_session(50);
		if($CF_E_VER == 2){
			$_POST['user_status'] = '1';			
		}else{
			$_POST['user_status'] = $random_string;
			$ad_text = '<br/>Before using this account, you will need to confirm the email we just send you.';		
		}
		$_POST['register_date'] = date("Y-m-d G:i:s");
		$_POST['last_access'] = date("Y-m-d G:i:s");
		$_POST['last_ip'] = get_ip();
		
		//Compose Query
		foreach($_POST as $is => $what){
			$what = mysql_escape_string($what);
			if(eregi('fieldxy_',$is)){
				$is = trim($is);
				if($is){
					$field_id = str_replace('fieldxy_','',$is);
					$_POST['custom_fields'] .= "[$field_id]{+|%|+}[$what]\n";
				}
			} else {
				if(!in_array($is,$remove_array)){
					array_push($array_fields,"`$is`");
					array_push($array_values,"'$what'");
				}
			}
		}
		array_push($array_fields,'`custom_fields`');
		array_push($array_values,"'$_POST[custom_fields]'");
		
		//Make Query
		$insert = mysql_query("INSERT INTO `memb_userlist` (".implode(',',$array_fields).") 
			VALUES (".implode(',',$array_values).")") or die(mysql_error());
			
		if($insert){
			$sent = welcome_user($_POST,$random_string,$CF_SITENAME,$CF_SITEEMAIL);
			if($_POST['user_status'] == 1){
				editfule($CF_FDACCESS,'Add',$_POST['user_name'],$_POST['user_password'],'');
			}
			if($sent){
				$message = 'Your account has been added.'.$ad_text;
			} else {
				$message = 'Account added, but we were unable to delivery confirmation email. Please contact us ASAP.';
			}
			$_POST = NULL;
		}else{
			$message = 'Unable to register your account. Internal Error.';
		}		
	}
}

//Disconnect Database
disconnect_data();
?>
<form name="register" id="register" method="post" action="<?=$_SERVER['PHP_SELF'];?>">

    <h1>Register for an account</h1>

	<?php
	if($message != NULL){
	?>
  <tr bgcolor="#FFDDDD">
    <td colspan="2"><strong><font color="#FF0000"><?=$message;?></font></strong></td>
  </tr><br/>
  <?php }
else { ?>

    Your name: <br/>
    <input name="user_name" type="text" id="user_name" value="<?=$_POST['user_name'];?>"> 
      [Required] <br/>
  Your email: <br/>
    <input name="user_email" type="text" id="user_email" value="<?=$_POST['user_email'];?>">
      [Required<?php if($CF_E_VER == 1){echo ' AND requires confirmation';}?>] <br/><br/>
	  
  Keep yourself updated on new stuff, care to join our mailing list? 
  <?php if($_POST['user_in_list'] == '1' || $_POST['user_in_list'] == NULL){$sel = ' checked';}else{$sel=NULL;}?>
      <input name="user_in_list" type="radio" value="1"<?=$sel;?>>
Yes
<?php if($_POST['user_in_list'] == '2'){$sel = ' checked';}else{$sel=NULL;}?>
<input name="user_in_list" type="radio" value="2"<?=$sel;?>>
No
<br/><br/>
    Please select a password: <br/>
    <input name="user_password" type="password" id="user_password" value="">
      [Required] <br/>

    Re-enter the password: <br/>
    <input name="user_password2" type="password" id="user_password2">
      [Required] <br/>

  <?php
  foreach($array_custom as $field){
  ?>
  <tr>
    <td><?=$field['field_name'];?></td>
    <td width="50%"><input name="fieldxy_<?=$field['field_id'];?>" type="text" id="fieldxy_<?=$field['field_id'];?>" value="<?=$_POST['fieldxy_'.$field[field_id]];?>">
<?php if($field['is_required'] == 1){ echo '[Required]';} ?> </td>
  </tr>
  <?php } 
  if($CF_CAPTHCA == 'IMAGE'){?>
  <tr>
    <td>Enter Verification Code: </td>
    <td><table  border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td class="acont"><div align="center">
            <?php
				$referenceid = md5(mktime()*rand());
				//Generate the random string
				$chars = array("a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J","k",
				"K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T","u","U","v",
				"V","w","W","x","X","y","Y","z","Z","1","2","3","4","5","6","7","8","9");
				$length = $CF_LENGH;
				$textstr = "";
				for ($i=0; $i<$length; $i++) {
				   $textstr .= $chars[rand(0, count($chars)-1)];
				}
				$new_string = encode_decode($textstr,$CF_ENCDEC);
				$image_link = bin2hex($new_string);
				?>
            <img src="captcha.php?code=<?=$image_link;?>">
            <input name="registration_id" type="hidden" id="registration_id" value="<?=$image_link;?>">
        </div></td>
      </tr>
      <tr>
        <td class="acont"><div align="center">
            <input name="answer" type="text" id="answer">
        </div></td>
      </tr>
    </table></td>
  </tr>
  <?php
  } else {
  	$f = fopen($CF_QUESTIONFILE,'r');
	while($t = fread($f,102465)){
		$content .= $t;
	}
	fclose($f);
	$content = trim(preg_replace('/\/\*.*\*\//ism', '', $content));
	$temp = explode("\n",$content);
	$random = rand(0,count($temp)-1);
	$rand = $temp[$random];
	list($question,$registration_id) = explode('\n\\',$rand);
	$registration_id = bin2hex(encode_decode($registration_id,$CF_ENCDEC));
  ?>
	<br/>Answer this: <strong><?=$question;?></strong> 
    <input name="answer" type="text" id="answer" value="<?=$_POST['answer'];?>">
      <input name="registration_id" type="hidden" id="registration_id" value="<?=$registration_id;?>"><br/>
 
  <?php } ?>
    <br/><br/>  <input type="submit" name="Submit" id="Submit" value="Register"><br/><br/>

    <a href="login.php">Login</a> - <a href="forgotpass.php">Forgot Password</a>
</form>
<?}?>
<!-- all required -->
</div>

<?include ("../common/sidenav.php"); ?>	

		<div class="clearer"><span></span></div>

	</div>
	
<?include ("../common/footer.php");?>