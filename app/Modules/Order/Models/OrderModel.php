<?php
namespace Modules\Order\Models;

use Mindy\QueryBuilder\Expression;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\TimestampField;
use Xcart\App\Traits\DataModelTrait;
use Xcart\App\Traits\FieldManagerCacheTrait;
use Xcart\Order;

class OrderModel extends AutoMetaModel
{
    use DataModelTrait, FieldManagerCacheTrait;

    public $max_eta;
    public $last_activity;
    public $last_message;
//    public $tag;

    public static function getDataModelClass()
    {
        return Order::className();
    }

    public static function tableName()
    {
        return 'xcart_orders';
    }

    public static  function getFields()
    {
        return [
            'orderid' => [
                'class' => AutoField::className(),
            ],
            'date' => [
                'class' => TimestampField::className(),
            ],
            'groups' => [
                'class' => HasManyField::className(),
                'modelClass' => OrderGroupModel::className(),
                'link' => ['orderid', 'orderid'],
            ],

            'tags' => [
                'class' => ManyToManyField::className(),
                'modelClass' => AttentionTagModel::className(),
                'through' => OrderAdditionalTagLinkModel::className(),
                'link' => ['orderid', 'status_id']
            ]
        ];
    }

    /**
     * @param Order $model
     */
    public function afterFetchDataModel($model)
    {
        /** @var OrderGroupModel $group */
        foreach ($this->groups as $group)
        {
            $model->orderGroup = $group->getDataModel();
        }
    }

    public function getAdminUrl()
    {
        return sprintf(Order::ADMIN_ORDER_MODIFY_URL, $this->orderid);
    }

    protected $__events_count = [];
    public function getCountEvents($user_id = null)
    {
        $result = 0;

        if (empty($user_id) && Xcart::app()->getIsWebMode())
        {
            $user_id = Xcart::app()->user->id;
        }

        if ($user_id)
        {
            if (!empty($this->__events_count[$user_id])) {
                $count = $this->__events_count[$user_id];
            }
            else {
                $qs = OrderEventsModel::objects();
                $topAlias = $qs->getTableAlias();

                $count = $qs
                    ->filter([
                        'a.user_id' => $user_id,
                        new Expression("`{$topAlias}`.`created_at` >= `a`.`created_at`"),
                        'order_id' => $this->orderid,
                    ])
                    ->getQuerySet()
                    ->join('join', OrderUserLastActivityModel::tableName(), ['a.order_id' => 'order_id'], 'a')
                    ->count();

                $this->__events_count[$user_id] = $count;


            }

            if ($count) {
                $result = $count;
            }
        }

        return $result;
    }
}