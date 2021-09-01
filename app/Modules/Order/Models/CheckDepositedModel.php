<?php


namespace Modules\Order\Models;


use Doctrine\DBAL\Types\Types;
use Modules\Sites\Models\CurrencyModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;

class CheckDepositedModel extends Model
{
    public const STATUS_PENDING = 'P';
    public const STATUS_DEPOSITED = 'D';
    public const STATUS_NEW = 'N';

    public static function tableName()
    {
        return 'xcart_checks_deposited';
    }

    public static function getFields()
    {
        return [
            'checks_deposited_id' => [
                'class' => AutoField::class,
            ],
            'date' => [
                'class' => UnixTimestampField::class,
                'verboseName' => 'Deposit date'
            ],
            'check_date' => [
                'class' => DateField::class,
                'verboseName' => 'Deposit date'
            ],
            'currency_model' => [
                'field' => 'currency',
                'class' => ForeignField::class,
                'modelClass' => CurrencyModel::class,
                'link' => ['currency' => 'currency_code'],
                'sqlType' => Types::STRING,
                'default' => 'USD',
                'verboseName' => 'Currency'
            ],
            'currency_locked' => BooleanCharField::class,
            'total_deposit_amount' => [
                'class' => DecimalField::class,
                'default' => false,
                'verboseName' => 'Deposit amount'
            ],
            'status' => [
                'class' => CharField::class,
                'default' => self::STATUS_NEW,
                'null' => false,
                'choices' => [
                    self::STATUS_NEW => 'Not yet entered',
                    self::STATUS_PENDING => 'Pending deposit',
                    self::STATUS_DEPOSITED => 'Deposited with the bank',
                ],
                'verboseName' => 'Deposit status'
            ],
            'orders' => [
                'class' => HasManyField::class,
                'modelClass' => CheckDepositedOrderModel::class,
                'link' => ['checks_deposited_id' => 'id']
            ]
        ];
    }

    public static function getShortName()
    {
        return 'Deposit';
    }
}