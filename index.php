<?php
echo "Hello LINE BOT <br />";

$url = "http://www.glo.or.th/glo_seize/lottary/check_lottary.php";

$post_data = array (
    "kuson" => 1,
    "ldate" => "2016-11-16",
    "lnumber" => "444444",
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

echo $output
