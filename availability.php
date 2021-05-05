<?php
// TODO get patient id
$patientId = 2;
include('config.php');
include('constants.php');

$timeSlots = $connection->prepare("SELECT * FROM Timeslot");
$timeSlots->execute();
$timeSlots->bindColumn(1, $timeId);
$timeSlots->bindColumn(2, $startTime);
$timeSlots->bindColumn(3, $endTime);

$currentAvail = $connection->prepare("SELECT week_day, time_id FROM PatientAvailability WHERE patient_id=$patientId");
$currentAvail->execute();
$currentAvail->bindColumn(1, $wd);
$currentAvail->bindColumn(2, $tid);
$availMap = [];
while ($currentAvail->fetch()) {
  $availMap["$tid:$wd"] = true;
}

echo "<div class='container'>";
echo "<form action='submit-availability.php' method='POST'>";
echo "<table class='table'>\n";
echo "<tr>";
echo "<th>Time Slot</th>";
for ($i = 0; $i < count($daysOfWeek); $i++) {
  $value = $daysOfWeek[$i];
  echo "<th>$value</th>";
}
echo "</tr>";
$timeSlotCounter = 0;
while ($timeSlots->fetch()) {
  echo "<tr>";
  echo "<td>$startTime - $endTime</td>";
  for ($day = 0; $day < count($daysOfWeek); $day++) {
    $inputBox = "<input type='checkbox' name='$timeSlotCounter:$day' value='$timeId'";
    if ($availMap["$timeId:$day"]) {
      $inputBox .= " checked";
    }
    echo "<td>$inputBox></td>";
  }
  $timeSlotCounter += 1;
  echo "</tr>";
}
echo "</table>";
echo "<input type='hidden' name='timeSlotCount' value='$timeSlotCounter'>";
echo "<input type='hidden' name='patientId' value='$patientId'>";
echo "<input type='submit' value='Submit'>";
echo "</form></div>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Availability</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>

<body>
</body>

</html>