<?php
$default_host = 'www.artistsupplysource.com';
if (isset($_SERVER['HTTP_HOST'])) {
    $host = $_SERVER['HTTP_HOST'];
} else {
    $host = $default_host;
}
$filename = 'sitemap.' . $host . '.xml';
if (file_exists($filename)) {
	header("Content-type: text/xml");
	echo file_get_contents($filename);
}
?>
