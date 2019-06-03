<?php


namespace Modules\Order\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class CheckDeposited extends Model
{
    use AutoMetaTrait;

    const STATUS_PENDING = 'P';
    const STATUS_DEPOSITED = 'D';

    public static function tableName()
    {
        return 'xcart_checks_deposited';
    }

    public static function getFields()
    {
        return [
            'status' => [
                'class' => CharField::class,
                'default' => 'P',
                'null' => false,
                'choices' => [
                    self::STATUS_PENDING => 'Pending deposit',
                    self::STATUS_DEPOSITED => 'Deposited with the bank',
                ]
            ],
        ];
    }
}