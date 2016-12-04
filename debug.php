<?

$stathtml = file_get_contents('http://glacial-anchorage-14478.herokuapp.com/stat.php?type=json');

$objstat = json_decode($stathtml);

foreach ($objstat[0] as $key => $value) {
    $d2array = $value;
}

echo $d2array[0];
echo '<br />';
echo $d2array[1];
echo '<br />';
echo $d2array[2];
echo '<br />';
echo $d2array[3];
echo '<br />';


?>
