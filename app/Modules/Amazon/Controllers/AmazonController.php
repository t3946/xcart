<?php

namespace Modules\Amazon\Controllers;


use Modules\Amazon\Helpers\AmazonReorderingHelper;
use Modules\Amazon\Models\AmazonReorderBatchDataModel;
use Modules\Amazon\Models\AmazonReorderBatchModel;
use Modules\Amazon\Stores\AmazonStore;
use Xcart\App\Controller\PrototypeAdminController;
use Xcart\App\Main\Xcart;
use Xcart\Connection;

class AmazonController extends PrototypeAdminController
{
    public $defaultAction = 'index';

    public function index()
    {
        $amazonStore = new AmazonStore([]);

        echo $this->renderInternal('amazon/index.tpl',
            [
                'amazon_products' => $amazonStore->calculateAmazonProducts()
            ]
        );
    }

    public function create_shipping()
    {
        $aProducts = AmazonReorderingHelper::calculateAmazonProducts();
        if ($aProducts) {
            $model = new AmazonReorderBatchModel(['user_id' => Xcart::app()->user->id]);
            $model->save();
            foreach ($aProducts as $aProduct) {
                $modelData = new AmazonReorderBatchDataModel(array_merge($aProduct, ['batch_id' => $model->batch_id]));
                $modelData->save();
            }
            if ($model->batch_id) {
                $this->redirect('amazon:batch', ['id' => $model->batch_id], 303);
            }
        }
    }

    public function batch($id)
    {
        $amazonStore = new AmazonStore(array_merge(AmazonReorderingHelper::getFilterData($_GET['filter']), ['batch_id' => $id]));

        echo $this->renderInternal('amazon/batch.tpl',
            [
                'batch_id' => $id,
                'amazon_products' => $amazonStore->getAmazonBatchData(),
                'filter_data' => AmazonReorderingHelper::getFilterData($_GET['filter'])
            ]
        );
    }

}