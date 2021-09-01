<?php

namespace Modules\Goods\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * @property mixed statusid
 */
class VerificationStatusModel extends Model
{
    public const PRODUCT_STATUS_NOT_VERIFY = 0;
    public const PRODUCT_STATUS_PROBLEM_NOT_FIXED = 1;
    public const PRODUCT_STATUS_PROBLEM_FIXED = 2;
    public const PRODUCT_STATUS_VERIFY = 3;

    public static function tableName()
    {
        return 'xcart_product_verification_statuses';
    }

    public static function getFields()
    {
        return [
            'statusid' => AutoField::class,
            'name' => CharField::class,
            'orderby' => IntField::class,
        ];
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }
}