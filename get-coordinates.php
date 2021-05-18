<?php
function getCoordinates($address)
{
  include('secrets.php');
  $ch = curl_init();
  $encodedAddress = urlencode($address);
  $url = $geoUrl . "?key=$apiKey&address=$encodedAddress";
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $response = curl_exec($ch);
  $resp = json_decode($response, true);
  $decRes;
  if ($resp['status']=='OK') { 
    $decRes = $resp['results'][0]['geometry']['location'];
   }
  else {
	  $decRes = [];
  }
  curl_close($ch);
  // decRes is an obj with props lat and lng;
  return $decRes;
}
