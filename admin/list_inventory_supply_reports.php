<?php
require "./auth.php";
require $xcart_dir . "/include/security.php";

$location[] = array("List inventory supply report", "");

if ($REQUEST_METHOD == 'POST') {
    $resTxt = null;
    if (empty($useQueryStartDateTime)) {
        $xcart_query = <<<SQL
Select P.productcode, P.productid
From xcart_k.xcart_products P
where P.forsale = 'Y' and (P.amazon_enabled = 'Y' or P.amazon_fba = 'Y')
UNION 
select missing_productcode as productcode, productid from xcart_fba_missing_sku
SQL;
        $products = func_query($xcart_query);

        $max_products2 = 50;
        $products_arr2 = array();
        $tmp_counter2 = 0;
        $products_arr_key2 = 0;

        foreach ($products as $product) {

            $products_arr2[$products_arr_key2][] = $product["productcode"];

            $tmp_counter2++;

            if ($tmp_counter2 == $max_products2) {
                $products_arr_key2++;
                $tmp_counter2 = 0;
            }
        }
    } else {
        $products_arr2[] = '';
    }

    $oAmasonRecomendation = new \Xcart\AmazonMWS('FBAInventoryServiceMWS_Client', '/FulfillmentInventory/2010-10-01');

    foreach ($products_arr2 as $products) {
        $NextToken = "start";

        while (!empty($NextToken)) {
            $dom_xml_arr = [];

            if ($NextToken == "start") {

                $request = new FBAInventoryServiceMWS_Model_ListInventorySupplyRequest();
                $request->setSellerId(MERCHANT_ID);
                $sellerSKUs = new FBAInventoryServiceMWS_Model_SellerSkuList();
                $sellerSKUs->setmember($products);


                if (!empty($ResponseGroup)) {
                    $request->setResponseGroup($ResponseGroup);
                }
                if (!empty($useQueryStartDateTime)) {
                    $oDate = (new DateTime())->sub(new \DateInterval('P' . (int)$QueryStartDateTime . 'D'));
                    $request->setQueryStartDateTime($oDate->format(\DateTime::ISO8601));
                } else {
                    $request->setSellerSkus($sellerSKUs);
                }
                $dom_xml = $oAmasonRecomendation->invokeListInventorySupply($request);

                while (!empty($dom_xml["Caught_Exception"]) && $dom_xml["Caught_Exception"] == "Request is throttled" && $dom_xml["Response_Status_Code"] == "503") {
                    sleep('60');

                    $request = new FBAInventoryServiceMWS_Model_ListInventorySupplyRequest();
                    $request->setSellerId(MERCHANT_ID);
                    $sellerSKUs = new FBAInventoryServiceMWS_Model_SellerSkuList();
                    $sellerSKUs->setmember($products);
                    $request->setSellerSkus($sellerSKUs);
                    // object or array of parameters
                    $dom_xml = $oAmasonRecomendation->invokeListInventorySupply($request);
                }
                if (!is_array($dom_xml)) {
                    $resTxt .= $dom_xml;
                }

                $dom_xml_arr = func_xml2hash($dom_xml, "UTF-8");

                if (!empty($dom_xml_arr) && !empty($dom_xml_arr["ListInventorySupplyResponse"]["ListInventorySupplyResult"]["NextToken"])) {
                    $NextToken = $dom_xml_arr["ListInventorySupplyResponse"]["ListInventorySupplyResult"]["NextToken"];
                } else {
                    $NextToken = "";
                }
            } elseif (!empty($NextToken)) {
                $request = new FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenRequest();
                $request->setSellerId(MERCHANT_ID);
                $request->setNextToken($NextToken);

                // object or array of parameters
                $dom_xml = $oAmasonRecomendation->invokeListInventorySupplyByNextToken($request);
                while (!empty($dom_xml["Caught_Exception"]) && $dom_xml["Caught_Exception"] == "Request is throttled" && $dom_xml["Response_Status_Code"] == "503"){
                    sleep('60');

                    $request = new FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenRequest();
                    $request->setSellerId(MERCHANT_ID);
                    $request->setNextToken($NextToken);

                    // object or array of parameters
                    $dom_xml = $oAmasonRecomendation->invokeListInventorySupplyByNextToken($request);
                }

                if (!is_array($dom_xml)) {
                    $resTxt .= $dom_xml;
                }
                $dom_xml_arr = func_xml2hash($dom_xml, "UTF-8");

                if (!empty($dom_xml_arr["ListInventorySupplyByNextTokenResponse"]["ListInventorySupplyByNextTokenResult"]["NextToken"])){
                    $NextToken = $dom_xml_arr["ListInventorySupplyByNextTokenResponse"]["ListInventorySupplyByNextTokenResult"]["NextToken"];
                }
                else {
                    $NextToken = "";
                }
            }
        }

    }


    $file_name = "List_inventory_supply_report_" . time();
    header("Content-type: text/plain");
    header("Content-Disposition: attachment;filename={$file_name}.txt");
    header("Content-Transfer-Encoding: binary");
    header('Pragma: no-cache');
    header('Expires: 0');
    print html_entity_decode($resTxt);

} else {

    $smarty->assign("main", "list_inventory_supply_report");

    include $xcart_dir . "/modules/gold_display.php";

    func_display("admin/home.tpl", $smarty);
}