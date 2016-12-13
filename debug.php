<?php

/*
$access_token = 'prIA1BgN1nWm2ieB6c9TkgBDSUQ7caE/VM/fHETGGtv1IyUqfJl79o0xwyW5GjtJC7DRrvix6SspnRw2R48NeFCkd/C0AxZt8Bt2yXJmDJRDHWl9gophkrplNu1LP2rwdONSJg0YszFlRwX+KRjMhAdB04t89/1O/w1cDnyilFU=';
$groupid = 'Cbd5c975f4d1ad9bb2d782ee4d55539a1';

$leaveurl = 'https://api.line.me/v2/bot/group/'.$groupid.'/leave';

$lheaders = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($leaveurl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $lheaders);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

echo $httpcode;
*/

$request = new HttpRequest();
$request->setUrl('https://api.line.me/v2/bot/group/Cbd5c975f4d1ad9bb2d782ee4d55539a1/leave');
$request->setMethod(HTTP_METH_POST);

$request->setHeaders(array(
  'postman-token' => 'c0360197-5b4f-dd35-d133-b28a9c224ce5',
  'cache-control' => 'no-cache',
  'authorization' => 'Bearer prIA1BgN1nWm2ieB6c9TkgBDSUQ7caE/VM/fHETGGtv1IyUqfJl79o0xwyW5GjtJC7DRrvix6SspnRw2R48NeFCkd/C0AxZt8Bt2yXJmDJRDHWl9gophkrplNu1LP2rwdONSJg0YszFlRwX+KRjMhAdB04t89/1O/w1cDnyilFU='
));

try {
  $response = $request->send();

  echo $response->getBody();
} catch (HttpException $ex) {
  echo $ex;
}
