<? 
//Call Database & Connect
require_once('../login/headers/database.php');
connect();

//Call functions
require_once('../login/headers/functions.php');

//Login Check Page
require_once('../login/headers/logincheck.php');

if($is_logged == false){
	//Disconnect Database
	disconnect_data();
	//die('You need to login before accessing this page.');
	header("Location: ../login/login.php");
}
include ("../common/header.php");
?>
<script src="js/upload.js"></script>

	<h1>Create A Listing</h1>
	
	<form name="create" method="post" target="upload_target" action="create_listing.php" enctype="multipart/form-data" onsubmit="startUpload();">
	 <p id="f1_upload_process">Loading...<br/><img src="images/loader.gif" /><br/></p>
     <p id="f1_upload_form"><br/>
        *�п�ܤ����G<br/>
		 <select name="category">
			<option value="1">��</option>
						  							
			<option value="2">�k�ʪA��</option>
			
			<option value="3">���ֻP�v��</option>

			<option value="4">����P�q��</option>			  							
						  							
			<option value="5">�B�ʤ~��</option>
						  							
		 </select> 
		<br/>
		*�ӫ~�W�١G<br/>
		<input name="listing_name" type="text" size="40" /><br/>
		
		*�ӫ~�y�z�G<br/>
		<textarea name="listing_description" cols="30" rows="7"></textarea><br/>
						  
		*�ӫ~����סG<br/>
		<select name="listing_condition">
			<option value="1">���s</option>
			<option value="2">�E���s</option>
			<option value="3">�K���s</option>
			<option value="4">�C���s</option>
			<option value="5">�����s</option>
			<option value="6">�����s�H�U</option>
		</select><br/>
					 
		*�ӫ~�Ҧb�a�G<br/>
		<select name="listing_location">
			<option value="1">�x�_��</option>
			<option value="2">				�x�_�� </option>
			<option value="3">				��鿤 </option>
			<option value="4">				�s�˿���</option>
			<option value="5">				�y����</option>
			<option value="6">				�򶩥�</option>
			<option value="7">				�x����</option>
			<option value="9">				�x���� </option>
			<option value="10">				���ƿ� </option>
			<option value="11">				���L�� </option>
			<option value="12">				�]�߿� </option>
			<option value="13">				�n�뿤 </option>
			<option value="14">				�Ÿq���� </option>
			<option value="15">				�x�n�� </option>
			<option value="16">				�x�n�� </option>
			<option value="17">				������ </option>
			<option value="18">				������ </option>
			<option value="19">				�̪F�� </option>
			<option value="20">				�x�F�� </option>
			<option value="21">				�Ὤ�� </option>
			<option value="22">				��� </option>
			<option value="23">				������ </option>
			<option value="24">				�s���� </option>
		</select><br/>
						
		�W�ǷӤ��G(�ݭn�W�Ǥ@�i)<br/>
		  <input type="file" name="listing_picture" size="30" /> <br/>
		 
		*�ӫ~���A�G<br/>
		<select name="listing_ready">
			<option value="1">�����Z�n</option>
		    <option value="2">��J�ܮw�]�w�Ƥ��^</option>
		</select>
		<br/><br/>
		
		<input type="submit" name="Submit" id="Submit" value="��J">
		</p>
		<input type="hidden" name="user" value="<?echo $user_id?>">
        <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
        </form>

		
<!--- required for all -->	
		</div>

<?include ("../common/sidenav.php"); ?>	
	
		<div class="clearer"><span></span></div>

	</div>
<?include ("../common/footer.php"); ?>

