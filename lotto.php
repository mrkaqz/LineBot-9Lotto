<?
$replyMsg = "";

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTMLFile('http://lottery.kapook.com/');

    //งวด
    $replyMsg .= "งวดวันที่ ";
    $data = $dom->getElementById("spLottoDate");
    $replyMsg .= $data->nodeValue;
    $replyMsg .=  chr(10);

    // รางวัลที่ 1
    $replyMsg .= "รางวัลที่ 1";
    $replyMsg .=  chr(10);
    $data = $dom->getElementById("no1");
    $replyMsg .=  $data->nodeValue;
    $replyMsg .=  chr(10);

    //เลขหน้า 3 ตัว
    $replyMsg .=  "เลขหน้า 3 ตัว";
    $replyMsg .=  chr(10);
    for ($i = 1; $i <= 2; $i++) {
        $data = $dom->getElementById("d3:".$i);
        $replyMsg .=  $data->nodeValue." ";
    }
    $replyMsg .=  chr(10);

    //เลขท้าย 3 ตัว
    $replyMsg .=  "เลขท้าย 3 ตัว";
    $replyMsg .=  chr(10);
    for ($i = 3; $i <= 4; $i++) {
        $data = $dom->getElementById("d3:".$i);
        $replyMsg .=  $data->nodeValue." ";
    }
    $replyMsg .=  chr(10);

    //เลขท้าย 2 ตัว
    $replyMsg .=  "เลขท้าย 2 ตัว";
    $replyMsg .=  chr(10);
    $data = $dom->getElementById("d2");
    $replyMsg .=  $data->nodeValue;
    $replyMsg .=  chr(10);

    //ใกล้เคียงรางวัลที่ 1
    $replyMsg .=  "ใกล้เคียงรางวัลที่ 1";
    $replyMsg .=  chr(10);
    for ($i = 1; $i <= 2; $i++) {
        $data = $dom->getElementById("no1nr:".$i);
        $replyMsg .=  $data->nodeValue." ";
    }
    $replyMsg .=  chr(10);

    //รางวัลที่ 2
    $replyMsg .=  "รางวัลที่ 2";
    $replyMsg .=  chr(10);
    for ($i = 1; $i <= 5; $i++) {
        $data = $dom->getElementById("no2:".$i);
        $replyMsg .=  $data->nodeValue." ";
    }
    $replyMsg .=  chr(10);

    //รางวัลที่ 3
    $replyMsg .=  "รางวัลที่ 3";
    $replyMsg .=  chr(10);
    for ($i = 1; $i <= 10; $i++) {
        $data = $dom->getElementById("no3:".$i);
        $replyMsg .=  $data->nodeValue." ";
    }
    $replyMsg .=  chr(10);

    //รางวัลที่ 4
    $replyMsg .=  "รางวัลที่ 4";
    $replyMsg .=  chr(10);
    for ($i = 1; $i <= 50; $i++) {
        $data = $dom->getElementById("no4:".$i);
        $replyMsg .=  $data->nodeValue." ";
    }
    $replyMsg .=  chr(10);

    //รางวัลที่ 5
    $replyMsg .=  "รางวัลที่ 5";
    $replyMsg .=  chr(10);
    for ($i = 1; $i <= 100; $i++) {
        $data = $dom->getElementById("no5:".$i);
        $replyMsg .=  $data->nodeValue." ";
    }
?>
