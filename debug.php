<?

echo time();
echo "<br />";

$number = "000044";

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

echo $output;
