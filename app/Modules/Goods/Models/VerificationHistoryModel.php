<?php


namespace Modules\Goods\Models;


use Doctrine\DBAL\Types\Types;
use Modules\User\Models\UserModel;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;

class VerificationHistoryModel extends Model
{
    public static function tableName()
    {
        return 'xcart_product_verification_history';
    }

    public static function getFields()
    {
        return [
            'product' => [
                'field' => 'productid',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['productid' => 'productid']
            ],
            'verification_note' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'timestamp' => [
                'class' => UnixTimestampField::class,
                'autoNowAdd' => true,
            ],
            'user' => [
                'field' => 'username',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['username' => 'login'],
                'sqlType' => Types::STRING,
            ],
            'oldstatusid' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0
            ],
            'newstatusid' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0
            ],
        ];
    }
}