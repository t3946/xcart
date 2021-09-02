<?php

namespace Modules\User\Models;

use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class FingerprintModel extends Model
{
    public static function tableName()
    {
        return 'xcart_fingerprints';
    }

    public static function getFields()
    {
        return [
            'user_id' => [
                'class' => IntField::class,
            ],
            'fingerprint' => [
                'class' => CharField::class,
            ],
        ];
    }
}
