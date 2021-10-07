<?php
namespace Modules\Core\Classes;

use Google_Client;
use Google_Service_Drive;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\Util;
use Xcart\App\Main\Xcart;

class GoogleDrive {

    private Google_Client $ob_client;
    private Google_Service_Drive $ob_service;
    private GoogleDriveAdapter $ob_adapter;
    public string $root_folder = '';
    public ?Filesystem $file_system;

    public function __construct()
    {
        $this->file_system = Xcart::app()->storage->getFilesystem('google');
    }

    public function getFolderByName(array $list_content, string $folder_name)
    {
        foreach ($list_content as $attr) {
            if ($attr['name'] === $folder_name && $attr['type'] === 'dir') {
                return $attr;
            }
        }
        return null;
    }
    public function getFileByName(array $list_content, string $file_name)
    {
        foreach ($list_content as $attr) {
            if ($attr['name'] === $file_name && $attr['type'] === 'file') {
                return $attr;
            }
        }
        return null;
    }
    public function uploadFile(string $name_folder, $file)
    {
        $list_content = $this->file_system->listContents('/');
        $folder = $this->getFolderByName($list_content, $name_folder);
        if (empty($folder)) {
            $folder = $this->createFolder($name_folder);
        }
        $file_content = fopen($file['tmp_name'], 'r');
        $file_name = basename($file['name']);
        $file_dir = Util::normalizePath("/{$folder['path']}/{$file_name}");
        try {
            if ($this->hasFile($file_dir)) {
                $file_name = $this->addTextInFileName($file_name['name'], date('d=F=Y'));
                $file_dir = Util::normalizePath("/{$folder['path']}/{$file_name}");
            }
            $file_save = $this->file_system->writeStream($file_dir, $file_content);
            if ($file_save) {
                return $this->getFileByName($this->getContentByPath("{$folder['path']}"), $file_name);
            }
        } catch (\Exception $e) {
            throw new \Exception($e);
        } finally {
            fclose($file_content);
        }
    }

    private function addTextInFileName(string $file_name, string $text) : string
    {
        $ar_file_name = explode('.', $file_name);
        $ar_file_name[0] .= $text;
        return implode('', $ar_file_name);
    }

    public function hasFile($dir_file) : bool
    {
        return $this->file_system->has($dir_file);
    }

    public function createFolder(string $name_folder) : array
    {
        $this->file_system->createDir($name_folder);
        return $this->getFolderByName($this->file_system->listContents(), $name_folder);
    }
    public function getContentByPath(string $path = '/') : array
    {
        return $this->file_system->listContents($path);
    }
    public function getLink(string $folder_id) : string
    {
        return "https://drive.google.com/drive/folders/{$folder_id}";
    }
}