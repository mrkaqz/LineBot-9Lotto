<?php
$access_token = 'prIA1BgN1nWm2ieB6c9TkgBDSUQ7caE/VM/fHETGGtv1IyUqfJl79o0xwyW5GjtJC7DRrvix6SspnRw2R48NeFCkd/C0AxZt8Bt2yXJmDJRDHWl9gophkrplNu1LP2rwdONSJg0YszFlRwX+KRjMhAdB04t89/1O/w1cDnyilFU=';

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

      // Get Lotto Check

      $today = date('Y-m-d');

      $url = "http://www.glo.or.th/glo_seize/lottary/check_lottary.php";

      $post_data = array (
          "kuson" => 1,
          "ldate" => "2016-11-16",
          "lnumber" => "$text",
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
      $replyMsg = 'เสียใจด้วยนะ โดนหวยแหลก!!';
      }
      else {
      $replyMsg = "เฮ้ยๆๆๆๆ ดูท่าจะรวยละ";
        if(strstr($output,"ท่านถูกรางวัลที่ 1"))
        {
          $replyMsg = 'เฮ้ย ถูกรางวัลที่ 1 เลยเฟร้ย';
        }
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
	}
}
echo "OK";

$today = date('Y-m-d');
echo $today;
