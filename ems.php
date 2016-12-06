<?

//$tracking=$_GET['tracking'];
$tracking='EY533045175TH';

$ch = curl_init();
$url2="http://www.thailandpost.com/php/webservice.php";
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_URL, $url2);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "action=getTrack&barcode=$tracking&language_id=");
$result=curl_exec ($ch);
curl_close ($ch);
$obj = json_decode($result,true);



foreach($obj as $a)
{
echo "$a[DateTime] $a[Location] $a[StatusName] $a[Description]<br />";
}
?>
