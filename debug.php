<?

$stathtml = file_get_contents('http://glacial-anchorage-14478.herokuapp.com/stat.php?type=json');

$objstat = json_decode($stathtml);

foreach ($objstat[0] as $key => $value) {
    $d2array = $value;
}

foreach ($d2array as $key => $value) {
  echo 'Key: '.$key;
  echo '<br />';
  var_dump($value);
  echo '<br />';
}


?>
