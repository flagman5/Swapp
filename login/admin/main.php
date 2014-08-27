<?php
/************* Membership V1.0 *******************/
/*
Released by AwesomePHP.com, under the GPL License, a
copy of it should be attached to the zip file, or
you can view it on http://AwesomePHP.com/gpl.txt
*/
/************* Membership V1.0 *******************/	

if($is_admin == false){
?>
<form name="login" id="login" method="post" action="?Main" style="display:inline;">
      <table width="100%"  border="1" cellspacing="0" cellpadding="2">
        <tr>
          <td colspan="2"><div align="center"><strong>Admin Login </strong></div></td>
          </tr>
        <tr>
          <td width="50%"><strong>Username:</strong></td>
          <td width="50%"><input name="username" type="text" id="username"></td>
        </tr>
        <tr>
          <td width="50%"><strong>Password:</strong></td>
          <td width="50%"><input name="password" type="password" id="password" value=""></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center">
            <input type="submit" name="Submit" value="Submit">
            <input name="doid" type="hidden" id="doid" value="1">
          </div></td>
          </tr>
      </table>
    </form>
	<?php } else { echo 'You are logged in.';}?>