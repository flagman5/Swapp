<?php
/************* Membership V1.0 *******************/
/*
Released by AwesomePHP.com, under the GPL License, a
copy of it should be attached to the zip file, or
you can view it on http://AwesomePHP.com/gpl.txt
*/
/************* Membership V1.0 *******************/	

$check = mysql_query("SELECT * FROM `memb_config`");
while($each = mysql_fetch_assoc($check)){$$each['config_name'] = $each['config_value'];}

//Admin Login
if($_POST['doid'] == 1){
	
	if($CF_ADMINNAME == $_POST['username'] && $CF_ADMINPASSWORD == $_POST['password']){
		$host_name = '.'.str_replace('www.','',$_SERVER['HTTP_HOST']);
		$usersession = generate_session(100);
		setcookie("usersession", $usersession, time()+31104000000, "/", $host_name, 0);
		$_SESSION['usersession'] = $usersession;

		$insert_session = mysql_query("INSERT INTO `memb_adminsessions` 
				(`session_id`,`session_date`) VALUES ('$usersession',NOW())") or die(mysql_error());
		$is_admin = true;
		$message = 'Login successful.';
	} else {
		$message = 'Invalid username/password.';
	}
	return;
}

if($is_admin == false){ die();}

//Configuration Updates
if($_POST['doid'] == 2){
	$remove_array = array('doid','Submit');	
	foreach($_POST as $is => $what){
		if(!in_array($is,$remove_array)){
			$check = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `memb_config` WHERE `config_name`='$is'"));
			if($check[0] > 0){
				$update = mysql_query("UPDATE `memb_config` SET `config_value`='$what' WHERE `config_name`='$is' LIMIT 1");
				$message .= "$is Updated<br/>";
			} else {
				$insert = mysql_query("INSERT INTO `memb_config` (`config_name`,`config_value`) VALUES ('$is','$what')");
				$message .= "$is Added<br/>";
			}
			
		}		
	}	
}

//Member Add/Edit
if($_POST['doid'] == 3){
	//Compost fields dynamically
	$remove_array = array('doid','Submit','user_id','old_email','old_name');
	$array_fields = array();
	$array_values = array();
	$array_updates = array();
	
	// Default user settings
	if($_POST['user_id'] == NULL){
		$_POST['allow_delete'] = $CF_E_DEL;
	}
	
	// Check username uniqueness (needed for directory protectness)
	if($_POST['user_name'] != $_POST['old_name']){
		$check = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) FROM `memb_userlist` 
			WHERE `user_name`='$_POST[user_name]'"));
		if($check['user_name'] != NULL){
			$_POST['user_name'] = $_POST['user_name'].generate_session(5);
		}	
	}
	
	foreach($_POST as $is => $what){
		if(eregi('cusfield_',$is)){
			$is = trim($is);
			if($is){
				$field_id = str_replace('cusfield_','',$is);
				$_POST['custom_fields'] .= "[$field_id]{+|%|+}[$what]\n";
			}
		} else {
			if(!in_array($is,$remove_array)){
				array_push($array_fields,"`$is`");
				array_push($array_values,"'$what'");
				array_push($array_updates,"`$is`='$what'");
			}
		}
	}
	array_push($array_fields,'`custom_fields`');
	array_push($array_values,"'$_POST[custom_fields]'");
	array_push($array_updates,"`custom_fields`='$_POST[custom_fields]'");
	
	if($_POST['user_id'] == NULL){
		//New User
		$insert = mysql_query("INSERT INTO `memb_userlist` (".implode(',',$array_fields).") 
			VALUES (".implode(',',$array_values).")");
		if($insert){
			if($_POST['user_status'] == 1){
				editfule($CF_FDACCESS,'Add',$_POST['user_name'],$_POST['user_password'],'');
			}
			$message = 'User added.';
		}else{$message = 'Unable to add user.';}		
	} else {
		//Edit user
		$update = mysql_query("UPDATE `memb_userlist` SET ".implode(',',$array_updates)." 
			WHERE `user_id`='$_POST[user_id]' LIMIT 1");
		if($update){
			if($_POST['user_status'] == 1){
				editfule($CF_FDACCESS,'Edit',$_POST['user_name'],$_POST['user_password'],$_POST['old_name']);
			} else {
				editfule($CF_FDACCESS,'Remove',$_POST['user_name'],'');
			}
			$message = 'User updated.';}else{$message = 'Unable to update user.';}	
	}
}

//Custom Field Add/Edit
if($_POST['doid'] == 4){
	if($_POST['newinput'] != NULL){
		$insert = mysql_query("INSERT INTO `memb_customfds` (`field_name`,`is_required`) 
			VALUES ('$_POST[newinput]','$_POST[is_required]')");
		if($insert){$message = 'Field inserted.<br/>';}else{$message = 'Unable to add field.';}
	}
	foreach($_POST as $is => $what){
		if(eregi('fieldxy',$is)){
			$field_id = str_replace('fieldxy_','',$is);
			$is_required = $_POST["fieldxz_$field_id"];
			$update = mysql_query("UPDATE `memb_customfds` SET `field_name`='$what',`is_required`='$is_required'
				 WHERE `field_id`='$field_id' LIMIT 1");
			if($update){$message .= 'Field updated.<br/>';}else{$message .= 'Unable to update field ID#'.$field_id.'.';}
		}
	}
}

//Send mass email
if($_POST['doid'] == 5){
	if($_POST['to_emails'] != NULL){
		$email_list = explode(',',$_POST['to_emails']);
		foreach($email_list as $this_email){
			$this_email = trim($this_email);
			if($this_email){
				$mailit = send_mail($_POST['return_name'],$_POST['return_email'],$_POST['from_name'],
				$_POST['from_email'],$_POST['subject'],$_POST['email_message'],$this_email);
				if($mailit){$message .= 'Email sent to: '.$this_email.'<br/>';
				}else{$message .= 'Unable to send email to: '.$this_email.'<br/>';}	
			}
		}
	} else {
		if($_POST['send_to'] == 1){
			$query = "SELECT `user_name`,`user_email`,`user_password` FROM `memb_userlist` WHERE `user_in_list`='1'";
		} else {
			$query = "SELECT `user_name`,`user_email`,`user_password` FROM `memb_userlist`";
		}
		$get_users = mysql_query($query);
		while($each = mysql_fetch_assoc($get_users)){
			$this_message = str_replace('%username%',$each['user_name'],$_POST['email_message']);
			$this_message = str_replace('%useremail%',$each['user_email'],$this_message);
			$this_message = str_replace('%userpassword%',$each['user_password'],$this_message);
			$mailit = send_mail($_POST['return_name'],$_POST['return_email'],$_POST['from_name'],
			
				$_POST['from_email'],$_POST['subject'],$this_message,$each['user_email']);
			if($mailit){$message .= 'Email sent to: '.$each['user_email'].'<br/>';
			}else{$message .= 'Unable to send email to: '.$each['user_email'].'<br/>';}			
		}
	}
}
?>