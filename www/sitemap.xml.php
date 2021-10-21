<?php
$default_host = 'www.artistsupplysource.com';
$host = $_SERVER['HTTP_HOST'] ?? $default_host;

$filename = $host . '-sitemap.xml.gz';
if (file_exists($filename)) {
    header("Content-type: application/x-gzip");
    header("Content-Encoding: gzip");
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    echo file_get_contents($filename);
}
