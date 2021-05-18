<?php
include('../config.php');
include('../include/header.php'); 
if (!isset($_SESSION))
  {
    session_start();
  }
  
if( isset( $_SESSION['counter'] ) ) {
  $_SESSION['counter'] += 1;
}else {
  $_SESSION['counter'] = 1;
}  
  
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
	$usertype = $_SESSION['usertype'];
		if ($usertype != "patient"){
		header("location: ../provider/provider.php");
		}	
	
} else {
    session_unset();
    session_write_close();
				header("location: ../index.php"); 
				exit;
}


$query = $connection->prepare("SELECT * FROM patient WHERE user_name=:username");
$query->bindParam("username", $username, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);
$id = $result["patient_id"];
			   
$query1 = $connection->prepare("SELECT * FROM patientupload WHERE patient_id=:id");
$query1->bindParam("id", $id, PDO::PARAM_STR);
$query1->execute();
$result1 = $query1->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<title>Profile</title>
<style>
body {
  background-color: #E8E8E8;
}
</style>
	<?php include '../menus.php'; ?>
   <link rel="stylesheet" href="../css/tables.css">	
			<div><span style="font-size:20px; margin-left: 15px;"><strong>Patient Details:</strong></span><div class="pull-right"></div>
				<div class="col-lg-10">		
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
  <title></title>
</head>
<body>
<div id="myMsg" class="stat-panel-number h4" style="color:#0000CD; text-align:center;"><?php echo htmlentities($_SESSION['status'])?></div>  
<script language="JavaScript" type="text/javascript">timedMsg()</script>
<?php
}}
?>				
		</p><table id="myTable" class="table table-boredered">		
			<tr>
					<td>
					<div class="form-group">
						<label for="patient_name" class="col-md-3 control-label">Name</label>
						<div class="col-md-5">
						  <form action="profile_update.php" method="post">
						  <button id="btn-update" style="position: absolute; right: 0; display:none;" type="submit" name="btn-update_name" class="btn btn-info"><i class="icon-hand-right"></i>Save Changes</button>			
					      <input type="hidden" name="patient_id" value="<?php echo htmlentities($id); ?>">			
						   <input id = "pat_input" type="text" style="width: 180px;" class="form-control" name="patient_name" value="<?php echo htmlentities($result["patient_name"]);?>" >
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
						  <button id="btn-update1" style="position: absolute; right: 0; display:none;" type="submit" name="btn-update_email" class="btn btn-info"><i class="icon-hand-right"></i>Save Changes</button>			
					      <input type="hidden" name="patient_id" value="<?php echo htmlentities($id); ?>">			
						   <input id = "pat_input1" type="email" style="width: 180px;" class="form-control" name="patient_email" value="<?php echo htmlentities($result["patient_email"]);?>" >
						   </form>
						</div>
					</div>			
					</td>	
								
			</tr>
			<tr>
                   <td>
					<div class="form-group">
						<label for="patient_dob" class="col-md-3 control-label">Date Of Birth</label>
						<div class="col-md-5">
						  <form action="profile_update.php" method="post">
						  <button id="btn-update2" style="position: absolute; right: 0; display:none;" type="submit" name="btn-update_dob" class="btn btn-info"><i class="icon-hand-right"></i>Save Changes</button>			
					      <input type="hidden" name="patient_id" value="<?php echo htmlentities($id); ?>">			
						   <input id = "pat_input2" type="date" style="width: 180px;" class="form-control" name="patient_dob" value="<?php echo htmlentities($result["patient_dob"]);?>" >
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
						  <button id="btn-update3" style="position: absolute; right: 0; display:none;" type="submit" name="btn-update_phone" class="btn btn-info"><i class="icon-hand-right"></i>Save Changes</button>			
					      <input type="hidden" name="patient_id" value="<?php echo htmlentities($id); ?>">			
						   <input id = "pat_input3" type="number" style="width: 180px;" class="form-control" name="patient_phno" value="<?php echo htmlentities($result["patient_phno"]);?>" >
						   </form>
						</div>
					</div>			
					</td>					
				
			</tr>
			<tr>
		
				
                   <td>
					<div class="form-group">
						<label for="patient_maxdist" class="col-md-3 control-label">Maximum Travel Distance (Km)</label>
						<div class="col-md-5">
						  <form action="profile_update.php" method="post">
						  <button id="btn-update4" style="position: absolute; right: 0; display:none;" type="submit" name="btn-update_max_dist" class="btn btn-info"><i class="icon-hand-right"></i>Save Changes</button>			
					      <input type="hidden" name="patient_id" value="<?php echo htmlentities($id); ?>">			
						   <input id = "pat_input4" type="number" style="width: 180px;" class="form-control" name="patient_maxdist" value="<?php echo htmlentities($result['max_travel']);?>" >
						   </form>
						</div>
					</div>			
					</td>									
			</tr>
			<tr>
		
				
                   <td>			   
					<div class="form-group">
						<label for="patient_maxdist" class="col-md-3 control-label">Upload Medical History</label>
						<div class="col-md-5">
						<form action="profile_update.php" method="post" enctype="multipart/form-data">
							Select a file to upload:
							<input id = "pat_input5" type="file" name="patient_file" id="fileToUpload"></p>
					        <input type="hidden" name="patient_id" value="<?php echo htmlentities($id); ?>">								
							<input id= "btn-update5" type="submit" value="Upload File" name="patient_upload" style="display:none;">
						</form>
						</div>
					</div>
						</td>
			</tr>
			
<?php			
		if(!empty($result1)) {?>	
			
      <table id="myTable" style="width: 400px; height: 120px; font-size: 15px; margin-left:0px;" id="dataTable" width="100%" cellspacing="10" >
	  		<div><span style="font-size:20px;"><strong>Uploaded Files:</strong></span><div class="pull-right"></div></div>

        <thead>
          </br><tr>
              <th>FileName</th>
          </tr>
        </thead>
        <tbody>			

			<?php
						foreach($result1 as $results)
						{ 
						$fileid = $results["file_id"];
						?>						
				<tr>
						<td><?php  echo htmlentities($results['file_pointer']); ?> </td>
						<td>
						<form action="profile_update.php" method="post">
						  <input type="hidden" name="filename" value="<?php echo htmlentities($results['file_pointer']); ?>">
							<input type="hidden" name="patient_id" value="<?php echo htmlentities($id); ?>">
							<input type="hidden" name="file_id" value="<?php echo htmlentities($fileid); ?>">
						  <button type="submit" name="remove_btn" class="btn btn-danger" style="margin-left:20px;">Remove File</button>
						</form>
						</td>

			<?php }} ?>											
				</tr>

			   </tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
 
document.getElementById("pat_input").addEventListener("keyup", function() {
    var nameInput = document.getElementById('pat_input').value;
    if (nameInput != "") {
		$("#btn-update").show();	
    } 
})
document.getElementById("pat_input1").addEventListener("change", function() {
    var nameInput = document.getElementById('pat_input').value;
    if (nameInput != "") {
		$("#btn-update1").show();	
    } 
})
document.getElementById("pat_input2").addEventListener("change", function() {
    var nameInput = document.getElementById('pat_input').value;
    if (nameInput != "") {
		$("#btn-update2").show();	
    } 
})
document.getElementById("pat_input3").addEventListener("change", function() {
    var nameInput = document.getElementById('pat_input').value;
    if (nameInput != "") {
		$("#btn-update3").show();	
    } 
})
document.getElementById("pat_input4").addEventListener("change", function() {
    var nameInput = document.getElementById('pat_input').value;
    if (nameInput != "") {
		$("#btn-update4").show();	
    } 
})
document.getElementById("pat_input5").addEventListener("change", function() {
    var nameInput = document.getElementById('pat_input').value;
    if (nameInput != "") {
		$("#btn-update5").show();	
    } 
})
;
</script>
<?php include('../include/footer.php');?>
</body>
</html>	 