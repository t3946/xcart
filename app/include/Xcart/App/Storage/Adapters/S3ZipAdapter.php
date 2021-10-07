<?php

namespace Xcart\App\Storage\Adapters;

use Xcart\App\Main\Xcart;
use Xcart\App\Storage\Adapters\LocalZipAdapter;

class S3ZipAdapter extends LocalZipAdapter
{
    public function write($location, $contents, \League\Flysystem\Config $config)
    {
        $local_save = parent::write($location, $contents, $config);
        $aws_adapter = Xcart::app()->storage->getFilesystem('s3');
        $path_info = pathinfo($location);
        $file_zip_path = "www/{$path_info['dirname']}/{$path_info['filename']}.zip";
        $file_zip = file_get_contents($file_zip_path);
        $result = $aws_adapter->getAdapter()->write("{$path_info['dirname']}/{$path_info['filename']}.zip", $file_zip, $config);
        unlink($file_zip_path);
        return $local_save;
    }
/*    public function read($path)
    {
        $aws_adapter = Xcart::app()->storage->getFilesystem('s3');
        $path_info = pathinfo($path);
        $file_info = $aws_adapter->getAdapter()->read("{$path_info['dirname']}/{$path_info['filename']}.zip");
        file_put_contents('php://memory', $file_info['contents']);
        $zip = new \ZipArchive;
        $res = $zip->open('php://memory');
        if ($res === TRUE) {
            $zip->extractTo($path);
            $zip->close();
        }
    }*/
}