<?php


namespace Modules\Sites\Models;


use Xcart\App\Form\Fields\LinkField;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class MerchantAccountModel extends Model
{
    public static function getFields(): array
    {
        return [
            'id' => AutoField::class,
            'corporate' => [
                'class' => ForeignField::class,
                'modelClass' => CorporateModel::class,
                'link' => ['corporate_id' => 'id']
            ],
            'issuer' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Merchant account issuer'
            ],
            'number' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Merchant account #'
            ],
            'url' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Login URL'
            ],
            'login' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Login/username'
            ],
            'password' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Password'
            ],
        ];
    }
}