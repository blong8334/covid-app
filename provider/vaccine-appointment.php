<?php
include('../config.php');
include('../include/header.php');
$message = '';
session_start();

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
$name = $result["provider_name"];
$providerId = $result["provider_id"];

$hideSuccess = "hidden";
$hideError = "hidden";
if (isset($_POST['submit'])) {
  $date = htmlentities($_POST['date']);
  $time = htmlentities($_POST['time']);
  $query = $connection->prepare("INSERT INTO VaccineAppointment(provider_id, appoint_date, appoint_time) VALUES (:providerId, :appDate, :appTime)");
  $query->bindParam("providerId", $providerId, PDO::PARAM_STR);
  $query->bindParam("appDate", $date, PDO::PARAM_STR);
  $query->bindParam("appTime", $time, PDO::PARAM_STR);
  $result = $query->execute();
  if ($result) {
	$message = "Appointment Created";
  } else {
	$message = "Failed! Try Again";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Appointment</title>
</head>
	<?php include '../menus.php'; ?>
<style>
body {
  background-color: #E8E8E8;
}
</style>
<body>
	<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12"></br>
<script type="text/javascript">
 
function timedMsg()
{
var t=setTimeout("document.getElementById('myMsg').style.display='none';",2000);
}
</script>	  
	<div id="myMsg" style="color:#006400; text-align:center; margin-right:50px;"><?php echo htmlentities($message);?></div>
<script language="JavaScript" type="text/javascript">timedMsg()</script>

  <div class="container">

    <div class="form-group">
      <form action="" method="post">
        <label for="date">Appointment Date:</label>
        <input type="date" class="form-control" name="date" required>
        <label for="time">Appointment Time:</label>
        <input type="time" class="form-control" name="time" required></br>
        <input type="submit" name="submit" style='margin-left:500px;' class="btn btn-primary" value="Submit">
      </form>

    </div>
	</div>
    </div>	
</body>
</html>