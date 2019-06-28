<?php


namespace Modules\Order\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;

class ReconciliationAccountModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_reconciliation_account';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}