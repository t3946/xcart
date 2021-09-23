<?php

use Modules\Translate\Controllers\DownloadTranslatesController;

return [
    [
        'route' => '/upload-translates',
        'target' => [DownloadTranslatesController::class, 'actionUpload'],
        'name' => 'upload',
    ],
    [
        'route' => '/download-translates',
        'target' => [DownloadTranslatesController::class, 'actionDownload'],
        'name' => 'download',
    ],
];