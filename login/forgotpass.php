<?php
include ("../common/header.php");
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

//We need functions
require_once('headers/functions.php');
	
//Get Admin Values
$get_cd = mysql_query("SELECT * FROM `memb_config`");
while($each = mysql_fetch_assoc($get_cd)){$$each['config_name'] = $each['config_value'];}

//If retrieving password
if($_POST['Submit'] == 'Get Pass'){
	//Check standard fields
	if(is_valid_email($_POST['user_email']) == false){$message = 'Please enter a valid email.';}
	
	//Check Cpatcha
	if($message == NULL){
		$realanswer = trim(encode_decode(hex2bin($_POST['registration_id']),$CF_ENCDEC));
		if($CF_CAPTHCA == 'IMAGE'){
			if($_POST['answer'] != $realanswer){
				$message = 'Please enter the correct verification code.';
			}
		} else {
			if($realanswer != '*' AND $realanswer != $_POST['answer']){
				$message = 'Please answer the question correctly.';
			}
		}
	}
	
	//Everything OK, procceed
	if($message == NULL){
		//Get password
		$_POST['user_email'] = mysql_escape_string($_POST['user_email']);
		$get_pass = mysql_fetch_assoc(mysql_query("SELECT `user_password`
			FROM `memb_userlist` WHERE `user_email`='$_POST[user_email]'"));
			
		if($get_pass['user_password'] != NULL){
			$message = 'Your password has been sent to your email.';
			send_password($_POST['user_email'],$get_pass['user_password'],$CF_SITENAME,$CF_SITEEMAIL);
			$_POST = NULL;
		}else{
			$message = 'Unable to find account.';
		}		
	}
}

//Disconnect Database
disconnect_data();
?>
<form name="forgotpass" id="forgotpass" method="post" action="<?=$_SERVER['PHP_SELF'];?>" style="display:inline;">
    <h1>Forgot password</h1>
    <br/><br/>
	<?php
	if($message != NULL){
	?>
  <tr bgcolor="#FFDDDD">
    <td colspan="2"><strong><font color="#FF0000"><?=$message;?></font></strong></td>
  </tr>
  <?php }
else { ?>
  Please enter your email: <br/>
    <input name="user_email" type="text" id="user_email" value="<?=$_POST['user_email'];?>"> 
      <br/>
  <?php
  if($CF_CAPTHCA == 'IMAGE'){?>
  <tr>
    <td>Enter Verification Code: </td>
    <td><table  border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td class="acont"><div align="center">
            <?php
				$referenceid = md5(mktime()*rand());
				//Generate the random string
				$chars = array("a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J","k",
				"K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T","u","U","v",
				"V","w","W","x","X","y","Y","z","Z","1","2","3","4","5","6","7","8","9");
				$length = $CF_LENGH;
				$textstr = "";
				for ($i=0; $i<$length; $i++) {
				   $textstr .= $chars[rand(0, count($chars)-1)];
				}
				$new_string = encode_decode($textstr,$CF_ENCDEC);
				$image_link = bin2hex($new_string);
				?>
            <img src="captcha.php?code=<?=$image_link;?>">
            <input name="registration_id" type="hidden" id="registration_id" value="<?=$image_link;?>">
        </div></td>
      </tr>
      <tr>
        <td class="acont"><div align="center">
            <input name="answer" type="text" id="answer">
        </div></td>
      </tr>
    </table></td>
  </tr>
<?php
  } else {
  	$f = fopen($CF_QUESTIONFILE,'r');
	while($t = fread($f,102465)){
		$content .= $t;
	}
	fclose($f);
	$content = trim(preg_replace('/\/\*.*\*\//ism', '', $content));

	$temp = explode("\n",$content);
	$random = rand(0,count($temp)-1);
	$rand = $temp[$random];
	list($question,$registration_id) = explode('\n\\',$rand);
	$registration_id = bin2hex(encode_decode($registration_id,$CF_ENCDEC));
  ?>
  <br/>Answer this: <strong><?=$question;?></strong>
    <input name="answer" type="text" id="answer" value="<?=$_POST['answer'];?>">
      <input name="registration_id" type="hidden" id="registration_id" value="<?=$registration_id;?>">

  <?php } ?>
  <br/><br/>
  
      <input type="submit" name="Submit" id="Submit" value="Get Pass">
    
    <br/><br/>
  [<a href="login.php">Login</a> - <a href="register.php">Register</a>] 
  
</form>
<?}?>
<!-- all required -->
</div>

<?include ("../common/sidenav.php"); ?>	

		<div class="clearer"><span></span></div>

	</div>
	
<?include ("../common/footer.php");?>