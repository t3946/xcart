<?php


namespace Modules\Distributor\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class DistributorUtilityModel extends Model
{
    public const REQUEST_AVAIL_UTILITY = 1;
    public const DISPATCH_UTILITY = 2;
    public const ORDER_MESSAGE_UTILITY = 3;
    public const ACCOUNT_RECEIVABLE_UTILITY = 4;
    public const REQUEST_PRODUCT_QUESTIONS_UTILITY = 5;

    public static function tableName()
    {
        return 'xcart_distributor_utility';
    }

    public static function getFields()
    {
        return [
            'utility_id' => [
                'class' => AutoField::class,
            ],
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Utility'
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}