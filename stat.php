<?php

$dburl = 'https://lotto-13fa4.firebaseio.com/result/.json';


$ch = curl_init();

// Read Firebase DB
curl_setopt( $ch, CURLOPT_URL,$dburl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo '<br> HTTP Code: ';
echo $httpcode;
echo '<br>';

$data = json_decode($response);

echo gettype($data);
echo '<br />';

//var_dump($data);


// เลขท้ายสองตัว

$d2 = array();
$i=0;
foreach ($data as $key => $value) {
  foreach ($value as $skey => $svalue) {
    //echo $skey.'->'.$svalue.'<br />';
    if ($skey=='d2'){
      $d2[$i]=$svalue;
      $i++;
    }
  }
}

sort($d2);

for($i=0;$i<count($d2);$i++){
  for($j=0;$j<=100;$j++){
    if($d2[$i]==str_pad($j, 2, '0', STR_PAD_LEFT)){
      $d2stat[$d2[$i]]++;
    }
  }
}

$json[]= array('count' => count($d2));

foreach ($d2stat as $key => $value) {
  $json[]= array($key => $value);
}

$obj[] = array( 'd2stat' => $json);


// เลขท้าย 3 ตัว

$d3 = array();
$i=0;
foreach ($data as $key => $value) {
  foreach ($value as $skey => $svalue) {
    //echo $skey.'->'.$svalue.'<br />';
    if (strstr($skey,'d3')){
      $d3[$i]=$svalue;
      $i++;
    }
  }
}

sort($d3);

for($i=0;$i<count($d3);$i++){
  for($j=0;$j<=100;$j++){
    if($d3[$i]==str_pad($j, 3, '0', STR_PAD_LEFT)){
      $d3stat[$d3[$i]]++;
    }
  }
}

$json[]= array('count' => count($d3));

foreach ($d3stat as $key => $value) {
  $json[]= array($key => $value);
}

$obj[] = array( 'd3stat' => $json);




$jsonstring = json_encode($obj);
echo $jsonstring;


 ?>
