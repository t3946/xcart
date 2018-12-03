<?php

namespace Modules\Amazon\Controllers;


use Modules\Amazon\Helpers\AmazonReorderingHelper;
use Modules\Amazon\Models\AmazonReorderBatchDataModel;
use Modules\Amazon\Models\AmazonReorderBatchModel;
use Modules\Amazon\Stores\AmazonStore;
use Modules\Core\Models\GlobalConfigModel;
use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Controller\PrototypeAdminController;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;

class AmazonController extends PrototypeAdminController
{
    public $defaultAction = 'index';

    public function index()
    {
        $errors = [];
        if (array_key_exists('calculate_shipping', $_POST)) {
            if (!AmazonReorderBatchModel::objects()->filter(['status' => 'processing'])->count()) {
                $model = new AmazonReorderBatchModel(['user_id' => Xcart::app()->user->id]);
                if (!empty($_POST['batch_assortment'])) {
                    $model->assortment = $_POST['batch_assortment'];
                }
                if (!empty($_POST['external_link'])) {
                    $model->link = $_POST['external_link'];
                }
                $model->save();
                if ($model->batch_id) {
                    $this->autoRedirect($model->batch_id);
                }
            } else {
                $errors[] = 'Another batch always processing. Please wait or inform tech support!';
            }
        }

        $pager = new Pagination( AmazonReorderBatchModel::objects()->order(['-batch_id'])->getQuerySet(), ['pageSize' => 50], new QuerySetDataSource());

        echo $this->renderInternal('amazon/index.tpl',
            [
                'errors' => $errors,
                'batches' => $pager->paginate(),
                'pager' => $pager,
            ]
        );
    }

    public function batch_processing_check()
    {
        $result = null;
        if (!empty($_GET) && is_numeric($_GET['batch_id'])) {
            $batch = AmazonReorderBatchModel::objects()->get(['batch_id' => $_GET['batch_id']]);
            if ($batch) {
                $result = $batch->status;
            }
        }
        print json_encode(['status' => $result]);
    }

    public function batch_delete()
    {
        $result = null;
        if (!empty($_POST) && is_numeric($_POST['batch_id'])) {
            $batch = AmazonReorderBatchModel::objects()->delete(['batch_id' => $_POST['batch_id']]);
            if ($batch) {
                $result = 'ok';
            }
        }
        print json_encode(['status' => $result]);
    }

    public function batch_processing()
    {
        set_time_limit(0);
        if (!empty($_GET) && is_numeric($_GET['batch_id'])) {
            $batch = AmazonReorderBatchModel::objects()->get(['batch_id' => $_GET['batch_id']]);
            if ($batch && $batch->status == 'processing') {
                $batch->status = 'lock';
                $batch->save();
                $params = [
                    'day_reorder' => AmazonReorderingHelper::getDaysBeforeNextReorder(current(GlobalConfigModel::objects()->filter(['name' => 'reorder_weekday'])->valuesList(['value'], true))),
                    'tau' => current(GlobalConfigModel::objects()->filter(['name' => 'ads_back_in_time_period'])->valuesList(['value'], true)),
                    'tau_m' => current(GlobalConfigModel::objects()->filter(['name' => 'ads_tau_m'])->valuesList(['value'], true)),
                    'assortment' => $batch->assortment
                ];
                if ($aProducts = AmazonReorderingHelper::calculateAmazonProducts($params)) {
                    foreach ($aProducts as $aProduct) {

                        //Можно обнулить количество к закупу, если у дистрибьютора в стоке меньше или равно 5, так как такой товар скоро обнулится на складе у дистрибьютора и отправлен не будет. Кроме CTI & ECS
                        if (!\in_array($aProduct['manufacturerid'], [523, 9])) {
                            if ($aProduct['r_avail'] <= 0 || !$aProduct['items_sold_last_1m_of_stock']) {
                                $aProduct['restocking_qty'] = 0;
                            }
                        }

                        if ($aProduct['restocking_qty']) {
                            $aProduct['restocking_qty'] = max($aProduct['restocking_qty'], $aProduct['min_amount']);
                        }

                        $modelData = new AmazonReorderBatchDataModel(array_merge($aProduct, ['batch_id' => $batch->batch_id]));
                        $modelData->save();
                    }
                }

                $params = ['batch_id' => $batch->batch_id];
                if ($aNewProducts = AmazonReorderingHelper::calculateBestSellers($params)) {
                    foreach ($aNewProducts as $aNewProduct) {
                        /** @var AmazonReorderBatchDataModel $modelData */
                        [$modelData] = AmazonReorderBatchDataModel::objects()->getOrNew([
                            'batch_id' => $batch->batch_id,
                            'productid' => $aNewProduct['productid']]
                        );
                        $modelData->setAttributes($aNewProduct);
                        $modelData->save();
                    }
                }

                $batch->status = 'done';
                $batch->save();
            }


        }
    }

    public function batch($id)
    {
        $batch = AmazonReorderBatchModel::objects()->get(['batch_id' => $id]);
        if ($batch) {
            if (!empty($_POST)) {
                if ($_POST['recalculate_submit']) {
                    AmazonReorderBatchDataModel::objects()->delete(['batch_id' => $id]);
                    $batch->status="processing";
                    $batch->save();
                    $this->autoRedirect($id);

                } elseif (!empty($_POST['update_changes'])) {
                    foreach ($_POST['restocking_qty'] as $batch_id => $products) {
                        foreach ($products as $product_id => $qty) {
                            $model = AmazonReorderBatchDataModel::objects()->get(['batch_id' => $batch_id, 'productid' => $product_id]);
                            if ($model) {
                                $model->restocking_qty = $qty;
                                $model->save();
                            }
                        }
                    }
                }
            }
            $amazonStore = new AmazonStore(array_merge(AmazonReorderingHelper::getFilterData($_GET['filter']), ['batch_id' => $batch->batch_id]));

            echo $this->renderInternal('amazon/batch.tpl',
                [
                    'distributors' => $amazonStore->getDistributors(),
                    'batch_id' => $id,
                    'batch_model' => $batch,
                    'amazon_products' => $amazonStore->getAmazonBatchData(),
                    'filter_data' => AmazonReorderingHelper::getFilterData($_GET['filter']),
                ]
            );
        }
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
        } else if (array_key_exists('calculate_shipping', $_POST)) {
            return ['amazon:batch', ['id' => $id]];
        } else {
            return ['amazon:index', []];
        }
    }

}