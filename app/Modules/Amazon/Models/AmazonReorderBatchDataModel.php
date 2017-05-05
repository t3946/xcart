<?php

namespace Modules\Amazon\Models;

use Xcart\App\Orm\AutoMetaModel;

class AmazonReorderBatchDataModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'amazon_reorder_batch_data';
    }
}