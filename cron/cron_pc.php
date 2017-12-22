<?php
use Mindy\QueryBuilder\QueryBuilder;
use Modules\Goods\Models\ProductCategoryTermsModel;
use Xcart\Connection;

define("CIDEV_CRON_START", "CRON");

require __DIR__ . DIRECTORY_SEPARATOR . "../www/top.inc.php";
require __DIR__ . DIRECTORY_SEPARATOR . "../www/init.php";

ini_set('memory_limit', '512M');
set_time_limit(0);

if ($config["cron_pc_launched"] == "Y") {

//	echo '<pre>'.print_r(opcache_get_status(), true).'</pre>';
    die("Already launched"); // ################################
}

db_query_param(/** @lang MySQL */"UPDATE xcart_config SET value='Y' WHERE name='cron_pc_launched'", []);

$pc_options = func_query_hash("SELECT * FROM $sql_tbl[pc_options]", 'storefrontid', false);

$storefronts[0]["storefrontid"] = 0;
$storefronts[0]["domain"] = "www.artistsupplysource.com";

$start_time = time();
echo "--";
foreach ($storefronts as $storefrontid => $store_info) {
    print_r($store_info);

    if (empty($pc_options[$storefrontid])) {
        db_query("INSERT INTO $sql_tbl[pc_options] (storefrontid, maximum_number_of_autoclassify_product_per_turn, minimum_number_of_autoclassify_product_per_turn, stop_words, excluded_char_sequences) VALUES ('$storefrontid', '50', '3', '- with for not as by this when x you your the a on and feature will would can to in must do or nor if of me is', '+#13+ +#10+')");
        $pc_options = func_query_hash("SELECT * FROM $sql_tbl[pc_options]", 'storefrontid', false);
    }

    $sql = /** @lang MySQL */<<<SQL
SELECT COUNT(*) FROM xcart_products p LEFT JOIN xcart_products_sf ps ON ps.productid=p.productid
	WHERE ps.sfid=:storefrontid AND p.forsale=:forsale AND p.pc_classify_status=:pc_classify_status
SQL;

    $count_AC_products = func_query_first_cell_param($sql, ['storefrontid' => $storefrontid, 'forsale' => 'Y', 'pc_classify_status' => 'AC']);
    $count_NC_products = func_query_first_cell_param($sql, ['storefrontid' => $storefrontid, 'forsale' => 'Y', 'pc_classify_status' => 'NC']);

    print_r($pc_options[$storefrontid]);

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

        db_query_param(/** @lang MySQL */"UPDATE xcart_config SET value=:storefrontid WHERE name=:name", ['storefrontid' => $storefrontid, 'name' => 'cron_pc_launched_storefrontid']);

        if ($pc_options[$storefrontid]["classification_approval_rate"] < $pc_options[$storefrontid]["recalc_if_approval_rate"] && $mcAccCount != $pc_options[$storefrontid]["last_mc_acc_products_count"]) {


            $categories = db_query_param($query = /** @lang MySQL */
                "SELECT categoryid FROM xcart_categories WHERE pc_ready_to_classify='Y' AND avail='Y' AND storefrontid=:storefrontid", ['storefrontid' => $storefrontid]);
            $counter = 0;
            while ($category = db_fetch_array($categories)) {
                $categoryid = $category["categoryid"];
                db_query_param(/** @lang MySQL */"DELETE FROM xcart_pc_category_terms WHERE categoryid = :categoryid", ['categoryid' => $categoryid]);
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
                            db_query_param(/** @lang MySQL */
                                "INSERT IGNORE INTO xcart_pc_terms (term) VALUES (:term)", ['term' => $term]);
                            $termid = func_query_first_cell_param(/** @lang MySQL */"SELECT termid FROM xcart_pc_terms WHERE term=:term", ['term' => $term]);
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

            $query_bayesian_weight = /** @lang MySQL */ <<<SQL
SELECT
        C.categoryid AS CategoryID,
        COALESCE(
                    LOG((SELECT Count(P1.productid)
                 FROM xcart_products P1
                      LEFT JOIN xcart_products_categories PC1 ON PC1.productid = P1.productid
                 WHERE P1.forsale = 'Y' AND PC1.categoryid = C.categoryid AND P1.pc_classify_status IN ('ACC','MC')
                ) /
                (SELECT COUNT(P2.productid) FROM xcart_products P2
                      LEFT JOIN xcart_products_sf PSF ON PSF.productid = P2.productid
                 WHERE P2.forsale='Y' AND PSF.sfid = :storefrontid AND P2.pc_classify_status IN ('ACC','MC')
                )),-1000000) AS bayesian_weight
    FROM xcart_categories C
    WHERE C.pc_ready_to_classify = 'Y' AND C.storefrontid = :storefrontid
SQL;

            $bayesian_weight_arr = func_query_param($query_bayesian_weight, ['storefrontid' => $storefrontid]);

            if (!empty($bayesian_weight_arr)) {
                foreach ($bayesian_weight_arr as $k => $v) {
                    if (!empty($v["bayesian_weight"])) {
                        db_query_param(/** @lang MySQL */
                            "UPDATE xcart_categories SET pc_category_weight=:pc_category_weight WHERE categoryid=:categoryid", ['pc_category_weight' => $v['bayesian_weight'], 'categoryid' => $v['CategoryID']]);
                    }
                }
            }

            $query_z = /** @lang MySQL */ <<<SQL
SELECT
    C0.categoryid AS CategoryID,
    (SELECT COALESCE(COUNT(DISTINCT PCT.termid),0)
     FROM xcart_categories C
                 LEFT JOIN xcart_pc_category_terms PCT ON PCT.categoryid = C.categoryid
     WHERE C.avail = 'Y' AND C.pc_ready_to_classify = 'Y' AND C.storefrontid = C0.storefrontid    ) +
    (SELECT COALESCE(SUM(PCT2.term_count),0)
     FROM xcart_pc_category_terms PCT2
     WHERE PCT2.categoryid = C0.categoryid) AS Z
FROM
    xcart_categories C0
WHERE C0.avail = 'Y' AND C0.pc_ready_to_classify = 'Y' AND C0.storefrontid = :storefrontid
SQL;

            $z_arr = func_query_param($query_z, ['storefrontid' => $storefrontid]);

            if (!empty($z_arr)) {
                foreach ($z_arr as $k => $v) {
                    db_query_param(/** @lang MySQL */"UPDATE xcart_categories SET pc_z=:pc_z WHERE categoryid=:categoryid", ['pc_z' => $v['Z'], 'categoryid' => $v['CategoryID']]);
                }
            }

        } //if ($pc_options[$storefrontid]["classification_approval_rate"] < $pc_options[$storefrontid]["recalc_if_approval_rate"])

        echo "4";
        $limit = $pc_options[$storefrontid]["amount_of_products_for_autoclassify_queue"] - $count_AC_products;
        if ($limit < 0) $limit = 10;

        $products = func_query_param($query = /** @lang MySQL */ "
            SELECT p.productid 
            FROM xcart_products p 
            LEFT JOIN xcart_products_sf psf ON psf.productid = p.productid 
            WHERE pc_classify_status='NC' 
            AND p.forsale='Y' 
            AND psf.sfid= :storefrontid 
            ORDER BY RAND() 
            LIMIT $limit", ['storefrontid' => $storefrontid]);

        $storefront_category_terms = func_query_param(/** @lang MySQL */
            "SELECT C.categoryid, T.term, COALESCE(LOG((COALESCE(CT.term_count, 0)+1)/C.pc_z),0) AS bayes_weight 
FROM xcart_categories C
LEFT JOIN xcart_pc_category_terms CT ON CT.categoryid = C.categoryid 
LEFT JOIN xcart_pc_terms T ON T.termid = CT.termid 
WHERE C.pc_ready_to_classify='Y' AND C.storefrontid = :storefrontid AND C.avail ='Y'" , ['storefrontid' => $storefrontid]);
        $aTerms = [];
        foreach ($storefront_category_terms as $cat) {
            $aTerms[$cat['term']][$cat['categoryid']] = floatval($cat['bayes_weight']);
        }

        $p_count = 0;
        if (!empty($products)) {
            foreach ($products as $product) {
                $p_count++;
                $productid = $product["productid"];
                func_pc_find_new_categoryid($productid, $aTerms);
                if ($p_count > 100) {
                    break;
                }
            }
            db_query_param(/** @lang MySQL */
                "UPDATE xcart_pc_options SET last_mc_acc_products_count = :last_mc_acc_products_count WHERE storefrontid = :storefrontid",
                ['last_mc_acc_products_count' => $mcAccCount, 'storefrontid' => $storefrontid]);
        }
    }
}

db_query_param(/** @lang MySQL */"UPDATE xcart_config SET value='N' WHERE name='cron_pc_launched'", []);
db_query_param(/** @lang MySQL */"UPDATE xcart_config SET value='' WHERE name='cron_pc_launched_storefrontid'", []);

print"<br />DONE!";