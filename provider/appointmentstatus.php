<?php 
include('../config.php');

if (!isset($_SESSION))
{
  session_start();
}
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
$id = $result["provider_id"];



?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<title>Appointment Status</title>
<style>
body {
  background-color: #E8E8E8;
}
</style>
</head>
<body>
	<?php include '../menus.php'; ?>
   <link rel="stylesheet" href="../css/tables.css">
	
	<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12"></br>			
    <?php

	$query1 = $connection->prepare("SELECT va.appoint_id, va.appoint_date, va.appoint_time, v.status, v.reply_date, p.patient_name 
	FROM vaccineappointment va left outer join vaccineoffer v on va.appoint_id = v.appoint_id left outer join patient p on v.patient_id = p.patient_id WHERE 
	va.provider_id=:id order by va.appoint_date desc");
	$query1->bindParam("id", $id, PDO::PARAM_STR);
	$query1->execute();
	$result1 = $query1->fetchAll(PDO::FETCH_ASSOC);

    ?>
	  <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search by Status"></p>
      <table id="myTable" class="table"   width="100%" cellspacing="0">
	  
	</div>
        <thead>
          <tr>
              <th>Appointment ID</th>
              <th>Patient Name</th>
              <th>Status</th>
              <th>Appointment Date</th>
              <th>Appointment Time</th>
              <th>Reply Date</th>
         </tr>
        </thead>
        <tbody>
        <?php
		if(empty($result1))
         {
			echo '<h4>No Appointments found</h4></br>';
		}
		else
		{
			foreach($result1 as $results)
			{
			?>
			<tr>
            <td><?php  echo htmlentities($results['appoint_id']); ?></td>
            <td><?php  if (empty($results['patient_name'])) { echo htmlentities("N/A"); } else { echo htmlentities($results['patient_name']); }  ?></td>			
			<td><?php  if (empty($results['status'])) { echo htmlentities("N/A"); } else { echo htmlentities($results['status']); } ?></td>
            <td><?php  echo htmlentities($results['appoint_date']); ?></td>
            <td><?php  echo htmlentities($results['appoint_time']); ?></td>
            <td><?php  if (empty($results['reply_date'])) { echo htmlentities("N/A"); } else { echo htmlentities($results['reply_date']); } ?></td>
			<?php
			}
		}
		?>
          </tr>
        </tbody>
      </table>
    </div>	
<script>
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
<?php include('../include/footer.php');
?>
</body>
</html>