<?php
use Mindy\QueryBuilder\QueryBuilder;
use Modules\Product\Models\ProductCategoryTermsModel;
use Xcart\Connection;

define("CIDEV_CRON_START", "CRON");

require "./top.inc.php";
require "./init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

if ($config["cron_pc_launched"] == "Y") {

//	echo '<pre>'.print_r(opcache_get_status(), true).'</pre>';
    //die("Already launched"); // ################################
}

db_query("UPDATE $sql_tbl[config] SET value='Y' WHERE name='cron_pc_launched'");  // <--------------------

$pc_options = func_query_hash("SELECT * FROM $sql_tbl[pc_options]", 'storefrontid', false);

$storefronts[0]["storefrontid"] = 0;
$storefronts[0]["domain"] = "www.artistsupplysource.com";

$start_time = time();
echo "--";
foreach ($storefronts as $storefrontid => $store_info) {
    func_print_r($store_info);

    if (empty($pc_options[$storefrontid])) {
        db_query("INSERT INTO $sql_tbl[pc_options] (storefrontid, maximum_number_of_autoclassify_product_per_turn, minimum_number_of_autoclassify_product_per_turn, stop_words, excluded_char_sequences) VALUES ('$storefrontid', '50', '3', '- with for not as by this when x you your the a on and feature will would can to in must do or nor if of me is', '+#13+ +#10+')");
        $pc_options = func_query_hash("SELECT * FROM $sql_tbl[pc_options]", 'storefrontid', false);
    }


    $count_AC_products = func_query_first_cell("
	SELECT COUNT(*) FROM $sql_tbl[products] LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[products_sf].productid=$sql_tbl[products].productid
	WHERE $sql_tbl[products_sf].sfid='$storefrontid' AND $sql_tbl[products].forsale='Y' AND $sql_tbl[products].pc_classify_status='AC'");

    $count_NC_products = func_query_first_cell("
    SELECT COUNT(*) FROM $sql_tbl[products] LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[products_sf].productid=$sql_tbl[products].productid
    WHERE $sql_tbl[products_sf].sfid='$storefrontid' AND $sql_tbl[products].forsale='Y' AND $sql_tbl[products].pc_classify_status='NC'");


    func_print_r($pc_options[$storefrontid]);

    $mcAccCountSQL = QueryBuilder::getInstance(Connection::getInstance())
        ->setTypeSelect()
        ->select('count(*)')
        ->from('xcart_products')
        ->setAlias('p')
        ->join('inner join', 'xcart_products_sf', ['ps.productid' => 'p.productid'], 'ps')
        ->where(['ps.sfid' => $storefrontid, 'forsale' => 'Y'])
        ->toSQL();
    $mcAccCount = Connection::getInstance()->executeQuery($mcAccCountSQL)->fetchColumn();

    if ($pc_options[$storefrontid]["classification_approval_rate"] >= 0
        && $count_AC_products < $pc_options[$storefrontid]["amount_of_products_for_autoclassify_queue"]
        && $count_NC_products > 0
    ) {

        db_query("UPDATE $sql_tbl[config] SET value='$storefrontid' WHERE name='cron_pc_launched_storefrontid'");

        if ($pc_options[$storefrontid]["classification_approval_rate"] < $pc_options[$storefrontid]["recalc_if_approval_rate"] && $mcAccCount != $pc_options[$storefrontid]["last_mc_acc_products_count"]) {

            db_query("delete CT, T from $sql_tbl[pc_category_terms] as CT inner join $sql_tbl[pc_terms] T ON T.termid = CT.termid");

            $categories = db_query($query = "SELECT categoryid FROM $sql_tbl[categories] WHERE pc_ready_to_classify='Y' AND avail='Y' AND storefrontid='$storefrontid'");
            $counter = 0;
            while ($category = db_fetch_array($categories)) {
                $categoryid = $category["categoryid"];
                $products = db_query_param(/** @lang MySQL */
                    "SELECT p.productid, 
                                   p.product, 
                                   p.fulldescr, 
                                   p.title_tag, 
                                   p.seo_product_name,
                                   b.brand 
                            FROM xcart_products p
                            INNER JOIN xcart_brands b ON p.brandid = b.brandid 
                            LEFT JOIN xcart_products_categories pc ON pc.productid = p.productid 
                            WHERE (p.pc_classify_status='MC' OR p.pc_classify_status='ACC') AND p.forsale='Y' AND pc.categoryid=:categoryid", ['categoryid' => $categoryid]);
                while ($product = db_fetch_array($products)) {
                    $text = $product["product"] . " " . $product["product"] . " " . $product["fulldescr"] . " " . $product["title_tag"] . " " . $product["seo_product_name"];
                    $text = func_del_excluded_char_sequences($text, $pc_options[$storefrontid]["excluded_char_sequences"]);
                    $text = func_del_stop_words($text, $pc_options[$storefrontid]["stop_words"] . "|{$product['brand']}");
                    if (!empty($text)) {
                        $text_arr = explode(" ", $text);
                        foreach ($text_arr as $term) {
                            db_query("INSERT IGNORE INTO $sql_tbl[pc_terms] (term) VALUES ('$term')");
                            $termid = func_query_first_cell("SELECT termid FROM $sql_tbl[pc_terms] WHERE term='$term'");
                            $productCategoryTerm = ProductCategoryTermsModel::objects()->get(['categoryid' => $categoryid, 'termid' => $termid]);
                            if (!$productCategoryTerm) {
                                $productCategoryTerm = new ProductCategoryTermsModel();
                                $productCategoryTerm->setAttributes(['categoryid' => $categoryid, 'termid' => $termid]);
                            }
                            $productCategoryTerm->term_count++;
                            $productCategoryTerm->save();
                        }
                    }
                    $counter++;
                    if ($counter % 10 == 0) {
                        func_flush(".");
                        if ($counter % 500 == 0) {
                            func_flush("<br />\n");
                        }
                        func_flush();
                    }
                }
            }

            $query_bayesian_weight = "Select
                C.categoryid As CategoryID, 
                LOG((Select Count(P1.productid) 
                 From xcart_products P1
                                left join xcart_products_categories PC1 ON PC1.productid = P1.productid
                 where P1.forsale = 'Y' and PC1.categoryid = C.categoryid and P1.pc_classify_status IN ('ACC','MC')
                ) /
                (Select COUNT(P2.productid) From xcart_products P2
                                left join xcart_products_sf PSF ON PSF.productid = P2.productid
                 where P2.forsale='Y' and PSF.sfid = '$storefrontid' and P2.pc_classify_status IN ('ACC','MC')
                )) As bayesian_weight
	from xcart_categories C
	where C.pc_ready_to_classify = 'Y' and C.storefrontid = '$storefrontid'";

            $bayesian_weight_arr = func_query($query_bayesian_weight);

            if (!empty($bayesian_weight_arr)) {
                foreach ($bayesian_weight_arr as $k => $v) {
                    if (!empty($v["bayesian_weight"])) {
                        db_query("UPDATE $sql_tbl[categories] SET pc_category_weight='$v[bayesian_weight]' WHERE categoryid='$v[CategoryID]'");
                    }
                }
            }

            $query_z = "Select
                    C.categoryid As CategoryID,
            		COALESCE(CT.term_count, 0) As Z
	from xcart_categories C
                        left join xcart_pc_category_terms CT ON CT.categoryid = C.categoryid 
	where C.pc_ready_to_classify = 'Y' and C.storefrontid = '$storefrontid'
	Group By C.categoryid";

            $z_arr = func_query($query_z);

            if (!empty($z_arr)) {
                foreach ($z_arr as $k => $v) {
                    db_query("UPDATE $sql_tbl[categories] SET pc_z='$v[Z]' WHERE categoryid='$v[CategoryID]'");
                }
            }

        } //if ($pc_options[$storefrontid]["classification_approval_rate"] < $pc_options[$storefrontid]["recalc_if_approval_rate"])

        echo "4";
        $limit = $pc_options[$storefrontid]["amount_of_products_for_autoclassify_queue"] - $count_AC_products;
        if ($limit < 0) $limit = 10;

        $products = func_query($query = "SELECT $sql_tbl[products].productid FROM $sql_tbl[products] LEFT JOIN $sql_tbl[products_sf] ON $sql_tbl[products_sf].productid = $sql_tbl[products].productid WHERE pc_classify_status='NC' AND $sql_tbl[products].forsale='Y' AND $sql_tbl[products_sf].sfid='$storefrontid' ORDER BY RAND() LIMIT $limit");

        $p_count = 0;
        if (!empty($products)) {
            foreach ($products as $product) {
                $p_count++;
                $productid = $product["productid"];
                func_pc_find_new_categoryid($productid);
                if ($p_count > 50) {
                    break;
                }
            }
            db_query_param(/** @lang MySQL */
                "UPDATE xcart_pc_options SET last_mc_acc_products_count = :last_mc_acc_products_count WHERE storefrontid = :storefrontid",
                ['last_mc_acc_products_count' => $mcAccCount, 'storefrontid' => $storefrontid]);
        }
    }
}

db_query("UPDATE $sql_tbl[config] SET value='N' WHERE name='cron_pc_launched'");
db_query("UPDATE $sql_tbl[config] SET value='' WHERE name='cron_pc_launched_storefrontid'");

print"<br />DONE!";