<?php
$access_token = 'prIA1BgN1nWm2ieB6c9TkgBDSUQ7caE/VM/fHETGGtv1IyUqfJl79o0xwyW5GjtJC7DRrvix6SspnRw2R48NeFCkd/C0AxZt8Bt2yXJmDJRDHWl9gophkrplNu1LP2rwdONSJg0YszFlRwX+KRjMhAdB04t89/1O/w1cDnyilFU=';
$debugmsg = "";
$replyMsg = "";
$debugmode = true;


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


function getlottoData($lottourl,$lottodate){

global $debugmsg;

$dburl = 'https://lotto-13fa4.firebaseio.com/result/lotto'.$lottodate.'.json';

$ch = curl_init();

// Read Firebase DB
curl_setopt( $ch, CURLOPT_URL,$dburl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$lottodb = json_decode($response);


$keycount=0;$valuecount=0;
foreach ($lottodb as $key => $value) {
$keycount++;
if($value !== "") {
	$valuecount++;
	}
}

$nprize = 174;

if ( $keycount !== $nprize or $valuecount !== $nprize) {

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

	if(strstr($GenStr,'GenStr=""') or !strstr($html,'GenStr=')){

	$debugmsg .= 'Data: Reed Web'.chr(10);
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


	}else{

		$lottoarr = explode("@", $GenStrVar);
		$i=0;
		foreach ($lottoarr as $lotto) {
			$lottoprize = explode("#", $lotto);
			$lottofinal[$lottoprize[0]] = $lottoprize[1];
		}

		$debugmsg .= 'Data: GenStr'.chr(10);
	}

	$data = json_encode($lottofinal);

	// PUT to Firebase DB
	curl_setopt( $ch, CURLOPT_URL,$dburl);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);


}else{
	foreach ($lottodb as $key => $value) {
		$lottofinal[$key] = $value;
	}
	$debugmsg .= 'Data: DB'.chr(10);
}
 	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$debugmsg .= 'Http Code '.$httpcode.chr(10);

	curl_close($ch);
	return $lottofinal;


}

function prizeName($code){

	switch ($code) {
		case strstr($code,"no1nr"):
				return "ถูกรางวัลข้างเคียงรางวัลที่ 1";
				break;
	    case strstr($code,"no1"):
	        return "เฮ้ย!!! ถูกรางวัลที่ 1";
	        break;
			case strstr($code,"no2"):
			    return "ถูกรางวัลที่ 2";
			    break;
			case strstr($code,"no3"):
			    return "ถูกรางวัลที่ 3";
			    break;
			case strstr($code,"no4"):
					return "ถูกรางวัลที่ 4";
					break;
			case strstr($code,"no5"):
			    return "ถูกรางวัลที่ 5";
			    break;
			case strstr($code,"d3:1"):
				  return "ถูกรางวัลเลขหน้า 3 ตัว";
				  break;
			case strstr($code,"d3:2"):
					return "ถูกรางวัลเลขหน้า 3 ตัว";
					break;
			case strstr($code,"d3:3"):
					return "ถูกรางวัลเลขท้าย 3 ตัว";
					break;
			case strstr($code,"d3:4"):
					return "ถูกรางวัลเลขท้าย 3 ตัว";
					break;
			case strstr($code,"d2"):
					return "ถูกรางวัลเลขท้าย 2 ตัว";
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
			// Remove Zero width space
			$text = str_replace("\xE2\x80\x8B", "", $text);
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

			// Get Lotto Date
			$year = date('Y');
			$month = date('m');
			$day = date('d');
			if ($day >= 16) {
				$lottoday = "16";
			}else{
				$lottoday = "01";
			}
			$currentlottodate = $year.$month.$lottoday;


			// Lotto section

			$offset = strpos($text,"ตรวจหวย ");
			$number = iconv_substr($text,$offset+8);

			$debugmsg .= 'Modifed Input '.$number.chr(10);

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

				$debugmsg .= 'Error Input'.chr(10);

      }else{

				$debugmsg .= 'Input OK'.chr(10);

				$kurl = 'http://lottery.kapook.com/';

				$lottofinal = getlottoData($kurl,$currentlottodate);
				$Win = false;
				// เช็ค 6 หลัก
				foreach ($lottofinal as $prize => $nlotto) {
					if ($number === $nlotto){
						$replyMsg .= prizeName($prize);
						$Win = true;

						$debugmsg .= 'Lotto Loop Matched '.$number.' = '.$nlotto.chr(10);
					}
				}

				// เช็คเลขหน้า 3 ตัว
				if (substr($number,0,3) == $lottofinal['d3:1'] or substr($number,0,3) == $lottofinal['d3:2']){
					if ($multiWin) {
							$replyMsg .= ", ";
					}
					$replyMsg .= prizeName('d3:1');
					$Win = true;

					$debugmsg .= 'First 3 Matched '.substr($number,0,3).chr(10);
				}
				// เช็คเลข ท้าย 3 ตัว
				if (substr($number,3,3) == $lottofinal['d3:3'] or substr($number,3,3) == $lottofinal['d3:4']){
					if ($multiWin) {
							$replyMsg .= ", ";
					}
					$replyMsg .= prizeName('d3:3');
					$Win = true;

					$debugmsg .= 'Last 3 Matched '.substr($number,3,3).chr(10);
				}
				// เช็คเลขท้าย 2 ตัว
				if (substr($number,4,2) == $lottofinal['d2']){
					if ($multiWin) {
							$replyMsg .= ", ";
					}
					$replyMsg .= prizeName('d2');
					$Win = true;

					$debugmsg .= 'Last 2 Matched '.substr($number,4,2).chr(10);
				}

				if ($Win){

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
	          $replyMsg .= chr(10).$endword[rand(0,count($endword)-1)];
						$replyMsg .= chr(10)."(งวดวันที่ ".$lottofinal['spLottoDate'].")";

					}else{

					$fword = array(
						"เสียใจด้วยนะ โดนหวยแหลก!! งวดหน้าเอาใหม่นะจ๊ะ",
						"ซื้อเลขอะไรมาเนี่ย มันไม่ได้ใกล้เคียงเล๊ย!",
						"เสียใจด้วย สิ้นเดือนนี้ก็กินมาม่าต่อไปละกันนะ",
						"กินมาม่ากับพี่เนี่ยแหล่ะไอ้น้อง ไม่ต้องเสียใจไป",
						"ถูกกิน! เอาน่า โอกาสหน้ายังมี",
						"ที่ขูดมา ยังตีเป็นเลขไม่แม่นนะ โดนหวยแหลก",
						"สะกดคำว่า ถูกหวย เป็นไหม? เฮ้อ!!!",
						"โดนกินตามเคย เอาให้มันแม่นๆหน่อยสิคราวหน้า",
					  "อย่าเสียใจไป งวดหน้าอาจจะถูกก็ได้ อย่าเพิ่งสิ้นหวัง"
					);
	        $replyMsg .= $fword[rand(0,count($fword)-1)];
					$replyMsg .= chr(10)."(งวดวันที่ ".$lottofinal['spLottoDate'].")";

					$debugmsg .= 'Not Matched '.chr(10);

				}

      }

			if($debugmode){
				$replyMsg .= chr(10)."debug msg".chr(10).$debugmsg;
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
				$checklottodate = $kyears.$kmonth.$kday;
				$lottofinal = getlottoData($kurl,$checklottodate);
			}else{
				$kurl = 'http://lottery.kapook.com/';
				$lottofinal = getlottoData($kurl,$currentlottodate);
			}


			//งวด
			$replyMsg .= "งวดวันที่ ";
	    $replyMsg .= $lottofinal['spLottoDate'];
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

			if($debugmode){
				$replyMsg .= chr(10)."debug msg".chr(10).$debugmsg;
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

		// ปลอบใจ

		if(strstr($text,"หวยกิน") or strstr($text,"หวยแดก")){

			$eatword = array(
				"หวยแดก เป็นเรื่องธรรมชาติ",
				"อย่าเพิ่งท้อใจไป โอกาสหน้ายังมี",
				"เชื่อสิ มันไม่ได้แดกเราทุกงวดหรอก",
				"เพื่อนๆก็โดนหวยแดกเหมือนกัน อย่างน้อยก็ไม่เหงา",
				"ให้แดกแค่งวดนี้แหล่ะ งวดหน้าต้องเป็นของเรา",
				"ก็เล่นซื้อไม่ปรึกษาผม ก็โดนแดกอย่างงี้แหล่ะ",
				"ถามผมสิ พิมพ์ว่า ใบ้หวย งวดหน้าถูกแน่นอน",
				"งวดที่แล้วก็โดน งวดนี้ก็ยังโดนแดกอีกเหรอ",
				"ถูกหวยกินไปตามระเบียบ",
				"ดูปากณัชชานะคะ ชิตังเม โป้ง รวย งวดหน้าถูกแน่นวล"
			);

			$replyMsg .= $eatword[rand(0,count($eatword)-1)];

			//reply back
			sendreply($replyMsg,$replyToken,$access_token);

		}


		// หวยสั้น

		if(strstr($text,"หวยออกไร") or strstr($text,"หวยออกอะไร") or strstr($text,"หวยออกยัง")){

		$kurl = 'http://lottery.kapook.com/';


		$lottofinal = getlottoData($kurl,$currentlottodate);

		//งวด
		$replyMsg .= "งวดวันที่ ";
		$replyMsg .= $lottofinal['spLottoDate'];
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

		if($debugmode){
			$replyMsg .= chr(10)."debug msg".chr(10).$debugmsg;
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
