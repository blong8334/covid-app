<?php 
if (!isset($_SESSION))
  {
    session_start();
  }
  
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

?>


<?php 
if ($usertype == "patient"){
?>

<div id="top-nav" class="navbar navbar-inverse navbar-static-top">
    <div class="container-fluid">
            <a class="navbar-brand" href="patient.php"><strong>Patient Dashboard</strong></a>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#"><i class="fa fa-user-circle"></i> Settings <span class="caret"></span></a>
                    <ul id="g-account-menu" class="dropdown-menu" role="menu">
                        <li><a href="profile.php"><i class="fa fa-user-secret"></i> My Profile</a></li>
						<li><a href="../change_password.php"><i class="fa fa-lock"></i> Change Password</a></li>
                    </ul>
                </li>
                <li><a href="../logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
        </div>
    </div>    
</div>
<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">

    <ul class="nav nav-pills nav-stacked" style="border-right:2px solid black">
        <li><a href="patient.php"><i class="fa fa-dashboard"></i><strong>Dashboard</strong></a></li>
        <li><a href="appointment.php"><i class="fa fa-tags"></i><strong>My Appointments</strong></a></li>
        <li><a href="calendar.php"><i class="fa fa-tags"></i><strong>My Calendar</strong></a></li>
        
	</ul>
</div>
<?php 
} 
elseif ($usertype == "provider"){
?>
<div id="top-nav" class="navbar navbar-inverse navbar-static-top">
    <div class="container-fluid">
            <a class="navbar-brand" href="provider.php"><strong>Provider Dashboard</strong></a>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#"><i class="fa fa-user-circle"></i> Settings <span class="caret"></span></a>
                    <ul id="g-account-menu" class="dropdown-menu" role="menu">
                        <li><a href="profile.php"><i class="fa fa-user-secret"></i> My Profile</a></li>
						<li><a href="../change_password.php"><i class="fa fa-lock"></i> Change Password</a></li>
                    </ul>
                </li>
                <li><a href="../logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
        </div>
    </div>    
</div>
<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">

    <ul class="nav nav-pills nav-stacked" style="border-right:2px solid black">
        <li><a href="provider.php"><i class="fa fa-dashboard"></i><strong>Dashboard</strong></a></li>
        <li><a href="vaccine-appointment.php"><i class="fa fa-tags"></i><strong>Create Appointment</strong></a></li>		
        <li><a href="appointmentstatus.php"><i class="fa fa-tags"></i><strong>Appointment Status</strong></a></li>
        
	</ul>
</div>
<?php 
}
?>
