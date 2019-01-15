<?php

namespace Modules\Dashboard\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

class InquiryAttentionTagModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_inquiries_attention_tags';
    }

    public static function getFields()
    {
        return [
            'inq_tag_id' => [
                'class' => AutoField::class,
            ],
            'inquiries' => [
                'class' => ManyToManyField::class,
                'modelClass' => InquiryModel::class,
                'through' => InquiryTagToModel::class,
            ]
        ];
    }

    public function count()
    {
        return $this->inquiries->count();
    }
}