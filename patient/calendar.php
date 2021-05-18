<?php 
include('../config.php');
include('../include/header.php');
include('../constants.php');

error_reporting(E_ERROR | E_PARSE);
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
$patientId = $result["patient_id"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<title>My Calendar</title>
<style>
body {
  background-color: #E8E8E8;
}
</style>
<?php include '../menus.php'; ?>
<link rel="stylesheet" href="../css/tables.css">	
<div class="col-lg-10"></br>
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
<div id="myMsg" class="stat-panel-number h4" style="color:#0000CD; margin-right:100px; text-align:center;"><?php echo htmlentities($_SESSION['status']);?></div>  
<script language="JavaScript" type="text/javascript">timedMsg()</script>
<?php
}}
?>		
<div class="table-responsive;" id="myTable" style="width: 0px;">	
<?php
$timeSlots = $connection->prepare("SELECT * FROM Timeslot");
$timeSlots->execute();
$timeSlots->bindColumn(1, $timeId);
$timeSlots->bindColumn(2, $startTime);
$timeSlots->bindColumn(3, $endTime);

$currentAvail = $connection->prepare("SELECT week_day, time_id FROM PatientAvailability WHERE patient_id=$patientId");
$currentAvail->execute();
$currentAvail->bindColumn(1, $wd);
$currentAvail->bindColumn(2, $tid);
$availMap = [];
while ($currentAvail->fetch()) {
  $availMap["$tid:$wd"] = true;
}

echo "<div class='container'>";
echo "<form action='submit-availability.php' method='POST'>";
echo "<table class='table'>\n";
echo "<tr>";
echo "<th>Time Slot</th>";
for ($i = 0; $i < count($daysOfWeek); $i++) {
  $value = $daysOfWeek[$i];
  echo "<th>$value</th>";
}
echo "</tr>";
$timeSlotCounter = 0;
while ($timeSlots->fetch()) {
  echo "<tr>";
  echo "<td>$startTime - $endTime</td>";
  for ($day = 0; $day < count($daysOfWeek); $day++) {
    $inputBox = "<input type='checkbox' name='$timeSlotCounter:$day' value='$timeId'";
    if ($availMap["$timeId:$day"]) {
      $inputBox .= " checked";
    }
    echo "<td>$inputBox></td>";
  }
  $timeSlotCounter += 1;
  echo "</tr>";
}
echo "</table>";
echo "<input type='hidden' name='timeSlotCount' value='$timeSlotCounter'>";
echo "<input type='hidden' name='patientId' value='$patientId'>";
echo "<input class='btn btn-info' style='margin-left:500px;' type='submit' value='Submit'>";
echo "</form></div>";
?>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
$(window).load(function() {
  $(":checkbox").on("change", function() {
    $(this).parent().toggleClass("checked", this.checked);
  });
});
$(window).load(function() {
  $(function() {
    $('input:checked').parent().toggleClass("checked", this.checked);
  });
});
</script>
<?php 
include('../include/footer.php');
?>
</body>
</html>