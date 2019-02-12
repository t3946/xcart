<?php

namespace Modules\Goods\Helpers;


use Modules\Goods\Models\ImageDModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Helpers\Paths;

class ImageHelper
{
    protected static $__extensions = ['gif', 'jpeg', 'jfif', 'jpg', 'jpe', 'bmp', 'png'];

    public static function getImageFileNameFromDownloadLink($imgLink, $defaultExtension = 'jpg')
    {
        return ProductHelper::getFileNameFromDownloadLink($imgLink, self::$__extensions, $defaultExtension);
    }

    /**
     * @param string $image
     * @param string $prefix
     * @return string
     */
    public static function getImageFileName($image, $prefix)
    {
        $image = html_entity_decode($image);
        $SET_IMAGE_URL = self::getImageFileNameFromDownloadLink($image);

        if (empty($SET_IMAGE_URL)) {

            $img_path_arr = explode("//", $image);
            $img_path_arr2 = explode("/", $img_path_arr[1]);
            unset($img_path_arr2[0]);

            $img_path_after = implode("_", $img_path_arr2);
            $img_path_after_arr = explode(".", $img_path_after);
            $ext = array_pop($img_path_after_arr);

            $Prod_ID = $prefix . "_" . implode("_", $img_path_after_arr);
            $image_file_name = $Prod_ID . "." . $ext;
        } else {
            $image_file_name = $prefix . "_" . $SET_IMAGE_URL;
        }

        $image_file_name = str_replace(' ', '', rawurldecode($image_file_name));
        $image_file_name = str_replace('/', '_', rawurldecode($image_file_name));

        return "/images/D/" . $image_file_name;
    }

    /**
     * @param string $image
     * @param string $upload_image
     * @param string $name
     * @return ImageDModel|null
     */
    public static function uploadMainImage($image, $upload_image, $name)
    {
        /** @var ImageDModel $imageModel */
        $imageModel = null;

        $client = new \GuzzleHttp\Client();
        $res = $client->get($image, [
            'save_to' => Paths::get('www') . $upload_image,
            'http_errors' => false,
        ]);

        if ($res->getStatusCode() === 200 && $img_info = getimagesize(Paths::get('www') . $upload_image)) {
            $imageModel = new ImageDModel([
                'date' => time(),
                'image_path' => '.' . $upload_image,
                'image_type' => $img_info["mime"],
                'image_x' => $img_info[0],
                'image_y' => $img_info[1],
                'image_size' => filesize(Paths::get('www') . $upload_image),
                'alt' => $name,
                'avail' => 'Y'
            ]);
        }
        return $imageModel;
    }
}