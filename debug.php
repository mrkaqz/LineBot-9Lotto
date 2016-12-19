<?php

// Get Lotto Date
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
