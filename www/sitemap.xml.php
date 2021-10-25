<?php
$default_host = 'www.artistsupplysource.com';
$host = $_SERVER['HTTP_HOST'] ?? $default_host;

$filename = $host . '-sitemap.xml';
if (file_exists($filename)) {
    header("Content-type: text/xml");
    echo file_get_contents($filename);
}
