<?php

namespace Modules\Dashboard\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class InquiryTagToModel extends Model
{
    public static function tableName()
    {
        return 'xcart_inquirires_tags';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
            'inquiry' => [
                'field' => 'inq_id',
                'class' => ForeignField::class,
                'modelClass' => InquiryModel::class,
                'link' => ['inq_id' => 'inq_id'],
            ],
            'inquiry_tag' => [
                'field' => 'inq_tag_id',
                'class' => ForeignField::class,
                'modelClass' => InquiryAttentionTagModel::class,
                'link' => ['inq_tag_id' => 'inq_tag_id'],
            ]
        ];
    }

}