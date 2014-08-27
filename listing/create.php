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
        *請選擇分類：<br/>
		 <select name="category">
			<option value="1">書</option>
						  							
			<option value="2">女性服飾</option>
			
			<option value="3">音樂與影片</option>

			<option value="4">玩具與電玩</option>			  							
						  							
			<option value="5">運動才具</option>
						  							
		 </select> 
		<br/>
		*商品名稱：<br/>
		<input name="listing_name" type="text" size="40" /><br/>
		
		*商品描述：<br/>
		<textarea name="listing_description" cols="30" rows="7"></textarea><br/>
						  
		*商品完整度：<br/>
		<select name="listing_condition">
			<option value="1">全新</option>
			<option value="2">九成新</option>
			<option value="3">八成新</option>
			<option value="4">七成新</option>
			<option value="5">六成新</option>
			<option value="6">五成新以下</option>
		</select><br/>
					 
		*商品所在地：<br/>
		<select name="listing_location">
			<option value="1">台北市</option>
			<option value="2">				台北縣 </option>
			<option value="3">				桃園縣 </option>
			<option value="4">				新竹縣市</option>
			<option value="5">				宜蘭縣</option>
			<option value="6">				基隆市</option>
			<option value="7">				台中市</option>
			<option value="9">				台中縣 </option>
			<option value="10">				彰化縣 </option>
			<option value="11">				雲林縣 </option>
			<option value="12">				苗栗縣 </option>
			<option value="13">				南投縣 </option>
			<option value="14">				嘉義縣市 </option>
			<option value="15">				台南縣 </option>
			<option value="16">				台南市 </option>
			<option value="17">				高雄市 </option>
			<option value="18">				高雄縣 </option>
			<option value="19">				屏東縣 </option>
			<option value="20">				台東縣 </option>
			<option value="21">				花蓮縣 </option>
			<option value="22">				澎湖縣 </option>
			<option value="23">				金門縣 </option>
			<option value="24">				連江縣 </option>
		</select><br/>
						
		上傳照片：(需要上傳一張)<br/>
		  <input type="file" name="listing_picture" size="30" /> <br/>
		 
		*商品狀態：<br/>
		<select name="listing_ready">
			<option value="1">直接刊登</option>
		    <option value="2">放入倉庫（預備中）</option>
		</select>
		<br/><br/>
		
		<input type="submit" name="Submit" id="Submit" value="輸入">
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

