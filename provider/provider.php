<?php
include('../config.php');
$message = '';
$groupmessage = '';
session_start();
include('../include/header.php');

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
$id = $result["provider_id"];

$query5 = $connection->prepare("SELECT patient_id FROM vaccineoffer WHERE patient_id = :id and status = 'completed'");
$query5->bindParam("id", $id, PDO::PARAM_INT);
$query5->execute();
$result5=$query5->fetchAll(PDO::FETCH_OBJ);
$vaccinedone=$query5->rowCount();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<title>Home</title>
<style>
body {
  background-color: #E8E8E8;
}
</style>
</head>
<body>
<link rel="stylesheet" href="../css/style.css">
	<?php include '../menus.php'; ?>
	<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
	
		<h4> Welcome Back,<strong> <?php echo $name; ?> </h4></strong>
		<hr>
		<div class="row" style="margin-left:125px;">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-body bk-primary text-light">
								<div class="stat-panel text-center">
<?php 
$query1 = $connection->prepare("SELECT provider_id FROM vaccineappointment WHERE provider_id = :id");
$query1->bindParam("id", $id, PDO::PARAM_INT);
$query1->execute();
$result1=$query1->fetchAll(PDO::FETCH_OBJ);
$vaccinetotal=$query1->rowCount();
?>
									<div class="stat-panel-number h1 "><?php echo htmlentities($vaccinetotal); ?></div>
									<div class="stat-panel-title text-uppercase">Total Appointments Created</div>
								</div>
							</div>											
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-body bk-success text-light">
								<div class="stat-panel text-center">
<?php 
$query2 = $connection->prepare("SELECT * FROM vaccineappointment natural join vaccineoffer WHERE provider_id = :id and status = 'accepted'");
$query2->bindParam("id", $id, PDO::PARAM_INT);
$query2->execute();
$result2=$query2->fetchAll(PDO::FETCH_OBJ);
$vaccineaccepted=$query2->rowCount();
?>
									<div class="stat-panel-number h1 "><?php echo htmlentities($vaccineaccepted); ?></div>
									<div class="stat-panel-title text-uppercase">Currently Scheduled Appointments</div>
								</div>
							</div>											
						</div>
					</div>		
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-body bk-success text-light">
								<div class="stat-panel text-center">
<?php 
$query3 = $connection->prepare("SELECT * FROM vaccineappointment natural join vaccineoffer WHERE provider_id = :id and status = 'completed'");
$query3->bindParam("id", $id, PDO::PARAM_INT);
$query3->execute();
$result3=$query3->fetchAll(PDO::FETCH_OBJ);
$vaccinedone=$query3->rowCount();
?>
									<div class="stat-panel-number h1 " ><?php echo htmlentities($vaccinedone); ?></div></br>
									<div class="stat-panel-title text-uppercase">Total Patients Vaccinated</div>
								</div>
							</div>											
						</div>
					</div>																				
				</div>
			</div>
		</div>		
	</div>
</body>
</html>