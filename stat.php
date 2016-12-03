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
$d2 = array();
$i=0;
foreach ($data as $key => $value) {
  foreach ($value as $skey => $svalue) {
    echo $skey.'->'.$svalue.'<br />';
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

foreach ($d2stat as $key => $value) {
  echo $key.' = '.$value.'<br />';
}
}

 ?>
