<?php

namespace Modules\Goods\Helpers;


use GuzzleHttp\Client;
use Modules\Goods\Models\ImageDModel;
use Modules\Goods\Models\ProductModel;
use Throwable;
use Xcart\App\Exceptions\Exception;
use Xcart\App\Helpers\Paths;

class ImageHelper
{
    protected static $__extensions = ['gif', 'jpeg', 'jfif', 'jpg', 'jpe', 'bmp', 'png'];

    public static function getImageFileNameFromDownloadLink($imgLink, $defaultExtension = 'jpg')
    {
        return ProductHelper::getFileNameFromDownloadLink($imgLink, $defaultExtension, self::$__extensions);
    }

    /**
     * @param string $image
     * @param string $prefix
     * @return string
     */
    public static function getImageFileName($image, $prefix)
    {
        $ext = 'jpg';
        $image = html_entity_decode($image);
        $SET_IMAGE_URL = self::getImageFileNameFromDownloadLink($image);

        if (empty($SET_IMAGE_URL)) {

            $img_path_arr = explode("//", $image);
            $img_path_arr2 = explode("/", $img_path_arr[1]);
            unset($img_path_arr2[0]);

            $img_path_after = implode("_", $img_path_arr2);
            $img_path_after_arr = explode(".", $img_path_after);

            if (count($img_path_after_arr) > 1) {
                $ext = array_pop($img_path_after_arr);
            }
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
     * @param string|null $name
     * @return ImageDModel|null
     * @throws \Exception
     */
    public static function uploadMainImage(string $image, string $upload_image, string $name = null): ?ImageDModel
    {
        /** @var ImageDModel $imageModel */
        $client = new Client(['verify' => false, 'timeout' => 30]);
        try {
            $image_path = Paths::get('www') . $upload_image;
            $res = $client->get($image, [
                'save_to' => $image_path,
                'http_errors' => false,
            ]);

            if ($res->getStatusCode() === 200 && $img_info = getimagesize($image_path)) {
                $imageModel = new ImageDModel([
                    'date' => time(),
                    'image_path' => '.' . $upload_image,
                    'image_type' => $img_info["mime"],
                    'image_x' => $img_info[0],
                    'image_y' => $img_info[1],
                    'image_size' => filesize($image_path),
                    'md5' => md5_file($image_path),
                    'alt' => $name,
                    'avail' => 'Y'
                ]);
            }
        } catch (Throwable $e) {
            print $e->getMessage();
            $imageModel = null;
        }
        return $imageModel;
    }
}