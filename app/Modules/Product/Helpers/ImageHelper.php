<?php
namespace Modules\Product\Helpers;


class ImageHelper
{
    protected static $__extensions = ['gif', 'jpeg', 'jfif', 'jpg', 'jpe', 'bmp', 'png'];

    public static function getImageFileNameFromDownloadLink($imgLink)
    {
        $result = null;
        $path = parse_url($imgLink);
        if (!empty($path)) {
            $fileName = basename($path['path']);
            $filename = pathinfo($fileName);
            if (!in_array($filename['extension'], self::$__extensions)) {
                parse_str($path['query'], $arrQueryParams);
                if (!empty($arrQueryParams)) {
                    $arrQueryParamsFiltered = array_filter($arrQueryParams, function ($var) {
                        foreach (self::$__extensions as $ext) {
                            if (strpos($var, ".{$ext}") !== false) {
                                return true;
                            }
                        }
                        return false;
                    });
                    if (!empty($arrQueryParamsFiltered)) {
                        $result = implode('_', array_values($arrQueryParamsFiltered));
                    } else {
                        $result = implode('_', array_values($arrQueryParams)) . '.jpg';
                    }
                }
            } else {
                $filePathPre = '';
                $dir = ltrim(dirname(ltrim($path['path'], '/')), '.');
                if (!empty($dir)) {
                    $aPath = explode('/', $dir);
                    if (!empty($aPath)) {
                        $filePathPre = implode('_', $aPath) . '_';
                    };
                }
                $result = $filePathPre . $fileName;
            }
        }
        return $result;
    }
}