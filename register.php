<?php
    session_start();
	$error = '';
	$success = '';
    include('config.php');
    include "get-coordinates.php";
	
    if (isset($_POST['submit'])) {
		
        $username = $_POST['username'];
        $password = $_POST['password'];
		$usertype = $_POST['usertype'];
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $query = $connection->prepare("SELECT * FROM user WHERE user_name=:username");
        $query->bindParam("username", $username, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {
		    $error = "Username already exists!";
		}
        elseif ($query->rowCount() == 0) {
            $query = $connection->prepare("INSERT INTO user(user_name,passwordhash,user_type) VALUES (:username,:password_hash,:usertype)");
            $query->bindParam("username", $username, PDO::PARAM_STR);
            $query->bindParam("password_hash", $password_hash, PDO::PARAM_STR);
            $query->bindParam("usertype", $usertype, PDO::PARAM_STR);

		if ($usertype == 'patient') {
			$patient_name = htmlentities($_POST['patient_name']);
			$patient_ssn = htmlentities($_POST['patient_ssn']);
			$patient_dob = htmlentities($_POST['patient_dob']);
			$patient_phno = htmlentities($_POST['patient_phno']);
			$patient_email = htmlentities($_POST['patient_email']);
			$patient_street = htmlentities($_POST['patient_street']);
			$patient_city = htmlentities($_POST['patient_city']);
			$patient_state = htmlentities($_POST['patient_state']);
			$patient_zip = htmlentities($_POST['patient_zip']);
			$max_travel = htmlentities($_POST['max_travel']);
			
			$add = ($patient_street.', '.$patient_city.', '.$patient_state.', '.$patient_zip);
			$coord = getCoordinates($add);
			if ($coord) {
				$lat = $coord['lat'];
				$long = $coord['lng'];
				echo "response received";
			}
			else { 
				$lat = 70;
				$long = 60;
				echo "default value used";
			}

			$query2 = $connection->prepare("INSERT INTO patient(user_name,patient_name,patient_ssn,patient_dob,
            patient_phno, patient_email, patient_location, patient_street, patient_city, patient_state, patient_zip, max_travel) VALUES (:username,:patient_name,:patient_ssn,:patient_dob,
            :patient_phno, :patient_email, ST_GeomFromText(:patient_location), :patient_street, :patient_city, :patient_state, :patient_zip, :max_travel)");
            $query2->bindParam("username", $username, PDO::PARAM_STR);
			$query2->bindParam("patient_name", $patient_name, PDO::PARAM_STR);
            $query2->bindParam("patient_ssn", $patient_ssn, PDO::PARAM_STR);
            $query2->bindParam("patient_dob", $patient_dob, PDO::PARAM_STR);
            $query2->bindParam("patient_phno", $patient_phno, PDO::PARAM_STR);
            $query2->bindParam("patient_email", $patient_email, PDO::PARAM_STR);
            $query2->bindValue("patient_location", "POINT($lat $long)", PDO::PARAM_STR);			
            $query2->bindParam("patient_street", $patient_street, PDO::PARAM_STR);
            $query2->bindParam("patient_city", $patient_city, PDO::PARAM_STR);
            $query2->bindParam("patient_state", $patient_state, PDO::PARAM_STR);
            $query2->bindParam("patient_zip", $patient_zip, PDO::PARAM_STR);	
            $query2->bindParam("max_travel", $max_travel, PDO::PARAM_INT);	

			$query->execute();
            $result2 = $query2->execute();  
			
            if ($result2) {
                $success = "Your registration was successful!";
            } else {
                $error = "Registration Failed";
            }
    }
		if ($usertype == 'provider') {
			$provider_name = htmlentities($_POST['provider_name']);
			$provider_type = htmlentities($_POST['provider_type']);
			$provider_phno = htmlentities($_POST['provider_phno']);
			$provider_email = htmlentities($_POST['provider_email']);
			$provider_street = htmlentities($_POST['provider_street']);
			$provider_city = htmlentities($_POST['provider_city']);
			$provider_state = htmlentities($_POST['provider_state']);
			$provider_zip = htmlentities($_POST['provider_zip']);
			
			$add = ($provider_street.', '.$provider_city.', '.$provider_state.', '.$provider_zip);
			$coord = getCoordinates($add);
			if ($coord) {
				$lat = $coord['lat'];
				$long = $coord['lng'];
				echo "response received";
			}
			else { 
				$lat = 70;
				$long = 60;
				echo "default value used";
			}

			$query3 = $connection->prepare("INSERT INTO provider(user_name, provider_name, provider_type, provider_phno, provider_email, provider_location, 
			provider_street, provider_city, provider_state, provider_zip) VALUES (:username, :provider_name, :provider_type, :provider_phno, 
			:provider_email, ST_GeomFromText(:provider_location), :provider_street, :provider_city, :provider_state, :provider_zip)");
            $query3->bindParam("username", $username, PDO::PARAM_STR);
            $query3->bindParam("provider_name", $provider_name, PDO::PARAM_STR);
			$query3->bindParam("provider_type", $provider_type, PDO::PARAM_STR);
            $query3->bindParam("provider_phno", $provider_phno, PDO::PARAM_STR);
            $query3->bindParam("provider_email", $provider_email, PDO::PARAM_STR);
            $query3->bindValue("provider_location", "POINT($lat $long)", PDO::PARAM_STR);			
            $query3->bindParam("provider_street", $provider_street, PDO::PARAM_STR);
            $query3->bindParam("provider_city", $provider_city, PDO::PARAM_STR);
            $query3->bindParam("provider_state", $provider_state, PDO::PARAM_STR);
            $query3->bindParam("provider_zip", $provider_zip, PDO::PARAM_STR);	

			$query->execute();
            $result3 = $query3->execute();					
            if ($result3) {
                $success = "Your registration was successful!";
            } else {
                $error = "Registration Failed";
            }
    }		
    }
	}
?>


<!DOCTYPE html>
<html lang="en"> 
<head> 
<meta charset="UTF-8"> 
<title>Vaccination System</title> 
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="js/reg.js"></script>

</head> 
<body> 
<div class="container"> 
<div class="row"> 
<div class="col-md-12"> 
<div class="card text-center" style="padding:20px;">
  <h3>Vaccine Registration</h3><br>
    <h5>Please fill this form to create an account.</h5>
	<div class=" text-right" >
<p>Already Registered? <a href="index.php">Login here</a>.</p>
</div>
</div><br>
</div>
<div class="col-md-5">
<div style="color:#F00; text-align:center; "><?php echo $error?></div>
<div style="color:#006400; text-align:center;"><?php echo $success?></div> 
<form action="" method="post"> 
<div class="form-group"> 
			<label for="usertype">Register as:</label>
            <select id = "usertype" class="form-control" name="usertype" required="">
            <option value="">Select an option</option>
            <option value="patient">Patient</option>
            <option value="provider">Provider</option>
			</select>
</div>
<div class="form-group"> 
<label>Username</label> 
<input type="text" name="username" class="form-control" required> 
</div> 
<div class="form-group"> 
<label>Password</label> 
<input type="password" name="password" class="form-control" required> 
</div> 
		 <div id="patientdetails" style="display:none;" class="form-group">
					<label for="patient_name">Name:</label>
					<input type="text" class="form-control" name="patient_name" placeholder="Enter Name" required><br>
					<label for="patient_ssn">SSN:</label>
					<input type="number" class="form-control" name="patient_ssn" placeholder="Enter SSN" required><br>
					<label for="patient_dob">Date of Birth:</label>
					<input type="date" class="form-control" name="patient_dob" required><br>
					<label for="patient_phno">Phone Number:</label>
					<input type="number" class="form-control" name="patient_phno" placeholder="Enter Phone Number" required><br>
					<label for="patient_email">Email:</label>
					<input type="email" class="form-control" name="patient_email" placeholder="Enter your Email" required><br>
					<label for="patient_street">Street:</label>
					<input type="text" class="form-control" name="patient_street" placeholder="Street" required><br>
					<label for="patient_city">City:</label>
					<input type="text" class="form-control" name="patient_city" placeholder="City" required><br>
					<label for="patient_state">State:</label>
					<input type="text" class="form-control" name="patient_state" placeholder="State" required><br>
					<label for="patient_zip">Zipcode:</label>
					<input type="number" class="form-control" name="patient_zip" placeholder="Zipcode" required></br>
					<label for="max_travel">How Much Are You Willing to Travel? (in Kms):</label>
					<input type="number" class="form-control" name="max_travel" placeholder="Maximum Travel Distance" required>					

          </div>
		 <div id="providerdetails" style="display:none;" class="form-group">
					<label for="provider_name">Name:</label>
					<input type="text" class="form-control" name="provider_name" placeholder="Enter Name" required><br>
					<label for="provider_type">Select Type:</label>
					<select id = "provider_type" class="form-control" name="provider_type" required><br>
					<option value="">Select an option</option>
					<option value="doctor">Doctor</option>
					<option value="hospital">Hospital</option>
					</select><br>
					<label for="provider_phno">Phone Number:</label>
					<input type="number" class="form-control" name="provider_phno" placeholder="Enter Phone Number" required><br>
					<label for="provider_email">Email:</label>
					<input type="email" class="form-control" name="provider_email" placeholder="Enter your Email" required><br>
					<label for="provider_street">Street:</label>
					<input type="text" class="form-control" name="provider_street" placeholder="Street" required><br>
					<label for="provider_city">City:</label>
					<input type="text" class="form-control" name="provider_city" placeholder="City" required><br>
					<label for="provider_state">State:</label>
					<input type="text" class="form-control" name="provider_state" placeholder="State" required><br>
					<label for="provider_zip">Zipcode:</label>
					<input type="number" class="form-control" name="provider_zip" placeholder="Zipcode" required>
          </div>

<div class="form-group"> 
<input type="submit" name="submit" class="btn btn-primary" value="Submit"> 
</div>
</form> 
</div>
</div> 
</div> 
</body>
</html>
