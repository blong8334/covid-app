<?php
include("config.php");
include("constants.php");

$availability = array();
$patientId = $_POST["patientId"];
for ($row = 0; $row < $_POST["timeSlotCount"]; $row++) {
  for ($day = 0; $day < count($daysOfWeek); $day++) {
    $timeId = $_POST["$row:$day"];
    if (isset($timeId)) {
      array_push($availability, [
        'patient_id' => $patientId,
        'time_id' => $timeId,
        'week_day' => $day,
      ]);
    }
  }
}
$query = $connection->prepare("DELETE FROM PatientAvailability WHERE patient_id=$patientId");
$query->execute();
$insertString = "";
for ($i = 0; $i < count($availability); $i++) {
  $row = $availability[$i];
  $pid = $row['patient_id'];
  $wd = $row['week_day'];
  $tid = $row['time_id'];
  $insertString .= "($pid, $wd, $tid),";
}
$trimmed = rtrim($insertString, ',');
$results = 1;
if (strlen($trimmed)) {
  $query = $connection->prepare("INSERT INTO PatientAvailability(patient_id, week_day, time_id) VALUES $trimmed;");
  $results = $query->execute();
}
if ($results) {
  echo "<h2 class='header'>Update successful</h2>";
} else {
  echo "<h2 class='header'>Update failed</h2>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Submit Availability</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>

<body>

</body>

</html>