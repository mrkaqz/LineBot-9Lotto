<?

$stathtml = file_get_contents('http://glacial-anchorage-14478.herokuapp.com/stat.php?type=json');

$objstat = json_decode($stathtml);

foreach ($objstat[0] as $key => $value) {
    $d2array = $value;
}

$x = '44';
echo $d2array[$x];

?>
