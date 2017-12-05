<?php

namespace Modules\Product\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class OptionValueModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_class_options';
    }

    public static  function getFields()
    {
        return [
            'optionid' => [
                'class' => AutoField::className(),
            ],
            'option_name' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ],
            'modified_price' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ]
        ];
    }

    public function getFrontendName()
    {
        return $this->option_name;
    }
}