<?php


namespace Modules\Account\Models;


use Modules\Order\Models\OrderModel;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class TransactionsModel extends Model
{
    public static function tableName()
    {
        return 'account_transactions';
    }

    public static function getFields()
    {
        return [
            'transaction_id' => [
                'class' => AutoField::class,
            ],
            'credit_card' => [
                'field' => 'credit_card_id',
                'class' => ForeignField::class,
                'modelClass' => CreditCardsModel::class,
                'link' => ['credit_card_id' => 'credit_card_id'],
            ],
            'order' => [
                'field' => 'order_id',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['order_id' => 'orderid'],
            ],
            'user' => [
                'field' => 'user_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['user_id' => 'user_id'],
            ],
        ];
    }
}