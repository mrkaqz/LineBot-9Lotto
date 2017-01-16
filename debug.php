<?php

$lottourl = 'http://lottery.kapook.com/';
$lottodate = '20170117';


date_default_timezone_set('Asia/Bangkok');

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

echo 'kapook';
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

	// เช็คงวด
	$data = $dom->getElementById('spLottoDate');
	$kapooklottodate = $data->nodeValue;


	$lottodaycheck = substr($lottodate,6,2);
	$kapookday = intval(substr($kapooklottodate, 0, 2));

	if ($kapookday == $lottodaycheck){

	//งวด

	$data = $dom->getElementById('spLottoDate');
	$lottofinal['spLottoDate'] = $data->nodeValue;

	// รางวัล

	if(strstr($GenStr,'GenStr=""') or !strstr($html,'GenStr=')){

	$debugmsg .= 'Data: Read Web'.chr(10);
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

	}

}else{

	// Read lotto data from DB
	foreach ($lottodb as $key => $value) {
		$lottofinal[$key] = $value;
	}
	$debugmsg .= 'Data: DB'.chr(10);

	// Gen lottoset table
	$setyear = substr($lottodate,0,4);
	$setmonth = substr($lottodate,4,2);
	$setdate = substr($lottodate,6,2);
	$uri = 'https://lotto-13fa4.firebaseio.com/lottoset/'.$setyear.'/'.$setmonth.'.json';

	$checked = array($setdate => 'Checked');
	$setdata = json_encode($checked);

    $setch = curl_init();
	// PUT to Firebase DB
	curl_setopt( $setch, CURLOPT_URL,$uri);
	curl_setopt($setch, CURLOPT_CUSTOMREQUEST, "PATCH");
	curl_setopt($setch, CURLOPT_POSTFIELDS,$setdata);
	curl_setopt($setch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($setch);
	curl_close($setch);


}
 	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$debugmsg .= 'Http Code '.$httpcode.chr(10);

	curl_close($ch);
  var_dump($lottofinal);