<?php


namespace Modules\Sites\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DecimalField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
/**
 * @property string currency_code
 * @property string after
 * @property string symbol
 * @property string symbol_prefix
 * @property double coefficient
 * @property string is_primary
 * @property int position
 * @property string decimals_separator
 * @property string thousands_separator
 * @property int|string decimals
 * @property bool active
 */
class CurrencyModel extends Model
{

    public static function tableName()
    {
        return 'xcart_currencies';
    }

    public static function getFields()
    {
        return [
            'currency_id' => AutoField::class,
            'currency_code' => [
                'class' => CharField::class,
                'default' => ''
            ],
            'symbol' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'symbol_prefix' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'coefficient' => [
                'class' => DecimalField::class,
                'default' => 1
            ],
            'position' => [
                'class' => IntField::class
            ],
            'decimals_separator' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '.'
            ],
            'is_primary' => BooleanCharField::class,
            'after' => BooleanCharField::class,
            'decimals' => [
                'class' => IntField::class,
                'default' => 2
            ],
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
    public function getFrontendData() : array
    {
        return [
            'currency' => (string)$this,
            'symbol_prefix' => $this->symbol_prefix,
            'after' => $this->after,
            'currency_code' => $this->currency_code,
            'decimal' => (int)$this->decimals,
            'thousands_separator' => $this->thousands_separator,
            'decimals_separator' => $this->decimals_separator
        ];
    }

    public function __toString(): string
    {
        return (string) $this->symbol;
    }
}