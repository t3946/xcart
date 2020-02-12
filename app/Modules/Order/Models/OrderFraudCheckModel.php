<?php

namespace Modules\Order\Models;

use Modules\Goods\Models\ProductHardResellModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Model;

class OrderFraudCheckModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_order_fraud_checks';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
            'additional_info' => [
                'class' => SerializeField::class,
                'null' => false,
                'default' => []
            ],
            'order' => [
                'field' => 'orderid',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['orderid' => 'orderid'],
                'null' => false,
            ],
        ];
    }

    public function getScore(FraudCheckModel $fraudModel)
    {
        if ($this->manual_action) {
            if ($this->question_code === 'MANUAL_IS_ORDER_ITEMS_EASY_TO_SELL'
                && array_key_exists('manual_action', $this->getChangedAttributes())
            ) {
                foreach (OrderDetailModel::objects()->filter(['orderid' => $this->orderid]) as $orderDetailModel) {
                    [$hardSellModel] = ProductHardResellModel::objects()->getOrCreate(['product_id' => $orderDetailModel->productid]);
                    switch ($this->manual_action) {
                        case 'Y':
                            $hardSellModel->positive_count++;
                            break;
                        case 'N':
                            $hardSellModel->negative_count++;
                            break;
                    }
                    $hardSellModel->save();
                }
            }
            $importance_factor_arr = $fraudModel->getImportanceFactor();
            if ($this->manual_action === 'Y') {
                return [$importance_factor_arr[2], 1, 'positive'];
            }
            return [$importance_factor_arr[0] * 1, 1, 'negative'];
        }
        return [$this->fraud_score, $this->bare_fraud_score, $this->fraud_result];
    }
}