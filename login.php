<?php
    session_start();
    include('config.php');
	$error = '';
	$success = '';
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = $connection->prepare("SELECT * FROM user WHERE user_name=:username");
        $query->bindParam("username", $username, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
                $error = "Wrong Username and Password Combination";
        } else {
            if (password_verify($password, $result['passwordhash'])) {
                $_SESSION['user_name'] = $result['user_name'];
                $success = "Login was successful!";
            } else {
                $error = "Wrong Username and Password Combination";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en"> 
<head> 
<meta charset="UTF-8"> 
<title>Login</title> 
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> 
</head> 
<body> 
<div class="container"> 
<div class="row" > 
<div class="col-md-6" style="padding:40px; margin-top:40px; margin-left:250px;">
<div style="color:#F00; text-align:center;"><?php echo $error?></div>
<div style="color:#006400; text-align:center;"><?php echo $success?></div>
<div class="card text-center" style="padding:40px;">
<h2>Login</h2> 
<p>Please fill in your username and password.</p>

<div class="col-md-8" style= "margin-left:70px;">
<form action="" method="post"> 
<div class="form-group"> 
<label>Username</label> 
<input type="text" name="username" class="form-control" required /> 
</div> 
<div class="form-group"> 
<label>Password</label> 
<input type="password" name="password" class="form-control" required> 
</div> 
<div class="form-group"> 
<input type="submit" name="submit" class="btn btn-primary" value="submit"> 
</div> 
<p>Don't have an account? <a href="register.php">Register here</a>.</p> 
</form> 
</div> 
</div> 
</div> 
</div>
</body>
</html>
