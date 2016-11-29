<?php
$access_token = 'prIA1BgN1nWm2ieB6c9TkgBDSUQ7caE/VM/fHETGGtv1IyUqfJl79o0xwyW5GjtJC7DRrvix6SspnRw2R48NeFCkd/C0AxZt8Bt2yXJmDJRDHWl9gophkrplNu1LP2rwdONSJg0YszFlRwX+KRjMhAdB04t89/1O/w1cDnyilFU=';
$debugmsg = "";
$replyMsg = "";
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

      // Get Lotto Date
      $year = date('Y');
      $month = date('m');
      $day = date('d');
      if ($day >= 16) {
        $lottoday = "16";
      }else{
        $lottoday = "01";
      }
      $lottodate = $year."-".$month."-".$lottoday;

      $url = "http://www.glo.or.th/glo_seize/lottary/check_lottary.php";

      $post_data = array (
          "kuson" => 1,
          "ldate" => "$lottodate",
          "lnumber" => "$number",
          "c_set" => ""
      );

      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      // we are doing a POST request
      curl_setopt($ch, CURLOPT_POST, 1);
      // adding the post variables to the request
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
      $output = curl_exec($ch);

      curl_close($ch);


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
      }

      }

			if(strstr($text,"debug ")){
				$replyMsg .= " debug ".$debugmsg;
			}
			// Build message to reply back

			$messages = [
				'type' => 'text',
				'text' => $replyMsg
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

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

	}elseif ($event['type'] == 'join') {

			$replyToken = $event['replyToken'];

			// Build message to reply back

			$messages = [
				'type' => 'text',
				'text' => 'วิธีตรวจหวยงวดล่าสุด\n ให้พิมพ์คำว่า ตรวจหวย เว้นวรรค ตามด้วยตัวเลข 6 หลัก เช่น \n "ตรวจหวย 123456"'
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

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



	}
}
echo "OK";
echo "<br />";

// Debug Zone
