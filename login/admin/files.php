<?php
/************* Membership V1.0 *******************/
/*
Released by AwesomePHP.com, under the GPL License, a
copy of it should be attached to the zip file, or
you can view it on http://AwesomePHP.com/gpl.txt
*/
/************* Membership V1.0 *******************/	
if($is_admin == false){ die();}
?>
  <table width="100%"  border="1" cellspacing="0" cellpadding="5">
    <tr>
      <td width="98%"><div align="center"><font color="#999999" size="4"><strong>How to limit  file access to only authorized users? </strong></font></div></td>
    </tr>
    <tr>
      <td bgcolor="#FFF2F2"><div align="left">
        <p>1) To protect any PHP file from being accessed except from authorized users, please this PHP code on top-most of the file:
</p>
      </div></td>
    </tr>
    <tr>
    
<td>
<div align="center">

<textarea name="textarea" rows="10" style="width:90%;" readonly>/************* Membership V1.0 *******************/
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
	die('You need to login before accessing this page.');
}

//If Database access is not needed anymore,
disconnect_data();</textarea>

</div>
</td>

</tr>

<tr>

<td>
<div align="center">
<font color="#999999" size="4"><strong>How to limit directory access to only authorized users? </strong></font>
</div>
</td>

</tr>

<tr>

<td bgcolor="#FFF2F2">
1) To protect any directory access to authorized users, please create/update an .htaccess file within the directory with this code:
<br/>
<font color="#FF0000"><strong> .fdaccess FILE MUST BE [<a href="http://AwesomePHP.com/?CHMOD" target="_blank">CHMODED</a> TO 700] OR PLACED UNDER PUBLIC FOLDER. </strong></font></td>

</tr>

<tr>

<td>
<div align="center">
<textarea name="textarea" rows="5" style="width:90%;" readonly>AuthUserFile <?=$CF_FDACCESS;?>

AuthName "Please Log In"
AuthType Basic
require valid-user</textarea>
</div>
</td>

</tr>

  </table>
