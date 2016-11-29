<?php
echo "Hello LINE BOT <br />";

// Get POST body content
$content = file_get_contents('https://api.rss2json.com/v1/api.json?rss_url=http%3A%2F%2Frssfeeds.sanook.com%2Frss%2Ffeeds%2Fsanook%2Fnews.lotto.xml');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data

  echo "status is ".$events['status'];
  echo "<br />";
  echo "<br />";

for ($i = 0; $i < 10; $i++) {

  echo "งวดวันที่: ".substr($events['items'][$i]['pubDate'],0,10);
  echo "<br />";
  echo "Header: ".$events['items'][$i]['guid'];
  echo "<br />";
  echo "ผลรางวัล: <br />".$events['items'][$i]['content'];
  echo "<br />";
  echo "<br />";

}
