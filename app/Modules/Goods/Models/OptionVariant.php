<?php

namespace Modules\Goods\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Model;

class OptionVariant extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_option_variants';
    }
}