<?php
echo "Hello LINE BOT <br />";

$endword = array("เลขเด็ดจริงๆ", "รวย!!!", "อย่างนี้ต้องฉลอง","รวยเลยครัชงานนี้","เลขนี้ท่านได้แต่ใดมา","อย่าลืมแก้บนนะ","รวยไม่รู้ตัว","ระวังเพื่อนยืมนะ","เอาเงินไปฝันดินไว้เลย","รวยครับรวย");
echo $endword[rand(0,count($endword)-1)];
echo "<br />";

$text = "ตรวจหวย 385723";
$number = iconv_substr($text,8);

if(strstr($text,"ตรวจหวย ")){
  echo $number;
}else{
  echo "Not OK";
}
