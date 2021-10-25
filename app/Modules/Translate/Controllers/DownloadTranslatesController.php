<?php

namespace Modules\Translate\Controllers;

use Modules\Admin\Controllers\BackendController;
use Xcart\App\Main\Xcart;

class DownloadTranslatesController extends BackendController
{
    public function actionUpload()
    {
        $lang_code = $_GET['lang_code'];
        $file = Xcart::app()->getModule('Translate')->getPath() . "/lang/{$lang_code}.po";
        if (file_exists($file)) {
            $content = file_get_contents($_FILES['file-0']['tmp_name']);
            file_put_contents($file, $content);
        }
    }

    public function actionDownload()
    {
        ob_end_clean();

        $lang_code = $_GET['lang_code'];
        $file = Xcart::app()->getModule('Translate')->getPath() . "/lang/{$lang_code}.po";

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file));

        readfile($file);
        exit();
    }
}