<?php

namespace Modules\Amazon\Controllers;


use Modules\Amazon\Helpers\AmazonReorderingHelper;
use Modules\Amazon\Models\AmazonReorderBatchDataModel;
use Modules\Amazon\Models\AmazonReorderBatchModel;
use Modules\Amazon\Stores\AmazonStore;
use Modules\Core\Models\GlobalConfigModel;
use Xcart\App\Controller\PrototypeAdminController;
use Xcart\App\Main\Xcart;

class AmazonController extends PrototypeAdminController
{
    public $defaultAction = 'index';

    public function index()
    {
        $amazonStore = new AmazonStore([]);

        echo $this->renderInternal('amazon/index.tpl',
            [
                //'amazon_products' => $amazonStore->calculateAmazonProducts()
            ]
        );
    }

    public function create_shipping()
    {
        global $config;
        $params = [
            'day_reorder' => AmazonReorderingHelper::getDaysBeforeNextReorder(current(GlobalConfigModel::objects()->filter(['name' => 'reorder_weekday'])->valuesList(['value'], true))),
            'tau' => current(GlobalConfigModel::objects()->filter(['name' => 'ads_back_in_time_period'])->valuesList(['value'], true)),
            'tau_m' => current(GlobalConfigModel::objects()->filter(['name' => 'ads_tau_m'])->valuesList(['value'], true))
        ];
        $aProducts = AmazonReorderingHelper::calculateAmazonProducts($params);
        if ($aProducts) {
            $model = new AmazonReorderBatchModel(['user_id' => Xcart::app()->user->id]);
            $model->save();
            foreach ($aProducts as $aProduct) {
                $modelData = new AmazonReorderBatchDataModel(array_merge($aProduct, ['batch_id' => $model->batch_id]));
                $modelData->save();
            }
            if ($model->batch_id) {
                $this->autoRedirect($model->batch_id);
            }
        }
    }

    public function batch($id)
    {
        if (!empty($_POST)) {
            if ($_POST['recalculate_submit']) {
                AmazonReorderBatchDataModel::objects()->delete(['batch_id' => $id]);
                $params = [
                    'day_reorder' => AmazonReorderingHelper::getDaysBeforeNextReorder(current(GlobalConfigModel::objects()->filter(['name' => 'reorder_weekday'])->valuesList(['value'], true))),
                    'tau' => current(GlobalConfigModel::objects()->filter(['name' => 'ads_back_in_time_period'])->valuesList(['value'], true)),
                    'tau_m' => current(GlobalConfigModel::objects()->filter(['name' => 'ads_tau_m'])->valuesList(['value'], true))
                ];
                $aProducts = AmazonReorderingHelper::calculateAmazonProducts($params);
                if ($aProducts) {
                    foreach ($aProducts as $aProduct) {
                        $modelData = new AmazonReorderBatchDataModel(array_merge($aProduct, ['batch_id' => $id]));
                        $modelData->save();
                    }
                }
            } elseif (!empty($_POST['update_changes'])) {
               foreach ($_POST['restocking_qty'] as $batch_id => $products) {
                   foreach ($products as $product_id => $qty) {
                       $model = AmazonReorderBatchDataModel::objects()->get(['batch_id' => $batch_id, 'productid' => $product_id]);
                       if ($model) {
                           $model->setAttribute('restocking_qty', $qty);
                           $model->save();
                       }
                   }
               }
            }
            $this->autoRedirect($id);
        }
        $amazonStore = new AmazonStore(array_merge(AmazonReorderingHelper::getFilterData($_GET['filter']), ['batch_id' => $id]));

        echo $this->renderInternal('amazon/batch.tpl',
            [
                'batch_id' => $id,
                'amazon_products' => $amazonStore->getAmazonBatchData(),
                'filter_data' => AmazonReorderingHelper::getFilterData($_GET['filter'])
            ]
        );
    }

    private function autoRedirect($id)
    {
        list($url, $params) = $this->autoActions($id);
        $this->redirect($url, $params, 303);
    }

    private function autoActions($id)
    {
        if (array_key_exists('recalculate_submit', $_POST)) {
            return ['amazon:batch', ['id' => $id]];
        } else if (array_key_exists('update_changes', $_POST)) {
            return ['amazon:batch', ['id' => $id]];
        } else {
            return ['amazon:index', []];
        }
    }

}