<?php

namespace Modules\Dashboard\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

class InquiryModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_inquiries';
    }

    public static function getFields()
    {
        return [
            'inq_id' => [
                'class' => AutoField::class
            ],

        ];
    }
}