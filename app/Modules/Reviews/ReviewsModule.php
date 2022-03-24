<?php

namespace Modules\Reviews;

use Modules\Reviews\Controllers\Api\ReviewsApi;
use Modules\Reviews\Models\RatingsModel;
use Modules\Sites\Helpers\StorageHelper;
use Xcart\App\Module\Module;

class ReviewsModule extends Module
{
    public const MAX_ATTACHMENTS_NUMBER = 10;
    public const MAX_IMAGE_SIZE_MB = 20;
    public const MAX_VIDEOS_SIZE_MB = 100;
    public const IMAGES_UPLOAD_TO = 'reviews/images';
    public const VIDEOS_UPLOAD_TO = 'reviews/videos';
}
