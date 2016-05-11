<?php
global $xcart_dir;
require_once $xcart_dir."/include/class/classCloneData.php";

class classManufacturer extends classCloneData
{

    public function __construct()
    {   parent::__construct();
        $this->init();
    }

    public function init()
    {
        $this->sPrimaryTable = "manufacturers";
        $this->sPrimaryKeyFiled = "manufacturerid";

        $this->arrCloneTableStructure[] = array("table" => $this->sPrimaryTable,"key_field" => $this->sPrimaryKeyFiled, "primary_key" => $this->sPrimaryKeyFiled);
        $this->arrCloneTableStructure[] = array("table" => "shipping_rates","key_field" => $this->sPrimaryKeyFiled, "primary_key" =>"rateid");
        $this->arrCloneTableStructure[] = array("table" => "manufacturers_lng","key_field" => $this->sPrimaryKeyFiled, "primary_key" =>$this->sPrimaryKeyFiled);
        $this->arrCloneTableStructure[] = array("table" => "distributor_contacts","key_field" => $this->sPrimaryKeyFiled, "primary_key" =>"id");
        $this->arrCloneTableStructure[] = array("table" => "distributor_return_address","key_field" => $this->sPrimaryKeyFiled, "primary_key" =>"id");
        $this->arrCloneTableStructure[] = array("table" => "images_M","key_field" => "id", "primary_key" =>"imageid");

        $this->arrCheckFields[$this->sPrimaryTable] = array('manufacturerid',
            'manufacturer',
            'url',
            'descr',
            'orderby',
            'provider',
            'avail',
            'manufact_text_displayed',
            'mess_body',
            'email',
            'submit_to_operator',
            'm_address',
            'm_address_2',
            'm_city',
            'm_country',
            'm_state',
            'm_zipcode',
            'cart_manufact_text_displayed',
            'code',
            'catalog_sku',
            'catalog_price',
            'catalog_text',
            'cost_to_us_coef_x',
            'price_coef_x',
            'price_coef_y',
            'price_coef_z',
            'map_price_coef_x',
            'reverse_sku',
            'remove_dashes',
            'new_map_price_coef_x',
            'd_product_catalog',
            'd_price_list',
            'd_map_policy',
            'd_map_prices',
            'd_shipping_weights_dimensions',
            'd_website_search_for_sku_url',
            'd_ships_to_within',
            'd_shipping_methods_usps',
            'd_shipping_methods_ups',
            'd_shipping_methods_fedex',
            'd_shipping_methods_trucking_company',
            'd_shipping_methods_other',
            'd_drop_ship_fee_select',
            'd_drop_ship_fee_in_us',
            'd_minimum_order_amount',
            'd_minimum_order_amount_in_us',
            'd_for_orders_below_min_order_amount',
            'd_dealer_discount_reduced_from',
            'd_dealer_discount_reduced_to',
            'd_preferred_way_submit_orders',
            'd_url_to_login_to_distributor_website',
            'd_login',
            'd_password',
            'd_submit_to_order_entry_operator',
            'd_order_entry_operator_email',
            'd_instructions_to_order_entry_operator',
            'd_tax_policy_in_states',
            'd_warranty_starts_when_order_is',
            'd_warranty_last_day',
            'd_re_stocking_fee_for_authorized_returns',
            'd_re_stocking_fee_for_unauthorized_returns',
            'd_we_pay_to_distributor_by',
            'd_net_payment_terms_in_days',
            'd_bulk_or_individual_order_payments',
            'd_our_dealer_account_n',
            'd_available_on_distributor_site_checkbox',
            'd_sent_by_email_to',
            'd_put_on_the_invoices',
            'd_invoices_sent_by_email_to',
            'd_invoices_sent_by_fax_to',
            'd_invoices_mailed_to_our_checkbox',
            'd_available_on_distributor_site_url',
            'd_sent_by_email_to_email_address',
            'd_invoices_sent_to',
            'd_invoices_by_fax_sent_to',
            'd_invoices_mailed_to_our',
            'd_availability_must_be_checked',
            'd_send_to_email_14',
            'd_message_body_14',
            'd_email_subject_14',
            'd_link_to_order_distributors_website',
            'd_sec14_show_header',
            'd_sec14_show_items_stock',
            'd_sec14_show_shipto',
            'd_sec14_show_items_cost',
            'd_sec14_show_footer',
            'lead_time_message',
            'd_send_to_email_for_templates',
            'd_server_min_distributor_time',
            'd_contact_name_for_templates',
            'd_product_questions_send_to_email',
            'd_shipping_options',
            'd_specific_instructions',
            'd_subject_line_8',
            'd_order_entry_operator_subject_line_8',
            'd_main_sf',
            'd_enable_feed',
            'd_feed_updation_frequency',
            'd_ftp_host',
            'd_ftp_login',
            'd_ftp_password',
            'd_ftp_folder',
            'd_feed_procedure_id',
            'd_product_management_team_email',
            'd_most_recent_feed_updation_date',
            'd_distributor_return_policy',
            'product_feeds_comments',
            'd_last_feed_rows_processed',
            'd_validation_threshold',
            'supplier_products_price_multiplier',
            'd_search_keyphrase_for_reconciliation',
            'd_pay_to_distributor_by',
            'd_we_can_save',
            'd_pay_to_distributor_save_text',
            'update_approximation_shipping_rates',
            'shipping_rates_last_update_date',
            'USE_MY_UPS_FEDEX_ACCOUNT_functionality',
            'products_quantity_behavior',
            'display_quantity_of',
            'USE_MY_TRUCKING_ACCOUNT_functionality',
            'allow_pre_orders',
            'amazon_leadtimetoship',
            'd_dispatch_instructions',
            'add_ca_status_id',
            'warehouse_pickups_are_allowed',
            'd_product_questions_send_to_name',
            'd_product_questions_send_to_phone',
            'allow_dispatch_off_working_hours',
            'add_cost_to_us_column_to_dispatch_message',
            'distributor_offers_free_shipping',
            'free_shipping_on_orders_over_value',
            'dcad_bank_name',
            'dcad_address',
            'dcad_address_2',
            'dcad_city',
            'dcad_country',
            'dcad_state',
            'dcad_zipcode',
            'dcad_company_name',
            'dcad_routing_number',
            'dcad_account_number',
            'parent_manufacturer_id',
            'root_categoryid_for_cloned_products');

        $this->arrCheckFields['shipping_rates']  = array('rateid',
            'shippingid',
            'zoneid',
            'maxamount',
            'minweight',
            'maxweight',
            'mintotal',
            'maxtotal',
            'rate',
            'item_rate',
            'weight_rate',
            'rate_p',
            'provider',
            'type',
            'manufacturerid',
            'cost_marcup',
            'real_drop_ship_fee');

        $this->arrCheckFields['manufacturers_lng'] = array('manufacturerid',
            'code',
            'manufacturer',
            'descr');

        $this->arrCheckFields['distributor_contacts'] = array('id',
            'manufacturerid',
            'distributor_field_code',
            'distributor_field_name',
            'contact_name',
            'email',
            'phone',
            'ext',
            'fax',
            'pq');

        $this->arrCheckFields['distributor_return_address'] = array('id',
            'manufacturerid',
            'warehouse_name',
            'full_name',
            'company',
            'address',
            'address_2',
            'city',
            'country',
            'state',
            'zipcode',
            'email',
            'phone',
            'ext',
            'fax');

        $this->arrCheckFields['images_M'] = array('imageid',
            'id',
            'image',
            'image_path',
            'image_type',
            'image_x',
            'image_y',
            'image_size',
            'filename',
            'date',
            'alt',
            'avail',
            'orderby',
            'md5');

    }

    public function getManufacturerByCode($sManufacturerCode) {
        return func_query_first("SELECT * FROM ".$this->sql_tbl[$this->sPrimaryTable]." WHERE code = '$sManufacturerCode'");
    }

    private function getCloneManufacturerPrefix ($oldPrefix, $storePrefix) {
        return $oldPrefix."-".$storePrefix;
    }

    private function getCloneManufacturerName ($oldName, $Prefix) {
        return $oldName." (".$Prefix.")";
    }

    private function checkManufacturerCodeExists ($sManufacturerCode) {
        return (count($this->getManufacturerByCode($sManufacturerCode))) > 0;
    }

    public function cloneManufacturer($aOriginalManufacturer, $aCloneParams = array()) {

        if (!$this->checkDBChanges()) {
            $this->message[] = "lb_copy_manufacturer_fieldsets_changed";
            return false;
        }

        if (empty($aCloneParams["d_main_sf"])) {
            $this->message[] = "lb_copy_manufacturer_same_sf";
            return false;
        }

        if (empty($aCloneParams["root_categoryid_for_cloned_products"])) $aCloneParams["root_categoryid_for_cloned_products"] = 0;
        $aCloneParams['code'] = $this->getCloneManufacturerPrefix($aOriginalManufacturer["code"],$aCloneParams["sf_prefix"]);

        if ($this->checkManufacturerCodeExists($aCloneParams['code'])) {
            $this->message[] = "lb_copy_manufacturer_already_exist";
            return false;
        }

        $aCloneParams['manufacturer'] = $this->getCloneManufacturerName($aOriginalManufacturer["manufacturer"],$aCloneParams["sf_prefix"]);

        $this->primaryKeyValue = $this->DublicatePrimaryTable($aCloneParams);

        if ($this->primaryKeyValue) {
            $aCloneParams[$this->sPrimaryKeyFiled] = $this->primaryKeyValue;
            unset($aCloneParams['code']); // TODO проверять одинаковые названия полей в разных таблицах. используется также в xcart_manufacturers_lng
            $aCloneParams['provider'] = 'master'; //TODO для таблицы shipping_rates
            $this->DublicateNonPrimaryTable($aCloneParams);
            return $this->primaryKeyValue;
        }
        return true;
    }

    public function getMainufacturersInfo($aManufacturersId = array()) {
        $aRes = false;
        $globalLanguage = empty($GLOBALS['shop_language'])?"US":$GLOBALS['shop_language'];
        if (isset($aManufacturersId) && !empty($aManufacturersId))
            $aRes = func_query("SELECT m.".$this->sPrimaryKeyFiled.", m.code, ml.manufacturer, d_main_sf, sf.domain,  root_categoryid_for_cloned_products, parent_manufacturer_id
                      FROM ".$this->sql_tbl[$this->sPrimaryTable]." m
                      LEFT JOIN ".$this->sql_tbl['storefronts']." sf ON (sf.storefrontid = m.d_main_sf)
                      LEFT JOIN ".$this->sql_tbl['manufacturers_lng']." ml ON (m.".$this->sPrimaryKeyFiled." = ml.".$this->sPrimaryKeyFiled.")
                      WHERE m.".$this->sPrimaryKeyFiled." IN(".implode(',',$aManufacturersId).") AND ml.code = '".$globalLanguage."'");  //TODO remove GLOBALS
        return $aRes;
    }

    public function getChildrenManufacturers ($iManufacturerId){
        $aManufacturer = func_query("SELECT ".$this->sPrimaryKeyFiled." FROM ".$this->sql_tbl[$this->sPrimaryTable]." WHERE parent_manufacturer_id = ".$iManufacturerId);

        if (isset($aManufacturer) && is_array($aManufacturer) && !empty($aManufacturer)) {
            $aParents = array();

            foreach ($aManufacturer as $oManufacturer) {
                $aParents[] = $oManufacturer[$this->sPrimaryKeyFiled];
            }
            return $this->getMainufacturersInfo($aParents);
        }
        return false;
    }

    public function getParentManufacturers ($iManufacturerId){
        $aManufacturer = func_query("SELECT parent_manufacturer_id FROM ".$this->sql_tbl[$this->sPrimaryTable]." WHERE ".$this->sPrimaryKeyFiled." = ".$iManufacturerId);
        if (isset($aManufacturer) && is_array($aManufacturer) && !empty($aManufacturer)) {
            $aParents = array();

            foreach ($aManufacturer as $oManufacturer) {
                $aParents[] = $oManufacturer['parent_manufacturer_id'];
            }
            return $this->getMainufacturersInfo($aParents);
        }
        return false;
    }

    public function getProductsByManufacturer($iManufacturerid) {
        return func_query("SELECT * FROM ".$this->sql_tbl['products']." WHERE forsale = 'Y' AND manufacturerid = $iManufacturerid");
    }

    public function addProductsToQueue ($iManufacturerid, $product_to_copy_manufacturer) {
        $countProducts = 0;
        $aProducts = $this->getProductsByManufacturer($iManufacturerid);
        foreach($aProducts as $oProduct) {
            $aParams = array('productid' => $oProduct['productid'], 'clone'=> 'Y', 'insert_datetime'=> time(), 'manufacturerid' => $product_to_copy_manufacturer);
            func_array2insert('clone_products_queue', $aParams, true);
            $countProducts++;
        }

        return $countProducts;

    }

    public function getStoreFronInfo ($iStoreFrontId) {
        return func_query_first("SELECT xs.*, xs1.value AS sfprefix
              FROM ".$this->sql_tbl['storefronts']." xs
                   LEFT JOIN ".$this->sql_tbl['storefronts_config']." xs1 USING (storefrontid)
             WHERE xs.storefrontid = $iStoreFrontId AND xs1.name = 'opt_order_prefix'");
    }
}