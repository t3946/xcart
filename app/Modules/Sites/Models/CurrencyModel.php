<?php


namespace Modules\Sites\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\BooleanCharField;
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
            'is_primary' => BooleanCharField::class
        ];
    }

    public function getCurrencyFormat($number): string
    {
        return number_format($number, $this->decimals, $this->decimals_separator, $this->thousands_separator);
    }

    public function __toString(): string
    {
        return (string) $this->symbol;
    }
}