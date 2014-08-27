<?php
/************* Membership V1.0 *******************/
/*
Released by AwesomePHP.com, under the GPL License, a
copy of it should be attached to the zip file, or
you can view it on http://AwesomePHP.com/gpl.txt
*/
/************* Membership V1.0 *******************/	

//Start Session
@session_start();

include('../headers/database.php');
connect();

include('admin_login.php');
include('adminfunctions.php');

if($_POST['doid'] != NULL){
	include('adminsubmits.php');	
}

$get_cd = mysql_query("SELECT * FROM `memb_config`");
while($each = mysql_fetch_assoc($get_cd)){$$each['config_name'] = $each['config_value'];}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>MemberShip V1.0 Admin</title>
<style type="text/css">
* {
	line-height:150%;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
}
a {
	color:#0000FF;
	text-decoration:none;
}
a:hover {
	text-decoration:underline;
}
a:visited {
	color:#000099;
}
</style>
</head>
<body>
<table width="100%"  border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td colspan="2"><font size="6" face="Verdana, Arial, Helvetica, sans-serif">Administrator Control Panel</font> </td>
  </tr>
  <?php
  if($message != NULL){?>
  <tr bgcolor="#FFC6C6">
    <td colspan="2"><strong><font color="#FF0000"><?=$message;?></font></strong></td>
  </tr>
  <?php } ?>
  <tr>
    <td width="20%" valign="top"><ul>
      <li><a href="?Config">Configuration</a></li>
      <li><a href="?Users">Users</a></li>
      <li><a href="?Fields">User Fields</a></li>
      <li><a href="?Mail">Mailist List</a> </li>
	  <li><a href="?Files">Protect Files/Folders</a> </li>
      <li><a href="?LogOut">LogOut</a></li>
    </ul></td>
    <td valign="top">
	<?php
	if($is_admin == true){
		$allowed_pages = array('config','users','fields','mail','files','logout');
		list($page,$first_var,$second_var,$third_var,$fourth_var) = explode('*',$_SERVER['QUERY_STRING']);
		$page = strtolower($page);
		if(in_array($page,$allowed_pages)){ include($page.'.php');}else{include('main.php');}
	} else { 
		include('main.php');
	} ?>
	</td>
  </tr>
  <tr>
    <td colspan="2"><div align="center"><a href="http://AwesomePHP.com"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Membership V1.0 by AwesomePHP.com</font></strong></a></div></td>
  </tr>
</table>
<!--
/************* Membership V1.0 *******************/
/*
Released by AwesomePHP.com, under the GPL License, a
copy of it should be attached to the zip file, or
you can view it on http://AwesomePHP.com/gpl.txt
*/
/************* Membership V1.0 *******************/	
-->
</body>
</html>
<?php
disconnect_data();
?>