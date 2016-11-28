<?php
echo "Hello LINE BOT <br />";

$year = rand(2015,2018);
$month = rand(01,12);
$day = rand(01,31);
if ($day >= 16) {
  $lottoday = 16;
}else{
  $lottoday = 1;
}
$lottodate = $year."-".$month."-".$lottoday;
echo $year."-".$month."-".$day;
echo "<br />";
echo $lottodate;
