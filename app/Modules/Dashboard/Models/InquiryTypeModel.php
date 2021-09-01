<?php

namespace Modules\Dashboard\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Model;

class InquiryTypeModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_inquiry_types';
    }

    public static function getFields()
    {
        return [
            'inquiries' => [
                'field' => 'inq_type_id',
                'class' => HasManyField::class,
                'modelClass' => InquiryModel::class,
                'link' => ['inq_type_id' => 'inq_type_id'],
                'primary' => true
            ]

        ];
    }

    public function getUrl(): string
    {
        return "inquiries.php?inq_type_id={$this->inq_type_id}";
    }

    public function count()
    {
        return $this->inquiries->filter(['status' => 'O'])->count();
    }
}