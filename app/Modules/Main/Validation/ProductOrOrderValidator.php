<?php

namespace Modules\Main\Validation;


use Modules\Goods\Models\ProductModel;
use Modules\Order\Models\OrderModel;
use Xcart\App\Validation\Validator;

class ProductOrOrderValidator extends Validator
{

    /**
     * @param $value
     * @return mixed
     */
    public function validate($value)
    {
        if(!empty($value)) {

            $order_prefix = substr($value, 0, 3);
            $order_number = substr($value, 3);

            if (!(ProductModel::objects()->filter(['productcode' => $value])->count() > 0
                || ($order_number && OrderModel::objects()->filter(['pk' => $order_number, 'order_prefix' => $order_prefix])->count() > 0)))
            {
                $this->addError('SKU or Order # not found');
            }

        }

        return $this->hasErrors() === false;
    }
}