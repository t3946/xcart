<?php

namespace Modules\Reviews\Models\Videos;

use Modules\Reviews\ReviewsModule;
use Modules\Media\Models\VideosModel as BaseVideosModel;

class VideosModel extends BaseVideosModel
{
    protected static function getMaxUploadSizeMB(): int {
        return ReviewsModule::MAX_VIDEOS_SIZE_MB;
    }

    protected static function getUploadPath(): string
    {
        return ReviewsModule::VIDEOS_UPLOAD_TO;
    }
}