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

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<title>Appointments</title>
<style>
body {
  background-color: #E8E8E8;
}
</style>
<?php include '../menus.php'; ?>
<link rel="stylesheet" href="../css/tables.css">
<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12"></p>
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
    <?php

	$query1 = $connection->prepare("SELECT * FROM vaccineoffer natural join vaccineappointment WHERE patient_id=:id");
	$query1->bindParam("id", $id, PDO::PARAM_STR);
	$query1->execute();
	$result1 = $query1->fetchAll(PDO::FETCH_ASSOC);

    ?>
      <table class="table " id="myTable">
        <thead>
          <tr>
              <th>Appointment ID</th>
              <th>Status</th>
              <th>Appointment Date</th>
              <th>Appointment Time</th>
              <th>Reply Date</th>
              <th>Deadline Date</th>
              <th>Accept</th>
              <th>Decline</th>

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
			<td><?php  echo htmlentities($results['status']); ?></td>
            <td><?php  echo htmlentities($results['appoint_date']); ?></td>
            <td><?php  echo htmlentities(date("g:i A", strtotime($results['appoint_time']))); ?></td>
            <td><?php  if (empty($results['reply_date'])) { echo "N/A"; } else { echo htmlentities($results['reply_date']); } ?></td>
            <td><?php  echo htmlentities($results['deadline_date']); ?></td>
			<?php
			if ($results['status'] == 'pending') {
            ?>
            <td>
                <form action="selection.php" method="post">
                    <input type="hidden" name="appoint_id" value="<?php echo htmlentities($results['appoint_id']); ?>">
					<input type="hidden" name="patient_id" value="<?php echo htmlentities($id); ?>">
                    <button  type="submit" name="accept_btn" class="btn btn-success">Accept</button>
                </form>
            </td>
            <td>
                <form action="selection.php" method="post">
                  <input type="hidden" name="appoint_id" value="<?php echo htmlentities($results['appoint_id']); ?>">
					<input type="hidden" name="patient_id" value="<?php echo htmlentities($id); ?>">
                  <button type="submit" name="decline_btn" class="btn btn-warning">Decline</button>
                </form>
            </td>
		  <?php
			}
		elseif  ($results['status'] == 'accepted')
		{
		?>	
		    <td>
               <p>N/A</p>
            </td>
            <td>
               <p>N/A</p>

            </td>
			 <td>
                <form action="selection.php" method="post">
                  <input type="hidden" name="appoint_id" value="<?php echo htmlentities($results['appoint_id']); ?>">
					<input type="hidden" name="patient_id" value="<?php echo htmlentities($id); ?>">
                  <button type="submit" name="cancel_btn" class="btn btn-danger">Cancel</button>
                </form>
            </td>
				  <?php
			}
		else
		{
		?>	
		    <td>
               <p>N/A</p>
            </td>
            <td>
               <p>N/A</p>

            </td>
          </tr>
		<?php
		   }				
		 }
		}
		?>
          </div>
        </tbody>
      </table>
</body>
</html>	  
<?php include('../include/footer.php');?>