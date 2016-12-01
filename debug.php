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

    //$replyMsg .= $GenStrVar;

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

    //งวด
    $replyMsg .= "งวดวันที่ ";
    $data = $dom->getElementById("spLottoDate");
    $replyMsg .= $data->nodeValue."\n";
    $replyMsg .= chr(10).chr(10);

    // รางวัลที่ 1
    $replyMsg .= "รางวัลที่ 1";
    $replyMsg .= chr(10);
    $replyMsg .= $lottofinal['no1'];
    $replyMsg .= chr(10).chr(10);

    //เลขหน้า 3 ตัว
    $replyMsg .= "เลขหน้า 3 ตัว";
    $replyMsg .= chr(10);
    for ($i = 1; $i <= 2; $i++) {
        $loopkey = 'd3:'.$i;
        $replyMsg .= $lottofinal[$loopkey]." ";
    }
    $replyMsg .= chr(10).chr(10);

    //เลขท้าย 3 ตัว
    $replyMsg .= "เลขท้าย 3 ตัว";
    $replyMsg .= chr(10);
    for ($i = 3; $i <= 4; $i++) {
        $loopkey = 'd3:'.$i;
        $replyMsg .= $lottofinal[$loopkey]." ";
    }
    $replyMsg .= chr(10).chr(10);

    //เลขท้าย 2 ตัว
    $replyMsg .= "เลขท้าย 2 ตัว";
    $replyMsg .= chr(10);
    $replyMsg .= $lottofinal['d2'];
    $replyMsg .= chr(10).chr(10);

    //ใกล้เคียงรางวัลที่ 1
    $replyMsg .= "ใกล้เคียงรางวัลที่ 1";
    $replyMsg .= chr(10);
    for ($i = 1; $i <= 2; $i++) {
        $loopkey = 'no1nr:'.$i;
        $replyMsg .= $lottofinal[$loopkey]." ";
    }
    $replyMsg .= chr(10).chr(10);

    //รางวัลที่ 2
    $replyMsg .= "รางวัลที่ 2";
    $replyMsg .= chr(10);
    for ($i = 1; $i <= 5; $i++) {
        $loopkey = 'no2:'.$i;
        $replyMsg .= $lottofinal[$loopkey]." ";
    }
    $replyMsg .= chr(10).chr(10);

    //รางวัลที่ 3
    $replyMsg .= "รางวัลที่ 3";
    $replyMsg .= chr(10);
    for ($i = 1; $i <= 10; $i++) {
        $loopkey = 'no3:'.$i;
        $replyMsg .= $lottofinal[$loopkey]." ";
    }
    $replyMsg .= chr(10).chr(10);

    //รางวัลที่ 4
    $replyMsg .= "รางวัลที่ 4";
    $replyMsg .= chr(10);
    for ($i = 1; $i <= 50; $i++) {
        $loopkey = 'no4:'.$i;
        $replyMsg .= $lottofinal[$loopkey]." ";
    }
    $replyMsg .= chr(10).chr(10);

    //รางวัลที่ 5
    $replyMsg .= "รางวัลที่ 5";
    $replyMsg .= chr(10);
    for ($i = 1; $i <= 100; $i++) {
        $loopkey = 'no5:'.$i;
        $replyMsg .= $lottofinal[$loopkey]." ";
    }



?>
