<?php
// TODO get provider id
$providerId = 2;
include('config.php');
$hideSuccess = "hidden";
$hideError = "hidden";
if (isset($_POST['submit'])) {
  $date = $_POST['date'];
  $time = $_POST['time'];
  $query = $connection->prepare("INSERT INTO VaccineAppointment(provider_id, appoint_date, appoint_time) VALUES (:providerId, :appDate, :appTime)");
  $query->bindParam("providerId", $providerId, PDO::PARAM_STR);
  $query->bindParam("appDate", $date, PDO::PARAM_STR);
  $query->bindParam("appTime", $time, PDO::PARAM_STR);
  $result = $query->execute();
  if ($result) {
    header('Location: succeeded.html');
  } else {
    header('Location: failed.html');
  }
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Availability</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>

<body>
  <div class="container">
    <div class="form-group">
      <form action="" method="post">
        <label for="date">Appointment Date:</label>
        <input type="date" class="form-control" name="date" required>
        <label for="time">Appointment Date:</label>
        <input type="time" class="form-control" name="time" required>
        <input type="submit" name="submit" class="btn btn-primary" value="Submit">
      </form>
      <h3 <?php echo $hideSuccess ?>>Successfully created appointment</h3>
      <h3 <?php echo $hideError ?>>Failed to create appointment</h3>
    </div>

  </div>
</body>

</html>