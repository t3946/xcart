<?php

namespace Modules\Reviews\Models\Images;

use Modules\Reviews\ReviewsModule;
use Modules\Images\Models\ImagesModel as BaseImagesModel;

class ImagesModel extends BaseImagesModel
{
    public static function getMaxUploadSizeMB(): int {
        return ReviewsModule::MAX_IMAGE_SIZE_MB;
    }

    public static function getUploadPath(): string
    {
        return ReviewsModule::IMAGES_UPLOAD_TO;
    }
}