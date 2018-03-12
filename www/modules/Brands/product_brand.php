<?php

use Modules\Goods\Models\ProductModel;

if ( !defined('XCART_START') ) { header("Location: ../"); die("Access denied"); }

x_load('product');

# Update brand id
if ($REQUEST_METHOD == "POST" && isset($brandid) && $productid) {
    if ($product_model = ProductModel::objects()->get(['productid' => $productid])) {

        $brandid = empty($brandid) ? null : $brandid;

        if (!empty($active_modules['Multiple_Storefronts'])) {
            $old_brands = array();
            $old_brands[] = func_query_first_cell('SELECT brandid FROM ' . $sql_tbl['products']
                . ' WHERE productid = "' . $productid . '"');
        }

        if ($geid && $fields['brand'] == 'Y') {
            while ($pid = func_ge_each($geid, 100)) {
                if (!empty($active_modules['Multiple_Storefronts'])) {
                    $geid_old_brands = func_query_column('SELECT brandid FROM ' . $sql_tbl['products']
                        . ' WHERE productid IN ("' . implode('","', $pid) . '")');
                    if (is_array($geid_old_brands)) {
                        $old_brands = array_merge($old_brands, $geid_old_brands);
                    }
                }
                ProductModel::objects()->filter(['productid__in' => $pid])->update(['brandid' => $brandid]);
            }
        } else {
            $product_model->brandid = $brandid;
            $product_model->save();
        }

        # Update the list of storefronts for the brand
        if (!empty($active_modules['Multiple_Storefronts'])) {
            func_rebuild_brand_sf($brandid);
            foreach ($old_brands as $b) {
                if (!empty($b) && is_numeric($b)) {
                    func_rebuild_brand_sf($b);
                }
            }
        }
    }

# Get brands list
} else {
	if (!empty($active_modules['Multiple_Storefronts']) && $current_area == 'C') {
		$sf_join = "LEFT JOIN $sql_tbl[brands_sf] ON $sql_tbl[brands_sf].brandid=$sql_tbl[brands].brandid";
		$sf_condition = 'WHERE ' . $sql_tbl['brands_sf'] . '.sfid = ' . $current_storefront;
	} else {
		$sf_join = '';
		$sf_condition = '';
	}
	$brands = func_query("SELECT $sql_tbl[brands].brandid, IFNULL($sql_tbl[brands_lng].brand, $sql_tbl[brands].brand) as brand FROM $sql_tbl[brands] $sf_join LEFT JOIN $sql_tbl[brands_lng] ON $sql_tbl[brands].brandid = $sql_tbl[brands_lng].brandid AND $sql_tbl[brands_lng].code = '$shop_language' $sf_condition ORDER BY $sql_tbl[brands].orderby, $sql_tbl[brands].brand");

	if (!empty($brands)) {
		$smarty->assign("brands", $brands);
	}
}
?>
