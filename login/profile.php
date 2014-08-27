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

//Call functions
require_once('headers/functions.php');

//Login Check Page
require_once('headers/logincheck.php');

if($is_logged == false){
	//Disconnect Database
	disconnect_data();
	//die('You need to login before accessing this page.');
	header("Location: login.php");
}
include ("../common/header.php");
//Get custom fields
$get_fields = mysql_query("SELECT * FROM `memb_customfds`");
while($each = mysql_fetch_assoc($get_fields)){
	$array_custom[$each['field_id']] = array($each['field_name'],$each['is_required']);
}

//Get Admin Values
$get_cd = mysql_query("SELECT * FROM `memb_config`");
while($each = mysql_fetch_assoc($get_cd)){$$each['config_name'] = $each['config_value'];}

//If changing email
if($_GET['vernew'] != NULL){
	$code = encode_decode(hex2bin($_GET['vernew']),$CF_ENCDEC);
	list($user_id,$user_email,$new_email) = explode('++_++',$code);

	$check = mysql_fetch_assoc(mysql_query("SELECT `user_id`,`user_password` FROM `memb_userlist` 
		WHERE `user_id`='$user_id' AND `user_email`='$user_email'"));
	
	if($check['user_id'] == $user_id){
		$random_string = generate_session(50);

		if($CF_E_VER == 2){
			$_POST['user_status'] = '1';			
		}else{
			$_POST['user_status'] = $random_string;
			$ad_text = '<br/>IMPORTANT: Before using this account, you will need to confirm the email we just send you. You will not be able to login or view profile till then.';		
		}
		$update = mysql_query("UPDATE `memb_userlist` 
			SET `user_status`='$_POST[user_status]',`user_email`='$new_email' WHERE `user_id`='$user_info[user_id]' LIMIT 1");
		if($update){	
			$_POST['user_password'] = $check['user_password'];
			$_POST['user_email'] = $new_email; 
			$sent = welcome_user($_POST,$random_string,$CF_SITENAME,$CF_SITEEMAIL);	
			if($send){
				$message = 'Your email has been updated.'.$ad_text;
			} else {
				$message = 'Profile updated, but we were unable to delivery confirmation email. Please contact us ASAP.';
			}
		}
	} else {
		$message = 'Malformed request.';
	}	
}

//If profile update
if($_POST['Submit'] == 'Update Profile'){
	//Check General Fields

	if($_POST['user_password'] != $user_info['user_password']){$message = 'Please enter current password to update profile fields.';}
	if($message == NULL && validate_username($_POST['user_name']) == false){$message = 'Please enter a valid user name (numbers/letters only).';}
	if($message == NULL && is_valid_email($_POST['user_email']) == false){$message = 'Please enter a valid email.';}
	

	//Check for new email
	if($message == NULL && $_POST['user_email'] != $user_info['user_email']){
		$link_info = bin2hex(encode_decode("$user_info[user_id]++_++$user_info[user_email]++_++$_POST[user_email]",$CF_ENCDEC));
		$is_mail = send_change($user_info['user_name'],$user_info['user_email'],$_POST['user_email'],$link_info,
			$CF_SITENAME,$CF_SITEEMAIL);
		if($is_mail){$message_e1 = 'You need to confirm the email we sent to change to the new email address.<br/>';
		}else{ $message_e1 = 'Unable to send email confirmation.<br/>';}
	}
	
	//If delete account
	if($message == NULL AND $_POST['deleteaccount'] == 'yes' AND $CF_E_DEL == 1 AND $user_info['allow_delete'] == 1){
		$remove = mysql_query("DELETE FROM `memb_userlist` WHERE `user_id`='$user_info[user_id]' LIMIT 1");
		if($remove){ include('logout.php');}else{$message = 'Unable to remove account. Internal Error.';}
	}
	
	//If updating passwords
	if($message == NULL && $_POST['new_password'] != NULL){
		if($_POST['new_password'] != $_POST['new_password2']) {
			$message_e1 = "New passwords do not match, try again";
		}
		else {
			$_POST['new_password'] = mysql_escape_string($_POST['new_password']);
			$update = mysql_query("UPDATE `memb_userlist` SET `user_password`='$_POST[new_password]' 
				WHERE `user_id`='$user_info[user_id]' LIMIT 1");
			if($update){
				editfule($CF_FDACCESS,'Edit',$_POST['user_name'],$_POST['new_password'],$_POST['user_name']);
				$message_e2 = 'Password updated.<br/>';}else{$message_e2 = 'Unable to update password.<br/>';}	
		}
	}
	
	// Check for new username
	if($message == NULL && $_POST['user_name'] != $user_info['user_name']){
		$_POST['user_name'] = mysql_escape_string($_POST['user_name']);
		$get_name = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `memb_userlist` 
			WHERE `user_name`='$_POST[user_name]'"));
		if($get_name[0] > 0){
			$message_e3 = 'This username is already registered. Please enter a different name, or use forgot password link.<br/>';
		} else {
			$update = mysql_query("UPDATE `memb_userlist` SET `user_name`='$_POST[user_name]' 
				WHERE `user_id`='$user_info[user_id] LIMIT 1");
			if($update){
				editfule($CF_FDACCESS,'Edit',$_POST['user_name'],$_POST['user_password'],$user_info['old_name']);
				$message_e3 = 'Username updated.<br/>';
			} else {
				$message_e3 = 'Unable to update username. Internal error.<br/>';
			}	
		}
	}
	
	//If updating fields
	if($message == NULL){
		$remove_array = array('new_password','Submit','deleteaccount','user_password','user_email');
		$array_updates = array();
		
		//Loop through variables and create query
		foreach($_POST as $is => $what){
			$is = mysql_escape_string($is);
			$what = mysql_escape_string($what);
			if(eregi('cusfield_',$is)){
				$is = trim($is);
				if($is){
					$field_id = str_replace('cusfield_','',$is);
					if($array_custom[$field_id][1] == 1 && $what == NULL){
						$message = "<br/>Field ".$array_custom[$field_id][0]." is required";
						break;
					} else {
						$_POST['custom_fields'] .= "[$field_id]{+|%|+}[$what]\n";
					}
				}
			} else {
				if(!in_array($is,$remove_array)){
					array_push($array_updates,"`$is`='$what'");
				}
			}
		}	
		array_push($array_updates,"`custom_fields`='$_POST[custom_fields]'");
		
		if($message == NULL){
			//Edit user
			$update = mysql_query("UPDATE `memb_userlist` SET ".implode(',',$array_updates)." 
				WHERE `user_id`='$user_info[user_id]' LIMIT 1");
			if($update){$message = 'Profile updated.';}else{$message = 'Unable to update profile.Internal Error';}
		}
		$message = $message_e1.$message_e2.$message_e3.$message;
		
	}
	
}

//Get User info
$_POST = mysql_fetch_assoc(mysql_query("SELECT * FROM `memb_userlist` WHERE `user_id`='$user_info[user_id]'"));

?>
<form name="profile" id="profile" method="post" action="<?=$_SERVER['PHP_SELF'];?>" style="display:inline;">


    <h1>Your Account Information </h1>
  <?php
	if($message != NULL){
	?>
  <tr bgcolor="#FFDDDD">
    <td colspan="2"><strong><font color="#FF0000">
      <?=$message;?>
    </font></strong></td>
  </tr>
  <?php } ?>
  <br/>
   Username:<br/>
    <input name="user_name" type="text" id="user_name" value="<?=$_POST['user_name'];?>">
      [Required] <br/>
  Your email: <br/>
    <input name="user_email" type="text" id="user_email" value="<?=$_POST['user_email'];?>">
      [Required
        <?php if($CF_E_VER == 1){echo ' AND requires confirmation if changed.';}?>
        ] <br/><br/>
  Keep yourself updated on new stuff, care to join our mailing list? 
    <?php if($_POST['user_in_list'] == '1' || $_POST['user_in_list'] == NULL){$sel = ' checked';}else{$sel=NULL;}?>
        <input name="user_in_list" type="radio" value="1"<?=$sel;?>>
      Yes
      <?php if($_POST['user_in_list'] == '2'){$sel = ' checked';}else{$sel=NULL;}?>
      <input name="user_in_list" type="radio" value="2"<?=$sel;?>>
      No<br/><br/>
  <?php
	$get_fields = mysql_query("SELECT * FROM `memb_customfds`");
	$recrod_nums = mysql_num_rows($get_fields);
	if($recrod_nums <= 0){
		echo '<tr><td colspan="2"><strong>No custom fields on system.</strong></td></tr>';
	} else {
		$array_list = explode("\n",$_POST['custom_fields']);
		foreach($array_list as $line){
			$line = trim($line);
			if($line){
				//Format
				list($id,$value) = explode('{+|%|+}',$line);
				$id = substr($id,1,-1);$value = substr($value,1,-1);
				$_POST["cusfield_$id"] = $value;
			}
		}
		while($each = mysql_fetch_assoc($get_fields)){?>
   <tr>
      <td><?=$each['field_name'];?>:</td>
      <td width="50%"><input name="cusfield_<?=$each['field_id'];?>" type="text" id="cusfield_<?=$each['field_id'];?>" value="<?=$_POST['cusfield_'.$each['field_id']];?>"></td>
    </tr>
  <?php } 
  }
  if($CF_E_DEL == 1 AND $_POST['allow_delete'] == 1){?>
	<br/><br/>
	<strong></strong><font color="#FF0000">DELETE ACCOUNT (can't be undone):
      </font></strong>
      <input name="deleteaccount" type="checkbox" id="deleteaccount" value="yes">
      Check To Delete Account <strong><font size="2">[You will be automatically loged out] </font></strong>
  <?php } ?>
    <br/><br/>
	
	Enter current password to update profile:<br/>
    <input name="user_password" type="password" id="user_password" value="">
   
    <br/>Change a new password:    <br/>   
    <br/>Enter new password: <br/>
   <input name="new_password" type="password" id="new_password">
   <br/>Enter new password again: <br/>
   <input name="new_password2" type="password2" id="new_password2">
   <br/><br/>
    <input type="submit" name="Submit" id="Submit" value="Update Profile">
    
	<br/><br/>
	<a href="logout.php">[Log out]</a> 
	
</form>
<?php
//Disconnect Database
disconnect_data();
?>

<!-- all required -->
</div>

<?include ("../common/sidenav.php"); ?>	

		<div class="clearer"><span></span></div>

	</div>
	
<?include ("../common/footer.php");?>