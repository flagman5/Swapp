<?php
/************* Membership V1.0 *******************/
/*
Released by AwesomePHP.com, under the GPL License, a
copy of it should be attached to the zip file, or
you can view it on http://AwesomePHP.com/gpl.txt
*/
/************* Membership V1.0 *******************/	
if($is_admin == false){ die();}

if($first_var == 'Remove'){
	$get_email = mysql_fetch_assoc(mysql_query("SELECT `user_email` FROM `memb_userlist` WHERE `user_id`='$second_var'"));
	
	$remove_user = mysql_query("DELETE FROM `memb_userlist` WHERE `user_id`='$second_var' LIMIT 1");
	if($remove_user){$message = 'User removed.';}else{$message = 'Unable to remove user.';}
	$second_var = NULL;
	editfule($CF_FDACCESS,'Remove',"\n",$get_email['user_email'],'');	
} else {
	if(is_numeric($first_var)){	$_POST['user_id'] = $first_var;}
}

if(!is_numeric($_POST['user_id']) AND $first_var != 'Add'){	
	$get_list = "SELECT * FROM `memb_userlist`";
	$is_count = @mysql_query($get_list);
	$display_limit = '10'; 
	$items_count = @mysql_num_rows($is_count); 
	if($items_count > $display) { 
		$page_count = ceil ($items_count/$display_limit); 
	} else { 
		$page_count = 1; 
	}
	if(is_numeric($second_var)){ 
	    $start = $second_var; 
	} else { 
	    $start = 0; 
	} 
	$get_users = @mysql_query($get_list . " ORDER BY `user_id` DESC LIMIT $start,$display_limit");
?>
  <table width="100%"  border="1" cellspacing="0" cellpadding="5">
    <tr>
      <td colspan="4"><div align="center"><font color="#999999" size="4"><strong>User List [<a href="?Users*Add">Add</a>] </strong></font></div></td>
    </tr>
	<?php
	if($items_count > $display_limit){
	?>
    <tr>
      <td colspan="4"><div align="center"><?php include('pagination.php');?></div></td>
    </tr>
	<?php } ?>
    <tr bgcolor="#CCCCCC">
      <td width="26%"><div align="center"><strong>User Name </strong></div></td>
      <td width="26%"><div align="center"><strong>User Email </strong></div></td>
      <td width="26%"><div align="center"><strong>User Status </strong></div></td>
      <td width="20%"><div align="center"><strong>Options</strong></div></td>
    </tr>
	<?php
	if($items_count <= 0){ echo '<tr><td colspan="4">No users found.</td><tr>';}
	while($each = mysql_fetch_assoc($get_users)){?>
    <tr>
      <td width="26%"><font color="#990000"><?=$each['user_name'];?></font></td>
      <td width="26%"><font color="#990000"><?=$each['user_email'];?></font></td>
      <td width="26%"><font color="#990000"><?php if($each['user_status'] == 1){ echo 'Active';}else{echo 'Disabled';}?></font></td>
      <td width="20%"><div align="center">[<a href="?Users*<?=$each['user_id'];?>">Edit</a>] [<a style="cursor:pointer;" onclick="if(window.confirm('Are you sure you want to remove this user: <?=$each['user_name'];?>')){window.location.href='?Users*Remove*<?=$each['user_id'];?>';}"><font color="#0000FF">Remove</font></a>] </div></td>
    </tr>
	<?php } ?>
  </table>
<?php } else { 
	if($_POST['user_id'] != NULL){
		$_POST = mysql_fetch_assoc(mysql_query("SELECT * FROM `memb_userlist` WHERE `user_id`='$_POST[user_id]'"));
	}
	?>  
  <form name="users" id="users" method="post" action="?Users*<?=$first_var;?>" style="display:inline;">
  <table width="100%"  border="1" cellspacing="0" cellpadding="5">
    <tr>
      <td colspan="2"><div align="center"><font color="#999999" size="4"><strong>Add/Edit User: </strong></font></div></td>
    </tr>
    <tr>
      <td width="50%">User Name:</td>
      <td width="50%"><input name="user_name" type="text" id="user_name" value="<?=$_POST['user_name'];?>">
	  <input name="old_name" type="hidden" id="old_name" value="<?=$_POST['user_name'];?>"></td>
    </tr>
    <tr>
      <td width="50%">User Email:</td>
      <td width="50%"><input name="user_email" type="text" id="user_email" value="<?=$_POST['user_email'];?>">
      <input name="old_email" type="hidden" id="old_email" value="<?=$_POST['user_email'];?>"></td>
    </tr>
    <tr>
      <td width="50%">User Password:</td>
      <td width="50%"><input name="user_password" type="text" id="user_password" value="<?=$_POST['user_password'];?>">
      </td>
    </tr>
    <tr>
      <td width="50%">User Status: </td>
      <td width="50%">
	  <?php if($_POST['user_status'] == '1' || $_POST['user_status'] == NULL){$sel = ' checked';}else{$sel=NULL;}?>
	  <input name="user_status" type="radio" value="1"<?=$sel;?>>
        On <?php if($_POST['user_status'] == '2'){$sel = ' checked';}else{$sel=NULL;}?>
        <input name="user_status" type="radio" value="2"<?=$sel;?>>
        Off
		<?php if(strlen($_POST['user_status']) > 2){$sel = ' checked';}else{$sel=NULL;}?>
        <input name="user_status" type="radio" value="2"<?=$sel;?> disabled>
        Un-Confirmed</td>
    </tr>
    <tr>
      <td>Allow Account Deletion: </td>
      <td width="50%">
	  <?php if($_POST['allow_delete'] == '1' || $_POST['allow_delete'] == NULL){$sel = ' checked';}else{$sel=NULL;}?>
	  <input name="allow_delete" type="radio" value="1"<?=$sel;?>>
Yes
<?php if($_POST['allow_delete'] == '2'){$sel = ' checked';}else{$sel=NULL;}?>
  <input name="allow_delete" type="radio" value="2"<?=$sel;?>>
No</td>
    </tr>
    <tr>
      <td>In Mailing List: </td>
      <td width="50%">
	  <?php if($_POST['user_in_list'] == '1' || $_POST['user_in_list'] == NULL){$sel = ' checked';}else{$sel=NULL;}?>
	  <input name="user_in_list" type="radio" value="1"<?=$sel;?>>
Yes
<?php if($_POST['user_in_list'] == '2'){$sel = ' checked';}else{$sel=NULL;}?>
  <input name="user_in_list" type="radio" value="2"<?=$sel;?>>
No</td>
    </tr>
    <tr>
      <td colspan="2"><font color="#0000FF"><strong>Custom Fields: </strong></font></td>
    </tr>
	<?php
	$get_fields = mysql_query("SELECT * FROM `memb_customfds`");
	$recrod_nums = mysql_num_rows($get_fields);
	if($recrod_nums <= 0){
		echo '<tr><td colspan="2"><strong>No custom fields on system.</strong></td></tr>';
	} else {
		$array_list = explode("\n",$_POST['custom_fields']);
		foreach($array_list as $line){
			$line = trim($line);
			if($line){
				//Format
				list($id,$value) = explode('{+|%|+}',$line);
				$id = substr($id,1,-1);$value = substr($value,1,-1);
				$_POST["cusfield_$id"] = $value;
			}
		}
		while($each = mysql_fetch_assoc($get_fields)){?>
    <tr>
      <td><?=$each['field_name'];?>:</td>
      <td width="50%"><input name="cusfield_<?=$each['field_id'];?>" type="text" id="cusfield_<?=$each['field_id'];?>" value="<?=$_POST['cusfield_'.$each['field_id']];?>"></td>
    </tr>
	<?php } 
	}?>
    <tr>
      <td colspan="2"><div align="center">
        <input type="submit" name="Submit" value="Submit">
        <input name="doid" type="hidden" id="doid" value="3">
        <input name="user_id" type="hidden" id="user_id" value="<?=$_POST['user_id'];?>">
</div></td>
    </tr>
  </table>
</form>
<?php } ?>