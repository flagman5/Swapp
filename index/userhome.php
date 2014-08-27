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

	$user_name = $user_info['user_name'];
include ("../common/header.php");
?>


	<h1>Welcome <?echo $user_name?></h1>
	<div class="descr"><a href="../login/logout.php">Logout</a></div>	

	<div class="box"> getting started </div>
	
	<h1>My Account Information</h1>
	<hr>
	<div class="box2">

	<ul>
	<li><a href="../login/profile.php">Account Information and Preference</a></li>
	</ul>
	
	</div>
	
	<h1>My Statistics</h1>
	<hr>
	<div class="box2">
	<img src="../img/good.gif"><b>Positive:</b> <br/>
	<img src="../img/bad.gif"><b>Negative:</b> <br/>
	<br/>
	<b>Number of Trades: </b>
	</div>
	<h1>My Feedback</h1>
	<hr>
	<div class="box2">
	<b>Comments:</b>
	</div>
		
<!--- required for all -->	
		</div>

<?include ("../common/sidenav.php"); ?>	
	
		<div class="clearer"><span></span></div>

	</div>
<?include ("../common/footer.php"); ?>

