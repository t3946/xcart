<?php
date_default_timezone_set('Europe/Moscow');
/*
 *
 *
 *
 *
.htaccess
<IfModule mod_rewrite.c>
Options +FollowSymLinks -MultiViews -Indexes
RewriteEngine On
RewriteBase /cart/qt/

RewriteCond %{REQUEST_URI} \.jpg$
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
#RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule ^ image_gdlib_opt.php [L]
</IfModule>
 */
require "./top.inc.php";
require "./config.php";

error_reporting( E_ALL);
ini_set( 'error_log',dirname( __FILE__).'/var/images.r/error_log');
ini_set( 'log_errors',TRUE);
ini_set( 'display_errors', true);

$_SERVER['REDIRECT_URL'] = "/var/images.jpg/150-150/images/T/ALV-30381_th.jpg";

if( preg_match( "!(.*)(/var/images\.(jpg|p|r)/(\d+)-(\d+)/(.*\.jpg))$!", $_SERVER['REDIRECT_URL'], $match)) {
        $cached_image_path = $match[ 2];
        $x = $match[ 5];
        $y = $match[ 4];
        $imagepath = $match[ 6];
        $xcart_web_dir = $match[ 1];
} else {
        not_found();
}


function func_get_resized_data($width,$height,$x,$y) { // {{{

    if(!$x)
        $x = 1000000000;
    if(!$y)
        $y = 1000000000;

    if($width <= $x && $height <= $y)
        return array($width,$height) ;
        
    $wk = $y/$x ;
    if($height/$width > $wk) {
        $width = intval($y/$height*$width) ;
        $height = $y ;
    } else {
        $height = intval($x/$width*$height) ;
        $width = $x ;
    }

    return array($width,$height) ;
} // }}}


function not_found() {
               header("Status: 404 Not Found");
               header("HTTP/1.0 404 Not Found");
               exit;
}

function func_get_image_from_xcart( $imageid, & $from_img) { // {{{
        $image_result = db_query("SELECT image, image_path, image_type FROM $sql_tbl[images] WHERE imageid = '$imageid'");

        if (empty($image_result)) not_found();

    if (db_num_rows($image_result))
        list($image, $image_path, $image_type) = db_fetch_row($image_result);
    db_free_result($image_result);

    if ($config["Images"]["thumbnails_location"] == "DB"){
            if (!empty($image))
            $image_out = $image;
        else
            $no_image_db = true;
    }

    if (!empty($image_out)) {
        $from_img = imagecreatefromstring($image_out) ;
    }

    if (($config["Images"]["thumbnails_location"] == "FS" || !empty($no_image_db)) && (!empty($image_path))) {
//var_dump($image_path);
        $image_data_resize = file_get_contents($image_path);
        $from_img = imagecreatefromstring($image_data_resize);
    }
        return $from_img;
} // }}}

if (!empty($imageid) || !empty($imagepath)){
if( !empty($imageid)) {
        func_get_image_from_xcart( $imageid, $from_img);
        }
else {
        global $xcart_dir;
        $src = $xcart_dir.'/'.$imagepath;
        if( !file_exists( $src)) {
                $src = preg_replace( "/\.jpg$/", ".gif", $src);
                if( !file_exists( $src)) not_found();
        }
        $from_img = imagecreatefromstring( file_get_contents( $src));
//var_dump(  $xcart_dir.'/'.$imagepath);
}       

        if (empty($x) && empty($y)){
//              $x = $config["Appearance"]["image_width"];
                $x = 1000;
                $y = 1000;
        }

        $quality = 60;
        $imagesx=imagesx($from_img) ;
        $imagesy=imagesy($from_img) ;
        $size = func_get_resized_data($imagesx,$imagesy,$x,$y) ;
        $to_img = ImageCreateTrueColor($size[0], $size[1]);
        $bkcolor = ImageColorAllocate($to_img,255,255,255);
        imagefilledrectangle($to_img, 0, 0, $size[0]-1, $size[1]-1, $bkcolor);
        ImageCopyResampled($to_img, $from_img, 0, 0, 0, 0, $size[0], $size[1], $imagesx, $imagesy);
        $root_dir = realpath( dirname( __FILE__).'/.');
        $file_name = $root_dir.$cached_image_path;
        $dir_name = dirname( $file_name);
        if( !file_exists( $dir_name)) mkdir( $dir_name, 0777, TRUE);
        if( FALSE !== imagejpeg($to_img, $root_dir.$cached_image_path, $quality)) {
@chmod( $root_dir.$cached_image_path, 0666);
                header("Content-type: image/jpeg");
                imagejpeg($to_img, NULL, $quality);
        } else {
                error_log( $root_dir.$cached_image_path);
        }
}

