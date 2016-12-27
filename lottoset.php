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
    

    $year = $key[5].$key[6].$key[7].$key[8];
    $month = $key[9].$key[10];
    $date = $key[11].$key[12];

  foreach ($value as $skey => $svalue) {
    //echo $skey.'->'.$svalue.'<br />';
    if ($skey=='spLottoDate'){
      $lottoset[$i]=$svalue;
      echo $lottoset[$i].'<br>';
      $i++;
    }
  }

$uri = 'https://lotto-13fa4.firebaseio.com/lottoset/'.$year.'/'.$month.'.json';
echo $uri;
echo '<br>';
}

