<?php


namespace Modules\Sites\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class PaymentMethodModel extends Model
{
    public static function tableName(): string
    {
        return 'payment_methods';
    }

    public static function getFields(): array
    {
        return [
            'payment_method_id' => AutoField::class,
            'name' => [
                'class' => CharField::class
            ],
            'logo' => [
                'class' => ImageField::class,
                'adapterName' => 'www',
                'uploadTo' => 'images/payment_methods/',
                'required' => true
            ],
            'is_active' => [
                'class' => BooleanField::class,
                'default' => true,
            ],
            'position' => [
                'class' => IntField::class,
                'default' => 0
            ]
        ];
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}