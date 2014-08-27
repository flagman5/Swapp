<?php
/************* Membership V1.0 *******************/
/*
Released by AwesomePHP.com, under the GPL License, a
copy of it should be attached to the zip file, or
you can view it on http://AwesomePHP.com/gpl.txt
*/
/************* Membership V1.0 *******************/	

//Check User sessions (set it to NO at first)
$is_logged = false;

@session_start();

if($_SESSION['usersession'] == NULL){$_SESSION['usersession'] = $_COOKIE['usersession'];}

$check_user = @mysql_query("SELECT * FROM `memb_usersessions` WHERE `session_id`='$_SESSION[usersession]'");


if(@mysql_num_rows($check_user) > 0){
	$get_info = @mysql_fetch_array($check_user);
	$user_id = $get_info['user_id'];

	$user_info = @mysql_fetch_array(@mysql_query("SELECT * FROM `memb_userlist` WHERE `user_id`='$user_id'"));
	//Check User status
	// Status 1 means that the student is OK
	if($user_info['user_status'] != 1){
		//Remove sessions
		$remove = @mysql_query("DELETE FROM `memb_usersessions` WHERE `session_id`='$_SESSION[session_id]'");
		include('logout.php');
	} else {
		//If everything is good.. mark loged
		$is_logged = true;
	}
}

?>