<?php
$access_token = 'prIA1BgN1nWm2ieB6c9TkgBDSUQ7caE/VM/fHETGGtv1IyUqfJl79o0xwyW5GjtJC7DRrvix6SspnRw2R48NeFCkd/C0AxZt8Bt2yXJmDJRDHWl9gophkrplNu1LP2rwdONSJg0YszFlRwX+KRjMhAdB04t89/1O/w1cDnyilFU=';
$debugmsg = "";
$replyMsg = "";


function sendreply($msg,$token,$akey) {

	$messages = [
		'type' => 'text',
		'text' => $msg
	];

	// Make a POST Request to Messaging API to reply to sender
	$url = 'https://api.line.me/v2/bot/message/reply';
	$data = [
		'replyToken' => $token,
		'messages' => [$messages],
	];
	$post = json_encode($data);
	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $akey);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);

	echo $result . "\r\n";
}


function getlottoData($lottourl){

	// Get Lotto Data from Kapook
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

	//งวด
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
	$data = $dom->getElementById("d2");
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

	return $lottofinal;

}

function prizeName($code){

	switch ($code) {
		case strstr($code,"no1nr"):
				return "รางวัลข้างเคียงรางวัลที่ 1";
				break;
	    case strstr($code,"no1"):
	        return "รางวัลที่ 1";
	        break;
			case strstr($code,"no2"):
			    return "รางวัลที่ 2";
			    break;
			case strstr($code,"no3"):
			    return "รางวัลที่ 3";
			    break;
			case strstr($code,"no4"):
					return "รางวัลที่ 4";
					break;
			case strstr($code,"no5"):
			    return "รางวัลที่ 5";
			    break;
			case strstr($code,"d3:1"):
				  return "รางวัลเลขหน้า 3 ตัว";
				  break;
			case strstr($code,"d3:2"):
					return "รางวัลเลขหน้า 3 ตัว";
					break;
			case strstr($code,"d3:3"):
					return "รางวัลเลขท้าย 3 ตัว";
					break;
			case strstr($code,"d3:4"):
					return "รางวัลเลขท้าย 3 ตัว";
					break;
			case strstr($code,"d2"):
					return "รางวัลเลขท้าย 2 ตัว";
					break;
			default:
	        return "ถูกกิน!";
				}
}


// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			/* Get User ID and User Name
      $userid = $event['source']['userId'];

			$uurl = 'https://api.line.me/v2/bot/profile/'.$userid;

			$uheaders = array('Authorization: Bearer ' . $access_token);

			$uch = curl_init($uurl);
			curl_setopt($uch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($uch, CURLOPT_HTTPHEADER, $uheaders);
			curl_setopt($uch, CURLOPT_FOLLOWLOCATION, 1);
			$uresult = curl_exec($uch);
			curl_close($uch);

			$uevents = json_decode($uresult, true);
			$uname = $uevents['displayName'];

			$replyMsg .= $uname." ";
			*/

			$debugmsg .= $content;

			// Lotto

			$number = iconv_substr($text,8);

			if(strstr($text,"ตรวจหวย ")){

      // Get Lotto Check
      $len = strlen($number);
			$value = $number;
	    settype($value, "integer");

      if ( !(($value >= 0) and ($value <= 999999) and $len == 6)){

				$errword = array(
					"เดี๋ยวปั๊ดเหนี่ยวเลย ใส่ตัวเลข 6 หลักเท่านั้นนะจ๊ะ",
					"รู้จักหวยมั๊ย เลข 6 หลักอะ เคยซื้อมั๊ย?",
					"ต้องให้บอกมั๊ยว่าหวยมันเป็นเลข 6 หลัก",
					"สงสัยจะไม่เคยเล่นหวย ใส่เลขยังไม่ถูกเลย",
					"สะกดคำว่า หวย เป็นมั๊ย ห่วย!! เลข 6 หลักเท่านั้นจะ",
					"หวยมันเป็นเลข 6 ตัวนะจ๊ะ",
				  "อย่ามาทำเป็นเล่นนะ หวยนี่เรื่องใหญ่ เลข 6 หลักรู้จักมั๊ย!"
				);
        $replyMsg .= $errword[rand(0,count($errword)-1)];

      }else{

				$kurl = 'http://lottery.kapook.com/';

				$lottofinal = getlottoData($kurl);

				foreach ($lottofinal as $prize => $nlotto) {
					if ($number == $nlotto){
						$replyMsg .= "ถูก".prizeName($prize);
					}

				}

/*
      if(strstr($output,"ไม่ถูกรางวัลสลากกินแบ่งรัฐบาล"))
      {

				$fword = array(
					"เสียใจด้วยนะ โดนหวยแหลก!! งวดหน้าเอาใหม่นะจ๊ะ",
					"ซื้อเลขอะไรมาเนี่ย มันไม่ได้ใกล้เคียงเล๊ย!",
					"เสียใจด้วย สิ้นเดือนนี้ก็กินมาม่าต่อไปละกันนะ",
					"ที่ขูดมา ยังตีเป็นเลขไม่แม่นนะ โดนหวยแหลก",
					"สะกดคำว่า ถูกหวย เป็นไหม? เฮ้อ!!!",
					"โดนกินตามเคย เอาให้มันแม่นๆหน่อยสิคราวหน้า",
				  "อย่าเสียใจไป งวดหน้าอาจจะถูกก็ได้ อย่าเพิ่งสิ้นหวัง"
				);
        $replyMsg .= $fword[rand(0,count($fword)-1)];
				$replyMsg .= chr(10)."(งวดวันที่ ".$lottodate.")";

      }
      else {
        if(strstr($output,"ท่านถูกรางวัลที่ 1"))
        {
          $replyMsg .= 'เฮ้ย!! ถูกรางวัลที่ 1';
        }
        if(strstr($output,"ท่านถูกรางวัลข้างเคียงรางวัลที่ 1"))
        {
          $replyMsg .= 'ถูกรางวัลข้างๆ รางวัลที่ 1';
        }
        if(strstr($output,"ท่านถูกรางวัลที่ 2"))
        {
          $replyMsg .= 'ถูกรางวัลที่ 2';
        }
        if(strstr($output,"ท่านถูกรางวัลที่ 3"))
        {
          $replyMsg .= 'ถูกรางวัลที่ 3';
        }
        if(strstr($output,"ท่านถูกรางวัลที่ 4"))
        {
          $replyMsg .= 'ถูกรางวัลที่ 4';
        }
        if(strstr($output,"ท่านถูกรางวัลที่ 5"))
        {
          $replyMsg .= 'ถูกรางวัลที่ 5';
        }
        if(strstr($output,"ท่านถูกรางวัลเลขหน้า 3 ตัว"))
        {
          $replyMsg .= 'ถูกรางวัลเลขหน้า 3 ตัว';
        }
        if(strstr($output,"ท่านถูกรางวัลเลขท้าย 3 ตัว"))
        {
          $replyMsg .= 'ถูกรางวัลเลขท้าย 3 ตัว';
        }
        if(strstr($output,"ท่านถูกรางวัลเลขท้าย 2 ตัว"))
        {
          $replyMsg .= 'ถูกรางวัลเลขท้าย 2 ตัว';
        }
        if(strstr($output,", รางวัลเลขท้าย 2 ตัว"))
        {
          $replyMsg .= ' และรางวัลเลขท้าย 2 ตัว';
        }
        if(strstr($output,", รางวัลเลขท้าย 3 ตัว"))
        {
          $replyMsg .= ' และรางวัลเลขท้าย 3 ตัว';
        }

        $endword = array(
					"เลขเด็ดจริงๆ",
					"รวย!!!",
					"อย่างนี้ต้องฉลอง",
					"รวยเลยครัชงานนี้",
					"เลขนี้ท่านได้แต่ใดมา",
					"อย่าลืมแก้บนนะ",
					"รวยไม่รู้ตัว",
					"ระวังเพื่อนยืมนะ",
					"เตรียมเอาเงินไปฝังดินไว้เลย",
					"รวยครับรวย",
					"ชิตังเม โป้ง รวย!"
				);
          $replyMsg .= " ".$endword[rand(0,count($endword)-1)];
					$replyMsg .= chr(10)."(งวดวันที่ ".$lottodate.")";
      }
*/
      }

			if(strstr($text,"debug ")){
				$replyMsg .= " debug ".$debugmsg;
			}

			// reply back
			sendreply($replyMsg,$replyToken,$access_token);

		}


		// เรียงเบอร์
		if(strstr($text,"เรียงเบอร์") or strstr($text,"เรียงเบอร์ ")){

			if(strstr($text,"เรียงเบอร์ ")){

				$kdate = iconv_substr($text,11);

				$kday = iconv_substr($text,11,2);
				$kmonth = iconv_substr($text,13,2);
				$kyear = iconv_substr($text,15,4);
				$kyears = $kyear-543;

				$kurl = 'http://lottery.kapook.com/'.$kyear.'/'.$kyears.'-'.$kmonth.'-'.$kday.'.html';
			}else{
				$kurl = 'http://lottery.kapook.com/';
			}

			$lottofinal = getlottoData($kurl);

			//งวด
			$replyMsg .= "งวดวันที่ ";
	    $replyMsg .= $lottofinal['spLottoDate'];
	    $replyMsg .= chr(10);

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


			//reply back
			sendreply($replyMsg,$replyToken,$access_token);

		}

		//ใบ้หวย
		if(strstr($text,"ใบ้หวย") or strstr($text,"เลขเด็ด")){

			if (rand(0,10)>=1){

			$number2 = str_pad(rand(0,99), 2, '0', STR_PAD_LEFT);
			$number3 = str_pad(rand(0,999), 3, '0', STR_PAD_LEFT);

			$replyMsg .= "เลข 2 ตัว ".$number2;
			$replyMsg .= chr(10);
			$replyMsg .= "เลข 3 ตัว ".$number3;
			$replyMsg .= chr(10);

			$guessword = array(
				"จัดไปเลย",
				"ขอให้รวย ขอให้รวย!!!",
				"โปรใช้วิจารณญาณในการรับชม",
				"ชุด 30 ล้านเลยครับเลขนี้",
				"อย่าลืมกลับด้วยละ",
				"รีบไปซื้อด่วน หวยจะออกแล้ว",
				"ถ้าไม่ถูกก็ตัวใครตัวมัน",
				"ถ้าถูกก็แบ่งกันบ้างนะ",
				"ให้แค่นี้ที่เหลือไปตามหาเอาเอง",
				"ชิตังเม โป้ง รวย!"
			);

			$replyMsg .= chr(10).$guessword[rand(0,count($guessword)-1)];
		}else{
			$replyMsg .= "ไม่บอกหรอกนะ :P";
		}
			//reply back
			sendreply($replyMsg,$replyToken,$access_token);

		}


	}elseif ($event['type'] == 'join') {

			$replyToken = $event['replyToken'];

			// Build message to reply back
			$replyMsg =	'วิธีตรวจหวยงวดล่าสุด'.chr(10).'ให้พิมพ์คำว่า ตรวจหวย เว้นวรรค ตามด้วยตัวเลข 6 หลัก เช่น'.chr(10).'"ตรวจหวย 123456"'.chr(10).chr(10).'พิมพ์ "เรียงเบอร์" เพื่อดูผล 3 งวดล่าสุด';

			sendreply($replyMsg,$replyToken,$access_token);

		}


	}
}
echo "OK";
echo "<br />";

// Debug Zone
