<?php

namespace Modules\Goods\Controllers\Api;


use claviska\SimpleImage;
use Modules\Goods\Models\ImageDModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Helpers\Paths;

class ApiImageController extends Controller
{
    public function view($image_id, $width = null)
    {
        $image = new SimpleImage();
        if ($imageModel = ImageDModel::objects()->get(['imageid' => $image_id])) {
            $name = Paths::get('www') . ltrim($imageModel->image_path, '.');
            if (file_exists($name)) {
                $filename = $name;
                $image->fromFile($filename);
                if ($width !== null && is_numeric($width)) {
                    $image->resize($width);
                }
                $image->toScreen('image/jpeg', 94);
            }
        }
    }
}