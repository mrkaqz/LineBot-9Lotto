<?php

$dburl = 'https://lotto-13fa4.firebaseio.com/lottoset/.json';


$ch = curl_init();

// Read Firebase DB
curl_setopt( $ch, CURLOPT_URL,$dburl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($response);

$nextlottodate = '';

foreach ($data as $year => $value) {
 echo $year[0].$year[1].$year[2].$year[3].'<br>';

  foreach ($value as $month => $svalue) {
    echo $month[0].$month[1].'<br>';
    
    foreach ($svalue as $date => $check) {
      echo $date[0].$date[1].' : '.$check.'<br>';

      if ($nextlottodate == '' and $check == 'New') {
        $nextlottodate = $year[0].$year[1].$year[2].$year[3].$month[0].$month[1].$date[0].$date[1];
        echo 'Next Lotto Date : '.$nextlottodate.'<br>';
      }

    }

  }

}

/* Get Lotto Date
$year = 2017;
$month = 5;
$day = 18;

if ($month == 1 and $day < 16) {
  $lottoday = "30";
  $month = "12";
  $year = $year-1;
}elseif ($month == 12 and $day == 31) {
  $lottoday = "30";
  $month = "12";
  $year = $year;
}else{

if ($day >= 16) {
  $lottoday = "16";
}else{
  $lottoday = "01";
}

}

$month = str_pad($month, 2, '0', STR_PAD_LEFT);

$currentlottodate = $year.$month.$lottoday;

echo $currentlottodate;
*/