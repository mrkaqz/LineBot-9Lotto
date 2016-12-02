<?


    $lottourl = 'http://lottery.kapook.com/';

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

    echo '<br />';

    // Get Lotto Date
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    if ($day >= 16) {
      $lottoday = "16";
    }else{
      $lottoday = "01";
    }
    $lottodate = $year.$month.$lottoday;
    //$lottodate = '20161116';

$dburl = 'https://lotto-13fa4.firebaseio.com/result/lotto'.$lottodate.'.json';


$data = json_encode($lottofinal);
echo 'JSON : '.$data;

$ch = curl_init();

// Read Firebase DB
curl_setopt( $ch, CURLOPT_URL,$dburl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

/*
// Create Firebase DB
$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL,$dburl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
*/
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$response = curl_exec($ch);
curl_close($ch);


echo '<br><br> Respond: ';
echo $response;
echo '<br> HTTP Code: ';
echo $httpcode;
echo '<br>';

if ($response == "") {
  echo 'No Database';
}else{
  echo 'Database Node OK';
}

$lottofinal = json_decode($response);
echo '<br>Array count = '.count($lottofinal);
echo '<br>Not-Empty Array = '.count(array_filter($lottofinal));
