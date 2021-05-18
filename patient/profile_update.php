<?php
include('../config.php');
session_start();
$pat_id = $_POST['patient_id'];


if(isset($_POST['btn-update_name']))
{   
    $pat_name = $_POST['patient_name'];
	$_SESSION['counter'] = 0;
	$query = $connection->prepare("Update patient SET patient_name = :pat_name WHERE patient_id=:pat_id");
	$query->bindParam("pat_id", $pat_id, PDO::PARAM_INT);
	$query->bindParam("pat_name", $pat_name, PDO::PARAM_STR);
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
    $pat_email = $_POST['patient_email'];
	$_SESSION['counter'] = 0;
	$query1 = $connection->prepare("Update patient SET patient_email = :pat_email WHERE patient_id=:pat_id");
	$query1->bindParam("pat_id", $pat_id, PDO::PARAM_INT);
	$query1->bindParam("pat_email", $pat_email, PDO::PARAM_STR);
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


elseif(isset($_POST['btn-update_dob']))
{   
    $pat_dob = $_POST['patient_dob'];
	$_SESSION['counter'] = 0;
	$query2 = $connection->prepare("Update patient SET patient_dob = :pat_dob WHERE patient_id=:pat_id");
	$query2->bindParam("pat_id", $pat_id, PDO::PARAM_INT);
	$query2->bindParam("pat_dob", $pat_dob, PDO::PARAM_STR);
	$query2->execute();
    if($query2)
    {			
        $_SESSION['status'] = "Date Of Birth Successfully Updated";
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
    $pat_phone = $_POST['patient_phno'];
	$_SESSION['counter'] = 0;
	$query3 = $connection->prepare("Update patient SET patient_phno = :pat_phone WHERE patient_id=:pat_id");
	$query3->bindParam("pat_id", $pat_id, PDO::PARAM_INT);
	$query3->bindParam("pat_phone", $pat_phone, PDO::PARAM_STR);
	$query3->execute();
    if($query3)
    {			
        $_SESSION['status'] = "Phone Number Successfully Updated";
        header('Location: profile.php'); 
    }
    else
    {
        $_SESSION['status'] = "Failed";       
        header('Location: profile.php'); 
    }    
}

elseif(isset($_POST['btn-update_max_dist']))
{   
    $max_travel = $_POST['patient_maxdist'];
	$_SESSION['counter'] = 0;
	$query4 = $connection->prepare("Update patient SET max_travel = :max_travel WHERE patient_id=:pat_id");
	$query4->bindParam("pat_id", $pat_id, PDO::PARAM_INT);
	$query4->bindParam("max_travel", $max_travel, PDO::PARAM_INT);
	$query4->execute();
    if($query4)
    {			
        $_SESSION['status'] = "Travel Preference Successfully Updated";
        header('Location: profile.php'); 
    }
    else
    {
        $_SESSION['status'] = "Failed";       
        header('Location: profile.php'); 
    }    
}
elseif(isset($_POST['patient_upload']))
{
	$_SESSION['counter'] = 0;
	$currentDirectory = getcwd();
	$root = "uploads";
	 if(!is_dir($root)){
       mkdir($root, 0755);
     }
    $location = "uploads".DIRECTORY_SEPARATOR.$pat_id;
     if(!is_dir($location)){
       mkdir($location, 0755);
     }
    $fileExt = array('jpeg','jpg','png','pdf');

    $fileName = $_FILES['patient_file']['name'];
    $fileSize = $_FILES['patient_file']['size'];
    $fileTmpName  = $_FILES['patient_file']['tmp_name'];
    $fileType = $_FILES['patient_file']['type'];
	$tmp           = explode('.', $fileName);
	$fileExtension = end($tmp);

    $uploadPath = $currentDirectory . DIRECTORY_SEPARATOR. $location . DIRECTORY_SEPARATOR. basename($fileName); 
	
      if (! in_array($fileExtension,$fileExt)) {
        $_SESSION['status'] = "FileType is not Supported";
        header('Location: profile.php'); 
      }
      else {
      if ($fileSize > 4000000) {
        $_SESSION['status'] = "File exceeds maximum size (4MB)";
        header('Location: profile.php'); 		
      }
		if (file_exists($uploadPath)){
        $_SESSION['status'] = "File Already Exists!";
        header('Location: profile.php'); 
		}else{
        $Upload = move_uploaded_file($fileTmpName, $uploadPath);

        if ($Upload) {
		$query5 = $connection->prepare("Insert into patientupload (patient_id, file_pointer) VALUES (:pat_id, :fileName)");
		$query5->bindParam("pat_id", $pat_id, PDO::PARAM_INT);
		$query5->bindParam("fileName", $fileName, PDO::PARAM_STR);
		$query5->execute();			
        $_SESSION['status'] = "File Successfully Uploaded";
        header('Location: profile.php'); 
        } else {
        $_SESSION['status'] = "Error Occurred";
        header('Location: profile.php'); 
        }
		}
}}
elseif(isset($_POST['remove_btn']))
{   
    $filename = $_POST['filename'];
    $fileid = $_POST['file_id'];

	$_SESSION['counter'] = 0;
	$query6 = $connection->prepare("Delete from patientupload where file_pointer = :filename and patient_id=:pat_id");
	$query6->bindParam("pat_id", $pat_id, PDO::PARAM_INT);
	$query6->bindParam("filename", $filename, PDO::PARAM_STR);
	$query6->execute();
    if($query6)
    {
	$currentDirectory = getcwd();		
    $location = "uploads".DIRECTORY_SEPARATOR.$pat_id;
    $uploadPath = $currentDirectory . DIRECTORY_SEPARATOR. $location . DIRECTORY_SEPARATOR. basename($filename); 
    unlink($uploadPath);   	
		
        $_SESSION['status'] = "File Successfully Deleted";
        header('Location: profile.php'); 
    }
    else
    {
        $_SESSION['status'] = "Failed";       
        header('Location: profile.php'); 
    }    
}
?>