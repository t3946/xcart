<?php


namespace Modules\Sites\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class CurrencyModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_currencies';
    }

    public static function getFields()
    {
        return [
            'currency_id' => AutoField::class,
            'is_primary' => BooleanCharField::class,
            'after' => BooleanCharField::class,
            'thousands_separator' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ','
            ]
        ];
    }

    public function getCurrencyFormat($number): string
    {
        return number_format(round($number, 2), $this->decimals ?? 2, $this->decimals_separator?? '', $this->thousands_separator ?? '');
    }

    public function __toString(): string
    {
        return (string) $this->symbol;
    }
}