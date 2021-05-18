<?php
include('../config.php');
session_start();
$providerid = $_POST['provider_id'];


if(isset($_POST['btn-update_name']))
{   
    $pro_name = $_POST['provider_name'];
	$_SESSION['counter'] = 0;
	$query = $connection->prepare("Update provider SET provider_name =:pro_name WHERE provider_id=:providerid");
	$query->bindParam("providerid", $providerid, PDO::PARAM_INT);
	$query->bindParam("pro_name", $pro_name, PDO::PARAM_STR);
	$query->execute();
    if($query)
    {			
        $_SESSION['status'] = "Name Successfully Updated";
        header('Location: profile.php'); 
    }
    else
    {
        $_SESSION['status'] = "Failed";       
        header('Location: profile.php'); 
    }    
}

elseif(isset($_POST['btn-update_email']))
{   
    $pro_email = $_POST['provider_email'];
	$_SESSION['counter'] = 0;
	$query1 = $connection->prepare("Update provider SET provider_email =:pro_email WHERE provider_id=:providerid");
	$query1->bindParam("providerid", $providerid, PDO::PARAM_INT);
	$query1->bindParam("pro_email", $pro_email, PDO::PARAM_STR);
	$query1->execute();
    if($query1)
    {			
        $_SESSION['status'] = "Email Successfully Updated";
        header('Location: profile.php'); 
    }
    else
    {
        $_SESSION['status'] = "Failed";       
        header('Location: profile.php'); 
    }    
}

elseif(isset($_POST['btn-update_phone']))
{   
    $pro_phone = $_POST['provider_phno'];
	$_SESSION['counter'] = 0;
	$query2 = $connection->prepare("Update provider SET provider_phno =:pro_phone WHERE provider_id=:providerid");
	$query2->bindParam("providerid", $providerid, PDO::PARAM_INT);
	$query2->bindParam("pro_phone", $pro_phone, PDO::PARAM_STR);
	$query2->execute();
    if($query2)
    {			
        $_SESSION['status'] = "Phone No. Successfully Updated";
        header('Location: profile.php'); 
    }
    else
    {
        $_SESSION['status'] = "Failed";       
        header('Location: profile.php'); 
    }    
}

?>