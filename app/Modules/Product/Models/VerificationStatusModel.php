<?php

namespace Modules\Product\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Model;

class VerificationStatusModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_product_verification_statuses';
    }
}