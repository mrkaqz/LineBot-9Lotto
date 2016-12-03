<html>
<head>
  <title>Firebase</title>
</head>
<body>

<form action="firebase.php" method="get">
<select name="year">
  <option value="2016">2016</option>
  <option value="2016">2015</option>
  <option value="2016">2014</option>
  <option value="2016">2013</option>
  <option value="2016">2012</option>
  <option value="2016">2011</option>
  <option value="2016">2010</option>
  <option value="2016">2009</option>
</select>
<select name="month">
  <option value="01">01</option>
  <option value="02">02</option>
  <option value="03">03</option>
  <option value="04">04</option>
  <option value="05">05</option>
  <option value="06">06</option>
  <option value="07">07</option>
  <option value="08">08</option>
  <option value="09">09</option>
  <option value="10">10</option>
  <option value="11">11</option>
  <option value="12">12</option>
</select>
<select name="day">
  <option value="01">01</option>
  <option value="16">02</option>
</select>
<input type="submit">
</form>

<?php

if ($_GET["year"] !== ''){
$year = $_GET["year"];
$month = $_GET["month"];
$lottoday = $_GET["day"];
}else{
// Get Lotto Date
$year = date('Y');
$month = date('m');
$day = date('d');
if ($day >= 16) {
  $lottoday = "16";
}else{
  $lottoday = "01";
}

}

    $lottodate = $year.$month.$lottoday;
    //$lottodate = '20161116';

$dburl = 'https://lotto-13fa4.firebaseio.com/result/lotto'.$lottodate.'.json';


$ch = curl_init();

// Read Firebase DB
curl_setopt( $ch, CURLOPT_URL,$dburl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

$keycount=0;$valuecount=0;
$lottodb = json_decode($response);
foreach ($lottodb as $key => $value) {
  $keycount++;
  if($value !== "") {
    $valuecount++;
  }
}

$nprize = 174;

if ( $keycount !== $nprize or $valuecount !== $nprize) {

// Create Firebase DB

$lottourl = 'http://lottery.kapook.com/'.$year+543.'/'.$year.'-'.$month.'-'.$lottoday.'.html';

echo 'Source: '.$lottourl;
echo '<br />';

$html = file_get_contents($lottourl);

$begin = strpos($html, '//STARTPRIZE');
$end   = strpos($html, '//STOPPRIZE');

$GenStr = substr($html, $begin, ($end - $begin));


$begin = strpos($GenStr, '"');
$end   = strrpos($GenStr, '""');

$GenStrVar = substr($GenStr, $begin+1, ($end - $begin)-2);

//echo $GenStrVar;

$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTMLFile($lottourl);

$data = $dom->getElementById('spLottoDate');
$lottofinal['spLottoDate'] = $data->nodeValue;

// รางวัล

if(!strstr($GenStr,'GenStr=""')){

  $lottoarr = explode("@", $GenStrVar);
  $i=0;
  foreach ($lottoarr as $lotto) {
    $lottoprize = explode("#", $lotto);
    $lottofinal[$lottoprize[0]] = $lottoprize[1];
  }


}else{

// รางวัลที่ 1
$data = $dom->getElementById('no1');
$lottofinal['no1'] = $data->nodeValue;

for ($i = 1; $i <= 2; $i++) {
    $loopkey = 'd3:'.$i;
    $data = $dom->getElementById($loopkey);
    $lottofinal[$loopkey] = $data->nodeValue;
}

//เลขท้าย 3 ตัว
for ($i = 3; $i <= 4; $i++) {
    $loopkey = 'd3:'.$i;
    $data = $dom->getElementById($loopkey);
    $lottofinal[$loopkey] = $data->nodeValue;
}

//เลขท้าย 2 ตัว
$data = $dom->getElementById('d2');
$lottofinal['d2'] = $data->nodeValue;

//ใกล้เคียงรางวัลที่ 1
for ($i = 1; $i <= 2; $i++) {
    $loopkey = 'no1nr:'.$i;
    $data = $dom->getElementById($loopkey);
    $lottofinal[$loopkey] = $data->nodeValue;
}

//รางวัลที่ 2
for ($i = 1; $i <= 5; $i++) {
    $loopkey = 'no2:'.$i;
    $data = $dom->getElementById($loopkey);
    $lottofinal[$loopkey] = $data->nodeValue;
}

//รางวัลที่ 3
for ($i = 1; $i <= 10; $i++) {
    $loopkey = 'no3:'.$i;
    $data = $dom->getElementById($loopkey);
    $lottofinal[$loopkey] = $data->nodeValue;
}

//รางวัลที่ 4
for ($i = 1; $i <= 50; $i++) {
    $loopkey = 'no4:'.$i;
    $data = $dom->getElementById($loopkey);
    $lottofinal[$loopkey] = $data->nodeValue;
}

//รางวัลที่ 5
for ($i = 1; $i <= 100; $i++) {
    $loopkey = 'no5:'.$i;
    $data = $dom->getElementById($loopkey);
    $lottofinal[$loopkey] = $data->nodeValue;
}


}

//งวด
echo "งวดวันที่ ";
$data = $dom->getElementById("spLottoDate");
echo $data->nodeValue."\n";
echo "<br>";

// รางวัลที่ 1
echo "รางวัลที่ 1";
echo "<br>";
echo $lottofinal['no1'];
echo "<br>";

//เลขหน้า 3 ตัว
echo "เลขหน้า 3 ตัว";
echo "<br>";
for ($i = 1; $i <= 2; $i++) {
    $loopkey = 'd3:'.$i;
    echo $lottofinal[$loopkey]." ";
}
echo "<br>";

//เลขท้าย 3 ตัว
echo "เลขท้าย 3 ตัว";
echo "<br>";
for ($i = 3; $i <= 4; $i++) {
    $loopkey = 'd3:'.$i;
    echo $lottofinal[$loopkey]." ";
}
echo "<br>";

//เลขท้าย 2 ตัว
echo "เลขท้าย 2 ตัว";
echo "<br>";
echo $lottofinal['d2'];
echo "<br>";

//ใกล้เคียงรางวัลที่ 1
echo "ใกล้เคียงรางวัลที่ 1";
echo "<br>";
for ($i = 1; $i <= 2; $i++) {
    $loopkey = 'no1nr:'.$i;
    echo $lottofinal[$loopkey]." ";
}
echo "<br>";

//รางวัลที่ 2
echo "รางวัลที่ 2";
echo "<br>";
for ($i = 1; $i <= 5; $i++) {
    $loopkey = 'no2:'.$i;
    echo $lottofinal[$loopkey]." ";
}
echo "<br>";

//รางวัลที่ 3
echo "รางวัลที่ 3";
echo "<br>";
for ($i = 1; $i <= 10; $i++) {
    $loopkey = 'no3:'.$i;
    echo $lottofinal[$loopkey]." ";
}
echo "<br>";

//รางวัลที่ 4
echo "รางวัลที่ 4";
echo "<br>";
for ($i = 1; $i <= 50; $i++) {
    $loopkey = 'no4:'.$i;
    echo $lottofinal[$loopkey]." ";
}
echo "<br>";

//รางวัลที่ 5
echo "รางวัลที่ 5";
echo "<br>";
for ($i = 1; $i <= 100; $i++) {
    $loopkey = 'no5:'.$i;
    echo $lottofinal[$loopkey]." ";
}

$data = json_encode($lottofinal);

echo 'JSON : '.$data;

// PUT to Firebase DB
curl_setopt( $ch, CURLOPT_URL,$dburl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
echo '<br /> Build DB';

}

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);


echo '<br><br> Respond: ';
echo $response;
echo '<br> HTTP Code: ';
echo $httpcode;
echo '<br>';


echo '<br />Key: '.$keycount.' Value: '.$valuecount;
echo '<br />';

echo gettype($lottodb);

foreach ($lottodb as $key => $value) {
  $lottofinal[$key] = $value;
}
echo gettype($lottodb);
echo '<br />';
echo gettype($lottofinal);


?>

</body>
</html>
