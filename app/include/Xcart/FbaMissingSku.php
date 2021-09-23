<?php
namespace Xcart;


class FbaMissingSku extends Data
{
    /**
     * @var Product
     */
    private $oProduct = null;
    private $iOrdersCount = null;

    public function __construct($aOrderData = null)
    {
        $this->aPrimaryKeys = ['missing_productcode'];
        $this->sPrimaryTable = 'fba_missing_sku';

        parent::__construct($aOrderData);
    }

    public function getProductId()
    {
        return $this->getField('productid');
    }

    public function getProductInstance()
    {
        if (is_null($this->oProduct)) {
            $this->oProduct = Product::model(['productid' => $this->getProductId()]);
        }
        return $this->oProduct;
    }

    public function getMissingSKU()
    {
        return $this->getField('missing_productcode');
    }

    public function getOrdersCount()
    {
        if (is_null($this->iOrdersCount)) {
            $aResult = SQLBuilder::getInstance()->addSelect('count(distinct orderid)', 'orders_count')->
            addFromTable('order_details')->
            addCondition("productcode = '" . $this->getMissingSKU() . "'")->query_first()->getQueryResult();
            $this->iOrdersCount = $aResult['orders_count'];
        }
        return $this->iOrdersCount;
    }

    public function getOrdersWithMissingSKU()
    {
        return OrderDetail::model()->findAll(
            SQLBuilder::getInstance()->
            addCondition("productcode = '" . $this->getMissingSKU() . "'")
        );
    }

    public function fixOrders()
    {
        $aOrderDetails = $this->getOrdersWithMissingSKU();
        if (!empty($aOrderDetails)) {
            foreach ($aOrderDetails as $oOrderDetail) {
                $oOrderDetail->updateFields([
                    'productcode' => $this->getProductInstance()->getSKU(),
                    'productid' => $this->getProductInstance()->getProductId(),
                    'item_cost_to_us' => $this->getProductInstance()->getProductCostToUs()
                ]);
                /** @var Order $oOrder */
                $oOrder = $oOrderDetail->getOrderInstance();
                $aDetails = $oOrder->getOrderDetails();
                if (count($aDetails) == 1) {
                    OrderGroup::model(['orderid' => $oOrderDetail->getField('orderid'), 'manufacturerid' => 0])->updateField('manufacturerid', $this->getProductInstance()->getManufacturerId());
                }
                $oOrder->reCalculateTotals()->recalculateAccounting();
            }
        }
    }
}