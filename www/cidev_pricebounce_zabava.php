<?php

if (!$link = mysql_connect('localhost', 'igor', 'fBCDGyEBcb')) {
    echo 'Could not connect to mysql';
    exit;
}

if (!mysql_select_db('PriceBounce', $link)) {
    echo 'Could not select database';
    exit;
}

$sql    = 'call PriceBounce.pb_ChangeActiveProductsPrice;';

$result = mysql_query($sql, $link);

if (!$result) {
    echo "DB Error, could not query the database\n";
    echo 'MySQL Error: ' . mysql_error();
    exit;
}

/*while ($row = mysql_fetch_assoc($result)) {
    echo $row;
}
*/

mysql_free_result($result);

?> 