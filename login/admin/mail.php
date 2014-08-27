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
  <form name="mail" id="mail" method="post" action="?Mail" style="display:inline;">
    <table width="100%"  border="1" cellspacing="0" cellpadding="5">
    <tr>
      <td colspan="2"><div align="center"><font color="#999999" size="4"><strong>Mailing List </strong></font></div></td>
    </tr>
    <tr>
      <td width="50%">Subject:</td>
      <td width="50%"><input name="subject" type="text" id="subject" value="<?=$_POST['subject'];?>"></td>
    </tr>
    <tr>
      <td width="50%"><p>Message [HTML]:</p>
      <p>You can use [ONLY if sending to system users]: <br/>
        <font color="#0000FF">%username%<br/>
      %useremail%<br/>
      %userpassword%</font></p></td>
      <td width="50%"><textarea name="email_message" rows="10" id="email_message" style="width:90%;"><?=$_POST['email_message'];?></textarea></td>
    </tr>
    <tr>
      <td width="50%">Return Name: </td>
      <td width="50%"><input name="return_name" type="text" id="return_name" value="<?=$_POST['return_name'];?>"></td>
    </tr>
    <tr>
      <td>Return Email: </td>
      <td width="50%"><input name="return_email" type="text" id="return_email" value="<?=$_POST['return_email'];?>"></td>
    </tr>
    <tr>
      <td>From Name: </td>
      <td width="50%"><input name="from_name" type="text" id="from_name" value="<?=$_POST['from_name'];?>"></td>
    </tr>
    <tr>
      <td>From Email: </td>
      <td width="50%"><input name="from_email" type="text" id="from_email" value="<?=$_POST['from_email'];?>"></td>
    </tr>
    <tr>
      <td width="50%">Send to system users: </td>
      <td width="50%">
	  <?php if($_POST['send_to'] == '1' || $_POST['send_to'] == NULL){$sel = ' checked';}else{$sel=NULL;}?>
	  <input name="send_to" type="radio" value="1"<?=$sel;?>>
        Those opted in <?php if($_POST['send_to'] == '2'){$sel = ' checked';}else{$sel=NULL;}?>
        <input name="send_to" type="radio" value="2"<?=$sel;?>>
        All users </td>
    </tr>
    <tr>
      <td>or Send to emails: [comma delimited] </td>
      <td width="50%"><input name="to_emails" type="text" id="to_emails" value="<?=$_POST['to_emails'];?>"></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">
        <input type="submit" name="Submit" value="Send">
        <input name="doid" type="hidden" id="doid" value="5">
        </div></td>
    </tr>
  </table>
</form>