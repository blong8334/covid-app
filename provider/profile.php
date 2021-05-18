<?php 
include('../config.php');
if (!isset($_SESSION))
{
	session_start();
}
include('../include/header.php');
  
if( isset( $_SESSION['counter'] ) ) {
  $_SESSION['counter'] += 1;
}else {
  $_SESSION['counter'] = 1;
}  
  
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
	$usertype = $_SESSION['usertype'];
		if ($usertype != "provider"){
		header("location: ../patient/patient.php");
		}	
	
} else {
    session_unset();
    session_write_close();
				header("location: ../index.php"); 
				exit;
}

$query = $connection->prepare("SELECT * FROM provider WHERE user_name=:username");
$query->bindParam("username", $username, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);
$provider_id = $result["provider_id"];		

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<title>Profile</title>
	<?php include '../menus.php'; ?>
<style>
body {
  background-color: #E8E8E8;
}
</style>

   <link rel="stylesheet" href="../css/tables.css">	
			<div><span style="font-size:20px; margin-left: 15px;">Provider Details:</span><div class="pull-right"></div>
				<div class="col-lg-10"></br>			
			      <table id="myTable" class="table" style="width: 600px;" cellspacing="0">

<?php
if (isset($_SESSION['status'])) {
if ($_SESSION['counter'] == 1) {
?>	
<script type="text/javascript">
 
function timedMsg()
{
var t=setTimeout("document.getElementById('myMsg').style.display='none';",2000);
}
 
</script>
</head>
<body>
<div id="myMsg" class="stat-panel-number h4" style="color:#0000CD; text-align:left;"><?php echo htmlentities($_SESSION['status'])?></div>  
<script language="JavaScript" type="text/javascript">timedMsg()</script>
<?php
}}
?>				
			<tr>
					<td>
					<div class="form-group">
						<label for="patient_name" class="col-md-3 control-label">Name</label>
						<div class="col-md-5">
						  <form action="profile_update.php" method="post">
						  <button id="btn-update" style="position: absolute; margin-left: 300px ; display:none;" type="submit" name="btn-update_name" class="btn btn-info"><i class="icon-hand-right"></i>Save Changes</button>			
					      <input type="hidden" name="provider_id" value="<?php echo htmlentities($provider_id); ?>">			
						   <input id = "pro_input" type="text" style="width: 180px;" class="form-control" name="provider_name" value="<?php echo htmlentities($result["provider_name"]);?>" >
						   </form>
						</div>
					</div>
					</td>
			</tr>
			<tr>
                   <td>
					<div class="form-group">
						<label for="patient_email" class="col-md-3 control-label">Email</label>
						<div class="col-md-5">
						  <form action="profile_update.php" method="post">
						  <button id="btn-update1" style="position: absolute; margin-left: 300px ; display:none;" type="submit" name="btn-update_email" class="btn btn-info"><i class="icon-hand-right"></i>Save Changes</button>			
					      <input type="hidden" name="provider_id" value="<?php echo htmlentities($provider_id); ?>">			
						   <input id = "pro_input1" type="email" style="width: 180px;" class="form-control" name="provider_email" value="<?php echo htmlentities($result["provider_email"]);?>" >
						   </form>
						</div>
					</div>			
					</td>	
								
			</tr>
			<tr>
                   <td>
					<div class="form-group">
						<label for="patient_phno" class="col-md-3 control-label">Phone Number</label>
						<div class="col-md-5">
						  <form action="profile_update.php" method="post">
						  <button id="btn-update2" style="position: absolute; margin-left: 300px ; display:none;" type="submit" name="btn-update_phone" class="btn btn-info"><i class="icon-hand-right"></i>Save Changes</button>			
					      <input type="hidden" name="provider_id" value="<?php echo htmlentities($provider_id); ?>">			
						   <input id = "pro_input2" type="number" style="width: 180px;" class="form-control" name="provider_phno" value="<?php echo htmlentities($result["provider_phno"]);?>" >
						   </form>
						</div>
					</div>			
					</td>					
			</tr>
		</table>
	</div>
	</div>
<script type="text/javascript">
 
document.getElementById("pro_input").addEventListener("keyup", function() {
    var nameInput = document.getElementById('pro_input').value;
    if (nameInput != "") {
		$("#btn-update").show();	
    } 
})
document.getElementById("pro_input1").addEventListener("keyup", function() {
    var nameInput = document.getElementById('pro_input1').value;
    if (nameInput != "") {
		$("#btn-update1").show();	
    } 
})
document.getElementById("pro_input2").addEventListener("change", function() {
    var nameInput = document.getElementById('pro_input2').value;
    if (nameInput != "") {
		$("#btn-update2").show();	
	}
})
;
</script>
<?php include('../include/footer.php');?>
</body>
</html>