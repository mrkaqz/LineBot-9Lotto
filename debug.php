<?php

// Get Lotto Date
$year = 2017;
$month = 1;
$day = 10;

if ( ($month == 1 and $day < 16) or ( $month == 12 and $day == 31 )  ) {
  $lottoday = "30";
  $month = "12";
  $year = $year-1;
}else{
  

if ($day >= 16) {
  $lottoday = "16";
}else{
  $lottoday = "01";
}

}

$currentlottodate = $year.$month.$lottoday;

echo $currentlottodate;
