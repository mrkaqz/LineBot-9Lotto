<?php
echo "Hello LINE BOT <br />";

$text = 'ตรวจหวย 000000';
// Lotto

$number = iconv_substr($text,8);

echo $text;
echo "<br />";
echo $number;
echo "<br />";

if(strpos($text,'ตรวจหวย ') !== false){
echo "String OK";
echo "<br />";
// Get Lotto Check
$len = strlen($number);
settype($number, "integer");

echo $len;
echo "<br />";

if ( !(($number >= 0) and ($number <= 999999) and $len == 6)){

  $errword = array(
    "เดี๋ยวปั๊ดเหนี่ยวเลย ใส่ตัวเลข 6 หลักเท่านั้นนะจ๊ะ",
    "รู้จักหวยมั๊ย เลข 6 หลักอะ เคยซื้อมั๊ย?",
    "ต้องให้บอกมั๊ยว่าหวยมันเป็นเลข 6 หลัก",
    "สงสัยจะไม่เคยเล่นหวย ใส่เลขยังไม่ถูกเลย",
    "สะกดคำว่า หวย เป็นมั๊ย ห่วย!! เลข 6 หลักเท่านั้นจะ",
    "หวยมันเป็นเลข 6 ตัวนะจ๊ะ",
    "อย่ามาทำเป็นเล่นนะ หวยนี่เรื่องใหญ่ เลข 6 หลักรู้จักมั๊ย!"
  );
  $replyMsg = $errword[rand(0,count($errword)-1)];

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

$replyMsg = "";


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
  $replyMsg = $fword[rand(0,count($fword)-1)];

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
    "เอาเงินไปฝันดินไว้เลย",
    "รวยครับรวย",
    "ชิตังเม โป้ง รวย!"
  );
    $replyMsg .= " ".$endword[rand(0,count($endword)-1)];
}

}

// Build message to reply back
echo $replyMsg;

}
