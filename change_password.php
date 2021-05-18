<?php 
include('config.php');
$error = '';
$success = '';
if (!isset($_SESSION))
{
    session_start();
}
include('include/header.php');
     
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
	$usertype = $_SESSION['usertype'];	
    session_write_close();
	
} else {
    session_unset();
    session_write_close();
				header("location: index.php"); 
				exit;
}

        if(isset($_POST['change_password'])) {

            $old_pass=$_POST['old_pass'];
            $re_pass=$_POST['re_pass'];
            $new_pass=$_POST['new_pass'];
				
				$query = $connection->prepare("SELECT * FROM user WHERE user_name=:username");
				$query->bindParam("username", $username, PDO::PARAM_STR);
				$query->execute();
				$result = $query->fetch(PDO::FETCH_ASSOC);

                if($result)  
                {  
			     if ($new_pass == $re_pass) {
					if (password_verify($old_pass, $result['passwordhash'])) {


							$password = password_hash($new_pass, PASSWORD_BCRYPT);
                            $query2 = $connection->prepare("UPDATE user SET passwordhash =:password WHERE user_name =:username");
							$query2->bindParam("password", $password, PDO::PARAM_STR);
							$query2->bindParam("username", $username, PDO::PARAM_STR);
							$result2 = $query2->execute();

											
							if ($result2) {
								$success = "Password Change was successful!";
							} else {
								$error = "Password Change Failed";
							}
					}
					   else {
                           $error = "Your old Password is Incorrect!";
                         } 

                    }
					
					else {
						$error = "Passwords do not Match!";
					}

             }
		}

?>
<!doctype html>
<html lang="en">
<title>Password Change</title>
<head>
    <link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap.min.css">
</head>
<body> 
   <div class="container" style='margin-left:480px;'>	
		<div class="panel-heading">	
			<div class="row">
		</br><div style="color:#F00; text-align:center;margin-right:550px;"><?php echo $error?></div>
		<div style="color:#006400; text-align:center; margin-right:550px;"><?php echo $success?></div></br> 					
				<div class="col-md-10">
					<h3 class="panel-title"></h3>
				</div>
			</div>
		</div>	
        <div class="col-md-6">
                <div class="panel panel-default">
                <div class="panel-heading">	
<?php 
if ($usertype == "patient"){
?>						
				 <a href="patient/patient.php" class="pull-right">Back to Dashboard</a></br>
<?php 
} 
elseif ($usertype == "provider"){
?>						
				 <a href="provider/provider.php" class="pull-right">Back to Dashboard</a></br>
<?php 
}
?>							 
                <div class="panel-body">

                    <form action="" method="post">
                        <div class="form-group">
                            <input type="password" name="old_pass" class="form-control" placeholder="Current Password" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="new_pass" class="form-control" placeholder="New Password" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="re_pass" class="form-control" placeholder="Confirm New Password" required>
                        </div></br>
                        <div class="form-group">
                            <input type="submit" style="margin-left:170px;" name="change_password" class="btn btn-primary" value="Change Password"/>
                        </div>
                    </form>
                </div>
                </div>
        </div>
    </div>
</div>
   
</body>
</html>	
<?php include('include/footer.php');?>