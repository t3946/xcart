<?php
namespace Modules\Core\Classes;

use Google_Client;
use Google_Service_Drive;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\Util;

class GoogleDrive {

    private const CLIENT_ID = '319702114697-87o15ol5ev7pcnn7eenfj9md4slgbttp.apps.googleusercontent.com';
    private const CLIENT_SECRET = 'BBRU6N-2616AvusKPBzGIinR';
    private const REFRESH_TOKEN = '1//043_LueXtXhggCgYIARAAGAQSNwF-L9IrB_8s78Mu6fq3tyeYJtayH07_V0healVBWy-4MZbeqzyqaXAqiJ7SikC9VzKCMOPJNsQ';

    private Google_Client $ob_client;
    private Google_Service_Drive $ob_service;
    private GoogleDriveAdapter $ob_adapter;
    public string $root_folder = '';
    public Filesystem $file_system;

    public function __construct(string $app_name = 'Test', string $folder_id)
    {
        $client = new Google_Client();
        $client->setClientId(self::CLIENT_ID);
        $client->setClientSecret(self::CLIENT_SECRET);
        $client->refreshToken(self::REFRESH_TOKEN);
        $client->setApplicationName($app_name);

        $this->root_folder = $folder_id;
        $this->ob_client = $client;

        $this->initAdapter();
        $this->createFileSystem();
    }
    private function initAdapter() : void
    {
        $this->ob_service = new Google_Service_Drive($this->ob_client);
        $this->ob_adapter = new GoogleDriveAdapter($this->ob_service, $this->root_folder);
    }
    private function createFileSystem() : void
    {
        $this->file_system = new Filesystem($this->ob_adapter);
    }

    public function getFolderByName(array $list_content, string $folder_name)
    {
        return array_filter($list_content, function ($element) use ($folder_name) {
            return $element['name'] === $folder_name && $element['type'] === 'dir';
        })[0];
    }
    public function getFileByName(array $list_content, string $file_name)
    {
        return array_filter($list_content, function ($element) use ($file_name) {
            return $element['name'] === $file_name && $element['type'] === 'file';
        })[0];
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