<?php
include('../config.php');
$message = '';
$groupmessage = '';
session_start();
include('../include/header.php');

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
$name = $result["patient_name"];
$id = $result["patient_id"];
$group = $result["group_no"];


	if ($group == "") {
		$groupmessage = "Note: The Administrator has not assigned you a group yet";
		}
	else {
		$groupmessage = "You have been assigned group No. ";
	}
$query5 = $connection->prepare("SELECT patient_id FROM vaccineoffer WHERE patient_id = :id and status = 'completed'");
$query5->bindParam("id", $id, PDO::PARAM_INT);
$query5->execute();
$result5=$query5->fetchAll(PDO::FETCH_OBJ);
$vaccinedone=$query5->rowCount();
	if ($query5->rowCount() > 0) {
		$message = "Congratulations! You have been Vaccinated";
		}
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
<body>
<link rel="stylesheet" href="../css/style.css">
	<?php include '../menus.php'; ?>
	<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
	
		<h4> Welcome Back,<strong> <?php echo $name; ?> </h4></strong>
		<hr>
		<div class="stat-panel-number h4" style="color:#00755e; text-align:left;"><?php echo htmlentities($groupmessage); echo htmlentities($group);?></div>
        <div class="stat-panel-number h4" style="color:#006400; text-align:center;"><?php echo htmlentities($message);?></div>		
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-body bk-primary text-light">
								<div class="stat-panel text-center">
<?php 
$query1 = $connection->prepare("SELECT patient_id FROM vaccineoffer WHERE patient_id = :id");
$query1->bindParam("id", $id, PDO::PARAM_INT);
$query1->execute();
$result1=$query1->fetchAll(PDO::FETCH_OBJ);
$vaccinetotal=$query1->rowCount();
?>
									<div class="stat-panel-number h1 "><?php echo htmlentities($vaccinetotal); ?></div>
									<div class="stat-panel-title text-uppercase">Total Appointment Offers</div>
								</div>
							</div>											
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-body bk-success text-light">
								<div class="stat-panel text-center">
<?php 
$query2 = $connection->prepare("SELECT patient_id FROM vaccineoffer WHERE patient_id = :id and status = 'accepted'");
$query2->bindParam("id", $id, PDO::PARAM_INT);
$query2->execute();
$result2=$query2->fetchAll(PDO::FETCH_OBJ);
$vaccineaccepted=$query2->rowCount();
?>
									<div class="stat-panel-number h1 "><?php echo htmlentities($vaccineaccepted); ?></div>
									<div class="stat-panel-title text-uppercase">Currently Accepted Offers</div>
								</div>
							</div>											
						</div>
					</div>		
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-body bk-success text-light">
								<div class="stat-panel text-center">
<?php 
$query3 = $connection->prepare("SELECT patient_id FROM vaccineoffer WHERE patient_id = :id and status = 'pending'");
$query3->bindParam("id", $id, PDO::PARAM_INT);
$query3->execute();
$result3=$query3->fetchAll(PDO::FETCH_OBJ);
$vaccinepending=$query3->rowCount();
?>
									<div class="stat-panel-number h1 "><?php echo htmlentities($vaccinepending); ?></div>
									<div class="stat-panel-title text-uppercase">Pending Offers</div>
								</div>
							</div>											
						</div>
					</div>													
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-body bk-primary text-light">
								<div class="stat-panel text-center">
<?php 
	if ($query5->rowCount() > 0) {
		$closestappointment = "Not Required - Already Vaccinated";
		?><div class="stat-panel-number h5 " style="margin-top:25px;"><?php echo htmlentities($closestappointment); ?></div></br><?php 	
		}
	else{							
								
	$query4 = $connection->prepare("select distinct st_distance_sphere(p.patient_location, t2.provider_location)/1000 as location
        FROM patient p NATURAL join (
        SELECT patient_id, provider_name, appoint_id, appoint_date, appoint_time, provider_location
		from vaccineappointment natural join provider natural join vaccineoffer
        where status = 'pending' or status = 'accepted') t2
        where p.patient_id = t2.patient_id AND patient_id =:id
        order by st_distance_sphere(p.patient_location, t2.provider_location) LIMIT 1");
	$query4->bindParam("id", $id, PDO::PARAM_INT);
	$query4->execute();
	if ($query4->rowCount() == 0) {
	$closestappointment = "No Offered/Accepted Appointments Yet";
	?><div class="stat-panel-number h5 " style="padding:7px;"><?php echo htmlentities ($closestappointment); ?></div></br><?php 	
	}
	else{
	$result4=$query4->fetch(PDO::FETCH_ASSOC);
	$closestappointment= $result4["location"];
	?></br><div class="stat-panel-number h3" style="padding:7px; margin-top:0px;"><?php echo htmlentities(round($closestappointment, 2));?> Km </div><?php 	
}
}
?>																																	
									<div class="stat-panel-title text-uppercase">Closest appointment Distance</div>
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