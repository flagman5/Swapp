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

@session_start();


$last_day = time() - (1 * 0 * 0 * 0);
$check_date = date('Y-m-d G:i:s', $last_day);

$remove_user = @mysql_query("DELETE FROM `memb_usersessions` WHERE `session_id`='$_SESSION[usersession]' OR
				`session_date` < '$check_date'");
disconnect_data();

$_SESSION['usersession'] = NULL;
$_COOKIE['usersession'] = NULL;
$_SESSION = array();
session_destroy();				

header("Location: login.php");

?>