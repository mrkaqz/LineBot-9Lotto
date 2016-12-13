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

$client = new http\Client;
$request = new http\Client\Request;

$request->setRequestUrl('https://api.line.me/v2/bot/group/Cbd5c975f4d1ad9bb2d782ee4d55539a1/leave');
$request->setRequestMethod('POST');
$request->setHeaders(array(
  'postman-token' => '0f3db223-4832-52e8-8860-16ba594fb754',
  'cache-control' => 'no-cache',
  'authorization' => 'Bearer prIA1BgN1nWm2ieB6c9TkgBDSUQ7caE/VM/fHETGGtv1IyUqfJl79o0xwyW5GjtJC7DRrvix6SspnRw2R48NeFCkd/C0AxZt8Bt2yXJmDJRDHWl9gophkrplNu1LP2rwdONSJg0YszFlRwX+KRjMhAdB04t89/1O/w1cDnyilFU='
));

$client->enqueue($request)->send();
$response = $client->getResponse();

echo $response->getBody();
