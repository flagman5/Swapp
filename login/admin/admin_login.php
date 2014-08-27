<?php
/************* Membership V1.0 *******************/
/*
Released by AwesomePHP.com, under the GPL License, a
copy of it should be attached to the zip file, or
you can view it on http://AwesomePHP.com/gpl.txt
*/
/************* Membership V1.0 *******************/	

$is_admin = false;

if($_SESSION['usersession'] == NULL){$_SESSION['usersession'] = $_COOKIE['usersession'];}

$check_user = @mysql_query("SELECT * FROM `memb_adminsessions` WHERE `session_id`='$_SESSION[usersession]'");

if(@mysql_num_rows($check_user) > 0){
	$is_admin = true;
}

?>