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
  for($j=0;$j<=99;$j++){
    if($d2[$i]==str_pad($j, 2, '0', STR_PAD_LEFT)){
      $d2stat[$d2[$i]]++;
    }
  }
}

$json2[]= array('countd2' => count($d2));

foreach ($d2stat as $key => $value) {
  $json2[]= array($key => $value);
}

$obj[] = array( 'd2stat' => $json2);


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
  for($j=0;$j<=999;$j++){
    if($d3[$i]==str_pad($j, 3, '0', STR_PAD_LEFT)){
      $d3stat[$d3[$i]]++;
    }
  }
}

$json3[]= array('countd3' => count($d3));

foreach ($d3stat as $key => $value) {
  $json3[]= array($key => $value);
}

$obj[] = array( 'd3stat' => $json3);

$jsonstring = json_encode($obj);

if ($_GET['type'] == 'json'){

echo $jsonstring;

}else{

  echo '<br> HTTP Code: ';
  echo $httpcode;
  echo '<br>';
  
}
echo "<br>";
echo "Today is " . date("Y/m/d") . "<br>";
echo "<br>";
echo "The time is " . date("h:i:sa");

 ?>
