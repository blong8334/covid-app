<?php
function getCoordinates($address)
{
  include('./secrets.php');
  $ch = curl_init();
  $encodedAddress = urlencode($address);
  $url = $geoUrl . "?key=$apiKey&address=$encodedAddress";
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $response = curl_exec($ch);
  $decRes;
  if ($response) {
    $decRes = json_decode($response, true)['results'][0]['geometry']['location'];
  }
  curl_close($ch);
  // decRes is an obj with props lat and lng;
  return $decRes;
}
