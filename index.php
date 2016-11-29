<?php
echo "Hello LINE BOT <br />";

$result = '{"displayName":"Ron","userId":"Ub84ba9e603c59ba5cd8f1b76a03036a8","pictureUrl":"http://obs.line-apps.com/ch/v2/p/ub754ea225e4db2513819979fd38deeb3/1359310573658","statusMessage":"Just need to wake up from a bad dream."}';

$jevents = json_decode($result, true);

$uname = $jevent['displayName'];

echo $uname;
