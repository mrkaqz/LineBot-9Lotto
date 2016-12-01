<?


    //$lottourl = 'https://glacial-anchorage-14478.herokuapp.com/kapook.php';
    $lottourl = 'http://lottery.kapook.com/';

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

    //งวด
    echo "งวดวันที่ ";
    $data = $dom->getElementById("spLottoDate");
    echo $data->nodeValue."\n";
    echo "<br>";

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
    $data = $dom->getElementById("no1");
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
    $data = $dom->getElementById("nd2");
    $lottofinal['no1'] = $data->nodeValue;

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


?>
