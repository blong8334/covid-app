<?php
include('../config.php');
session_start();
$date = date('Y-m-d H:i:s');
$apt_id = $_POST['appoint_id'];
$pat_id = $_POST['patient_id'];
if(isset($_POST['accept_btn']))
{   $_SESSION['counter'] = 0;
	$query = $connection->prepare("Update vaccineoffer SET status = 'accepted', reply_date =:date WHERE patient_id=:pat_id AND appoint_id=:apt_id");
	$query->bindParam("pat_id", $pat_id, PDO::PARAM_INT);
	$query->bindParam("apt_id", $apt_id, PDO::PARAM_INT);
	$query->bindParam("date", $date, PDO::PARAM_STR);
	$query->execute();
    if($query)
    {			
        $_SESSION['status'] = "Appointment Accepted";
        header('Location: appointment.php'); 
    }
    else
    {
        $_SESSION['status'] = "Could not Accept the Appointment";       
        header('Location: appointment.php'); 
    }    
}

elseif(isset($_POST['decline_btn']))
{
    $_SESSION['counter'] = 0;
	$query1 = $connection->prepare("Update vaccineoffer SET status = 'declined', reply_date =:date WHERE patient_id=:pat_id AND appoint_id=:apt_id");
	$query1->bindParam("pat_id", $pat_id, PDO::PARAM_INT);
	$query1->bindParam("apt_id", $apt_id, PDO::PARAM_INT);
	$query1->bindParam("date", $date, PDO::PARAM_STR);
	$query1->execute();
    if($query1)
    {
        $_SESSION['status'] = "Appointment Declined";
        header('Location: appointment.php'); 
    }
    else
    {
        $_SESSION['status'] = "Could not Decline the Appointment";       
        header('Location: appointment.php'); 
    }    
}


elseif(isset($_POST['cancel_btn']))
{
    $_SESSION['counter'] = 0;
	$query2 = $connection->prepare("Update vaccineoffer SET status = 'cancelled' WHERE patient_id=:pat_id AND appoint_id=:apt_id");
	$query2->bindParam("pat_id", $pat_id, PDO::PARAM_INT);
	$query2->bindParam("apt_id", $apt_id, PDO::PARAM_INT);
	$query2->execute();
    if($query2)
    {
        $_SESSION['status'] = "Appointment Cancelled";
        header('Location: appointment.php'); 
    }
    else
    {
        $_SESSION['status'] = "Could not Cancel the Appointment";       
        header('Location: appointment.php'); 
    }    
}

?>