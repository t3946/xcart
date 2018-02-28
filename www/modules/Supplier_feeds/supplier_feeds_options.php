<?php

use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Goods\Models\CategoryModel;
use Xcart\App\Main\Xcart;

if ($REQUEST_METHOD == 'POST'){

    if ($mode == 'Update_Supplier_feeds'){
//dd($Supplier_feeds);
        if (!empty($Supplier_feeds) && is_array($Supplier_feeds)) {
            foreach ($Supplier_feeds as $k => $v){

                /** @var SupplierFeedModel $model */
                if($v['amended'] == 'Y'){
                    if ($v['delete'] == 'Y'){
                        $model = SupplierFeedModel::objects()->get(['feed_id' => $v['feed_id']]);
                        if ($model){
                            $model->delete();
                        }
                    }
                    else {
                        $v['base_category_id'] = $v['base_category_id'] ?: null;

                        if (!empty($v['base_category_id'])){
                            /** @var CategoryModel $category_model */

                            if (!$category_model = CategoryModel::objects()->get(['categoryid' => $v['base_category_id'], 'parentid' => 0, 'storefrontid' => $v['storefront_id']])){

                                if ($category_model = CategoryModel::objects()->get(['parentid' => 0, 'category' => 'New Products', 'storefrontid' => $v['storefront_id']])){
                                    Xcart::app()->request->session->add('top_message', ['content' => "Bad Category for this Storefront. Use {$category_model->categoryid} category id for this storefront", 'type' => 'E']);
                                }
                                else {
                                    Xcart::app()->request->session->add('top_message', ['content' => "This storefront haven't base category", 'type' => 'E']);
                                }

                                Xcart::app()->request->redirect("configuration.php?option=Supplier_feeds");
                            }
                        }

                        $model = SupplierFeedModel::objects()->get(['feed_id' => $v['feed_id']]);
                        $model->setAttributes($v);
                        $model->save();
                    }
                }
            }
        }
    }
    elseif ($mode == 'Add_Supplier_feed') {
        (new SupplierFeedModel(['feed_name' => 'new feed name']))->save();
    }

    Xcart::app()->request->session->add('top_message', ['content' => 'Done.', 'type' => 'I']);
    Xcart::app()->request->redirect("configuration.php?option=Supplier_feeds");

}

$Supplier_feeds = func_query("SELECT * FROM $sql_tbl[supplier_feeds] ORDER BY feed_id ASC");

if (!empty($Supplier_feeds)){
	foreach ($Supplier_feeds as $k => $v){

                $cur_time = time();
                $date1 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', $cur_time));
                $date2 = DateTime::createFromFormat('m-d-Y H:i:s', date('m-d-Y H:i:s', $cur_time+$v["average_update_period"]));
                $interval = $date1->diff($date2);
                $years = $interval->format("%y");
                $months = $interval->format("%m");
                $days = $interval->format("%d");
                $hours = $interval->format("%h");
                $mins = $interval->format("%i");
                $age_str = ($years != 0 ? $years." years, ":"").($months != 0 ? $months." months, ":"").($days != 0 ? $days." days, ":""). sprintf('%1$02d', $hours).":". sprintf('%1$02d', $mins). " hours";
                $Supplier_feeds[$k]["average_update_period_str"] = $age_str;
		$Supplier_feeds[$k]["last_update_late"] = intval($Supplier_feeds[$k]["last_update_late"]);
	}
}

$smarty->assign("Supplier_feeds", $Supplier_feeds);
?>
