<?php
include('config.php');
$query = $connection->prepare("SELECT * FROM Timeslot");
$query->execute();
$query->bindColumn(1, $timeId);
$query->bindColumn(2, $startTime);
$query->bindColumn(3, $endTime);
$daysOfWeek = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

echo "<div class='container'>";
echo "<form action='/submit-availability.php'>";
echo "<table class='table'>\n";
echo "<tr>";
echo "<th>Time Slot</th>";
for ($i = 0; $i < count($daysOfWeek); $i++) {
  $value = $daysOfWeek[$i];
  echo "<th>$value</th>";
}
echo "</tr>";
while ($query->fetch()) {
  echo "<tr>";
  echo "<td>$startTime - $endTime</td>";
  for ($i = 0; $i < 7; $i++) {
    echo "<td><input type='checkbox' value='$timeId:$i'></td>";
  }
  echo "</tr>";
}
echo "</table></form></div>";
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


</body>

</html>