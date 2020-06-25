<?php

namespace Modules\Goods\Controllers\Api;


use claviska\SimpleImage;
use Exception;
use Modules\Goods\Models\ImageDModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Helpers\Paths;
use Xcart\App\Main\Xcart;

class ApiImageController extends Controller
{
    public function view($image_id, $width = null)
    {
        $image = new SimpleImage();
        if ($imageModel = ImageDModel::objects()->get(['imageid' => $image_id])) {
            $name = Paths::get('www') . ltrim($imageModel->image_path, '.');
            if (file_exists($name)) {
                $filename = $name;
                try {
                    $image->fromFile($filename);
                    if ($width !== null && is_numeric($width)) {
                        $image->resize($width);
                    }
                    $image->toScreen('image/jpeg', 94);
                    die();
                } catch (Exception $exception) {
                    Xcart::app()->logger->error($exception->getMessage(), [], 'gd');
                }
            }
        }
        header('Content-Type: image/svg+xml');
        echo <<<SVG
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<svg xmlns="http://www.w3.org/2000/svg" width="500" height="500">
  <rect x="0" y="0" width="500" height="500" stroke="black" stroke-width="0px" fill="white"/>
  <text style="font-size: 25px" x="50%" y="50%" dominant-baseline="middle" text-anchor="middle">Image not available</text>    
</svg>
SVG;
    }
}