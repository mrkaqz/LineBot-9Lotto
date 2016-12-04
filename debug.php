<?

$stathtml = file_get_contents('http://glacial-anchorage-14478.herokuapp.com/stat.php?type=json');

$objstat = json_decode($stathtml);

var_dump($objstat['d2stat']);

var_dump($objstat['d3stat']);
?>
