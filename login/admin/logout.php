<?php

$last_day = time() - (1 * 0 * 0 * 0);
$check_date = date('Y-m-d G:i:s', $last_day);

$remove_user = @mysql_query("DELETE FROM `memb_adminsession` WHERE `session_id`='$_SESSION[usersession]' OR
				`session_date` < '$check_date'");

$_SESSION['usersession'] = NULL;
$_COOKIE['usersession'] = NULL;
$_SESSION = array();
session_destroy();				

echo "You are logged out.";


?>