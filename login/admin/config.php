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
<form name="config" id="config" method="post" action="?Config" style="display:inline;">
  <table width="100%"  border="1" cellspacing="0" cellpadding="5">
    <tr>
      <td colspan="2"><div align="center"><font color="#999999" size="4"><strong>System Configurations</strong></font></div></td>
    </tr>
    <tr>
      <td colspan="2"><font color="#0000FF"><strong>General:</strong></font></td>
    </tr>
    <tr>
      <td width="50%">CAPTHCA Type: </td>
      <td width="50%">
	  <?php if($CF_CAPTHCA == 'IMAGE'){$sel = ' checked';}else{$sel=NULL;}?>
	  <input name="CF_CAPTHCA" type="radio" value="IMAGE"<?=$sel;?>>
        Image Based 
		<?php if($CF_CAPTHCA == 'TEXT' || $CF_CAPTHCA == NULL){$sel = ' checked';}else{$sel=NULL;}?>
        <input name="CF_CAPTHCA" type="radio" value="TEXT"<?=$sel;?>>
        Text [Questions] based </td>
    </tr>
    <tr>
      <td width="50%">Captcha Backgrounds [comma delimieted]: </td>
      <td width="50%"><textarea name="CF_BACKGROUNDS" id="CF_BACKGROUNDS"><?=$CF_BACKGROUNDS;?></textarea></td>
    </tr>
    <tr>
      <td width="50%">Captcha Font Files  [comma delimieted]: </td>
      <td width="50%"><textarea name="CF_FONTS" id="CF_FONTS"><?=$CF_FONTS;?></textarea></td>
    </tr>
    <tr>
      <td width="50%">Captcha Font Size: </td>
      <td width="50%"><input name="CF_SIZE" type="text" id="CF_SIZE" value="<?=$CF_SIZE;?>"></td>
    </tr>
    <tr>
      <td width="50%">Captcha Character Length: </td>
      <td><input name="CF_LENGH" type="text" id="CF_LENGH" value="<?=$CF_LENGH;?>"></td>
    </tr>
    <tr>
      <td>Question List File:</td>
      <td width="50%"><input name="CF_QUESTIONFILE" type="text" id="CF_QUESTIONFILE" value="<?=$CF_QUESTIONFILE;?>">
        [<a href="http://AwesomePHP.com/?CHMOD" target="_blank">CHMODED</a> <strong><font color="#FF0000">700</font></strong>] </td>
    </tr>
    <tr>
      <td>Encoding/Decoding String:</td>
      <td width="50%"><input name="CF_ENCDEC" type="text" id="CF_ENCDEC" value="<?=$CF_ENCDEC;?>"></td>
    </tr>
    <tr>
      <td>Site Name: </td>
      <td><input name="CF_SITENAME" type="text" id="CF_SITENAME" value="<?=$CF_SITENAME;?>"></td>
    </tr>
    <tr>
      <td>Site Email: </td>
      <td width="50%"><input name="CF_SITEEMAIL" type="text" id="CF_SITEEMAIL" value="<?=$CF_SITEEMAIL;?>"></td>
    </tr>
    <tr>
      <td colspan="2"><font color="#0000FF"><strong>User Options:</strong></font></td>
    </tr>
    <tr>
      <td width="50%">Enable Registration: </td>
      <td width="50%">
	  <?php if($CF_E_REG == '1' || $CF_E_REG == NULL){$sel = ' checked';}else{$sel=NULL;}?>
	  <input name="CF_E_REG" type="radio" value="1"<?=$sel;?>>
        Yes 
		<?php if($CF_E_REG == '2'){$sel = ' checked';}else{$sel=NULL;}?>
        <input name="CF_E_REG" type="radio" value="2"<?=$sel;?>>
        No</td>
    </tr>
    <tr>
      <td width="50%">User Limit: </td>
      <td width="50%"><input name="CF_USER_LIMIT" type="text" id="CF_USER_LIMIT" value="<?=$CF_USER_LIMIT;?>"> 
        [0 - No Limit]</td>
    </tr>
    <tr>
      <td width="50%">Require Email Verification: </td>
      <td width="50%">
	  <?php if($CF_E_VER == '1' || $CF_E_VER == NULL){$sel = ' checked';}else{$sel=NULL;}?>
	  <input name="CF_E_VER" type="radio" value="1"<?=$sel;?>>
Yes
<?php if($CF_E_VER == '2'){$sel = ' checked';}else{$sel=NULL;}?>
  <input name="CF_E_VER" type="radio" value="2"<?=$sel;?>>
No</td>
    </tr>
    <tr>
      <td>Allow Account Deletetion:</td>
      <td width="50%">
	  <?php if($CF_E_DEL == '1' || $CF_E_DEL == NULL){$sel = ' checked';}else{$sel=NULL;}?>
	  <input name="CF_E_DEL" type="radio" value="1"<?=$sel;?>>
Yes
<?php if($CF_E_DEL == '2'){$sel = ' checked';}else{$sel=NULL;}?>
  <input name="CF_E_DEL" type="radio" value="2"<?=$sel;?>>
No</td>
    </tr>
    <tr>
      <td>.fdaccess file location [Absolute Location]: </td>
      <td width="50%"><input name="CF_FDACCESS" type="text" id="CF_FDACCESS" value="<?=$CF_FDACCESS;?>">
      [<a href="http://AwesomePHP.com/?CHMOD" target="_blank">CHMODED</a> <strong><font color="#FF0000">700</font></strong>] </td>
    </tr>
    <tr>
      <td colspan="2"><font color="#0000FF"><strong>Admin Options: </strong></font></td>
    </tr>
    <tr>
      <td>Username:</td>
      <td width="50%"><input name="CF_ADMINNAME" type="text" id="CF_ADMINNAME" value="<?=$CF_ADMINNAME;?>"></td>
    </tr>
    <tr>
      <td>Password:</td>
      <td width="50%"><input name="CF_ADMINPASSWORD" type="text" id="CF_ADMINPASSWORD" value="<?=$CF_ADMINPASSWORD;?>"></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
        <input type="submit" name="Submit" value="Submit">
        <input name="doid" type="hidden" id="doid" value="2">
      </div></td>
    </tr>
  </table>
</form>
