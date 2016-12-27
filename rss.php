<?

// Get RSS to JSON content
$lcontent = file_get_contents('https://api.rss2json.com/v1/api.json?rss_url=http%3A%2F%2Frssfeeds.sanook.com%2Frss%2Ffeeds%2Fsanook%2Fnews.lotto.xml');
// Parse JSON
$levents = json_decode($lcontent, true);
// Validate parsed JSON data

for ($i = 0; $i < 3; $i++) {

  $replyMsg .= "งวดวันที่: ".substr($levents['items'][$i]['pubDate'],0,10);
  $replyMsg .= chr(10);
  $replyMsg .= str_replace('<br> ',chr(10),$levents['items'][$i]['content']);
  $replyMsg .= chr(10);
  $replyMsg .= chr(10);
}

echo $replyMsg;
