<?php

namespace Modules\Order\Models\RMA;

use Modules\Order\OrderModule;
use Modules\Images\Models\ImagesModel as BaseImagesModel;

class ImagesModel extends BaseImagesModel
{
    public static function getUploadPath(): string
    {
        return OrderModule::IMAGES_UPLOAD_TO;
    }
}