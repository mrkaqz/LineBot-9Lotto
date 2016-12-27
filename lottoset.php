<?php

$dburl = 'https://lotto-13fa4.firebaseio.com/result/.json';


$ch = curl_init();

// Read Firebase DB
curl_setopt( $ch, CURLOPT_URL,$dburl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);



$data = json_decode($response);

$lottoset = array();
$i=0;
foreach ($data as $key => $value) { 
  foreach ($value as $skey => $svalue) {
    //echo $skey.'->'.$svalue.'<br />';
    if ($skey=='spLottoDate'){
      $lottoset[$i]=$svalue;
      echo $lottoset[$i].'<br>';
      $i++;
    }
  }
}