-- CreateTable
CREATE TABLE `a_coeff` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `d_shiiping_a_coeff` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `d_shipping_b_coeff` DECIMAL(12, 2) NOT NULL DEFAULT 1.00,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `account_addresses` (
    `address_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `full_name` VARCHAR(50) NULL,
    `country` CHAR(2) NOT NULL,
    `phone_number` VARCHAR(50) NOT NULL,
    `street` VARCHAR(50) NOT NULL,
    `detailed` VARCHAR(50) NULL,
    `city` VARCHAR(50) NOT NULL,
    `state` INTEGER NULL,
    `zip` VARCHAR(50) NOT NULL,
    `is_default` TINYINT NULL,
    `delivery_type_id` INTEGER UNSIGNED NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `address_type` CHAR(50) NULL DEFAULT 'shipping',
    `phone_ext` VARCHAR(10) NULL,

    INDEX `FK_account_addresses_account_delivery_types`(`delivery_type_id`),
    INDEX `FK_account_addresses_xcart_countries`(`country`),
    INDEX `FK_account_addresses_xcart_states`(`state`),
    INDEX `FK_account_addresses_xcart_users`(`user_id`),
    PRIMARY KEY (`address_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `account_credit_cards` (
    `credit_card_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NULL,
    `address_id` INTEGER UNSIGNED NOT NULL,
    `is_default` TINYINT NULL DEFAULT 0,
    `card_number` VARCHAR(50) NULL,
    `card_type` VARCHAR(50) NULL,
    `expires` VARCHAR(250) NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,

    INDEX `FK_account_credit_cards_account_addresses`(`address_id`),
    INDEX `FK_account_credit_cards_xcart_users`(`user_id`),
    PRIMARY KEY (`credit_card_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `account_decisions` (
    `decision_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `type` VARCHAR(63) NOT NULL,
    `solved` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `options` JSON NULL,
    `order_id` INTEGER NOT NULL,
    `order_number` VARCHAR(31) NULL,
    `created` DATETIME(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
    `updated` DATETIME(0) NULL DEFAULT CURRENT_TIMESTAMP(0),

    INDEX `account_decision_xcart_orders_orderid_fk`(`order_id`),
    PRIMARY KEY (`decision_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `account_delivery_types` (
    `delivery_type_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,

    PRIMARY KEY (`delivery_type_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `account_list_ideas` (
    `product_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(256) NULL,

    PRIMARY KEY (`product_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `account_list_items` (
    `list_items_id` INTEGER NOT NULL AUTO_INCREMENT,
    `product_id` INTEGER NOT NULL,
    `product_list_id` INTEGER UNSIGNED NOT NULL,
    `order_by` MEDIUMINT UNSIGNED NOT NULL DEFAULT 999999,
    `product_type` VARCHAR(50) NULL DEFAULT 'product',
    `comment` VARCHAR(256) NULL,
    `priority` ENUM('lwst', 'low', 'med', 'high', 'hgst') NULL,
    `needs` VARCHAR(50) NULL,
    `has` VARCHAR(50) NULL,
    `add_date` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    INDEX `FK_account_list_items_account_product_lists`(`product_list_id`),
    UNIQUE INDEX `account_list_items_product_id_product_list_id_uindex`(`product_id`, `product_list_id`),
    PRIMARY KEY (`list_items_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `account_order_cancel_items` (
    `request_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_item_id` INTEGER NULL,
    `amount` INTEGER NULL,

    PRIMARY KEY (`request_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `account_order_cancel_requests` (
    `request_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `request_open_time` TIMESTAMP(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
    `order_id` INTEGER NOT NULL,
    `cancel_text` VARCHAR(250) NULL,

    INDEX `FK_account_order_cancel_requests_xcart_orders`(`order_id`),
    PRIMARY KEY (`request_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `account_order_problems` (
    `problem_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `status_id` INTEGER UNSIGNED NULL DEFAULT 1,
    `order_id` INTEGER NULL DEFAULT 0,
    `problem_text` MEDIUMTEXT NULL,
    `created_at` TIMESTAMP(0) NULL DEFAULT CURRENT_TIMESTAMP(0),

    INDEX `FK_account_order_problems_account_order_problems_statuses`(`status_id`),
    PRIMARY KEY (`problem_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `account_order_problems_statuses` (
    `status_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `status_text` VARCHAR(50) NOT NULL DEFAULT '0',

    PRIMARY KEY (`status_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `account_product_lists` (
    `product_list_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `public` TINYINT NOT NULL DEFAULT 1,
    `name` VARCHAR(50) NOT NULL,
    `description` VARCHAR(300) NULL,
    `recipient_name` VARCHAR(50) NULL,
    `recipient_email` VARCHAR(50) NULL,
    `birthday` VARCHAR(50) NULL,
    `cache_url` VARCHAR(50) NULL,
    `address_id` INTEGER UNSIGNED NULL,

    PRIMARY KEY (`product_list_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `account_transactions` (
    `transaction_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `credit_card_id` INTEGER UNSIGNED NOT NULL,
    `order_id` INTEGER NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,

    INDEX `FK_account_transactions_account_credit_cards`(`credit_card_id`),
    INDEX `FK_account_transactions_xcart_orders`(`order_id`),
    INDEX `FK_account_transactions_xcart_users`(`user_id`),
    PRIMARY KEY (`transaction_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `account_user_list` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `product_list_id` INTEGER UNSIGNED NOT NULL,
    `role` VARCHAR(50) NULL DEFAULT 'owner',
    `list_type` VARCHAR(50) NULL DEFAULT 'private',
    `source` ENUM('default', 'simple') NULL DEFAULT 'simple',

    INDEX `FK_account_user_list_account_product_lists`(`product_list_id`),
    INDEX `FK_account_user_list_xcart_users`(`user_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `admin_config` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `module` VARCHAR(255) NOT NULL DEFAULT '',
    `admin` VARCHAR(255) NOT NULL DEFAULT '',
    `user_id` INTEGER NOT NULL,
    `columns` TEXT NULL,
    `page_size` INTEGER NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `amazon_inventory_queue` (
    `product_id` INTEGER NOT NULL,
    `type` ENUM('FBA', 'MFN') NOT NULL DEFAULT 'MFN',
    `created_at` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    PRIMARY KEY (`product_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `amazon_offers` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `ASIN` VARCHAR(15) NOT NULL,
    `myPrice` DECIMAL(12, 2) NULL,
    `lowest_LandedPrice` DECIMAL(12, 2) NULL,
    `lowest_ListingPrice` DECIMAL(12, 2) NULL,
    `lowest_Shipping` DECIMAL(12, 2) NULL,
    `lowest_Channel` ENUM('FBA', 'MFN') NULL,
    `buybox_LandedPrice` DECIMAL(12, 2) NULL,
    `buybox_ListingPrice` DECIMAL(12, 2) NULL,
    `buybox_Shipping` DECIMAL(12, 2) NULL,
    `buybox_Channel` ENUM('FBA', 'MFN') NULL,
    `offers` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `offer_change_time` DATETIME(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    `is_buybox_my` BOOLEAN NOT NULL DEFAULT false,
    `sales_rank` INTEGER UNSIGNED NULL,
    `fba_total_supply` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `fba_instock_supply` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `fba_days_of_supply` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `FNSKU` VARCHAR(15) NULL,
    `updated_at` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    `created_at` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    UNIQUE INDEX `ASIN`(`ASIN`),
    INDEX `is_buybox_my`(`is_buybox_my`),
    INDEX `offer_change_time`(`offer_change_time`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `amazon_offers_competitors` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `offer_id` INTEGER UNSIGNED NOT NULL,
    `seller` VARCHAR(50) NOT NULL,
    `rating` INTEGER NOT NULL DEFAULT 0,
    `LandingPrice` DOUBLE NULL,
    `ListingPrice` DOUBLE NULL,
    `Shipping` DOUBLE NULL,
    `minimumHours` SMALLINT UNSIGNED NULL,
    `maximumHours` SMALLINT UNSIGNED NULL,
    `availabilityType` ENUM('NOW', 'FUTURE_WITHOUT_DATE', 'FUTURE_WITH_DATE') NULL,
    `availableDate` DATETIME(0) NULL,
    `country` CHAR(2) NULL,
    `state` CHAR(2) NULL,
    `channel` ENUM('FBA', 'MFN') NULL,
    `is_buybox` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `updated_at` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    `created_at` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    INDEX `seller_channel`(`seller`, `channel`),
    UNIQUE INDEX `offer_id_seller`(`offer_id`, `seller`, `channel`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `amazon_offers_stock_by_date` (
    `offer_id` INTEGER UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `quantity` SMALLINT UNSIGNED NOT NULL,

    PRIMARY KEY (`offer_id`, `date`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `amazon_price_queue` (
    `product_id` INTEGER NOT NULL,
    `type` ENUM('FBA', 'MFN', 'SFN') NOT NULL DEFAULT 'MFN',
    `created_at` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    PRIMARY KEY (`product_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `amazon_products` (
    `item_sku` VARCHAR(50) NOT NULL DEFAULT 'zzzzz',
    `enabled` VARCHAR(1) NULL,

    UNIQUE INDEX `idxItemSku`(`item_sku`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `amazon_reorder_batch` (
    `batch_id` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` INTEGER NULL,
    `created_at` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    `status` ENUM('done', 'processing', 'lock') NOT NULL DEFAULT 'processing',
    `assortment` ENUM('Y', 'N') NOT NULL DEFAULT 'Y',
    `link` VARCHAR(255) NULL,

    PRIMARY KEY (`batch_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `amazon_reorder_batch_data` (
    `batch_id` INTEGER NOT NULL DEFAULT 0,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `productid` INTEGER NOT NULL DEFAULT 0,
    `UPC` VARCHAR(64) NULL,
    `SKU` VARCHAR(64) NULL,
    `ASIN` VARCHAR(64) NULL,
    `cost_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `amazon_fba` CHAR(1) NOT NULL DEFAULT 'N',
    `total_stock` INTEGER NOT NULL DEFAULT 0,
    `last_order_days` INTEGER NULL,
    `items_sold_last_1m` INTEGER NULL,
    `instock_days_3m` INTEGER NULL,
    `items_sold_last_1m_of_stock` INTEGER NULL,
    `instock_days_1m` INTEGER NULL,
    `overall_orders_rate` DECIMAL(14, 4) NOT NULL DEFAULT 0.0000,
    `orders_rate_last_1_month` DECIMAL(14, 4) NOT NULL DEFAULT 0.0000,
    `price` DECIMAL(18, 2) NULL,
    `r_avail` MEDIUMINT NOT NULL DEFAULT 0,
    `restocking_qty` INTEGER NOT NULL DEFAULT 0,
    `min_fba_price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `avg_comp_price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `lowest_price` DECIMAL(12, 2) NULL,
    `buy_box_price` DECIMAL(12, 2) NULL,
    `ads_a` DECIMAL(12, 5) NOT NULL DEFAULT 0.00000,
    `ads_x` DECIMAL(12, 5) NOT NULL DEFAULT 0.00000,
    `sales_rank` INTEGER NULL,

    INDEX `FK_amazon_reorder_batch_data_xcart_manufacturers`(`manufacturerid`),
    INDEX `FK_amazon_reorder_batch_data_xcart_products`(`productid`),
    INDEX `cost_to_us`(`cost_to_us`),
    PRIMARY KEY (`batch_id`, `productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `anveo_calls` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `anveo_account` VARCHAR(32) NULL,
    `session` VARCHAR(255) NOT NULL,
    `file` VARCHAR(255) NULL,
    `cname` VARCHAR(255) NULL,
    `e164` VARCHAR(255) NULL,
    `rdnis` VARCHAR(255) NULL,
    `start_at` DATETIME(0) NULL,
    `end_at` DATETIME(0) NULL,
    `is_lost` BOOLEAN NOT NULL DEFAULT false,
    `is_outgoing` BOOLEAN NOT NULL DEFAULT false,
    `is_voice_mail` BOOLEAN NOT NULL DEFAULT false,
    `user_id` INTEGER UNSIGNED NULL,

    INDEX `FK_anveo_calls_xcart_pbx_options`(`anveo_account`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `anveo_calls_listens` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `call_id` INTEGER UNSIGNED NOT NULL,
    `user_id` INTEGER UNSIGNED NOT NULL,
    `listen_at` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    INDEX `FK_anveo_calls_listens_anveo_calls`(`call_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `area_codes` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(50) NULL,
    `state` VARCHAR(50) NULL,
    `c1` VARCHAR(50) NULL,
    `c2` VARCHAR(50) NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `cart_cart` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `data` LONGTEXT NULL,
    `created_at` DATETIME(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `cart_coupon_kit` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `uses_per_user` INTEGER UNSIGNED NOT NULL DEFAULT 1,
    `deleted` BOOLEAN NOT NULL DEFAULT false,
    `active` BOOLEAN NOT NULL DEFAULT false,
    `type` BOOLEAN NOT NULL,
    `code` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `discount` DECIMAL(10, 3) NOT NULL,
    `max_discount` DECIMAL(10, 3) NOT NULL,
    `created_at` DATETIME(0) NULL,
    `updated_at` DATETIME(0) NULL,
    `description` TEXT NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `cart_coupon_orders` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_id` INTEGER NULL,
    `coupon_id` INTEGER NOT NULL,
    `login` VARCHAR(30) NOT NULL DEFAULT '',
    `created_at` DATETIME(0) NOT NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `cart_coupon_restriction` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `coupon_id` INTEGER NOT NULL,
    `class` VARCHAR(255) NULL,
    `data` TEXT NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `cat_categories` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `categoryid` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `parent_category` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `products_count` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `newpath1` BIGINT UNSIGNED NULL,
    `newpath2` BIGINT UNSIGNED NULL,
    `clevel` BIGINT UNSIGNED NULL,

    INDEX `idx_categoryid`(`categoryid`),
    INDEX `idx_categoryid_parent_category`(`categoryid`, `parent_category`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `cat_options` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `productoption` VARCHAR(100) NULL,
    `productid` INTEGER NULL,
    `option_no` INTEGER NULL,

    INDEX `idxOption`(`productoption`),
    INDEX `idx_Option`(`productoption`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `cidev_aaj_units` (
    `uom` VARCHAR(50) NOT NULL,
    `supplierid` VARCHAR(50) NOT NULL DEFAULT '',
    `sample` VARCHAR(2600) NOT NULL DEFAULT '',

    INDEX `idx_supplierid`(`supplierid`),
    INDEX `idx_uom`(`uom`),
    UNIQUE INDEX `uom`(`uom`, `supplierid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `cidev_amazon_shipping_rates` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `tier` CHAR(100) NULL DEFAULT '',
    `delivery` CHAR(100) NULL DEFAULT '',
    `shippingid` INTEGER NULL,
    `order_fee` DECIMAL(18, 2) NULL DEFAULT 0.00,
    `pickpack_per_unit` DECIMAL(18, 2) NULL DEFAULT 0.00,
    `wrange1` DECIMAL(18, 2) NULL DEFAULT 0.00,
    `wrange1_l1` DECIMAL(18, 2) NULL DEFAULT 0.00,
    `wrange2` DECIMAL(18, 2) NULL DEFAULT 0.00,
    `wrange2_l1` DECIMAL(18, 2) NULL DEFAULT 0.00,
    `wrange3` DECIMAL(18, 2) NULL DEFAULT 0.00,
    `wrange3_l1` DECIMAL(18, 2) NULL DEFAULT 0.00,
    `dunnage` DECIMAL(18, 2) NULL DEFAULT 0.10,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `collaborative_fitering` (
    `session_id` INTEGER UNSIGNED NOT NULL,
    `product_id` INTEGER NOT NULL,
    `rank` INTEGER UNSIGNED NOT NULL,

    INDEX `FK_collaborative_fitering_xcart_products`(`product_id`),
    PRIMARY KEY (`session_id`, `product_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `core_cron` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `active` INTEGER NOT NULL DEFAULT 0,
    `is_run` INTEGER NOT NULL DEFAULT 0,
    `run_force` INTEGER NOT NULL DEFAULT 0,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `command` VARCHAR(255) NOT NULL DEFAULT '',
    `schedule` VARCHAR(255) NOT NULL DEFAULT '',
    `log_file` VARCHAR(255) NOT NULL DEFAULT '',
    `run_start` DATETIME(0) NULL,
    `run_end` DATETIME(0) NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `core_static_notification` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `active` BOOLEAN NOT NULL DEFAULT false,
    `bg_color` VARCHAR(30) NOT NULL DEFAULT '#58af42',
    `text_color` VARCHAR(30) NOT NULL DEFAULT '#ffffff',
    `title` VARCHAR(255) NULL DEFAULT '',
    `description` TEXT NOT NULL,
    `start_at` DATETIME(0) NULL,
    `end_at` DATETIME(0) NULL,
    `storefront_id` INTEGER NULL,

    INDEX `FK_core_static_notification_xcart_storefronts`(`storefront_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `customer_files` (
    `file_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `path` VARCHAR(255) NULL,
    `original_name` VARCHAR(255) NULL,
    `created` DATETIME(0) NULL DEFAULT CURRENT_TIMESTAMP(0),

    PRIMARY KEY (`file_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `decision_licenses` (
    `decision_license_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `decision_id` INTEGER UNSIGNED NOT NULL,
    `path` VARCHAR(255) NOT NULL,

    INDEX `decision_licenses_account_decisions_decision_id_fk`(`decision_id`),
    PRIMARY KEY (`decision_license_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `decisions_customer_files` (
    `decision_id` INTEGER UNSIGNED NULL,
    `file_id` INTEGER UNSIGNED NULL,

    INDEX `decisions_customer_files_customer_files_file_id_fk`(`file_id`),
    INDEX `decisions_files_account_decisions_decision_id_fk`(`decision_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `faxes_fax` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `fax_id` INTEGER NOT NULL,
    `fax_date` DATETIME(0) NOT NULL,
    `fax_from` VARCHAR(50) NOT NULL,
    `fax_to` VARCHAR(50) NOT NULL,
    `filename` VARCHAR(50) NOT NULL,
    `order_id` INTEGER NULL,
    `path` VARCHAR(255) NULL,
    `is_active` TINYINT NOT NULL DEFAULT 1,
    `pagecount` SMALLINT UNSIGNED NULL,

    UNIQUE INDEX `fax_id`(`fax_id`),
    INDEX `FK_faxes_fax_xcart_orders`(`order_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `forms_email` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `message_id` VARCHAR(50) NOT NULL,
    `thread_id` VARCHAR(50) NULL,
    `subject` MEDIUMTEXT NULL,
    `snippet` MEDIUMTEXT NULL,
    `type` ENUM('inbox', 'sent', 'draft') NOT NULL DEFAULT 'inbox',
    `from_address` VARCHAR(255) NULL,
    `delivered_to_address` VARCHAR(255) NULL,
    `to_address` MEDIUMTEXT NULL,
    `reply_to` VARCHAR(255) NULL,
    `date` DATETIME(0) NOT NULL,
    `account_id` INTEGER UNSIGNED NOT NULL,
    `contains_action` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `original_sender` VARCHAR(255) NULL,

    UNIQUE INDEX `message_id`(`message_id`),
    INDEX `date`(`date`),
    INDEX `thread_id`(`thread_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `forms_email_action` (
    `email_id` INTEGER UNSIGNED NOT NULL,
    `user_id` INTEGER UNSIGNED NOT NULL,
    `date` DATETIME(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    INDEX `FK_forms_email_action_xcart_customers`(`user_id`),
    PRIMARY KEY (`email_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `forms_email_attachment` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `email_id` INTEGER UNSIGNED NOT NULL,
    `attachment` VARCHAR(1024) NULL,
    `filename` VARCHAR(255) NULL,

    INDEX `FK_forms_email_attachment_forms_email`(`email_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `forms_email_body` (
    `email_id` INTEGER UNSIGNED NOT NULL,
    `body` VARCHAR(1024) NULL,

    PRIMARY KEY (`email_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `forms_email_entity` (
    `email_id` INTEGER UNSIGNED NOT NULL,
    `entity_id` INTEGER UNSIGNED NOT NULL,
    `model` ENUM('Modules\\Distributor\\Models\\DistributorModel', 'Modules\\Order\\Models\\OrderModel') NOT NULL,

    PRIMARY KEY (`email_id`, `entity_id`, `model`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `forms_email_favorite` (
    `email_id` INTEGER UNSIGNED NOT NULL,
    `user_id` INTEGER UNSIGNED NOT NULL,

    INDEX `FK_forms_email_user_xcart_customers`(`user_id`),
    PRIMARY KEY (`email_id`, `user_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `forms_email_label` (
    `email_id` INTEGER UNSIGNED NOT NULL,
    `label_id` INTEGER UNSIGNED NOT NULL,

    INDEX `FK_forms_email_label_forms_label`(`label_id`),
    PRIMARY KEY (`email_id`, `label_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `forms_email_sorter` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `entity` ENUM('Modules\\Distributor\\Models\\DistributorModel', 'Modules\\Order\\Models\\OrderModel') NOT NULL,
    `filter_field` VARCHAR(255) NOT NULL,
    `cond` VARCHAR(255) NOT NULL,
    `value` VARCHAR(255) NULL,
    `related_value` VARCHAR(255) NULL,
    `type` ENUM('inbox', 'sent') NULL,
    `target` INTEGER NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `forms_email_user` (
    `email_id` INTEGER UNSIGNED NOT NULL,
    `user_id` INTEGER UNSIGNED NOT NULL,

    INDEX `FK_forms_email_user_xcart_customers`(`user_id`),
    PRIMARY KEY (`email_id`, `user_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `forms_email_viewed` (
    `email_id` INTEGER UNSIGNED NOT NULL,
    `user_id` INTEGER UNSIGNED NOT NULL,

    INDEX `FK_forms_email_user_xcart_customers`(`user_id`),
    PRIMARY KEY (`email_id`, `user_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `forms_label` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `label_id` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `background_color` VARCHAR(7) NULL,
    `color` VARCHAR(7) NULL,
    `type` ENUM('system', 'user') NULL,

    UNIQUE INDEX `label_id`(`label_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `forms_snippet` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `template` MEDIUMTEXT NULL,

    UNIQUE INDEX `code`(`code`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `google_products` (
    `product_id` INTEGER NOT NULL,
    `shopping_status` ENUM('approved', 'disapproved') NULL,

    PRIMARY KEY (`product_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `google_products_stats` (
    `date` DATE NOT NULL,
    `count` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`date`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `helpful_reviews` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `review_id` BIGINT UNSIGNED NULL,
    `user_id` BIGINT UNSIGNED NULL,

    INDEX `helpful_reviews_xcart_users_user_id_fk`(`user_id`),
    UNIQUE INDEX `helpful_reviews_review_id_user_id_uindex`(`review_id`, `user_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `m1_export_category_matching` (
    `feed_id` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `category_id` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `taxonomy_id` VARCHAR(64) NOT NULL DEFAULT '0',

    PRIMARY KEY (`feed_id`, `category_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `m1_export_clickstats` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `time` DATETIME(0) NULL,
    `product_id` INTEGER UNSIGNED NULL,
    `cse_id` VARCHAR(255) NULL,
    `action` VARCHAR(255) NULL,
    `sessid` VARCHAR(32) NULL,
    `order_id` INTEGER NULL,
    `order_quantity` INTEGER NULL,
    `order_total` DECIMAL(8, 2) NULL,
    `referer` VARCHAR(255) NULL,
    `ip` VARCHAR(16) NULL,
    `user_agent` VARCHAR(255) NULL,
    `user_language` VARCHAR(255) NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `meta_meta` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `is_custom` BOOLEAN NOT NULL,
    `title` VARCHAR(200) NOT NULL DEFAULT '',
    `keywords` VARCHAR(200) NULL,
    `description` VARCHAR(200) NULL,
    `url` VARCHAR(255) NOT NULL,
    `site_code` VARCHAR(10) NOT NULL,
    `site_id` INTEGER NULL,

    UNIQUE INDEX `url_site_code`(`site_code`, `url`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `meta_meta_template` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(255) NOT NULL,
    `title` TEXT NOT NULL,
    `description` TEXT NULL,
    `advanced` LONGTEXT NULL,

    UNIQUE INDEX `code`(`code`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `meta_meta_text` (
    `code` VARCHAR(10) NOT NULL DEFAULT '',
    `title` VARCHAR(255) NOT NULL DEFAULT '',
    `text` TEXT NOT NULL,

    PRIMARY KEY (`code`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `order_extra` (
    `order_id` INTEGER NOT NULL,
    `submit_operator_id` INTEGER NULL,
    `payment_operator_id` INTEGER NULL,
    `purchase_order` TEXT NULL,
    `ip` VARCHAR(255) NULL,

    PRIMARY KEY (`order_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `orders_calls` (
    `call_id` INTEGER UNSIGNED NOT NULL,
    `order_id` INTEGER UNSIGNED NOT NULL,
    `relevance_type` INTEGER NOT NULL,
    `relevance_order` INTEGER UNSIGNED NOT NULL,
    `confirmed_user_id` INTEGER NULL,
    `confirmed_at` DATETIME(0) NULL,

    PRIMARY KEY (`call_id`, `order_id`, `relevance_type`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `pages_info_block` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `text` TEXT NULL,
    `tag` VARCHAR(255) NOT NULL,
    `lang_id` MEDIUMINT UNSIGNED NOT NULL DEFAULT 1,

    UNIQUE INDEX `key`(`tag`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `pages_page` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `parent_id` INTEGER NULL,
    `root` INTEGER NULL,
    `lft` INTEGER NULL,
    `rgt` INTEGER NULL,
    `level` INTEGER NULL DEFAULT 0,
    `is_published` BOOLEAN NOT NULL DEFAULT true,
    `is_index` BOOLEAN NOT NULL DEFAULT false,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `url` VARCHAR(255) NOT NULL DEFAULT '',
    `file` VARCHAR(255) NULL,
    `view` VARCHAR(255) NULL,
    `view_children` VARCHAR(255) NULL,
    `sorting` VARCHAR(255) NULL,
    `created_at` DATETIME(0) NULL,
    `updated_at` DATETIME(0) NULL,
    `published_at` DATETIME(0) NULL,
    `content` TEXT NULL,
    `content_short` TEXT NULL,
    `no_index` BOOLEAN NOT NULL DEFAULT false,
    `lang_id` MEDIUMINT UNSIGNED NOT NULL DEFAULT 1,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `pages_pages_storefront_link` (
    `storefront_id` INTEGER NOT NULL,
    `page_id` INTEGER NOT NULL,

    PRIMARY KEY (`storefront_id`, `page_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `payment_methods` (
    `payment_method_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `logo` VARCHAR(512) NOT NULL,
    `is_active` TINYINT NOT NULL DEFAULT 1,
    `position` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,

    PRIMARY KEY (`payment_method_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `pb_GoogleAdwordsStats` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `g_date` DATE NULL,
    `cost` DECIMAL(12, 2) NULL,
    `clicks` INTEGER NULL,
    `impressions` INTEGER NULL,
    `conversions` INTEGER NULL,
    `ctr` DECIMAL(12, 2) NULL,

    INDEX `idx_gdate`(`g_date`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `pb_activeproducts` (
    `productid` INTEGER NOT NULL,
    `netincome1` DECIMAL(12, 2) NULL,
    `netincome0` DECIMAL(12, 2) NULL,
    `saleamount1` INTEGER NULL,
    `saleamount0` INTEGER NULL,
    `minprice` DECIMAL(12, 2) NULL,
    `currentprice` DECIMAL(12, 2) NULL,
    `oldprice` DECIMAL(12, 2) NULL,
    `nextpricestep` DECIMAL(12, 2) NULL,
    `pricedelta` DECIMAL(12, 2) NULL,
    `nextprice` DECIMAL(12, 2) NULL,
    `pricedelta_mult` DECIMAL(12, 2) NULL,
    `mapprice` DECIMAL(12, 2) NULL,
    `cost_to_us` DECIMAL(12, 2) NULL,
    `list_price` DECIMAL(12, 2) NULL,
    `update_date` TIMESTAMP(0) NULL DEFAULT CURRENT_TIMESTAMP(0),

    PRIMARY KEY (`productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `pb_debuglog` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `message` VARCHAR(1000) NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `pb_excluded_products` (
    `productid` INTEGER NOT NULL,

    PRIMARY KEY (`productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `pb_history` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `productid` MEDIUMINT UNSIGNED NULL,
    `pb_datetime` DATETIME(0) NULL,
    `pb_reason` INTEGER NULL,
    `price_old` DECIMAL(12, 2) NULL,
    `price_new` DECIMAL(12, 2) NULL,
    `last_sales_performance` VARCHAR(60) NULL,
    `price_map` DECIMAL(12, 2) NULL,
    `cost_to_us` DECIMAL(12, 2) NULL,
    `dx_price_multiplier` DECIMAL(6, 2) NULL,
    `product_price_multiplier` DECIMAL(6, 2) NULL,

    INDEX `idxPRODUCTID_ID`(`productid`, `id`),
    INDEX `pb_datetime`(`pb_datetime`),
    INDEX `productid`(`productid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `product_reviews` (
    `product_review_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `product_id` INTEGER NULL,
    `header` VARCHAR(128) NOT NULL,
    `body` TEXT NOT NULL,
    `location` CHAR(2) NOT NULL DEFAULT 'US',
    `created` DATETIME(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
    `helpful_total` INTEGER UNSIGNED NOT NULL DEFAULT 0,

    INDEX `product_reviews_xcart_products_productid_fk`(`product_id`),
    INDEX `product_reviews_xcart_users_user_id_fk`(`user_id`),
    PRIMARY KEY (`product_review_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `products_sf_moves` (
    `batch_id` INTEGER NOT NULL,
    `productid` INTEGER NOT NULL,
    `resource_id` INTEGER NOT NULL,
    `resource_type` ENUM('CS', 'SF', 'FL') NOT NULL,
    `resource_extra_value` CHAR(1) NOT NULL DEFAULT '',

    PRIMARY KEY (`batch_id`, `productid`, `resource_id`, `resource_type`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `ratings` (
    `rating_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(256) NOT NULL,
    `slug` VARCHAR(256) NULL,

    UNIQUE INDEX `ratings_slug_uindex`(`slug`),
    PRIMARY KEY (`rating_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `review_ratings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `review_id` BIGINT UNSIGNED NOT NULL,
    `rating_id` BIGINT UNSIGNED NOT NULL,
    `rating` TINYINT UNSIGNED NOT NULL,

    INDEX `review_ratings_product_reviews_product_review_id_fk`(`review_id`),
    INDEX `review_ratings_ratings_rating_id_fk`(`rating_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `s3_employees` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `isCeo` BOOLEAN NOT NULL DEFAULT false,
    `name` VARCHAR(255) NOT NULL,
    `post` VARCHAR(255) NULL,
    `photo` VARCHAR(255) NOT NULL,
    `photo2` VARCHAR(255) NULL,
    `position` INTEGER NOT NULL DEFAULT 9999,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `s3_storefront_config` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `sf_code` VARCHAR(10) NOT NULL DEFAULT '',
    `name` VARCHAR(255) NULL DEFAULT '',
    `description` TEXT NULL,
    `list_icon` VARCHAR(255) NOT NULL DEFAULT '',
    `list_image` VARCHAR(255) NOT NULL DEFAULT '',
    `position` INTEGER NOT NULL DEFAULT 9999,

    UNIQUE INDEX `sf_code`(`sf_code`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `sites_bank_account` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `corporate_id` INTEGER UNSIGNED NULL,
    `bank_name` VARCHAR(255) NULL,
    `account_type` ENUM('Checking', 'Savings') NOT NULL,
    `account_number` VARCHAR(255) NULL,
    `routing_number` VARCHAR(255) NULL,
    `street_address` VARCHAR(255) NULL,
    `street_address_line2` VARCHAR(255) NULL,
    `city` VARCHAR(255) NULL,
    `country` CHAR(2) NULL,
    `state` VARCHAR(9) NULL,
    `zip` VARCHAR(50) NULL,
    `phone` VARCHAR(50) NULL,
    `email` VARCHAR(255) NULL,
    `account_manager_name` VARCHAR(255) NULL,
    `account_manager_phone` VARCHAR(255) NULL,
    `account_manager_email` VARCHAR(255) NULL,
    `url` VARCHAR(255) NULL,
    `login` VARCHAR(255) NULL,
    `password` VARCHAR(255) NULL,

    INDEX `FK_sites_bank_account_sites_corporate`(`corporate_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `sites_corporate` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `country` CHAR(2) NULL,
    `state` INTEGER NULL,
    `registration_number` VARCHAR(255) NULL,
    `incorporation_date` DATE NULL,
    `agent_company_name` VARCHAR(255) NULL,
    `agent_contact_person` VARCHAR(255) NULL,
    `agent_phone` VARCHAR(255) NULL,
    `agent_email` VARCHAR(255) NULL,
    `formal_street_address` VARCHAR(255) NULL,
    `formal_street_address_line2` VARCHAR(255) NULL,
    `formal_city` VARCHAR(255) NULL,
    `formal_country` VARCHAR(2) NULL,
    `formal_state` VARCHAR(9) NULL,
    `formal_zip` VARCHAR(50) NULL,
    `physical_street_address` VARCHAR(255) NULL,
    `physical_street_address_line2` VARCHAR(255) NULL,
    `physical_city` VARCHAR(255) NULL,
    `physical_country` VARCHAR(2) NULL,
    `physical_state` VARCHAR(9) NULL,
    `physical_zip` VARCHAR(50) NULL,
    `mailing_street_address` VARCHAR(255) NULL,
    `mailing_street_address_line2` VARCHAR(255) NULL,
    `mailing_city` VARCHAR(255) NULL,
    `mailing_country` VARCHAR(2) NULL,
    `mailing_state` VARCHAR(9) NULL,
    `mailing_zip` VARCHAR(50) NULL,
    `inc_company_name` VARCHAR(255) NULL,
    `inc_street_address` VARCHAR(255) NULL,
    `inc_street_address_line2` VARCHAR(255) NULL,
    `inc_city` VARCHAR(255) NULL,
    `inc_country` VARCHAR(2) NULL,
    `inc_state` VARCHAR(9) NULL,
    `inc_zip` VARCHAR(50) NULL,
    `inc_phone` VARCHAR(255) NULL,
    `inc_email` VARCHAR(255) NULL,
    `inc_representative_name` VARCHAR(255) NULL,
    `inc_representative_phone` VARCHAR(255) NULL,
    `inc_representative_email` VARCHAR(255) NULL,
    `inc_login_url` VARCHAR(255) NULL,
    `inc_login` VARCHAR(255) NULL,
    `inc_password` VARCHAR(255) NULL,
    `shares` INTEGER UNSIGNED NOT NULL DEFAULT 100000,
    `federal_tax_id_name` VARCHAR(255) NULL,
    `federal_tax_id` VARCHAR(255) NULL,
    `federal_tax_url` VARCHAR(255) NULL,
    `federal_tax_login` VARCHAR(255) NULL,
    `federal_tax_password` VARCHAR(255) NULL,
    `statel_tax_id_name` VARCHAR(255) NULL,
    `state_tax_id` VARCHAR(255) NULL,
    `state_tax_url` VARCHAR(255) NULL,
    `state_tax_login` VARCHAR(255) NULL,
    `state_tax_password` VARCHAR(255) NULL,
    `federal_tax_year` DATE NULL,
    `state_tax_year` DATE NULL,
    `accounting_company_name` VARCHAR(255) NULL,
    `accounting_company_phone` VARCHAR(255) NULL,
    `accounting_company_email` VARCHAR(255) NULL,
    `accountant_name` VARCHAR(255) NULL,
    `accountant_phone` VARCHAR(255) NULL,
    `accountant_email` VARCHAR(255) NULL,
    `secretary_name` VARCHAR(255) NULL,
    `secretary_phone` VARCHAR(255) NULL,
    `secretary_email` VARCHAR(255) NULL,
    `accounting_company_address` VARCHAR(255) NULL,
    `accounting_company_address_line2` VARCHAR(255) NULL,
    `accounting_company_city` VARCHAR(255) NULL,
    `accounting_company_country` CHAR(2) NULL,
    `accounting_company_state` VARCHAR(9) NULL,
    `accounting_company_zip` VARCHAR(50) NULL,
    `income_tax_period_starts_day` TINYINT UNSIGNED NULL,
    `income_tax_period_starts_month` TINYINT UNSIGNED NULL,
    `income_period_duration` ENUM('year', 'quarter', 'month') NULL,
    `sales_tax_period_starts_day` TINYINT UNSIGNED NULL,
    `sales_tax_period_starts_month` TINYINT UNSIGNED NULL,
    `sales_period_duration` ENUM('year', 'quarter', 'month') NULL,
    `vat_tax_period_starts_day` TINYINT UNSIGNED NULL,
    `vat_tax_period_starts_month` TINYINT UNSIGNED NULL,
    `vat_period_duration` ENUM('year', 'quarter', 'month') NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `sites_corporate_storefronts` (
    `storefront_id` INTEGER UNSIGNED NOT NULL,
    `corporate_id` INTEGER UNSIGNED NOT NULL,

    UNIQUE INDEX `storefront_id`(`storefront_id`),
    PRIMARY KEY (`storefront_id`, `corporate_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `sites_corporate_taxes` (
    `corporate_id` INTEGER UNSIGNED NOT NULL,
    `tax_id` INTEGER UNSIGNED NOT NULL,

    PRIMARY KEY (`corporate_id`, `tax_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `sites_merchant_account` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `corporate_id` INTEGER UNSIGNED NOT NULL,
    `issuer` VARCHAR(255) NULL,
    `number` VARCHAR(255) NULL,
    `url` VARCHAR(255) NULL,
    `login` VARCHAR(255) NULL,
    `password` VARCHAR(255) NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `sites_payment_methods` (
    `site_id` INTEGER NOT NULL,
    `payment_method_id` INTEGER UNSIGNED NOT NULL,

    INDEX `FK_sites_payment_methods_payment_methods`(`payment_method_id`),
    PRIMARY KEY (`site_id`, `payment_method_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `sites_share_holder` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NULL,
    `shares` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `percent` DECIMAL(12, 2) NULL,
    `corporate_id` INTEGER UNSIGNED NOT NULL,

    INDEX `FK_sites_shareholder_sites_corporate`(`corporate_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `sites_tax_return` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `corporate_id` INTEGER UNSIGNED NOT NULL,
    `tax_type` ENUM('Income', 'Sales', 'VAT') NOT NULL,
    `from_date` DATE NOT NULL,
    `to_date` DATE NOT NULL,
    `status` ENUM('Filed') NULL,

    INDEX `FK__sites_corporate`(`corporate_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `sites_taxes` (
    `site_id` INTEGER UNSIGNED NOT NULL,
    `tax_id` INTEGER UNSIGNED NOT NULL,

    PRIMARY KEY (`site_id`, `tax_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `subscribe_subscriber` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(50) NOT NULL,
    `sfid` INTEGER NOT NULL,
    `subscribe` TINYINT NOT NULL DEFAULT 0,
    `unsubscribe` TINYINT NOT NULL DEFAULT 0,
    `created_at` DATETIME(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    `nonce` VARCHAR(50) NOT NULL DEFAULT '',

    INDEX `nonce`(`nonce`),
    UNIQUE INDEX `email_sfid`(`email`, `sfid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `tmp_og` (
    `orderid` INTEGER NOT NULL AUTO_INCREMENT,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `cb_status` VARCHAR(1) NOT NULL,

    INDEX `idx1`(`cb_status`),
    PRIMARY KEY (`orderid`, `manufacturerid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `tmp_og_status` (
    `code` VARCHAR(1) NOT NULL,
    `name` CHAR(50) NOT NULL,

    PRIMARY KEY (`code`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `total_product_ratings` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` INTEGER NULL,
    `rating_id` BIGINT UNSIGNED NULL,
    `total` FLOAT NULL,

    INDEX `total_product_ratings_review_ratings_rating_id_fk`(`rating_id`),
    UNIQUE INDEX `total_product_ratings_product_id_rating_id_uindex`(`product_id`, `rating_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `translate_language` (
    `lang_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `lang_code` CHAR(2) NOT NULL DEFAULT '',
    `name` VARCHAR(64) NOT NULL DEFAULT '',
    `status` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `country_code` CHAR(2) NOT NULL,

    UNIQUE INDEX `lang_code`(`lang_code`),
    PRIMARY KEY (`lang_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_Telephone_area_codes` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `area_code` VARCHAR(255) NOT NULL DEFAULT '',
    `country` VARCHAR(255) NOT NULL DEFAULT '',
    `state` VARCHAR(255) NOT NULL DEFAULT '',
    `area` VARCHAR(255) NOT NULL DEFAULT '',
    `intro_date` VARCHAR(255) NOT NULL DEFAULT '',
    `parent_area_code` VARCHAR(255) NOT NULL DEFAULT '',
    `overlay` VARCHAR(255) NOT NULL DEFAULT '',
    `country_code` VARCHAR(5) NULL,
    `state_code` VARCHAR(5) NULL,

    UNIQUE INDEX `idx_area_code`(`area_code`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_ab_point_variants` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `point_id` INTEGER NOT NULL DEFAULT 0,
    `variant_id` INTEGER NOT NULL DEFAULT 0,
    `variant_name` VARCHAR(50) NOT NULL DEFAULT '',
    `total_hits_count` INTEGER NOT NULL DEFAULT 0,
    `reach_goal_count` INTEGER NOT NULL DEFAULT 0,
    `is_default` CHAR(1) NOT NULL DEFAULT 'Y',
    `for_webbot` CHAR(1) NOT NULL,
    `average_success_measure` INTEGER NOT NULL DEFAULT 0,
    `success_measure_range` VARCHAR(32) NOT NULL DEFAULT '',
    `outcome` VARCHAR(32) NOT NULL DEFAULT '',
    `dollar_amount_of_goal_conversions` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,

    INDEX `idx_p_v`(`point_id`, `variant_id`),
    INDEX `is_default`(`is_default`),
    INDEX `point_id`(`point_id`),
    INDEX `variant_id`(`variant_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_ab_testing_points` (
    `point_id` INTEGER NOT NULL AUTO_INCREMENT,
    `enabled` CHAR(1) NOT NULL DEFAULT 'N',
    `point_name` VARCHAR(50) NOT NULL DEFAULT 'new point',
    `point_descr` TEXT NOT NULL,
    `point_start_date` INTEGER NOT NULL DEFAULT 0,
    `point_end_date` INTEGER NOT NULL DEFAULT 0,
    `point_goal_url` VARCHAR(900) NOT NULL DEFAULT '',
    `total_hits` INTEGER NOT NULL DEFAULT 0,
    `mod_param` INTEGER NOT NULL DEFAULT 1,
    `storefronts_enabled` VARCHAR(100) NOT NULL DEFAULT '',
    `exclude_mobile` CHAR(1) NOT NULL DEFAULT '',
    `exclude_payment_methods` VARCHAR(32) NOT NULL DEFAULT '',

    INDEX `enabled`(`enabled`),
    INDEX `idx_point_enabled`(`point_id`, `enabled`),
    PRIMARY KEY (`point_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_amazon_fulfillment_locations` (
    `code` CHAR(4) NOT NULL,
    `country` CHAR(2) NOT NULL,
    `zipcode` VARCHAR(20) NOT NULL,
    `state` INTEGER NULL,
    `city` VARCHAR(255) NULL,
    `address` VARCHAR(255) NULL,

    INDEX `FK_xcart_amazon_fulfillment_locations_xcart_countries`(`country`),
    INDEX `FK_xcart_amazon_fulfillment_locations_xcart_states`(`state`),
    INDEX `FK_xcart_amazon_fulfillment_locations_xcart_zip_country`(`zipcode`, `country`),
    PRIMARY KEY (`code`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_amazon_list_inbound_shipment_items` (
    `productid` INTEGER NOT NULL DEFAULT 0,
    `shipment_id` VARCHAR(50) NOT NULL DEFAULT '',
    `seller_sku` VARCHAR(50) NOT NULL DEFAULT '',
    `fulfillment_network_sku` VARCHAR(50) NOT NULL DEFAULT '',
    `quantity_shipped` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `quantity_received` SMALLINT UNSIGNED NOT NULL DEFAULT 0,

    INDEX `FK_xcart_amazon_list_inbound_shipment_items`(`shipment_id`),
    PRIMARY KEY (`productid`, `shipment_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_amazon_list_inbound_shipments` (
    `shipment_id` VARCHAR(50) NOT NULL DEFAULT '',
    `shipment_name` VARCHAR(255) NULL,
    `destination_fulfillment_center_id` CHAR(4) NULL,
    `label_prep_type` ENUM('NO_LABEL', 'SELLER_LABEL', 'AMAZON_LABEL') NULL,
    `shipment_status` ENUM('WORKING', 'SHIPPED', 'IN_TRANSIT', 'DELIVERED', 'CHECKED_IN', 'RECEIVING', 'CLOSED', 'CANCELLED', 'DELETED', 'ERROR') NULL,
    `are_cases_required` TINYINT NOT NULL DEFAULT 0,
    `confirmed_need_by_date` VARCHAR(15) NOT NULL DEFAULT '',
    `box_contents_source` ENUM('NONE', 'FEED', '2D_BARCODE', 'INTERACTIVE') NULL,
    `total_units` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `fee_per_unit` DOUBLE NOT NULL DEFAULT 0.00,
    `total_fee` DOUBLE NOT NULL DEFAULT 0.00,
    `order_id` INTEGER NULL,

    UNIQUE INDEX `FK_xcart_amazon_list_inbound_shipments_xcart_orders`(`order_id`),
    INDEX `destination_fulfillment_center_id`(`destination_fulfillment_center_id`),
    INDEX `shipment_status`(`shipment_status`),
    PRIMARY KEY (`shipment_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_approximation_shipping_rates` (
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `state` VARCHAR(3) NOT NULL DEFAULT '',
    `bw_1` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `bw_75` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `bw_150` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `last_updated_date` INTEGER NOT NULL DEFAULT 0,
    `updated_at` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    `shipping_id` INTEGER NOT NULL DEFAULT 1,

    INDEX `idxState`(`state`),
    PRIMARY KEY (`manufacturerid`, `state`, `shipping_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_attention_tags_values` (
    `status_id` INTEGER NOT NULL AUTO_INCREMENT,
    `status` VARCHAR(255) NOT NULL DEFAULT '',
    `active` CHAR(1) NOT NULL DEFAULT 'Y',
    `events` BOOLEAN NOT NULL DEFAULT false,
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `color` VARCHAR(7) NOT NULL DEFAULT '#F4CCCC',
    `description` MEDIUMTEXT NOT NULL,

    INDEX `idxStatusId_Active`(`status_id`, `active`, `orderby`),
    PRIMARY KEY (`status_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_attention_tags_values_logins` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `status_id` INTEGER NOT NULL DEFAULT 0,
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `action` VARCHAR(8) NOT NULL DEFAULT '',

    INDEX `idxStatusId`(`status_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_authenticators` (
    `authenticator_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `secret` VARCHAR(16) NOT NULL,
    `created_at` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    INDEX `xcart_authenticators_xcart_users_user_id_fk`(`user_id`),
    PRIMARY KEY (`authenticator_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_avs_codes` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `code` CHAR(1) NOT NULL DEFAULT '',
    `description` VARCHAR(255) NOT NULL DEFAULT '',
    `network` VARCHAR(255) NOT NULL DEFAULT '',

    INDEX `idxAVSCode`(`code`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_backprocess_logs` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `date` INTEGER NOT NULL DEFAULT 0,
    `process_id` VARCHAR(100) NOT NULL DEFAULT '',
    `log_text` MEDIUMTEXT NOT NULL,
    `diff` SMALLINT UNSIGNED NULL,

    INDEX `date`(`date`),
    INDEX `diff`(`diff`),
    INDEX `process_id`(`process_id`),
    INDEX `process_id-date`(`process_id`, `date`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_banners` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `position` VARCHAR(255) NOT NULL,
    `storefronts` VARCHAR(100) NULL,
    `start_at` DATETIME(0) NULL,
    `end_at` DATETIME(0) NULL,
    `enabled` CHAR(1) NULL DEFAULT 'Y',
    `html` MEDIUMTEXT NULL,
    `type` VARCHAR(45) NULL DEFAULT 'html',
    `url` VARCHAR(255) NULL,
    `pages` VARCHAR(255) NULL DEFAULT '',

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_benchmark_pages` (
    `pageid` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `script` VARCHAR(64) NOT NULL DEFAULT '',
    `data` VARCHAR(255) NOT NULL DEFAULT '',
    `method` CHAR(1) NOT NULL DEFAULT 'G',

    UNIQUE INDEX `sdm`(`script`, `data`, `method`),
    PRIMARY KEY (`pageid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_bpu_result` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `productcode` VARCHAR(255) NOT NULL DEFAULT '',
    `result` VARCHAR(255) NOT NULL DEFAULT '',

    INDEX `productcode`(`productcode`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_bpu_rows` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `productcode` VARCHAR(255) NOT NULL DEFAULT '',
    `serialized_row` TEXT NULL,

    INDEX `productcode`(`productcode`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_brands` (
    `brandid` INTEGER NOT NULL AUTO_INCREMENT,
    `brand` VARCHAR(255) NOT NULL DEFAULT '',
    `url` VARCHAR(255) NOT NULL DEFAULT '',
    `descr` MEDIUMTEXT NOT NULL,
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `provider` VARCHAR(32) NOT NULL DEFAULT '',
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `customer_service_phone` VARCHAR(32) NOT NULL DEFAULT '',
    `customer_service_email` VARCHAR(128) NOT NULL DEFAULT '',
    `link_to_us_url` VARCHAR(255) NOT NULL DEFAULT '',
    `customer_service_name` VARCHAR(255) NOT NULL DEFAULT '',
    `title` VARCHAR(600) NOT NULL DEFAULT '',
    `product_brand_name` VARCHAR(255) NULL,
    `SEO_brand_name_h1` VARCHAR(600) NOT NULL DEFAULT '',
    `SEO_h2` VARCHAR(600) NOT NULL DEFAULT '',
    `meta_descr` MEDIUMTEXT NOT NULL,
    `disclaimer_text` MEDIUMTEXT NOT NULL,
    `prevent_search_indexing_of_all_brand_products` CHAR(1) NOT NULL DEFAULT 'N',
    `prevent_search_indexing_brand_page` CHAR(1) NOT NULL DEFAULT 'N',
    `parent_brand_id` INTEGER NULL,
    `leadtime_from` TINYINT UNSIGNED NULL,
    `leadtime_to` TINYINT UNSIGNED NULL,
    `image` VARCHAR(512) NULL,

    UNIQUE INDEX `brand`(`brand`),
    INDEX `avail`(`avail`),
    INDEX `orderby`(`orderby`),
    INDEX `parent_brand_id`(`parent_brand_id`),
    PRIMARY KEY (`brandid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_brands_lng` (
    `brandid` INTEGER NOT NULL DEFAULT 0,
    `code` CHAR(2) NOT NULL DEFAULT 'US',
    `brand` VARCHAR(255) NOT NULL DEFAULT '',
    `descr` MEDIUMTEXT NOT NULL,

    UNIQUE INDEX `mc`(`brandid`, `code`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_brands_sf` (
    `brandid` INTEGER NOT NULL,
    `sfid` INTEGER NOT NULL DEFAULT 0,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `products_count` INTEGER NULL DEFAULT 0,

    INDEX `brandid_sf_pc`(`brandid`, `sfid`, `products_count`),
    INDEX `sfid`(`sfid`),
    UNIQUE INDEX `brandid_sfid`(`brandid`, `sfid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_bulk_tmp` (
    `key` VARCHAR(32) NOT NULL DEFAULT '',
    `data` LONGTEXT NOT NULL,
    `name` VARCHAR(64) NOT NULL DEFAULT '',
    `login` VARCHAR(32) NOT NULL DEFAULT '',

    INDEX `login`(`login`),
    PRIMARY KEY (`key`, `name`, `login`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_categories` (
    `categoryid` INTEGER NOT NULL AUTO_INCREMENT,
    `parentid` INTEGER NOT NULL DEFAULT 0,
    `categoryid_path` VARCHAR(255) NOT NULL DEFAULT '',
    `category` VARCHAR(255) NOT NULL DEFAULT '',
    `description` MEDIUMTEXT NOT NULL,
    `meta_descr` VARCHAR(255) NOT NULL DEFAULT '',
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `views_stats` INTEGER NOT NULL DEFAULT 0,
    `order_by` INTEGER NOT NULL DEFAULT 0,
    `threshold_bestsellers` INTEGER NOT NULL DEFAULT 1,
    `product_count` INTEGER NOT NULL DEFAULT 0,
    `meta_keywords` VARCHAR(255) NOT NULL DEFAULT '',
    `is_bold` CHAR(1) NOT NULL DEFAULT 'Y',
    `storefrontid` INTEGER NOT NULL DEFAULT 0,
    `linked_out_category_id_1` INTEGER NOT NULL DEFAULT 0,
    `linked_out_category_id_2` INTEGER NOT NULL DEFAULT 0,
    `linked_out_category_id_3` INTEGER NOT NULL DEFAULT 0,
    `linked_out_category_id_4` INTEGER NOT NULL DEFAULT 0,
    `linked_out_category_id_5` INTEGER NOT NULL DEFAULT 0,
    `linked_out_category_id_6` INTEGER NOT NULL DEFAULT 0,
    `linked_out_category_keyphrase_id_1` INTEGER NOT NULL DEFAULT 0,
    `linked_out_category_keyphrase_id_2` INTEGER NOT NULL DEFAULT 0,
    `linked_out_category_keyphrase_id_3` INTEGER NOT NULL DEFAULT 0,
    `linked_out_category_keyphrase_id_4` INTEGER NOT NULL DEFAULT 0,
    `linked_out_category_keyphrase_id_5` INTEGER NOT NULL DEFAULT 0,
    `linked_out_category_keyphrase_id_6` INTEGER NOT NULL DEFAULT 0,
    `title_tag` VARCHAR(255) NOT NULL DEFAULT '',
    `pc_ready_to_classify` CHAR(1) NOT NULL DEFAULT 'N',
    `pc_category_weight` DECIMAL(18, 8) NOT NULL DEFAULT 0.00000000,
    `pc_z` DECIMAL(18, 8) NOT NULL DEFAULT 0.00000000,
    `SEO_category_name` VARCHAR(600) NOT NULL DEFAULT '',
    `google_product_category` VARCHAR(300) NOT NULL,
    `SEO_h2` MEDIUMTEXT NOT NULL,
    `prevent_index_products` CHAR(1) NOT NULL DEFAULT 'N',
    `prevent_index_category_page` CHAR(1) NOT NULL DEFAULT 'N',
    `global_product_count` INTEGER NOT NULL DEFAULT 0,
    `active_product_count` INTEGER NOT NULL DEFAULT 0,
    `subcategory_count` INTEGER NOT NULL DEFAULT 0,
    `supplemental_category` CHAR(1) NOT NULL DEFAULT 'N',
    `root` INTEGER NULL,
    `level` INTEGER NULL,
    `lft` INTEGER NULL,
    `rgt` INTEGER NULL,

    INDEX `am`(`avail`),
    INDEX `categoryid_path`(`categoryid_path`),
    INDEX `categoryid_path_avail`(`categoryid_path`, `avail`),
    INDEX `ia`(`categoryid`, `avail`),
    INDEX `idxPARENTID`(`parentid`),
    INDEX `idx_nested_set`(`root`, `lft`, `rgt`, `level`),
    INDEX `idx_pc_ready_to_classify`(`categoryid_path`, `pc_ready_to_classify`, `storefrontid`),
    INDEX `order_by`(`order_by`, `category`),
    INDEX `order_by_i`(`parentid`, `storefrontid`, `avail`, `order_by`, `active_product_count`),
    INDEX `storefrontid`(`storefrontid`),
    INDEX `storefrontid_level`(`storefrontid`, `level`),
    UNIQUE INDEX `opt-T`(`categoryid`, `avail`, `storefrontid`),
    PRIMARY KEY (`categoryid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_categories_lng` (
    `code` CHAR(2) NOT NULL DEFAULT '',
    `categoryid` INTEGER NOT NULL DEFAULT 0,
    `category` VARCHAR(255) NOT NULL DEFAULT '',
    `description` MEDIUMTEXT NOT NULL,

    PRIMARY KEY (`code`, `categoryid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_categories_parents` (
    `categoryid` INTEGER NOT NULL DEFAULT 0,
    `parentid` INTEGER NOT NULL DEFAULT 0,
    `order_by` INTEGER NOT NULL,
    `is_bold` CHAR(1) NOT NULL DEFAULT 'Y',

    INDEX `categoryid`(`categoryid`),
    PRIMARY KEY (`categoryid`, `parentid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_categories_subcount` (
    `categoryid` INTEGER NOT NULL DEFAULT 0,
    `subcategory_count` INTEGER NOT NULL DEFAULT 0,
    `product_count` INTEGER NOT NULL DEFAULT 0,
    `membershipid` INTEGER NOT NULL DEFAULT 0,
    `global_product_count` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`categoryid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_category_memberships` (
    `categoryid` INTEGER NOT NULL DEFAULT 0,
    `membershipid` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`categoryid`, `membershipid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cc_gestpay_data` (
    `value` CHAR(32) NOT NULL DEFAULT '',
    `type` CHAR(1) NOT NULL DEFAULT 'C',

    PRIMARY KEY (`value`, `type`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cc_pp3_data` (
    `ref` VARCHAR(255) NOT NULL DEFAULT '',
    `sessionid` VARCHAR(255) NOT NULL DEFAULT '',
    `param1` VARCHAR(255) NOT NULL DEFAULT '',
    `param2` VARCHAR(255) NOT NULL DEFAULT '',
    `param3` VARCHAR(255) NOT NULL DEFAULT '',
    `param4` VARCHAR(255) NOT NULL DEFAULT '',
    `param5` VARCHAR(255) NOT NULL DEFAULT '',
    `trstat` VARCHAR(255) NOT NULL DEFAULT '',
    `is_callback` CHAR(1) NOT NULL DEFAULT '',

    UNIQUE INDEX `refk`(`ref`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_ccprocessors` (
    `module_name` VARCHAR(255) NOT NULL DEFAULT '',
    `type` CHAR(1) NOT NULL DEFAULT '',
    `processor` VARCHAR(255) NOT NULL DEFAULT '',
    `template` VARCHAR(255) NOT NULL DEFAULT '',
    `param01` VARCHAR(255) NOT NULL DEFAULT '',
    `param02` VARCHAR(255) NOT NULL DEFAULT '',
    `param03` VARCHAR(255) NOT NULL DEFAULT '',
    `param04` VARCHAR(255) NOT NULL DEFAULT '',
    `param05` VARCHAR(255) NOT NULL DEFAULT '',
    `param06` VARCHAR(255) NOT NULL DEFAULT '',
    `param07` VARCHAR(255) NOT NULL DEFAULT '',
    `param08` VARCHAR(255) NOT NULL DEFAULT '',
    `param09` VARCHAR(255) NOT NULL DEFAULT '',
    `disable_ccinfo` CHAR(1) NOT NULL DEFAULT 'N',
    `background` CHAR(1) NOT NULL DEFAULT 'N',
    `testmode` CHAR(1) NOT NULL DEFAULT 'N',
    `is_check` CHAR(1) NOT NULL DEFAULT '',
    `is_refund` CHAR(1) NOT NULL DEFAULT '',
    `c_template` VARCHAR(255) NOT NULL DEFAULT '',
    `paymentid` INTEGER NOT NULL DEFAULT 0,
    `cmpi` CHAR(1) NOT NULL DEFAULT '',

    INDEX `paymentid`(`paymentid`),
    INDEX `processor`(`processor`),
    PRIMARY KEY (`module_name`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_checks_deposited` (
    `checks_deposited_id` INTEGER NOT NULL AUTO_INCREMENT,
    `date` INTEGER NOT NULL DEFAULT 0,
    `currency` VARCHAR(3) NOT NULL DEFAULT 'USD',
    `total_deposit_amount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `currency_locked` CHAR(1) NOT NULL DEFAULT 'N',
    `status` CHAR(1) NOT NULL DEFAULT 'P',
    `check_date` DATE NULL,

    PRIMARY KEY (`checks_deposited_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_checks_deposited_orders` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `checks_deposited_id` INTEGER NOT NULL DEFAULT 0,
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `check_number` VARCHAR(32) NOT NULL DEFAULT '',
    `amount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `notes` VARCHAR(255) NOT NULL DEFAULT '',
    `date_added` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_amazon_fba_products` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `productid` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
    `productcode` VARCHAR(50) NULL DEFAULT '0',
    `report_date` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `cpr_LandedPrice` DECIMAL(10, 2) NULL DEFAULT 0.00,
    `cpr_OurLandedPrice` DECIMAL(10, 2) NULL DEFAULT 0.00,
    `cpr_belongs_LandedPrice` DECIMAL(10, 2) NULL DEFAULT 0.00,
    `cpr_SalesRank` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
    `lis_TotalSupplyQuantity` SMALLINT NULL DEFAULT 0,
    `lis_InStockSupplyQuantity` SMALLINT NULL DEFAULT 0,
    `lp_LandedPrice` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `lp_MultipleOfferListingsAtLowestPrice` CHAR(1) NOT NULL DEFAULT '',
    `lp_AllOfferListingsConsidered` CHAR(1) NOT NULL DEFAULT '',
    `lp_NumberOfOfferListingsConsidered` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `lp_SellerFeedbackCount` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
    `lp_FulfillmentChannel` ENUM('', 'AFN', 'MFN') NOT NULL DEFAULT '',
    `lp_ShippingTime` VARCHAR(30) NOT NULL DEFAULT '',
    `lp_SellerPositiveFeedbackRating` VARCHAR(30) NOT NULL DEFAULT '',
    `ASIN` VARCHAR(30) NOT NULL DEFAULT '',
    `precise_data` ENUM('N', 'Y', 'D') NOT NULL DEFAULT 'Y',
    `reserved_qty` SMALLINT NOT NULL DEFAULT 0,
    `reserved_customerorders` SMALLINT NOT NULL DEFAULT 0,
    `reserved_fc_transfers` SMALLINT NOT NULL DEFAULT 0,
    `reserved_fc_processing` SMALLINT NOT NULL DEFAULT 0,
    `is_prevent_selling_on_amazon` ENUM('FBA', 'MFN', 'No') NOT NULL DEFAULT 'No',
    `buybox_in` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `buybox_out` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `last_cpr_belongs_LandedPrice` DECIMAL(10, 2) NULL,
    `last_competitor_price_when_buybox_our` DECIMAL(10, 2) NULL,
    `last_cpr_competitor_price_when_buybox_our` DECIMAL(10, 2) NULL,
    `hold_last_buybox_price` SMALLINT NULL,

    INDEX `cpr_SalesRank`(`cpr_SalesRank`, `productid`),
    INDEX `lp_FulfillmentChannel`(`lp_FulfillmentChannel`, `productid`),
    INDEX `productid`(`productid`, `precise_data`, `report_date`),
    INDEX `report_date`(`report_date`),
    UNIQUE INDEX `productid_reportdate`(`productid`, `report_date`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_amazon_fba_products_max_price` (
    `productid` INTEGER UNSIGNED NOT NULL,
    `hold_price` DECIMAL(18, 2) NOT NULL DEFAULT 0.00,
    `rigid_compete` CHAR(1) NOT NULL DEFAULT 'N',
    `rigid_noprofit_addon` DECIMAL(18, 2) NOT NULL DEFAULT 1.00,
    `setup_date` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    `min_mfn_price` DECIMAL(18, 2) NULL,
    `afn_max_price_mult` DECIMAL(18, 2) NULL,
    `map_price` DECIMAL(18, 2) NULL,

    INDEX `setup_date`(`setup_date`),
    PRIMARY KEY (`productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_amazon_fba_products_quick` (
    `productid` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `data_id` INTEGER UNSIGNED NOT NULL DEFAULT 0,

    PRIMARY KEY (`productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_amazon_order_raw` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `order_info` TEXT NOT NULL,
    `orderitems_info` TEXT NOT NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_daily_fba_stats` (
    `reportdate` INTEGER NOT NULL DEFAULT 0,
    `items_count` SMALLINT UNSIGNED NULL DEFAULT 0,
    `avg_item_cost` DECIMAL(18, 2) NULL DEFAULT 0.00,
    `avg_item_amount` DECIMAL(18, 2) NULL DEFAULT 0.00,
    `total_amount` DECIMAL(18, 2) NULL DEFAULT 0.00,
    `one_item_amount` DECIMAL(18, 2) NULL DEFAULT 0.00,
    `total_cost` DECIMAL(18, 2) NULL DEFAULT 0.00,
    `compete_items` SMALLINT UNSIGNED NULL DEFAULT 0,
    `bb_lost` SMALLINT UNSIGNED NULL DEFAULT 0,
    `bb_our` SMALLINT UNSIGNED NULL DEFAULT 0,
    `bb_nostats` SMALLINT UNSIGNED NULL DEFAULT 0,

    PRIMARY KEY (`reportdate`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_filter_products` (
    `fv_id` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `productid` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `is_feed` TINYINT UNSIGNED NOT NULL DEFAULT 0,

    INDEX `fv_id`(`fv_id`),
    INDEX `productid_is_feed`(`productid`, `is_feed`),
    PRIMARY KEY (`productid`, `fv_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_filter_values` (
    `fv_id` INTEGER NOT NULL AUTO_INCREMENT,
    `f_id` INTEGER NOT NULL DEFAULT 0,
    `fv_name` VARCHAR(512) NOT NULL DEFAULT '',
    `fv_order_by` INTEGER NOT NULL DEFAULT 10,
    `fv_active` CHAR(1) NOT NULL DEFAULT 'Y',

    INDEX `f_id`(`f_id`),
    INDEX `fv_aon`(`fv_order_by`, `fv_name`),
    UNIQUE INDEX `FilterType-ValueName`(`fv_name`, `f_id`),
    PRIMARY KEY (`fv_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_filters` (
    `f_id` INTEGER NOT NULL AUTO_INCREMENT,
    `f_name` VARCHAR(128) NOT NULL DEFAULT '',
    `f_order_by` INTEGER NOT NULL DEFAULT 10,
    `f_active` CHAR(1) NOT NULL DEFAULT 'Y',
    `storefrontid` INTEGER NOT NULL DEFAULT 0,

    INDEX `f_aon`(`f_order_by`, `f_name`),
    UNIQUE INDEX `f_name`(`f_name`, `storefrontid`),
    PRIMARY KEY (`f_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_gmc_quality_issues` (
    `productid` INTEGER NOT NULL,
    `issue_id` INTEGER NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `fixed` ENUM('Y', 'N') NOT NULL DEFAULT 'N',
    `issue_date` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    `update_date` TIMESTAMP(0) NULL,

    INDEX `fixed`(`fixed`),
    INDEX `xcart_cidev_gmc_quality_issues_ibfk_1`(`issue_id`),
    PRIMARY KEY (`productid`, `issue_id`, `name`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_google_product_taxonomy` (
    `id` INTEGER NOT NULL,
    `value` VARCHAR(350) NOT NULL,

    INDEX `idx_value`(`value`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_issues_processing_rules` (
    `issue_id` INTEGER NOT NULL AUTO_INCREMENT,
    `issue_name` VARCHAR(200) NOT NULL,
    `issue_gmc_id` VARCHAR(50) NOT NULL,
    `issue_processing` ENUM('exclude', 'manual', 'skip') NOT NULL DEFAULT 'manual',

    UNIQUE INDEX `issue_gms_id`(`issue_gmc_id`),
    PRIMARY KEY (`issue_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_manufacturers_pass_view_log` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `manufacturerid` INTEGER NULL DEFAULT 0,
    `date` INTEGER NULL DEFAULT 0,
    `login` VARCHAR(50) NOT NULL DEFAULT '',

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_otrs_new_message_rules` (
    `rule_id` INTEGER NOT NULL AUTO_INCREMENT,
    `cb_status` CHAR(2) NOT NULL,
    `dc_status` CHAR(2) NOT NULL,
    `bd_status` CHAR(2) NOT NULL,
    `action` ENUM('Include', 'Exclude') NOT NULL,

    UNIQUE INDEX `unique_status`(`cb_status`, `dc_status`, `bd_status`),
    PRIMARY KEY (`rule_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_related_objects` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `resource_id` INTEGER NOT NULL DEFAULT 0,
    `resource_type` VARCHAR(2) NOT NULL DEFAULT '',
    `referal_domain` VARCHAR(200) NOT NULL DEFAULT '',
    `related_resource_id` INTEGER NOT NULL DEFAULT 0,
    `related_resource_type` CHAR(2) NULL,
    `related_resource_orderby` INTEGER NOT NULL DEFAULT 0,
    `related_type` INTEGER NOT NULL DEFAULT 0,

    INDEX `related_resource_id`(`related_resource_id`, `related_resource_type`, `related_resource_orderby`),
    INDEX `related_resource_orderby`(`related_resource_orderby`),
    INDEX `resource_id`(`resource_id`, `resource_type`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_surf_meta` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `sessid` CHAR(32) NOT NULL,
    `date` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `referal_url` TEXT NULL,
    `is_mobile` CHAR(1) NULL DEFAULT '',
    `goal_order` CHAR(1) NOT NULL DEFAULT 'N',
    `goal_checkout` CHAR(1) NOT NULL DEFAULT 'N',
    `goal_addtocart` CHAR(1) NOT NULL DEFAULT 'N',
    `goal_search` CHAR(1) NOT NULL DEFAULT 'N',
    `points_visited` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `last_update` INTEGER NOT NULL DEFAULT 0,
    `storefrontid` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `user_id` INTEGER UNSIGNED NULL,

    UNIQUE INDEX `sessid`(`sessid`),
    INDEX `FK_surf_meta__customers__id`(`user_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_surf_path` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `position` SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    `resource_type` CHAR(1) NOT NULL DEFAULT '',
    `resource_id` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `additional_data` VARCHAR(700) NULL DEFAULT '',
    `timestamp` INTEGER NOT NULL DEFAULT 0,
    `meta_id` INTEGER UNSIGNED NOT NULL DEFAULT 0,

    INDEX `meta_id`(`meta_id`, `position`, `resource_id`, `resource_type`),
    INDEX `resource_id_type`(`resource_id`, `resource_type`),
    INDEX `resource_type`(`resource_type`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_updated_amazon_orders` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `orderid` INTEGER NULL,
    `type` INTEGER NULL,
    `time_stamp` INTEGER NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_cidev_updated_products` (
    `resourceid` INTEGER NOT NULL,
    `type` INTEGER NOT NULL,
    `time_stamp` INTEGER NULL DEFAULT 1,
    `source` VARCHAR(20) NULL DEFAULT 'manual',
    `mask` INTEGER UNSIGNED NULL,
    `extra_data_int` INTEGER UNSIGNED NULL,

    INDEX `mask`(`mask`),
    INDEX `time_stamp`(`time_stamp`),
    INDEX `type`(`type`),
    UNIQUE INDEX `idx_resourceidid_type`(`resourceid`, `type`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_class_lng` (
    `code` CHAR(2) NOT NULL DEFAULT 'US',
    `classid` INTEGER NOT NULL DEFAULT 0,
    `class` VARCHAR(128) NOT NULL DEFAULT '',
    `classtext` VARCHAR(255) NOT NULL DEFAULT '',

    PRIMARY KEY (`classid`, `code`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_class_options` (
    `optionid` INTEGER NOT NULL AUTO_INCREMENT,
    `classid` INTEGER NOT NULL DEFAULT 0,
    `option_name` VARCHAR(255) NOT NULL DEFAULT '',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `price_modifier` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `modifier_type` CHAR(1) NOT NULL DEFAULT '$',
    `price_modifier_old` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `modified_price` CHAR(1) NOT NULL DEFAULT '',

    INDEX `ia`(`classid`, `avail`),
    INDEX `orderby`(`orderby`, `avail`),
    PRIMARY KEY (`optionid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_classes` (
    `classid` INTEGER NOT NULL AUTO_INCREMENT,
    `productid` INTEGER NOT NULL DEFAULT 0,
    `class` VARCHAR(128) NOT NULL DEFAULT '',
    `classtext` VARCHAR(255) NOT NULL DEFAULT '',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `is_modifier` CHAR(1) NOT NULL DEFAULT 'Y',

    INDEX `class`(`class`),
    INDEX `is_modifier`(`is_modifier`),
    INDEX `orderby`(`orderby`, `avail`),
    INDEX `productid`(`productid`),
    PRIMARY KEY (`classid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_clean_urls_history` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `resource_type` CHAR(1) NOT NULL DEFAULT '',
    `resource_id` INTEGER NOT NULL DEFAULT 0,
    `clean_url` VARCHAR(250) NOT NULL DEFAULT '',
    `mtime` INTEGER NOT NULL DEFAULT 0,

    UNIQUE INDEX `rrc`(`resource_type`, `resource_id`, `clean_url`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_clone_products_queue` (
    `productid` INTEGER NOT NULL DEFAULT 0,
    `clone` CHAR(1) NOT NULL DEFAULT 'N',
    `insert_datetime` INTEGER NULL,
    `manufacturerid` INTEGER NOT NULL DEFAULT -1,

    PRIMARY KEY (`productid`, `manufacturerid`, `clone`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_config` (
    `name` VARCHAR(255) NOT NULL,
    `comment` VARCHAR(255) NOT NULL DEFAULT '',
    `value` MEDIUMTEXT NOT NULL,
    `category` VARCHAR(32) NOT NULL DEFAULT '',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `type` ENUM('numeric', 'text', 'textarea', 'checkbox', 'separator', 'selector', 'multiselector') NULL DEFAULT 'text',
    `defvalue` MEDIUMTEXT NOT NULL,
    `variants` MEDIUMTEXT NOT NULL,
    `validation` VARCHAR(255) NOT NULL DEFAULT '',

    INDEX `orderby`(`orderby`),
    INDEX `type`(`type`),
    PRIMARY KEY (`name`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_contact_fields` (
    `fieldid` INTEGER NOT NULL AUTO_INCREMENT,
    `field` VARCHAR(255) NOT NULL DEFAULT '',
    `type` CHAR(1) NOT NULL DEFAULT 'T',
    `variants` TEXT NOT NULL,
    `def` VARCHAR(255) NOT NULL DEFAULT '',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `avail` VARCHAR(4) NOT NULL DEFAULT '',
    `required` VARCHAR(4) NOT NULL DEFAULT '',

    INDEX `avail`(`avail`),
    INDEX `required`(`required`),
    PRIMARY KEY (`fieldid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_counters` (
    `type` CHAR(1) NOT NULL DEFAULT '',
    `value` INTEGER NOT NULL AUTO_INCREMENT,

    PRIMARY KEY (`value`, `type`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_counties` (
    `countyid` INTEGER NOT NULL AUTO_INCREMENT,
    `stateid` INTEGER NOT NULL DEFAULT 0,
    `county` VARCHAR(255) NOT NULL DEFAULT '',

    INDEX `countyid`(`stateid`, `countyid`),
    UNIQUE INDEX `countyname`(`stateid`, `county`),
    PRIMARY KEY (`countyid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_countries` (
    `code` CHAR(2) NOT NULL DEFAULT '',
    `code_A3` CHAR(3) NOT NULL DEFAULT '',
    `code_N3` INTEGER NOT NULL DEFAULT 0,
    `region` CHAR(2) NOT NULL DEFAULT '',
    `charset` VARCHAR(32) NOT NULL DEFAULT 'iso-8859-1',
    `active` CHAR(1) NOT NULL DEFAULT 'Y',
    `fedex_zone` CHAR(1) NOT NULL DEFAULT '',
    `display_states` CHAR(1) NOT NULL DEFAULT 'Y',
    `name` VARCHAR(255) NULL,
    `phone_code` INTEGER UNSIGNED NULL,

    UNIQUE INDEX `xcart_countries_phone_code_uindex`(`phone_code`),
    INDEX `fedex_zone`(`fedex_zone`),
    INDEX `name`(`name`),
    PRIMARY KEY (`code`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_country_currencies` (
    `code` CHAR(3) NOT NULL DEFAULT '',
    `country_code` CHAR(2) NOT NULL DEFAULT '',

    PRIMARY KEY (`code`, `country_code`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_currencies` (
    `currency_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `currency_code` VARCHAR(10) NOT NULL DEFAULT '',
    `after` CHAR(1) NOT NULL DEFAULT 'N',
    `symbol` TINYTEXT NULL,
    `symbol_prefix` TINYTEXT NULL,
    `coefficient` DOUBLE NOT NULL DEFAULT 1.00000,
    `is_primary` CHAR(1) NOT NULL DEFAULT 'N',
    `position` SMALLINT NOT NULL,
    `decimals_separator` VARCHAR(6) NOT NULL DEFAULT '.',
    `thousands_separator` VARCHAR(6) NOT NULL DEFAULT ',',
    `decimals` SMALLINT NOT NULL DEFAULT 2,
    `active` TINYINT UNSIGNED NOT NULL DEFAULT 0,

    UNIQUE INDEX `currency_code`(`currency_code`),
    PRIMARY KEY (`currency_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_customers` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `usertype` CHAR(1) NOT NULL DEFAULT '',
    `password` VARCHAR(255) NOT NULL DEFAULT '',
    `password_hint` VARCHAR(128) NOT NULL DEFAULT '',
    `password_hint_answer` VARCHAR(128) NOT NULL DEFAULT '',
    `b_title` VARCHAR(32) NOT NULL DEFAULT '',
    `b_firstname` VARCHAR(128) NOT NULL DEFAULT '',
    `b_lastname` VARCHAR(128) NOT NULL DEFAULT '',
    `b_address` VARCHAR(64) NOT NULL DEFAULT '',
    `b_city` VARCHAR(64) NOT NULL DEFAULT '',
    `b_county` VARCHAR(32) NOT NULL DEFAULT '',
    `b_state` VARCHAR(32) NOT NULL DEFAULT '',
    `b_country` CHAR(2) NOT NULL DEFAULT '',
    `b_zipcode` VARCHAR(32) NOT NULL DEFAULT '',
    `title` VARCHAR(32) NOT NULL DEFAULT '',
    `firstname` VARCHAR(128) NOT NULL DEFAULT '',
    `lastname` VARCHAR(128) NOT NULL DEFAULT '',
    `company` VARCHAR(255) NOT NULL DEFAULT '',
    `s_title` VARCHAR(32) NOT NULL DEFAULT '',
    `s_firstname` VARCHAR(128) NOT NULL DEFAULT '',
    `s_lastname` VARCHAR(128) NOT NULL DEFAULT '',
    `s_address` VARCHAR(255) NOT NULL DEFAULT '',
    `s_city` VARCHAR(255) NOT NULL DEFAULT '',
    `s_county` VARCHAR(32) NOT NULL DEFAULT '',
    `s_state` VARCHAR(32) NOT NULL DEFAULT '',
    `s_country` CHAR(2) NOT NULL DEFAULT '',
    `s_zipcode` VARCHAR(32) NOT NULL DEFAULT '',
    `email` VARCHAR(128) NOT NULL DEFAULT '',
    `phone` VARCHAR(32) NOT NULL DEFAULT '',
    `fax` VARCHAR(32) NOT NULL DEFAULT '',
    `url` VARCHAR(128) NOT NULL DEFAULT '',
    `card_name` VARCHAR(255) NOT NULL DEFAULT '',
    `card_type` VARCHAR(16) NOT NULL DEFAULT '',
    `card_number` VARCHAR(128) NOT NULL DEFAULT '',
    `card_expire` VARCHAR(4) NOT NULL DEFAULT '',
    `card_cvv2` VARCHAR(64) NOT NULL DEFAULT '',
    `last_login` INTEGER NOT NULL DEFAULT 0,
    `first_login` INTEGER NOT NULL DEFAULT 0,
    `status` CHAR(1) NOT NULL DEFAULT 'Y',
    `referer` VARCHAR(255) NOT NULL DEFAULT '',
    `ssn` VARCHAR(32) NOT NULL DEFAULT '',
    `language` CHAR(2) NOT NULL DEFAULT 'US',
    `cart` LONGTEXT NOT NULL,
    `change_password` CHAR(1) NOT NULL DEFAULT 'N',
    `parent` VARCHAR(32) NOT NULL DEFAULT '',
    `pending_plan_id` INTEGER NOT NULL DEFAULT 0,
    `activity` CHAR(1) NOT NULL DEFAULT 'Y',
    `membershipid` INTEGER NOT NULL DEFAULT 0,
    `pending_membershipid` INTEGER NOT NULL DEFAULT 0,
    `tax_number` VARCHAR(50) NOT NULL DEFAULT '',
    `tax_exempt` CHAR(1) NOT NULL DEFAULT 'N',
    `last_message` CHAR(1) NOT NULL DEFAULT 'N',
    `manufacturerids` MEDIUMTEXT NOT NULL,
    `phone_ext` VARCHAR(32) NOT NULL DEFAULT '',
    `position` VARCHAR(128) NOT NULL DEFAULT '',
    `pbx_extension` VARCHAR(128) NOT NULL DEFAULT '',
    `allow_operate_as_membership` VARCHAR(30) NULL,
    `cart_number` INTEGER NOT NULL DEFAULT 0,
    `show_events` BOOLEAN NOT NULL DEFAULT false,
    `show_events_min_date` DATE NULL,
    `parent_user_id` INTEGER UNSIGNED NULL,

    UNIQUE INDEX `login_UNIQUE`(`login`),
    INDEX `FK_xcart_customers_xcart_customers`(`parent_user_id`),
    INDEX `cart_number`(`cart_number`),
    INDEX `first_login`(`first_login`),
    INDEX `last_login`(`last_login`),
    INDEX `membershipid`(`membershipid`),
    INDEX `pbx_extension`(`pbx_extension`),
    INDEX `status`(`status`),
    INDEX `usertype`(`usertype`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_dashboard_filters` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `group_id` INTEGER UNSIGNED NULL,
    `name` VARCHAR(255) NOT NULL,
    `enabled` BOOLEAN NOT NULL DEFAULT false,
    `bold` TINYINT NOT NULL DEFAULT 0,
    `position_row` INTEGER NULL,
    `position_column` INTEGER NULL,
    `tag` VARCHAR(5) NULL,
    `color` VARCHAR(7) NULL,
    `form_data` TEXT NULL,
    `direct_url` VARCHAR(255) NULL,
    `manual_url` VARCHAR(255) NULL,
    `sorting` TINYINT NOT NULL DEFAULT 1,
    `entity` VARCHAR(255) NULL,
    `information` BOOLEAN NOT NULL DEFAULT false,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_dashboard_filters_statistic` (
    `date` DATE NOT NULL,
    `hour` TINYINT UNSIGNED NOT NULL,
    `filter_id` INTEGER UNSIGNED NOT NULL,
    `count` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,

    PRIMARY KEY (`filter_id`, `date`, `hour`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_dashboard_groups` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_delivery` (
    `shippingid` INTEGER NOT NULL DEFAULT 0,
    `productid` INTEGER NOT NULL DEFAULT 0,

    INDEX `productid_index`(`productid`),
    PRIMARY KEY (`shippingid`, `productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_departments` (
    `depid` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `email` VARCHAR(128) NOT NULL DEFAULT '',
    `frozen` CHAR(1) NOT NULL DEFAULT '',

    PRIMARY KEY (`depid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_discount_coupons` (
    `coupon` CHAR(16) NOT NULL DEFAULT '',
    `discount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `coupon_type` CHAR(12) NOT NULL DEFAULT '',
    `productid` INTEGER NOT NULL DEFAULT 0,
    `categoryid` INTEGER NOT NULL DEFAULT 0,
    `minimum` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `times` INTEGER NOT NULL DEFAULT 0,
    `per_user` CHAR(1) NOT NULL DEFAULT 'N',
    `times_used` INTEGER NOT NULL DEFAULT 0,
    `expire` INTEGER NOT NULL DEFAULT 0,
    `status` CHAR(1) NOT NULL DEFAULT '',
    `provider` CHAR(32) NOT NULL DEFAULT '',
    `recursive` CHAR(1) NOT NULL DEFAULT 'N',
    `apply_category_once` CHAR(1) NOT NULL DEFAULT 'N',
    `apply_product_once` CHAR(1) NOT NULL DEFAULT 'N',
    `storefrontid` INTEGER NOT NULL DEFAULT 0,

    INDEX `provider`(`provider`),
    INDEX `status`(`status`),
    INDEX `storefrontid`(`storefrontid`),
    PRIMARY KEY (`coupon`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_discount_coupons_login` (
    `coupon` VARCHAR(16) NOT NULL DEFAULT '',
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `times_used` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`coupon`, `login`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_discount_memberships` (
    `discountid` INTEGER NOT NULL DEFAULT 0,
    `membershipid` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`discountid`, `membershipid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_discounts` (
    `discountid` INTEGER NOT NULL AUTO_INCREMENT,
    `minprice` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `discount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `discount_type` CHAR(32) NOT NULL DEFAULT 'absolute',
    `provider` CHAR(32) NOT NULL DEFAULT '',
    `storefrontid` INTEGER NOT NULL DEFAULT 0,

    INDEX `minprice`(`minprice`),
    INDEX `provider`(`provider`),
    INDEX `storefrontid`(`storefrontid`),
    PRIMARY KEY (`discountid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_distributor_contact_utility` (
    `contact_id` INTEGER NOT NULL,
    `utility_id` INTEGER UNSIGNED NOT NULL,

    INDEX `FK_xcart_distributor_contact_utility_xcart_distributor_utility`(`utility_id`),
    PRIMARY KEY (`contact_id`, `utility_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_distributor_contacts` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `distributor_field_code` VARCHAR(255) NOT NULL DEFAULT '',
    `distributor_field_name` VARCHAR(255) NOT NULL DEFAULT '',
    `contact_name` VARCHAR(255) NOT NULL DEFAULT '',
    `email` VARCHAR(255) NOT NULL DEFAULT '',
    `phone` VARCHAR(255) NOT NULL DEFAULT '',
    `ext` VARCHAR(255) NOT NULL DEFAULT '',
    `fax` VARCHAR(255) NOT NULL DEFAULT '',
    `pq` CHAR(1) NOT NULL DEFAULT 'N',
    `position` INTEGER UNSIGNED NOT NULL DEFAULT 0,

    INDEX `idxCode`(`distributor_field_code`),
    INDEX `idxManufacturereId`(`manufacturerid`),
    INDEX `idxName`(`distributor_field_name`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_distributor_return_address` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `warehouse_name` VARCHAR(255) NOT NULL DEFAULT '',
    `full_name` VARCHAR(255) NOT NULL DEFAULT '',
    `company` VARCHAR(255) NOT NULL DEFAULT '',
    `address` VARCHAR(255) NOT NULL DEFAULT '',
    `address_2` VARCHAR(255) NOT NULL DEFAULT '',
    `city` VARCHAR(255) NOT NULL DEFAULT '',
    `country` VARCHAR(255) NOT NULL DEFAULT '',
    `state` VARCHAR(255) NOT NULL DEFAULT '',
    `zipcode` VARCHAR(255) NOT NULL DEFAULT '',
    `email` VARCHAR(255) NOT NULL DEFAULT '',
    `phone` VARCHAR(255) NOT NULL DEFAULT '',
    `ext` VARCHAR(255) NOT NULL DEFAULT '',
    `fax` VARCHAR(255) NOT NULL DEFAULT '',

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_distributor_tabs` (
    `tab_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `content` MEDIUMTEXT NULL,
    `distributor_id` INTEGER NOT NULL,
    `position` INTEGER UNSIGNED NOT NULL DEFAULT 0,

    INDEX `FK_xcart_distributor_tabs_xcart_manufacturers`(`distributor_id`),
    PRIMARY KEY (`tab_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_distributor_taxes` (
    `distributor_taxes_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `distributor_id` INTEGER NOT NULL,
    `tax_id` INTEGER NOT NULL,

    INDEX `FK_xcart_distributor_taxes_xcart_taxes`(`tax_id`),
    UNIQUE INDEX `distributor_id_tax_id`(`distributor_id`, `tax_id`),
    PRIMARY KEY (`distributor_taxes_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_distributor_utility` (
    `utility_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,

    PRIMARY KEY (`utility_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_download_keys` (
    `download_key` CHAR(100) NOT NULL DEFAULT '',
    `expires` INTEGER NOT NULL DEFAULT 0,
    `productid` INTEGER NOT NULL DEFAULT 0,
    `itemid` INTEGER NOT NULL DEFAULT 0,

    UNIQUE INDEX `itemid`(`itemid`),
    INDEX `productid`(`productid`),
    PRIMARY KEY (`download_key`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_export_ranges` (
    `sec` VARCHAR(64) NOT NULL DEFAULT '',
    `id` VARCHAR(64) NOT NULL DEFAULT '',

    PRIMARY KEY (`sec`, `id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_external_verification_batches` (
    `batch_id` INTEGER NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(32) NOT NULL,
    `batch_start` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    `batch_number` INTEGER NOT NULL,
    `batch_amount` INTEGER NOT NULL,
    `batch_status` ENUM('In progress', 'Completed', 'Paid') NOT NULL DEFAULT 'In progress',
    `batch_product_speed` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `last_cross_verify_login` VARCHAR(32) NULL,
    `is_test` ENUM('Y', 'N', 'U') NOT NULL DEFAULT 'N',
    `test_failed` ENUM('Y', 'N') NOT NULL DEFAULT 'N',

    INDEX `external_ver_login_status`(`login`, `batch_status`),
    PRIMARY KEY (`batch_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_external_verification_feeds` (
    `feed_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `amazon_submition_id` VARCHAR(75) NOT NULL DEFAULT '0',
    `status` VARCHAR(50) NOT NULL,
    `feed_date` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    `login` VARCHAR(50) NOT NULL,

    UNIQUE INDEX `amazon_submition_id`(`amazon_submition_id`),
    INDEX `feed_date`(`feed_date`),
    PRIMARY KEY (`feed_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_external_verification_products` (
    `productid` INTEGER NOT NULL,
    `login` VARCHAR(32) NOT NULL,
    `batch_id` INTEGER NOT NULL,
    `action` ENUM('open', 'match', 'not_match', 'not_sure', 'not_found', 'comments_if_not', 'asin_on_amazon', 'lowest_total_price', 'competitors_count_FBA', 'competitors_count_MFN', 'product_image', 'product_names', 'product_description', 'qty_on_amazon', 'qty_on_our_website', 'arbitrage_confirmation', 'arbitrage_asin', 'arbitrage_amazon_qty', 'arbitrage_our_qty', 'arbitrage_confirmation_qty', 'arbitrage_confirmation_image', 'arbitrage_confirmation_name', 'arbitrage_confirmation_desc', 'listing_upload_asin') NOT NULL,
    `value` VARCHAR(255) NOT NULL,
    `action_date` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    INDEX `login`(`login`, `action`),
    INDEX `xcart_external_verification_products_ibfk_2`(`batch_id`),
    PRIMARY KEY (`productid`, `batch_id`, `action`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_external_verification_products_queue` (
    `productid` INTEGER NOT NULL,
    `status` ENUM('in progress', 'verified', 'etalon_match', 'etalon_not_match', 'etalon_not_found') NOT NULL DEFAULT 'in progress',
    `cross_verify_count` INTEGER NOT NULL DEFAULT 0,
    `asin` VARCHAR(255) NOT NULL,
    `position` INTEGER NOT NULL,
    `product_image` ENUM('different', 'same') NULL,
    `product_names` ENUM('contradict', 'not_contradict') NULL,
    `product_description` ENUM('contradict', 'not_contradict') NULL,
    `pack_qty_amazon` INTEGER NULL,
    `pack_qty_website` INTEGER NULL,
    `feed_id` INTEGER UNSIGNED NULL,
    `amz_listing_status` ENUM('submit_to_feed_failed', 'submitted_to_listing_loader', 'submit_to_feed_success') NULL,

    INDEX `FK_xcart_external_verification_feeds`(`feed_id`),
    INDEX `cross_verify_count`(`cross_verify_count`),
    INDEX `external_ver_queue_status`(`status`, `cross_verify_count`),
    PRIMARY KEY (`productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_extra_field_values` (
    `productid` INTEGER NOT NULL DEFAULT 0,
    `fieldid` INTEGER NOT NULL DEFAULT 0,
    `value` CHAR(255) NOT NULL DEFAULT '',

    INDEX `value`(`value`),
    PRIMARY KEY (`productid`, `fieldid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_extra_fields` (
    `fieldid` INTEGER NOT NULL AUTO_INCREMENT,
    `provider` CHAR(32) NOT NULL DEFAULT '',
    `field` CHAR(255) NOT NULL DEFAULT '',
    `value` CHAR(255) NOT NULL DEFAULT '',
    `active` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `service_name` CHAR(32) NOT NULL DEFAULT '',

    INDEX `active`(`active`),
    INDEX `provider`(`provider`),
    PRIMARY KEY (`fieldid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_extra_fields_lng` (
    `fieldid` INTEGER NOT NULL DEFAULT 0,
    `code` CHAR(2) NOT NULL DEFAULT 'US',
    `field` CHAR(255) NOT NULL DEFAULT '',

    UNIQUE INDEX `fc`(`fieldid`, `code`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_fba_inventory_receipts` (
    `inventory_id` INTEGER NOT NULL AUTO_INCREMENT,
    `received_date` DATE NOT NULL,
    `sku` VARCHAR(50) NOT NULL,
    `quantity` SMALLINT NOT NULL,
    `fba_shipment_id` VARCHAR(50) NOT NULL,

    INDEX `received_date`(`received_date`, `sku`),
    PRIMARY KEY (`inventory_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_fba_missing_sku` (
    `productid` INTEGER NULL,
    `missing_productcode` VARCHAR(52) NOT NULL,

    PRIMARY KEY (`missing_productcode`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_fba_roi_accounting` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `edate` DATE NOT NULL,
    `credit` DECIMAL(18, 2) NOT NULL DEFAULT 0.00,
    `debit` DECIMAL(18, 2) NOT NULL DEFAULT 0.00,
    `account` ENUM('notes_payable', 'cash', 'inventory', 'fba_expense', 'equity') NOT NULL,
    `comments` VARCHAR(50) NULL,
    `orderid` INTEGER NULL,
    `productid` INTEGER NULL,
    `source` ENUM('inventory_receipts', 'orders') NOT NULL DEFAULT 'orders',

    INDEX `account`(`account`, `orderid`, `productid`),
    INDEX `edate`(`edate`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_featured_products` (
    `productid` INTEGER NOT NULL DEFAULT 0,
    `categoryid` INTEGER NOT NULL DEFAULT 0,
    `product_order` INTEGER NOT NULL DEFAULT 0,
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `storefrontid` INTEGER NOT NULL DEFAULT 0,

    INDEX `avail`(`avail`),
    INDEX `pacpo`(`productid`, `avail`, `categoryid`, `product_order`),
    INDEX `product_order`(`product_order`),
    INDEX `storefrontid`(`storefrontid`),
    PRIMARY KEY (`productid`, `categoryid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_fedex_rates` (
    `r_id` INTEGER NOT NULL AUTO_INCREMENT,
    `r_zone` VARCHAR(6) NOT NULL DEFAULT '',
    `r_weight` VARCHAR(255) NOT NULL DEFAULT '0',
    `r_meth_id` INTEGER NOT NULL DEFAULT 0,
    `r_rate` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `r_ishundreds` INTEGER NOT NULL DEFAULT 0,
    `r_container` INTEGER NOT NULL DEFAULT 0,

    INDEX `r_meth_id`(`r_meth_id`),
    INDEX `r_rate`(`r_rate`),
    INDEX `r_zone`(`r_zone`),
    PRIMARY KEY (`r_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_fedex_zips` (
    `zip_id` INTEGER NOT NULL AUTO_INCREMENT,
    `zip_first` VARCHAR(5) NOT NULL DEFAULT '000',
    `zip_last` VARCHAR(5) NOT NULL DEFAULT '',
    `zip_zone` VARCHAR(6) NOT NULL DEFAULT '',
    `zip_meth` INTEGER NOT NULL DEFAULT 0,

    INDEX `zip_first`(`zip_first`),
    INDEX `zip_last`(`zip_last`),
    INDEX `zip_zone`(`zip_zone`),
    PRIMARY KEY (`zip_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_filter_preset_attention_tag_statuses` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `fid` INTEGER NOT NULL DEFAULT 0,
    `status_id` INTEGER NOT NULL DEFAULT 0,

    INDEX `idxfid`(`fid`),
    INDEX `idxstatus`(`status_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_filter_preset_distributors` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `fid` INTEGER NOT NULL DEFAULT 0,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_filter_preset_fraud_statuses` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `fid` INTEGER NOT NULL,
    `fraud_status` VARCHAR(1) NOT NULL DEFAULT '',

    INDEX `idx_fid`(`fid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_filter_preset_po_statuses` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `fid` INTEGER NOT NULL,
    `status` ENUM('uploaded', 'droped', 'entered') NOT NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_filter_preset_product_question_statuses` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `fid` INTEGER NOT NULL,
    `pq_status` VARCHAR(32) NOT NULL DEFAULT '',

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_filter_preset_ship_to_country` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `fid` INTEGER NOT NULL,
    `country_code` VARCHAR(32) NOT NULL DEFAULT '',

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_filter_preset_statuses` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `fid` INTEGER NOT NULL,
    `status` VARCHAR(2) NOT NULL DEFAULT '',

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_filter_preset_storefronts` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `fid` INTEGER NOT NULL,
    `storefrontid` VARCHAR(32) NOT NULL DEFAULT '0',

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_filter_presets` (
    `fid` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL DEFAULT '',
    `time_from_date` INTEGER NULL,
    `time_from` INTEGER NOT NULL DEFAULT 0,
    `time_from_mode` VARCHAR(1) NOT NULL DEFAULT 'D',
    `time_to` INTEGER NOT NULL DEFAULT 0,
    `placement_time_from_type` VARCHAR(1) NOT NULL DEFAULT 'O',
    `placement_time_to_type` VARCHAR(1) NOT NULL DEFAULT 'O',
    `enabled` VARCHAR(1) NOT NULL DEFAULT 'Y',
    `bold` VARCHAR(1) NOT NULL DEFAULT 'N',
    `preset_position` VARCHAR(255) NOT NULL DEFAULT '',
    `processor_empty` CHAR(1) NOT NULL DEFAULT '',
    `marker` VARCHAR(255) NOT NULL DEFAULT '',
    `orders_source` VARCHAR(32) NOT NULL DEFAULT 'any',
    `direct_link` VARCHAR(255) NOT NULL,

    PRIMARY KEY (`fid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_fingerprints` (
    `user_id` BIGINT UNSIGNED NOT NULL,
    `fingerprint` CHAR(32) NOT NULL,
    `created` TIMESTAMP(0) NULL DEFAULT CURRENT_TIMESTAMP(0),

    UNIQUE INDEX `xcart_fingerprints_user_id_fingerprint_uindex`(`user_id`, `fingerprint`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_fraud_check` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `question_code` VARCHAR(255) NOT NULL DEFAULT '',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `question_template_body` MEDIUMTEXT NULL,
    `importance_factor` VARCHAR(255) NOT NULL DEFAULT '',
    `auto` CHAR(1) NOT NULL DEFAULT '',

    UNIQUE INDEX `question_code`(`question_code`),
    INDEX `idx_id_orderby_auto`(`id`, `orderby`, `auto`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_froogle_options` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `storefrontid` INTEGER NOT NULL DEFAULT 0,
    `MerchantID` VARCHAR(100) NOT NULL DEFAULT '',
    `ClientID` VARCHAR(255) NOT NULL DEFAULT '',
    `BingMerchantID` VARCHAR(45) NOT NULL DEFAULT '',
    `BingCatalogID` VARCHAR(45) NOT NULL DEFAULT '',
    `enable_incremental_feed_updates` CHAR(1) NOT NULL DEFAULT '',

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_gcheckout_orders` (
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `goid` VARCHAR(255) NOT NULL DEFAULT '',
    `total` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `refunded_amount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `fulfillment_state` VARCHAR(255) NOT NULL DEFAULT '',
    `financial_state` VARCHAR(255) NOT NULL DEFAULT '',
    `state_log` TEXT NOT NULL,
    `archived` CHAR(1) NOT NULL DEFAULT 'N',

    INDEX `goid`(`goid`),
    PRIMARY KEY (`orderid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_gcheckout_restrictions` (
    `productid` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_ge_products` (
    `sessid` VARCHAR(40) NOT NULL DEFAULT '',
    `geid` VARCHAR(32) NOT NULL DEFAULT '',
    `productid` INTEGER NOT NULL DEFAULT 0,

    INDEX `geid`(`geid`),
    UNIQUE INDEX `sgp`(`sessid`, `geid`, `productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_geo_litecity_blocks` (
    `startIpNum` INTEGER UNSIGNED NOT NULL,
    `locId` INTEGER UNSIGNED NOT NULL,

    PRIMARY KEY (`startIpNum`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_geo_litecity_location` (
    `locId` INTEGER UNSIGNED NOT NULL,
    `country` CHAR(2) NOT NULL DEFAULT '',
    `region` CHAR(3) NOT NULL DEFAULT '',
    `city` VARCHAR(50) NOT NULL DEFAULT '',
    `postalCode` CHAR(10) NOT NULL DEFAULT '',
    `latitude` FLOAT NULL,
    `longitude` FLOAT NULL,
    `metroCode` INTEGER UNSIGNED NULL,
    `areaCode` INTEGER UNSIGNED NULL,

    PRIMARY KEY (`locId`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_giftcerts` (
    `gcid` VARCHAR(16) NOT NULL DEFAULT '',
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `purchaser` VARCHAR(64) NOT NULL DEFAULT '',
    `recipient` VARCHAR(64) NOT NULL DEFAULT '',
    `send_via` CHAR(1) NOT NULL DEFAULT 'E',
    `recipient_email` VARCHAR(64) NOT NULL DEFAULT '',
    `recipient_firstname` VARCHAR(128) NOT NULL DEFAULT '',
    `recipient_lastname` VARCHAR(128) NOT NULL DEFAULT '',
    `recipient_address` VARCHAR(64) NOT NULL DEFAULT '',
    `recipient_city` VARCHAR(64) NOT NULL DEFAULT '',
    `recipient_state` VARCHAR(32) NOT NULL DEFAULT '',
    `recipient_zipcode` VARCHAR(32) NOT NULL DEFAULT '',
    `recipient_country` CHAR(2) NOT NULL DEFAULT '',
    `recipient_phone` VARCHAR(32) NOT NULL DEFAULT '',
    `message` TEXT NOT NULL,
    `amount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `debit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `status` CHAR(1) NOT NULL DEFAULT 'P',
    `add_date` INTEGER NOT NULL DEFAULT 0,
    `block_date` INTEGER NOT NULL DEFAULT 0,
    `tpl_file` VARCHAR(255) NOT NULL DEFAULT 'template_default.tpl',
    `recipient_county` VARCHAR(32) NOT NULL DEFAULT '',
    `recipient_phone_ext` VARCHAR(32) NOT NULL DEFAULT '',

    INDEX `add_date`(`add_date`),
    INDEX `orderid`(`orderid`),
    INDEX `status`(`status`),
    PRIMARY KEY (`gcid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_ground_map` (
    `zipcode` VARCHAR(8) NOT NULL DEFAULT '',
    `map_url` VARCHAR(255) NOT NULL DEFAULT '',

    PRIMARY KEY (`zipcode`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_help_menu_content` (
    `item_content_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `form_type` VARCHAR(255) NULL,
    `answer` MEDIUMTEXT NOT NULL,
    `question` MEDIUMTEXT NOT NULL,
    `menu_id` INTEGER UNSIGNED NOT NULL,
    `order_by` INTEGER UNSIGNED NOT NULL DEFAULT 0,

    INDEX `FK_xcart_help_menu_content_xcart_help_menu_items`(`menu_id`),
    PRIMARY KEY (`item_content_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_help_menu_items` (
    `menu_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `icon` VARCHAR(255) NOT NULL,
    `active_icon` VARCHAR(255) NOT NULL,
    `order_by` INTEGER UNSIGNED NOT NULL DEFAULT 0,

    PRIMARY KEY (`menu_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_history_data` (
    `resourceid` INTEGER NOT NULL,
    `resource_type` ENUM('product', 'category', 'brand') NOT NULL,
    `changedate` DATETIME(0) NOT NULL,
    `fieldname` ENUM('cost_to_us', 'forsale', 'old_brand_id') NOT NULL,
    `value` VARCHAR(100) NOT NULL,

    PRIMARY KEY (`resourceid`, `resource_type`, `fieldname`, `changedate`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_images` (
    `image_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `path` VARCHAR(255) NULL,
    `width` SMALLINT UNSIGNED NULL,
    `height` SMALLINT UNSIGNED NULL,
    `created_at` TIMESTAMP(0) NULL DEFAULT CURRENT_TIMESTAMP(0),

    PRIMARY KEY (`image_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_images_A` (
    `imageid` INTEGER NOT NULL AUTO_INCREMENT,
    `id` INTEGER NOT NULL DEFAULT 0,
    `image` MEDIUMBLOB NOT NULL,
    `image_path` VARCHAR(255) NOT NULL DEFAULT '',
    `image_type` VARCHAR(64) NOT NULL DEFAULT 'image/jpeg',
    `image_x` INTEGER NOT NULL DEFAULT 0,
    `image_y` INTEGER NOT NULL DEFAULT 0,
    `image_size` INTEGER NOT NULL DEFAULT 0,
    `filename` VARCHAR(255) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `alt` VARCHAR(255) NOT NULL DEFAULT '',
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `md5` VARCHAR(32) NOT NULL DEFAULT '',

    UNIQUE INDEX `id`(`id`),
    INDEX `image_path`(`image_path`),
    PRIMARY KEY (`imageid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_images_B` (
    `imageid` INTEGER NOT NULL AUTO_INCREMENT,
    `id` INTEGER NOT NULL DEFAULT 0,
    `image` MEDIUMBLOB NOT NULL,
    `image_path` VARCHAR(255) NOT NULL DEFAULT '',
    `image_type` VARCHAR(64) NOT NULL DEFAULT 'image/jpeg',
    `image_x` INTEGER NOT NULL DEFAULT 0,
    `image_y` INTEGER NOT NULL DEFAULT 0,
    `image_size` INTEGER NOT NULL DEFAULT 0,
    `filename` VARCHAR(255) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `alt` VARCHAR(255) NOT NULL DEFAULT '',
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `md5` VARCHAR(32) NOT NULL DEFAULT '',

    UNIQUE INDEX `id`(`id`),
    INDEX `image_path`(`image_path`),
    PRIMARY KEY (`imageid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_images_C` (
    `imageid` INTEGER NOT NULL AUTO_INCREMENT,
    `id` INTEGER NOT NULL DEFAULT 0,
    `image` MEDIUMBLOB NOT NULL,
    `image_path` VARCHAR(255) NOT NULL DEFAULT '',
    `image_type` VARCHAR(64) NOT NULL DEFAULT 'image/jpeg',
    `image_x` INTEGER NOT NULL DEFAULT 0,
    `image_y` INTEGER NOT NULL DEFAULT 0,
    `image_size` INTEGER NOT NULL DEFAULT 0,
    `filename` VARCHAR(255) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `alt` VARCHAR(255) NOT NULL DEFAULT '',
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `md5` VARCHAR(32) NOT NULL DEFAULT '',

    UNIQUE INDEX `id`(`id`),
    INDEX `image_path`(`image_path`),
    PRIMARY KEY (`imageid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_images_D` (
    `imageid` INTEGER NOT NULL AUTO_INCREMENT,
    `id` INTEGER NOT NULL DEFAULT 0,
    `image` MEDIUMBLOB NOT NULL,
    `image_path` VARCHAR(255) NOT NULL DEFAULT '',
    `image_type` VARCHAR(64) NOT NULL DEFAULT 'image/jpeg',
    `image_x` INTEGER NOT NULL DEFAULT 0,
    `image_y` INTEGER NOT NULL DEFAULT 0,
    `image_size` INTEGER NOT NULL DEFAULT 0,
    `filename` VARCHAR(255) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `alt` VARCHAR(1024) NOT NULL DEFAULT '',
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `md5` VARCHAR(32) NULL,

    INDEX `id`(`id`),
    INDEX `image_path`(`image_path`),
    PRIMARY KEY (`imageid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_images_F` (
    `imageid` INTEGER NOT NULL AUTO_INCREMENT,
    `id` INTEGER NOT NULL DEFAULT 0,
    `image` MEDIUMBLOB NULL,
    `image_path` VARCHAR(255) NOT NULL DEFAULT '',
    `image_type` VARCHAR(64) NOT NULL DEFAULT 'image/jpeg',
    `image_x` INTEGER NOT NULL DEFAULT 0,
    `image_y` INTEGER NOT NULL DEFAULT 0,
    `image_size` INTEGER NOT NULL DEFAULT 0,
    `filename` VARCHAR(255) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `alt` VARCHAR(255) NOT NULL DEFAULT '',
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `md5` VARCHAR(32) NOT NULL DEFAULT '',

    UNIQUE INDEX `id`(`id`),
    INDEX `image_path`(`image_path`),
    PRIMARY KEY (`imageid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_images_M` (
    `imageid` INTEGER NOT NULL AUTO_INCREMENT,
    `id` INTEGER NOT NULL DEFAULT 0,
    `image` MEDIUMBLOB NOT NULL,
    `image_path` VARCHAR(255) NOT NULL DEFAULT '',
    `image_type` VARCHAR(64) NOT NULL DEFAULT 'image/jpeg',
    `image_x` INTEGER NOT NULL DEFAULT 0,
    `image_y` INTEGER NOT NULL DEFAULT 0,
    `image_size` INTEGER NOT NULL DEFAULT 0,
    `filename` VARCHAR(255) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `alt` VARCHAR(255) NOT NULL DEFAULT '',
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `md5` VARCHAR(32) NOT NULL DEFAULT '',

    UNIQUE INDEX `id`(`id`),
    INDEX `image_path`(`image_path`),
    PRIMARY KEY (`imageid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_images_P` (
    `imageid` INTEGER NOT NULL AUTO_INCREMENT,
    `id` INTEGER NOT NULL DEFAULT 0,
    `image` MEDIUMBLOB NOT NULL,
    `image_path` VARCHAR(255) NOT NULL DEFAULT '',
    `image_type` VARCHAR(64) NOT NULL DEFAULT 'image/jpeg',
    `image_x` INTEGER NOT NULL DEFAULT 0,
    `image_y` INTEGER NOT NULL DEFAULT 0,
    `image_size` INTEGER NOT NULL DEFAULT 0,
    `filename` VARCHAR(255) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `alt` VARCHAR(255) NOT NULL DEFAULT '',
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `md5` VARCHAR(32) NOT NULL DEFAULT '',

    UNIQUE INDEX `id`(`id`),
    PRIMARY KEY (`imageid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_images_R` (
    `imageid` INTEGER NOT NULL AUTO_INCREMENT,
    `id` INTEGER NOT NULL DEFAULT 0,
    `image` MEDIUMBLOB NOT NULL,
    `image_path` VARCHAR(255) NOT NULL DEFAULT '',
    `image_type` VARCHAR(64) NOT NULL DEFAULT 'image/jpeg',
    `image_x` INTEGER NOT NULL DEFAULT 0,
    `image_y` INTEGER NOT NULL DEFAULT 0,
    `image_size` INTEGER NOT NULL DEFAULT 0,
    `filename` VARCHAR(255) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `alt` VARCHAR(255) NOT NULL DEFAULT '',
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `md5` VARCHAR(32) NOT NULL DEFAULT '',

    INDEX `id`(`id`),
    INDEX `image_path`(`image_path`),
    PRIMARY KEY (`imageid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_images_S` (
    `imageid` INTEGER NOT NULL AUTO_INCREMENT,
    `id` INTEGER NOT NULL DEFAULT 0,
    `image` MEDIUMBLOB NOT NULL,
    `image_path` VARCHAR(255) NOT NULL DEFAULT '',
    `image_type` VARCHAR(64) NOT NULL DEFAULT 'image/jpeg',
    `image_x` INTEGER NOT NULL DEFAULT 0,
    `image_y` INTEGER NOT NULL DEFAULT 0,
    `image_size` INTEGER NOT NULL DEFAULT 0,
    `filename` VARCHAR(255) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `alt` VARCHAR(255) NOT NULL DEFAULT '',
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `md5` VARCHAR(32) NOT NULL DEFAULT '',

    UNIQUE INDEX `id`(`id`),
    INDEX `image_path`(`image_path`),
    PRIMARY KEY (`imageid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_images_T` (
    `imageid` INTEGER NOT NULL AUTO_INCREMENT,
    `id` INTEGER NOT NULL DEFAULT 0,
    `image` MEDIUMBLOB NOT NULL,
    `image_path` VARCHAR(255) NOT NULL DEFAULT '',
    `image_type` VARCHAR(64) NOT NULL DEFAULT 'image/jpeg',
    `image_x` INTEGER NOT NULL DEFAULT 0,
    `image_y` INTEGER NOT NULL DEFAULT 0,
    `image_size` INTEGER NOT NULL DEFAULT 0,
    `filename` VARCHAR(255) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `alt` VARCHAR(255) NOT NULL DEFAULT '',
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `md5` VARCHAR(32) NOT NULL DEFAULT '',

    UNIQUE INDEX `id`(`id`),
    INDEX `image_path`(`image_path`),
    PRIMARY KEY (`imageid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_images_W` (
    `imageid` INTEGER NOT NULL AUTO_INCREMENT,
    `id` INTEGER NOT NULL DEFAULT 0,
    `image` MEDIUMBLOB NOT NULL,
    `image_path` VARCHAR(255) NOT NULL DEFAULT '',
    `image_type` VARCHAR(64) NOT NULL DEFAULT 'image/jpeg',
    `image_x` INTEGER NOT NULL DEFAULT 0,
    `image_y` INTEGER NOT NULL DEFAULT 0,
    `image_size` INTEGER NOT NULL DEFAULT 0,
    `filename` VARCHAR(255) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `alt` VARCHAR(255) NOT NULL DEFAULT '',
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `md5` VARCHAR(32) NOT NULL DEFAULT '',

    UNIQUE INDEX `id`(`id`),
    INDEX `image_path`(`image_path`),
    PRIMARY KEY (`imageid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_images_splash` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `splash_name` VARCHAR(255) NULL,
    `image_path` VARCHAR(255) NULL,
    `comment` TEXT NULL,
    `active` ENUM('Y', 'N') NOT NULL DEFAULT 'N',

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_import_cache` (
    `data_type` CHAR(3) NOT NULL DEFAULT '',
    `id` VARCHAR(255) NOT NULL DEFAULT '',
    `value` VARCHAR(255) NOT NULL DEFAULT '',
    `login` VARCHAR(32) NOT NULL DEFAULT '',

    PRIMARY KEY (`data_type`, `id`, `login`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_info_pages` (
    `pageid` INTEGER NOT NULL AUTO_INCREMENT,
    `categoryid` INTEGER NOT NULL DEFAULT 0,
    `filename` VARCHAR(255) NOT NULL DEFAULT '',
    `title` VARCHAR(255) NOT NULL DEFAULT '',
    `level` CHAR(1) NOT NULL DEFAULT 'E',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `active` CHAR(1) NOT NULL DEFAULT 'Y',
    `language` CHAR(2) NOT NULL DEFAULT '',

    INDEX `orderby`(`level`, `orderby`, `title`),
    PRIMARY KEY (`pageid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_info_pages_categories` (
    `categoryid` INTEGER NOT NULL AUTO_INCREMENT,
    `parentid` INTEGER NOT NULL DEFAULT 0,
    `categoryid_path` VARCHAR(255) NOT NULL DEFAULT '',
    `category` VARCHAR(255) NOT NULL DEFAULT '',
    `description` TEXT NOT NULL,
    `meta_descr` VARCHAR(255) NOT NULL DEFAULT '',
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `order_by` INTEGER NOT NULL DEFAULT 0,
    `product_count` INTEGER NOT NULL DEFAULT 0,
    `meta_keywords` VARCHAR(255) NOT NULL DEFAULT '',

    INDEX `am`(`avail`),
    INDEX `avail`(`avail`),
    INDEX `category_path`(`parentid`, `categoryid_path`),
    INDEX `category_path2`(`categoryid`, `categoryid_path`),
    INDEX `ia`(`categoryid`, `avail`),
    INDEX `order_by`(`order_by`, `category`),
    INDEX `pa`(`categoryid_path`, `avail`),
    PRIMARY KEY (`categoryid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_info_pages_subcount` (
    `categoryid` INTEGER NOT NULL DEFAULT 0,
    `subcategory_count` INTEGER NOT NULL DEFAULT 0,
    `page_count` INTEGER NOT NULL DEFAULT 0,
    `membershipid` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`categoryid`, `membershipid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_inquiries` (
    `inq_id` INTEGER NOT NULL AUTO_INCREMENT,
    `inq_type_id` INTEGER NOT NULL DEFAULT 0,
    `inq_subject` VARCHAR(500) NOT NULL DEFAULT '',
    `status` CHAR(1) NOT NULL DEFAULT 'O',
    `datetime` INTEGER NOT NULL DEFAULT 0,
    `createdby_login` VARCHAR(32) NOT NULL DEFAULT '',
    `otrs_ticket_link` VARCHAR(300) NOT NULL DEFAULT '',
    `inq_email_subject` VARCHAR(300) NOT NULL DEFAULT '',

    PRIMARY KEY (`inq_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_inquiries_attention_tags` (
    `inq_tag_id` INTEGER NOT NULL AUTO_INCREMENT,
    `inquiry_attn_tag` VARCHAR(255) NOT NULL DEFAULT '',
    `active` CHAR(1) NOT NULL DEFAULT 'N',
    `tag_position` VARCHAR(32) NOT NULL DEFAULT '',

    PRIMARY KEY (`inq_tag_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_inquirires_tags` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `inq_id` INTEGER NOT NULL DEFAULT 0,
    `inq_tag_id` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_inquiry_types` (
    `inq_type_id` INTEGER NOT NULL AUTO_INCREMENT,
    `inquiry_type` VARCHAR(255) NOT NULL DEFAULT '',
    `active` CHAR(1) NOT NULL DEFAULT 'N',
    `type_position` VARCHAR(32) NOT NULL DEFAULT '',

    PRIMARY KEY (`inq_type_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_languages` (
    `code` CHAR(2) NOT NULL DEFAULT '',
    `name` VARCHAR(128) NOT NULL DEFAULT '',
    `value` MEDIUMTEXT NOT NULL,
    `topic` VARCHAR(24) NOT NULL DEFAULT '',

    INDEX `topic`(`topic`),
    PRIMARY KEY (`code`, `name`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_languages_alt` (
    `code` CHAR(2) NOT NULL DEFAULT '',
    `name` VARCHAR(128) NOT NULL DEFAULT '',
    `value` TEXT NOT NULL,

    PRIMARY KEY (`code`, `name`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_links_to_distributor_invoices` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `link_to_distributor_invoice` VARCHAR(255) NOT NULL DEFAULT '',
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `invoice_number` INTEGER NOT NULL DEFAULT 1,

    INDEX `idx_id_orderid_manufid`(`id`, `orderid`, `manufacturerid`),
    INDEX `idx_orderid`(`orderid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_links_to_distributor_memos` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `link_to_distributor_memo` VARCHAR(255) NOT NULL DEFAULT '',
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `memo_number` INTEGER NOT NULL DEFAULT 1,

    INDEX `idx_id_orderid_manufid`(`id`, `orderid`, `manufacturerid`),
    INDEX `idx_orderid`(`orderid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_locks` (
    `lock_type` ENUM('orders', 'purchase_order') NOT NULL,
    `last_time_visited` TIMESTAMP(0) NULL,
    `login` VARCHAR(32) NOT NULL,
    `entity_id` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`lock_type`, `entity_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_login_history` (
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `date_time` INTEGER NOT NULL DEFAULT 0,
    `usertype` CHAR(1) NOT NULL DEFAULT '',
    `action` VARCHAR(32) NOT NULL DEFAULT '',
    `status` VARCHAR(32) NOT NULL DEFAULT '',
    `ip` VARCHAR(32) NOT NULL DEFAULT '',

    PRIMARY KEY (`login`, `date_time`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_logs` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `resource_type` ENUM('orders', 'purchase_orders', 'amazon_product_verification', 'shipping_quotes', 'amazon_listings') NOT NULL,
    `resource_id` INTEGER NOT NULL,
    `type` ENUM('C', 'PP', 'X', 'S') NOT NULL,
    `login` VARCHAR(32) NULL,
    `date` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    `log` LONGTEXT NOT NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_mailchimp_newslists` (
    `listid` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `descr` TEXT NOT NULL,
    `show_as_news` CHAR(1) NOT NULL DEFAULT 'N',
    `avail` CHAR(1) NOT NULL DEFAULT 'N',
    `subscribe` CHAR(1) NOT NULL DEFAULT 'N',
    `lngcode` CHAR(2) NOT NULL DEFAULT 'US',
    `mc_list_id` VARCHAR(15) NULL DEFAULT '',
    `storefrontid` INTEGER NOT NULL,

    PRIMARY KEY (`listid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_manufacturer_feed_fields` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `feed_id` INTEGER NULL DEFAULT 0,
    `manufacturerid` INTEGER NULL DEFAULT 0,
    `field_name` VARCHAR(100) NOT NULL DEFAULT '',
    `locked` CHAR(1) NOT NULL DEFAULT '',
    `admin_lock` CHAR(1) NOT NULL DEFAULT 'Y',

    INDEX `manufacturerid`(`manufacturerid`),
    UNIQUE INDEX `f_m_fn`(`feed_id`, `manufacturerid`, `field_name`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_manufacturers` (
    `manufacturerid` INTEGER NOT NULL AUTO_INCREMENT,
    `manufacturer` VARCHAR(255) NOT NULL DEFAULT '',
    `url` VARCHAR(255) NOT NULL DEFAULT '',
    `descr` MEDIUMTEXT NOT NULL,
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `provider` VARCHAR(32) NOT NULL DEFAULT '',
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `manufact_text_displayed` MEDIUMTEXT NOT NULL,
    `mess_body` MEDIUMTEXT NOT NULL,
    `email` VARCHAR(128) NOT NULL DEFAULT '',
    `submit_to_operator` VARCHAR(64) NOT NULL DEFAULT 'through_distributor_website',
    `m_address` VARCHAR(64) NOT NULL DEFAULT '',
    `m_address_2` VARCHAR(64) NOT NULL DEFAULT '',
    `m_city` VARCHAR(64) NOT NULL DEFAULT '',
    `m_country` CHAR(2) NOT NULL DEFAULT 'US',
    `m_state` VARCHAR(32) NOT NULL DEFAULT 'NY',
    `m_zipcode` VARCHAR(32) NOT NULL DEFAULT '',
    `cart_manufact_text_displayed` MEDIUMTEXT NOT NULL,
    `code` VARCHAR(10) NOT NULL DEFAULT '',
    `catalog_sku` VARCHAR(32) NOT NULL DEFAULT '',
    `catalog_price` VARCHAR(16) NOT NULL DEFAULT '',
    `catalog_text` VARCHAR(255) NOT NULL DEFAULT '',
    `cost_to_us_coef_x` DECIMAL(12, 2) NOT NULL DEFAULT 1.00,
    `price_coef_x` DECIMAL(12, 2) NOT NULL DEFAULT 1.30,
    `price_coef_y` DECIMAL(12, 2) NOT NULL DEFAULT 0.50,
    `price_coef_z` DECIMAL(12, 2) NOT NULL DEFAULT 0.94,
    `map_price_coef_x` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `reverse_sku` CHAR(1) NOT NULL DEFAULT '',
    `remove_dashes` CHAR(1) NOT NULL DEFAULT '',
    `new_map_price_coef_x` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `d_product_catalog` VARCHAR(255) NOT NULL DEFAULT '',
    `d_price_list` VARCHAR(255) NOT NULL DEFAULT '',
    `d_map_policy` VARCHAR(255) NOT NULL DEFAULT '',
    `d_map_prices` VARCHAR(255) NOT NULL DEFAULT '',
    `d_shipping_weights_dimensions` VARCHAR(255) NOT NULL DEFAULT '',
    `d_website_search_for_sku_url` VARCHAR(255) NOT NULL DEFAULT '',
    `d_ships_to_within` VARCHAR(255) NOT NULL DEFAULT '',
    `d_shipping_methods_usps` CHAR(1) NOT NULL DEFAULT '',
    `d_shipping_methods_ups` CHAR(1) NOT NULL DEFAULT '',
    `d_shipping_methods_fedex` CHAR(1) NOT NULL DEFAULT '',
    `d_shipping_methods_trucking_company` CHAR(1) NOT NULL DEFAULT '',
    `d_shipping_methods_other` VARCHAR(255) NOT NULL DEFAULT '',
    `d_drop_ship_fee_select` VARCHAR(255) NOT NULL DEFAULT '',
    `d_drop_ship_fee_in_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `d_drop_ship_fee_type` ENUM('value', 'percent') NOT NULL DEFAULT 'value',
    `d_minimum_order_amount` VARCHAR(255) NOT NULL DEFAULT '',
    `d_minimum_order_amount_in_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `d_for_orders_below_min_order_amount` VARCHAR(255) NOT NULL DEFAULT '',
    `d_dealer_discount_reduced_from` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `d_dealer_discount_reduced_to` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `d_preferred_way_submit_orders` VARCHAR(255) NOT NULL DEFAULT 'through_distributor_website',
    `d_url_to_login_to_distributor_website` VARCHAR(255) NOT NULL DEFAULT '',
    `d_login` VARCHAR(255) NOT NULL DEFAULT '',
    `d_password` VARCHAR(255) NOT NULL DEFAULT '',
    `d_submit_to_order_entry_operator` CHAR(1) NOT NULL DEFAULT '',
    `d_order_entry_operator_email` VARCHAR(255) NOT NULL DEFAULT '',
    `d_instructions_to_order_entry_operator` MEDIUMTEXT NOT NULL,
    `d_tax_policy_in_states` VARCHAR(255) NOT NULL DEFAULT '',
    `d_warranty_starts_when_order_is` VARCHAR(255) NOT NULL DEFAULT '',
    `d_warranty_last_day` VARCHAR(255) NOT NULL DEFAULT '',
    `d_re_stocking_fee_for_authorized_returns` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `d_re_stocking_fee_for_unauthorized_returns` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `d_we_pay_to_distributor_by` VARCHAR(255) NOT NULL DEFAULT '',
    `d_net_payment_terms_in_days` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `d_bulk_or_individual_order_payments` ENUM('distributor_charges_for_each_order_separately', 'distributor_may_charge_for_several_orders_at_once', 'distributor_charges_for_each_order_twice_one_charge_for_products_and_one_charge_for_shipping') NOT NULL DEFAULT 'distributor_charges_for_each_order_separately',
    `d_our_dealer_account_n` VARCHAR(255) NOT NULL DEFAULT '',
    `d_available_on_distributor_site_checkbox` CHAR(1) NOT NULL DEFAULT '',
    `d_sent_by_email_to` CHAR(1) NOT NULL DEFAULT '',
    `d_put_on_the_invoices` CHAR(1) NOT NULL DEFAULT '',
    `d_invoices_sent_by_email_to` CHAR(1) NOT NULL DEFAULT '',
    `d_invoices_sent_by_fax_to` CHAR(1) NOT NULL DEFAULT '',
    `d_invoices_mailed_to_our_checkbox` CHAR(1) NOT NULL DEFAULT '',
    `d_available_on_distributor_site_url` VARCHAR(255) NOT NULL DEFAULT '',
    `d_sent_by_email_to_email_address` VARCHAR(255) NOT NULL DEFAULT '',
    `d_invoices_sent_to` VARCHAR(255) NOT NULL DEFAULT '',
    `d_invoices_by_fax_sent_to` VARCHAR(255) NOT NULL DEFAULT '',
    `d_invoices_mailed_to_our` VARCHAR(255) NOT NULL DEFAULT '',
    `d_availability_must_be_checked` ENUM('Y', 'N') NOT NULL DEFAULT 'N',
    `d_send_to_email_14` VARCHAR(255) NOT NULL DEFAULT '',
    `d_message_body_14` MEDIUMTEXT NOT NULL,
    `d_email_subject_14` VARCHAR(255) NOT NULL DEFAULT '',
    `d_link_to_order_distributors_website` VARCHAR(255) NOT NULL DEFAULT '',
    `d_sec14_show_header` CHAR(1) NOT NULL DEFAULT 'Y',
    `d_sec14_show_items_stock` CHAR(1) NOT NULL DEFAULT 'Y',
    `d_sec14_show_shipto` CHAR(1) NOT NULL DEFAULT 'Y',
    `d_sec14_show_items_cost` CHAR(1) NOT NULL DEFAULT 'Y',
    `d_sec14_show_footer` CHAR(1) NOT NULL DEFAULT 'Y',
    `lead_time_message` VARCHAR(255) NOT NULL DEFAULT '',
    `d_send_to_email_for_templates` VARCHAR(255) NOT NULL DEFAULT '',
    `d_server_min_distributor_time` INTEGER NOT NULL DEFAULT 0,
    `d_contact_name_for_templates` VARCHAR(255) NOT NULL DEFAULT '',
    `d_product_questions_send_to_email` VARCHAR(255) NOT NULL DEFAULT '',
    `d_shipping_options` VARCHAR(255) NOT NULL DEFAULT '',
    `d_specific_instructions` MEDIUMTEXT NULL,
    `d_subject_line_8` VARCHAR(255) NOT NULL DEFAULT '',
    `d_order_entry_operator_subject_line_8` VARCHAR(255) NOT NULL DEFAULT '',
    `d_main_sf` INTEGER NOT NULL DEFAULT 0,
    `d_enable_feed` CHAR(1) NOT NULL DEFAULT '',
    `d_feed_updation_frequency` VARCHAR(255) NOT NULL DEFAULT '',
    `d_ftp_host` VARCHAR(255) NOT NULL DEFAULT '',
    `d_ftp_login` VARCHAR(255) NOT NULL DEFAULT '',
    `d_ftp_password` VARCHAR(255) NOT NULL DEFAULT '',
    `d_ftp_folder` VARCHAR(255) NOT NULL DEFAULT '',
    `d_feed_procedure_id` VARCHAR(255) NOT NULL DEFAULT '',
    `d_product_management_team_email` VARCHAR(255) NOT NULL DEFAULT '',
    `d_most_recent_feed_updation_date` VARCHAR(255) NOT NULL DEFAULT '',
    `d_distributor_return_policy` MEDIUMTEXT NOT NULL,
    `product_feeds_comments` MEDIUMTEXT NOT NULL,
    `d_last_feed_rows_processed` VARCHAR(255) NOT NULL DEFAULT '',
    `d_validation_threshold` VARCHAR(255) NOT NULL DEFAULT '',
    `supplier_products_price_multiplier` DECIMAL(12, 2) NOT NULL DEFAULT 1.00,
    `recalc_supplier_products_price_multiplier` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `d_search_keyphrase_for_reconciliation` VARCHAR(255) NOT NULL DEFAULT '',
    `d_pay_to_distributor_by` VARCHAR(255) NOT NULL DEFAULT '',
    `d_we_can_save` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `d_pay_to_distributor_save_text` VARCHAR(255) NOT NULL DEFAULT '',
    `update_approximation_shipping_rates` CHAR(1) NOT NULL DEFAULT 'N',
    `shipping_rates_last_update_date` INTEGER NOT NULL DEFAULT 0,
    `USE_MY_UPS_FEDEX_ACCOUNT_functionality` CHAR(1) NOT NULL DEFAULT 'N',
    `products_quantity_behavior` ENUM('R', 'N', 'D') NOT NULL DEFAULT 'N',
    `display_quantity_of` INTEGER NOT NULL DEFAULT 1000,
    `USE_MY_TRUCKING_ACCOUNT_functionality` CHAR(1) NOT NULL DEFAULT 'N',
    `allow_pre_orders` CHAR(1) NOT NULL DEFAULT '',
    `amazon_leadtimetoship` INTEGER NOT NULL DEFAULT 7,
    `d_dispatch_instructions` MEDIUMTEXT NOT NULL,
    `add_ca_status_id` INTEGER NOT NULL DEFAULT 0,
    `warehouse_pickups_are_allowed` CHAR(1) NOT NULL DEFAULT 'N',
    `d_product_questions_send_to_name` VARCHAR(255) NOT NULL DEFAULT '',
    `d_product_questions_send_to_phone` VARCHAR(255) NOT NULL DEFAULT '',
    `allow_dispatch_off_working_hours` CHAR(1) NOT NULL DEFAULT '',
    `add_cost_to_us_column_to_dispatch_message` CHAR(1) NOT NULL DEFAULT '',
    `distributor_offers_free_shipping` VARCHAR(32) NOT NULL DEFAULT 'never',
    `free_shipping_on_orders_over_value` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `dcad_bank_name` VARCHAR(64) NOT NULL DEFAULT '',
    `dcad_address` VARCHAR(64) NOT NULL DEFAULT '',
    `dcad_address_2` VARCHAR(64) NOT NULL DEFAULT '',
    `dcad_city` VARCHAR(64) NOT NULL DEFAULT '',
    `dcad_country` VARCHAR(64) NOT NULL DEFAULT '',
    `dcad_state` VARCHAR(64) NOT NULL DEFAULT '',
    `dcad_zipcode` VARCHAR(64) NOT NULL DEFAULT '',
    `dcad_company_name` VARCHAR(64) NOT NULL DEFAULT '',
    `dcad_routing_number` VARCHAR(64) NOT NULL DEFAULT '',
    `dcad_account_number` VARCHAR(64) NOT NULL DEFAULT '',
    `dcad_swift` VARCHAR(64) NULL,
    `parent_manufacturer_id` INTEGER NOT NULL DEFAULT -1,
    `root_categoryid_for_cloned_products` INTEGER NOT NULL DEFAULT 0,
    `distributor_charges_for_each_order_twice_and_split_invoices` CHAR(1) NOT NULL DEFAULT 'N',
    `products_always_verify` CHAR(1) NOT NULL DEFAULT 'N',
    `days_before_verify` INTEGER NOT NULL DEFAULT 60,
    `reduce_extra_margin` ENUM('Y', 'N') NOT NULL DEFAULT 'N',
    `max_extra_margin` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `amazon_leadtime_to_ship` TINYINT UNSIGNED NOT NULL DEFAULT 5,
    `amazon_leadtime_for_fba_loads` TINYINT UNSIGNED NULL DEFAULT 5,
    `dx_leadtime` TINYINT UNSIGNED NULL DEFAULT 0,
    `dx_leadtime_to` TINYINT UNSIGNED NULL DEFAULT 0,
    `calculate_shipping` CHAR(1) NOT NULL DEFAULT 'N',
    `d_shiiping_a_coeff` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `d_shipping_b_coeff` DECIMAL(12, 2) NOT NULL DEFAULT 1.00,
    `d_currency` MEDIUMINT UNSIGNED NOT NULL DEFAULT 1,
    `update_prices` BOOLEAN NOT NULL DEFAULT false,
    `dx_eta_date` DATE NULL,
    `logo` VARCHAR(512) NULL,
    `disabled_reason` TEXT NULL,
    `shipping_last_update_date` DATE NULL,
    `d_questionable_1` TINYINT NOT NULL DEFAULT 0,
    `d_questionable_2` TINYINT NOT NULL DEFAULT 0,
    `d_questionable_3` TINYINT NOT NULL DEFAULT 0,
    `d_questionable_4` TINYINT NOT NULL DEFAULT 0,
    `d_questionable_5` TINYINT NOT NULL DEFAULT 0,
    `d_questionable_6` TINYINT NOT NULL DEFAULT 0,
    `d_questionable_7` TINYINT NOT NULL DEFAULT 0,
    `d_questionable_8` TINYINT NOT NULL DEFAULT 0,
    `d_questionable_9` TINYINT NOT NULL DEFAULT 0,
    `d_questionable_10` TINYINT NOT NULL DEFAULT 0,
    `d_questionable_11` TINYINT NOT NULL DEFAULT 0,
    `d_questionable_12` TINYINT NOT NULL DEFAULT 0,
    `d_questionable_13` TINYINT NOT NULL DEFAULT 0,
    `request_avail_template_id` INTEGER NULL,
    `order_entry_template_id` INTEGER NULL,
    `order_submit_template_id` INTEGER NULL,
    `order_entry_special_instructions` MEDIUMTEXT NULL,
    `order_submit_special_instructions` MEDIUMTEXT NULL,
    `dx_paypal_account_email` VARCHAR(100) NULL,
    `d_frontend_return_policy` MEDIUMTEXT NULL,
    `created_at` TIMESTAMP(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
    `is_moderated` TINYINT UNSIGNED NULL DEFAULT 0,

    UNIQUE INDEX `code`(`code`),
    INDEX `FK_xcart_manufacturers_xcart_currencies`(`d_currency`),
    INDEX `FK_xcart_manufacturers_xcart_templates_for_communication`(`request_avail_template_id`),
    INDEX `FK_xcart_manufacturers_xcart_templates_for_communication_2`(`order_entry_template_id`),
    INDEX `FK_xcart_manufacturers_xcart_templates_for_communication_3`(`order_submit_template_id`),
    INDEX `avail`(`avail`),
    INDEX `created_at`(`created_at`),
    INDEX `d_availability_must_be_checked`(`d_availability_must_be_checked`),
    INDEX `manufacturer`(`manufacturer`),
    INDEX `orderby`(`orderby`),
    INDEX `provider`(`provider`),
    PRIMARY KEY (`manufacturerid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_manufacturers_carrier` (
    `manufacturer_id` INTEGER NOT NULL,
    `carrier_id` INTEGER NOT NULL,

    INDEX `FK_xcart_manufacturers_carrier_xcart_tracking_links_carrier`(`carrier_id`),
    PRIMARY KEY (`manufacturer_id`, `carrier_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_manufacturers_lng` (
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `code` CHAR(2) NOT NULL DEFAULT 'US',
    `manufacturer` VARCHAR(255) NOT NULL DEFAULT '',
    `descr` MEDIUMTEXT NOT NULL,

    UNIQUE INDEX `mc`(`manufacturerid`, `code`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_manufacturers_site` (
    `manufacturer_id` INTEGER NOT NULL,
    `site_id` INTEGER NOT NULL,

    INDEX `FK_xcart_manufacturers_site_xcart_storefronts`(`site_id`),
    PRIMARY KEY (`manufacturer_id`, `site_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_memberships` (
    `membershipid` INTEGER NOT NULL AUTO_INCREMENT,
    `area` CHAR(1) NOT NULL DEFAULT 'C',
    `membership` VARCHAR(255) NOT NULL DEFAULT '',
    `active` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `flag` CHAR(2) NOT NULL DEFAULT '',
    `slug` VARCHAR(50) NULL,

    UNIQUE INDEX `slug`(`slug`),
    INDEX `active`(`active`),
    INDEX `area`(`area`),
    INDEX `orderby`(`orderby`),
    PRIMARY KEY (`membershipid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_memberships_lng` (
    `membershipid` INTEGER NOT NULL DEFAULT 0,
    `code` CHAR(2) NOT NULL DEFAULT 'US',
    `membership` VARCHAR(255) NOT NULL DEFAULT '',

    UNIQUE INDEX `mc`(`membershipid`, `code`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_modules` (
    `moduleid` INTEGER NOT NULL AUTO_INCREMENT,
    `module_name` VARCHAR(255) NOT NULL DEFAULT '',
    `module_descr` VARCHAR(255) NOT NULL DEFAULT '',
    `active` CHAR(1) NOT NULL DEFAULT 'Y',

    UNIQUE INDEX `module_name`(`module_name`),
    INDEX `active`(`active`),
    PRIMARY KEY (`moduleid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_newsletter` (
    `newsid` INTEGER NOT NULL AUTO_INCREMENT,
    `subject` VARCHAR(128) NOT NULL DEFAULT '',
    `body` TEXT NOT NULL,
    `send_date` INTEGER NOT NULL DEFAULT 0,
    `email1` VARCHAR(128) NOT NULL DEFAULT '',
    `email2` VARCHAR(128) NOT NULL DEFAULT '',
    `email3` VARCHAR(128) NOT NULL DEFAULT '',
    `status` CHAR(1) NOT NULL DEFAULT 'N',
    `listid` INTEGER NOT NULL DEFAULT 0,
    `show_as_news` CHAR(1) NOT NULL DEFAULT 'N',
    `allow_html` CHAR(1) NOT NULL DEFAULT 'N',

    INDEX `send_date`(`send_date`),
    INDEX `status`(`status`),
    PRIMARY KEY (`newsid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_newslist_subscription` (
    `listid` INTEGER NOT NULL DEFAULT 0,
    `email` CHAR(128) NOT NULL DEFAULT '',
    `since_date` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`listid`, `email`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_newslists` (
    `listid` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `descr` TEXT NOT NULL,
    `show_as_news` CHAR(1) NOT NULL DEFAULT 'N',
    `avail` CHAR(1) NOT NULL DEFAULT 'N',
    `subscribe` CHAR(1) NOT NULL DEFAULT 'N',
    `lngcode` CHAR(2) NOT NULL DEFAULT 'US',
    `storefrontid` INTEGER NOT NULL DEFAULT 0,

    INDEX `storefrontid`(`storefrontid`),
    PRIMARY KEY (`listid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_notify_when_in_stock` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `productid` INTEGER NOT NULL DEFAULT 0,
    `first_name` VARCHAR(50) NOT NULL,
    `email` VARCHAR(255) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `sent` CHAR(1) NOT NULL DEFAULT 'N',
    `storefrontid` INTEGER NOT NULL DEFAULT 0,

    INDEX `email`(`email`, `sent`, `productid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_off_hours_messages` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `message` TEXT NOT NULL,

    UNIQUE INDEX `orderid_manufacturerid`(`orderid`, `manufacturerid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_old_passwords` (
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `password` VARCHAR(32) NOT NULL DEFAULT '',

    PRIMARY KEY (`login`, `password`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_one_time_passwords` (
    `one_time_password_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `one_time_password` CHAR(6) NULL,
    `created` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    `attempts_number` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `confirmed` BOOLEAN NULL DEFAULT false,
    `label` VARCHAR(64) NOT NULL,
    `expired` VARCHAR(32) NOT NULL,

    INDEX `xcart_one_time_passwords_created_index`(`created`),
    UNIQUE INDEX `xcart_one_time_passwords_user_id_label_uindex`(`user_id`, `label`),
    PRIMARY KEY (`one_time_password_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_option_variants` (
    `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `option_id` MEDIUMINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `value` VARCHAR(255) NULL,
    `position` SMALLINT UNSIGNED NOT NULL DEFAULT 10,

    INDEX `FK_xcart_option_variants_xcart_options`(`option_id`),
    INDEX `position`(`position`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_options` (
    `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `type` ENUM('select', 'color', 'radio') NOT NULL DEFAULT 'select',

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_additional_fee` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `additional_fee_name` VARCHAR(255) NOT NULL DEFAULT '',
    `additional_fee_value` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_amazon_details` (
    `AmazonShipmentID` VARCHAR(50) NOT NULL,
    `SKU` VARCHAR(64) NOT NULL,
    `AmazonOrderItemCode` VARCHAR(30) NOT NULL,
    `FBAPerOrderFulfillmentFee` DECIMAL(12, 2) NOT NULL,
    `FBAPerUnitFulfillmentFee` DECIMAL(12, 2) NOT NULL,
    `FBAWeightBasedFee` DECIMAL(12, 2) NOT NULL,
    `FBATransportationFee` DECIMAL(12, 2) NOT NULL,
    `ShippingFee` DECIMAL(12, 2) NOT NULL,
    `AmazonCommission` DECIMAL(12, 2) NOT NULL,
    `Principal` DECIMAL(12, 2) NOT NULL,
    `PrincipalRefund` DECIMAL(12, 2) NOT NULL,
    `Shipping` DECIMAL(12, 2) NOT NULL,
    `ShippingRefund` DECIMAL(12, 2) NOT NULL,
    `Refund` DECIMAL(12, 2) NOT NULL,
    `Quantity` INTEGER NOT NULL DEFAULT 1,
    `orderid` INTEGER NOT NULL,
    `type` ENUM('Fee', 'Refund') NOT NULL DEFAULT 'Fee',
    `manufacturerid` INTEGER NOT NULL,
    `reportId` BIGINT UNSIGNED NOT NULL,

    INDEX `AmazonOrderItemCode`(`AmazonOrderItemCode`),
    INDEX `SKU`(`SKU`),
    INDEX `orderid`(`orderid`, `manufacturerid`),
    PRIMARY KEY (`AmazonShipmentID`, `SKU`, `AmazonOrderItemCode`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_cx_invoices` (
    `orderid` INTEGER NOT NULL,
    `invoice_order_number` SMALLINT UNSIGNED NOT NULL,
    `invoice_number` VARCHAR(25) NOT NULL,
    `status` VARCHAR(50) NOT NULL,
    `payer_email` VARCHAR(75) NOT NULL,
    `payment_request_subject` VARCHAR(255) NOT NULL,
    `short_payment_description` VARCHAR(255) NOT NULL,
    `amount` DECIMAL(12, 2) NOT NULL,
    `currency` VARCHAR(3) NOT NULL,
    `invoice_date` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    UNIQUE INDEX `invoice_number`(`invoice_number`),
    PRIMARY KEY (`orderid`, `invoice_order_number`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_details` (
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `productid` INTEGER NOT NULL DEFAULT 0,
    `order_group_id` INTEGER NULL,
    `price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `amount` INTEGER NOT NULL DEFAULT 0,
    `back` INTEGER NOT NULL,
    `provider` VARCHAR(32) NOT NULL DEFAULT '',
    `product_options` LONGTEXT NULL,
    `extra_data` LONGTEXT NOT NULL,
    `itemid` INTEGER NOT NULL AUTO_INCREMENT,
    `productcode` VARCHAR(255) NOT NULL DEFAULT '',
    `product` VARCHAR(600) NOT NULL DEFAULT '',
    `original_provider` VARCHAR(32) NOT NULL DEFAULT '',
    `items_stock` INTEGER NULL,
    `item_cost_to_us` DECIMAL(12, 2) NULL,
    `offer_backorder` CHAR(1) NOT NULL DEFAULT '',
    `stock_request_status` VARCHAR(32) NOT NULL DEFAULT '',
    `FBAPerOrderFulfillmentFee` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `FBAPerUnitFulfillmentFee` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `FBAWeightBasedFee` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `ShippingFee` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `AmazonCommission` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `AmazonOrderItemCode` VARCHAR(30) NOT NULL DEFAULT '',
    `amazon_item_refunded` CHAR(1) NOT NULL DEFAULT 'N',
    `retail_trust_item` ENUM('N', 'Y') NOT NULL DEFAULT 'N',
    `retail_trust_price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `coupon_discount` DECIMAL(12, 3) NULL,
    `amazon_price` DECIMAL(12, 2) NULL,
    `amazon_shipping` DECIMAL(12, 2) NULL,

    INDEX `FK_xcart_order_details_xcart_order_groups`(`order_group_id`),
    INDEX `orderid`(`orderid`),
    INDEX `orderid_productid`(`orderid`, `productid`),
    INDEX `productcode`(`productcode`),
    INDEX `productid`(`productid`),
    INDEX `provider`(`provider`),
    UNIQUE INDEX `orderid_productcode`(`orderid`, `productcode`),
    PRIMARY KEY (`itemid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_events` (
    `event_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_id` BIGINT UNSIGNED NOT NULL,
    `created_at` DATETIME(0) NOT NULL,
    `message` VARCHAR(225) NULL,
    `user_id` INTEGER NULL,

    INDEX `order_id`(`order_id`),
    PRIMARY KEY (`event_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_extras` (
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `khash` VARCHAR(64) NOT NULL DEFAULT '',
    `value` TEXT NOT NULL,

    PRIMARY KEY (`orderid`, `khash`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_fraud_checks` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `question_code` VARCHAR(60) NOT NULL DEFAULT '',
    `manual_action` CHAR(1) NOT NULL DEFAULT '',
    `fraud_score` DECIMAL(6, 2) NOT NULL DEFAULT 0.00,
    `bare_fraud_score` DECIMAL(6, 2) NOT NULL DEFAULT 0.00,
    `fraud_result` VARCHAR(15) NOT NULL DEFAULT '',
    `additional_info` LONGTEXT NOT NULL,

    INDEX `idx_orderid`(`orderid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_fraud_statuses` (
    `code` CHAR(1) NOT NULL DEFAULT '',
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `order_by` INTEGER NOT NULL DEFAULT 0,

    INDEX `idx_code`(`code`),
    INDEX `idx_code_orderby`(`code`, `order_by`),
    PRIMARY KEY (`code`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_group_invoices` (
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `invoice_number` INTEGER NOT NULL DEFAULT 0,
    `invoice_received` CHAR(1) NOT NULL DEFAULT 'N',
    `cost_to_us_for_products_charged` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `tax_charged_except_HST` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `products_total` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `shipping_charged` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `drop_ship_fee_charged` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `shipping_total` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `HST_charged` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `invoice_total` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `extra_items_on_invoice` CHAR(1) NOT NULL DEFAULT 'N',
    `items_shipped_to_wrong_address` CHAR(1) NOT NULL DEFAULT 'N',
    `status` CHAR(1) NOT NULL DEFAULT 'A',
    `part_of_total_transaction_in_amount_of` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `reconciliation_id` INTEGER NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
    `invoice_date` DATE NULL,
    `payment_due_date` DATE NULL,
    `update_date` DATETIME(0) NULL,
    `dx_invoice_number` VARCHAR(255) NULL,

    INDEX `manufacturerid`(`manufacturerid`),
    INDEX `orderid`(`orderid`),
    INDEX `reconciliation_id`(`reconciliation_id`),
    PRIMARY KEY (`orderid`, `manufacturerid`, `invoice_number`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_group_invoices_products` (
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `invoice_number` INTEGER NOT NULL DEFAULT 0,
    `itemid` INTEGER NOT NULL DEFAULT 0,
    `item_string` VARCHAR(255) NOT NULL DEFAULT '',
    `item_type` TINYINT NOT NULL DEFAULT 0,
    `unit_cost` DECIMAL(12, 3) NOT NULL DEFAULT 0.000,
    `qty_inv` INTEGER NOT NULL DEFAULT 0,
    `unit_cost_total` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `customer_id` INTEGER NULL,
    `product_id` INTEGER NULL,
    `updated_at` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    INDEX `itemid`(`itemid`),
    INDEX `manufacturerid`(`manufacturerid`),
    INDEX `orderid`(`orderid`),
    PRIMARY KEY (`orderid`, `manufacturerid`, `invoice_number`, `itemid`, `item_string`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_group_memos` (
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `memo_number` INTEGER NOT NULL DEFAULT 0,
    `memo_received` CHAR(1) NOT NULL DEFAULT 'N',
    `memo_descr` VARCHAR(255) NOT NULL DEFAULT '',
    `ref_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `ref_to_us_HST` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `ref_to_us_total` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `status` CHAR(1) NOT NULL DEFAULT 'A',
    `ref_to_us_part_of_transaction` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `reconciliation_id` INTEGER NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    `memo_date` DATE NULL,
    `update_date` DATETIME(0) NULL,
    `dx_invoice_number` VARCHAR(255) NULL,

    INDEX `manufacturerid`(`manufacturerid`),
    INDEX `orderid`(`orderid`),
    INDEX `reconciliation_id`(`reconciliation_id`),
    PRIMARY KEY (`orderid`, `manufacturerid`, `memo_number`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_group_taxes` (
    `order_group_id` INTEGER NOT NULL,
    `tax_rate_id` INTEGER NOT NULL,
    `value` DECIMAL(12, 2) NULL DEFAULT 0.00,

    PRIMARY KEY (`order_group_id`, `tax_rate_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_groups` (
    `order_group_id` INTEGER NOT NULL AUTO_INCREMENT,
    `orderid` INTEGER NULL,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `shippingid` INTEGER NULL,
    `shipping` VARCHAR(255) NOT NULL DEFAULT '',
    `real_shipping_method` VARCHAR(255) NULL DEFAULT '',
    `cb_status` CHAR(2) NULL,
    `dc_status` CHAR(2) NULL,
    `bd_status` CHAR(2) NULL,
    `c2a_status` CHAR(2) NULL,
    `a2c_status` CHAR(2) NULL,
    `a2b_status` CHAR(2) NULL,
    `d2a_status` CHAR(2) NULL,
    `tracking` TEXT NULL,
    `notify_sent` CHAR(1) NOT NULL DEFAULT 'N',
    `coupon_discount` DECIMAL(12, 3) NULL,
    `total_net` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `total_gst` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `total_pst` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `total_tax` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `total_gross` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `product_net` DECIMAL(12, 2) NULL,
    `product_gst` DECIMAL(12, 2) NULL,
    `product_pst` DECIMAL(12, 2) NULL,
    `product_gross` DECIMAL(12, 2) NULL,
    `shipping_net` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `shipping_gst` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `shipping_pst` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `shipping_gross` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting` TEXT NULL,
    `profit_margin` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `acc_paymentid` INTEGER NOT NULL DEFAULT 0,
    `manufacturer_data` TEXT NULL,
    `dc_dispatched_time` INTEGER NOT NULL DEFAULT 0,
    `actual_shipping_net` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `actual_shipping_gst` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `actual_shipping_pst` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `actual_shipping_gross` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `shipping_value_selectbox` VARCHAR(64) NOT NULL DEFAULT 'actual_shipping_cost',
    `additional_shipping_status` CHAR(1) NOT NULL DEFAULT '',
    `additional_vt_paymentid` INTEGER NOT NULL DEFAULT 0,
    `additional_transaction_id_link` VARCHAR(255) NULL DEFAULT '',
    `additional_avs_code` VARCHAR(3) NULL DEFAULT '',
    `ru_status` CHAR(2) NULL DEFAULT '',
    `time_to_dispatch` INTEGER NOT NULL DEFAULT 0,
    `po_status` CHAR(2) NOT NULL DEFAULT 'PN',
    `stock_request_shipping_cost` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `dc_received_by_distributor_time` INTEGER NOT NULL DEFAULT 0,
    `accounting_net_0` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gst_0` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_pst_0` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gross_0` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_filled_0` CHAR(1) NULL DEFAULT '',
    `accounting_net_1_cost_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gst_1_cost_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_pst_1_cost_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gross_1_cost_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_filled_1_cost_to_us` CHAR(1) NULL DEFAULT '',
    `accounting_net_2_shipping` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gst_2_shipping` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_pst_2_shipping` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gross_2_shipping` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_filled_2_shipping` CHAR(1) NULL DEFAULT '',
    `accounting_net_3_ref_to_cust` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gst_3_ref_to_cust` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_pst_3_ref_to_cust` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gross_3_ref_to_cust` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_filled_3_ref_to_cust` CHAR(1) NULL DEFAULT '',
    `accounting_net_4_ref_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gst_4_ref_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_pst_4_ref_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gross_4_ref_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_filled_4_ref_to_us` CHAR(1) NULL DEFAULT '',
    `accounting_net_5_profit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gst_5_profit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_pst_5_profit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gross_5_profit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_filled_5_profit` CHAR(1) NULL DEFAULT '',
    `OLD_accounting` TEXT NULL,
    `paid_date` INTEGER NOT NULL DEFAULT 0,
    `order_entry_flag` CHAR(1) NULL DEFAULT '',
    `amz_send_with_notes` ENUM('Y', 'N') NULL DEFAULT 'N',
    `amz_customer_notes` TEXT NULL,
    `amz_fullfilment_order_placed` ENUM('Y', 'N') NULL DEFAULT 'N',
    `cb_update_datetime` DATETIME(0) NULL,
    `dc_update_datetime` DATETIME(0) NULL,
    `shipping_quote` DECIMAL(12, 2) NULL,
    `distributor_price_multiplier` DECIMAL(12, 2) NOT NULL DEFAULT 1.00,
    `dispatched_by_amazon` ENUM('Y', 'N') NULL DEFAULT 'N',
    `voided_reason` VARCHAR(255) NULL,

    INDEX `FK2_order_groups_dc_status`(`dc_status`),
    INDEX `FK3_order_groups_bd_status`(`bd_status`),
    INDEX `cb_status_dc_status`(`cb_status`, `dc_status`),
    INDEX `idx_orderid_cb`(`orderid`, `cb_status`),
    INDEX `manufacturerid`(`manufacturerid`),
    UNIQUE INDEX `orderid_manufacturerid`(`orderid`, `manufacturerid`),
    PRIMARY KEY (`order_group_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_logs` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `orderid` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
    `type` CHAR(2) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `login` VARCHAR(40) NOT NULL DEFAULT '',
    `log` MEDIUMTEXT NOT NULL,

    INDEX `idx_id_orderid`(`id`, `orderid`),
    INDEX `idx_id_orderid_type_date`(`id`, `orderid`, `type`, `date`),
    INDEX `orderid`(`orderid`),
    INDEX `type`(`type`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_page_permissions` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `element_id` VARCHAR(255) NOT NULL DEFAULT '',
    `membership_ids` VARCHAR(255) NOT NULL DEFAULT '',

    INDEX `element_id`(`element_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_status_availability` (
    `source_status_id` INTEGER UNSIGNED NOT NULL,
    `destination_status_id` INTEGER UNSIGNED NOT NULL,

    INDEX `FK_xcart_order_status_availability_xcart_order_statuses`(`destination_status_id`),
    PRIMARY KEY (`source_status_id`, `destination_status_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_status_notifications` (
    `code` VARCHAR(2) NOT NULL DEFAULT '',
    `customer_subject` VARCHAR(255) NOT NULL DEFAULT '',
    `copy_subject` VARCHAR(255) NOT NULL DEFAULT '',
    `email_body` TEXT NOT NULL,
    `enabled` CHAR(1) NOT NULL DEFAULT 'Y',
    `customer_attach_pdf_invoice` CHAR(1) NOT NULL DEFAULT '',
    `admin_attach_pdf_invoice` CHAR(1) NOT NULL DEFAULT '',

    PRIMARY KEY (`code`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_statuses` (
    `status_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` CHAR(2) NOT NULL DEFAULT '',
    `name` CHAR(255) NOT NULL DEFAULT '',
    `description` MEDIUMTEXT NULL,
    `type` CHAR(2) NOT NULL DEFAULT '',
    `orderby` INTEGER UNSIGNED NOT NULL DEFAULT 0,

    UNIQUE INDEX `code`(`code`),
    INDEX `orderby`(`orderby`),
    INDEX `type2`(`type`),
    PRIMARY KEY (`status_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_statuses_history` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `group_id` INTEGER NULL,
    `status` VARCHAR(31) NULL,
    `old_status` VARCHAR(31) NULL,
    `updated` TIMESTAMP(0) NULL DEFAULT CURRENT_TIMESTAMP(0),

    INDEX `adshf9h9h2934f`(`group_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_tracking` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_group_id` INTEGER NOT NULL,
    `linkid` INTEGER NULL,
    `tracknum` VARCHAR(255) NULL,
    `shipping_date` DATE NOT NULL,
    `carrier_id` INTEGER NOT NULL,
    `aftership_id` VARCHAR(50) NULL,
    `send_to_amazon` TINYINT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    INDEX `FK_xcart_order_tracking_xcart_tracking_links`(`linkid`),
    INDEX `FK_xcart_order_tracking_xcart_tracking_links_carrier`(`carrier_id`),
    UNIQUE INDEX `order_group_id_tracknum`(`order_group_id`, `tracknum`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_transactions` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `paymentid` INTEGER NOT NULL DEFAULT 0,
    `transaction_id` VARCHAR(64) NOT NULL DEFAULT '',
    `type` ENUM('authorization', 'capture', 'refund') NULL,
    `transaction_status` VARCHAR(32) NOT NULL DEFAULT '',
    `transaction_currency` VARCHAR(3) NOT NULL DEFAULT 'USD',
    `transaction_amount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `transaction_fee` DECIMAL(12, 2) NULL,
    `date` INTEGER NOT NULL DEFAULT 0,
    `login` VARCHAR(50) NOT NULL DEFAULT '',
    `transaction_response` MEDIUMTEXT NULL,
    `manual_transaction` CHAR(1) NOT NULL DEFAULT '',
    `parent_id` INTEGER NULL,
    `parent_transaction_id` VARCHAR(64) NULL,

    INDEX `FK_xcart_order_transactions_xcart_order_transactions`(`parent_id`),
    INDEX `orderid`(`orderid`),
    UNIQUE INDEX `transaction_id`(`transaction_id`, `orderid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_user_actives` (
    `user_id` BIGINT NOT NULL,
    `order_id` BIGINT NOT NULL,
    `created_at` DATETIME(0) NOT NULL,
    `action` ENUM('tab', 'view', 'calldx', 'callcx', 'callship') NULL,

    INDEX `user_id_created_at_action`(`action`, `user_id`, `created_at`),
    PRIMARY KEY (`user_id`, `order_id`, `created_at`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_order_user_actives_last` (
    `user_id` BIGINT NOT NULL,
    `order_id` BIGINT NOT NULL,
    `created_at` DATETIME(0) NOT NULL,

    PRIMARY KEY (`user_id`, `order_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_orders` (
    `orderid` INTEGER NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `order_prefix` VARCHAR(50) NOT NULL,
    `order_type` ENUM('XCART', 'MFN', 'FBA', 'FB') NOT NULL DEFAULT 'XCART',
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `membership` VARCHAR(255) NOT NULL DEFAULT '',
    `total` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `giftcert_discount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `giftcert_ids` MEDIUMTEXT NOT NULL,
    `subtotal` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `discount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `coupon` VARCHAR(32) NOT NULL DEFAULT '',
    `coupon_discount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `shippingid` MEDIUMTEXT NOT NULL,
    `tracking` MEDIUMTEXT NOT NULL,
    `shipping_cost` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `shipping_costs` MEDIUMTEXT NOT NULL,
    `tax` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `taxes_applied` MEDIUMTEXT NOT NULL,
    `date` INTEGER NOT NULL DEFAULT 0,
    `cb_status` CHAR(2) NULL,
    `dc_status` CHAR(2) NULL,
    `bd_status` CHAR(2) NULL,
    `c2a_status` CHAR(2) NULL,
    `a2c_status` CHAR(2) NULL,
    `a2b_status` CHAR(2) NULL,
    `d2a_status` CHAR(2) NULL,
    `payment_method` VARCHAR(64) NOT NULL DEFAULT '',
    `flag` CHAR(1) NOT NULL DEFAULT 'N',
    `notes` MEDIUMTEXT NOT NULL,
    `details` MEDIUMTEXT NOT NULL,
    `customer_notes` MEDIUMTEXT NOT NULL,
    `customer` VARCHAR(32) NOT NULL DEFAULT '',
    `title` VARCHAR(32) NOT NULL DEFAULT '',
    `firstname` VARCHAR(255) NOT NULL DEFAULT '',
    `lastname` VARCHAR(32) NOT NULL DEFAULT '',
    `company` VARCHAR(255) NOT NULL DEFAULT '',
    `b_title` VARCHAR(32) NOT NULL DEFAULT '',
    `b_firstname` VARCHAR(128) NOT NULL DEFAULT '',
    `b_lastname` VARCHAR(128) NOT NULL DEFAULT '',
    `b_full_address` VARCHAR(256) NULL,
    `b_address` VARCHAR(128) NOT NULL DEFAULT '',
    `b_city` VARCHAR(180) NOT NULL DEFAULT '',
    `b_county` VARCHAR(32) NOT NULL DEFAULT '',
    `b_state` VARCHAR(32) NOT NULL DEFAULT '',
    `b_country` VARCHAR(50) NOT NULL DEFAULT '',
    `b_zipcode` VARCHAR(32) NOT NULL DEFAULT '',
    `s_title` VARCHAR(32) NOT NULL DEFAULT '',
    `s_firstname` VARCHAR(128) NOT NULL DEFAULT '',
    `s_lastname` VARCHAR(128) NOT NULL DEFAULT '',
    `s_full_address` VARCHAR(256) NULL,
    `s_address` VARCHAR(128) NOT NULL DEFAULT '',
    `s_city` VARCHAR(180) NOT NULL DEFAULT '',
    `s_county` VARCHAR(32) NOT NULL DEFAULT '',
    `s_state` VARCHAR(32) NOT NULL DEFAULT '',
    `s_country` VARCHAR(50) NOT NULL DEFAULT '',
    `s_zipcode` VARCHAR(32) NOT NULL DEFAULT '',
    `phone` VARCHAR(32) NOT NULL DEFAULT '',
    `fax` VARCHAR(32) NOT NULL DEFAULT '',
    `url` VARCHAR(32) NOT NULL DEFAULT '',
    `email` VARCHAR(128) NOT NULL DEFAULT '',
    `language` CHAR(2) NOT NULL DEFAULT 'US',
    `clickid` INTEGER NOT NULL DEFAULT 0,
    `extra` MEDIUMTEXT NOT NULL,
    `membershipid` INTEGER NOT NULL DEFAULT 0,
    `paymentid` INTEGER NOT NULL DEFAULT 0,
    `payment_surcharge` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `tax_number` VARCHAR(50) NOT NULL DEFAULT '',
    `tax_exempt` CHAR(1) NOT NULL DEFAULT 'N',
    `shipping_groups` MEDIUMTEXT NOT NULL,
    `storefrontid` INTEGER NOT NULL DEFAULT 0,
    `phone_ext` VARCHAR(32) NOT NULL DEFAULT '',
    `orig_po` VARCHAR(255) NOT NULL DEFAULT '',
    `ca_status` CHAR(1) NOT NULL DEFAULT '',
    `po_number` VARCHAR(255) NOT NULL DEFAULT '',
    `fraud_status` CHAR(1) NOT NULL DEFAULT 'N',
    `overall_fraud_score` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `bare_fraud_score` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `vt_paymentid` INTEGER NOT NULL DEFAULT 0,
    `transaction_id_link` VARCHAR(255) NOT NULL DEFAULT '',
    `avs_code` VARCHAR(3) NOT NULL DEFAULT '',
    `otrs_ticket` VARCHAR(255) NOT NULL DEFAULT '',
    `login_last_opened_or_saved` VARCHAR(32) NOT NULL DEFAULT '',
    `time_last_opened_or_saved` INTEGER NOT NULL DEFAULT 0,
    `note_is_taken_care_of` CHAR(1) NOT NULL DEFAULT 'N',
    `tracking_all_filled` CHAR(1) NOT NULL DEFAULT 'N',
    `thankyou_for_order_email_sent` CHAR(1) NOT NULL DEFAULT 'N',
    `tracking_fill_time` INTEGER NOT NULL DEFAULT 0,
    `cloned_from` INTEGER NOT NULL DEFAULT 0,
    `sessid` VARCHAR(40) NOT NULL DEFAULT '',
    `total_shipping_charge_on_orig_po` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `po_issued_to` CHAR(1) NOT NULL DEFAULT '',
    `non_us_confirmation` CHAR(1) NOT NULL DEFAULT 'N',
    `cloned_by` VARCHAR(32) NOT NULL DEFAULT '',
    `alt_items` VARCHAR(500) NOT NULL DEFAULT '',
    `amazonorderid` VARCHAR(32) NOT NULL DEFAULT '',
    `product_question_status_id` INTEGER NOT NULL DEFAULT 0,
    `amazon_fulfillment_channel` CHAR(10) NOT NULL DEFAULT '',
    `quantity_decreased` CHAR(1) NOT NULL DEFAULT 'N',
    `is_mobile_checkout` CHAR(1) NOT NULL DEFAULT 'N',
    `currency` VARCHAR(3) NOT NULL DEFAULT 'USD',
    `unfreeze_cb_status` CHAR(1) NOT NULL DEFAULT '',
    `cart_number` INTEGER UNSIGNED NULL,
    `shipping_total_net` DECIMAL(12, 2) NULL,
    `shipping_total_gst` DECIMAL(12, 2) NULL,
    `shipping_total_pst` DECIMAL(12, 2) NULL,
    `shipping_total_gross` DECIMAL(12, 2) NULL,
    `product_total_net` DECIMAL(12, 2) NULL,
    `product_total_gst` DECIMAL(12, 2) NULL,
    `product_total_pst` DECIMAL(12, 2) NULL,
    `product_total_gross` DECIMAL(12, 2) NULL,
    `total_net` DECIMAL(12, 2) NULL,
    `total_gst` DECIMAL(12, 2) NULL,
    `total_pst` DECIMAL(12, 2) NULL,
    `total_gross` DECIMAL(12, 2) NULL,
    `tax_info_display_taxed_order_totals` CHAR(1) NULL,
    `tax_info_display_cart_products_tax_rates` CHAR(1) NULL,
    `tax_info_taxed_subtotal` DECIMAL(12, 2) NULL,
    `tax_info_taxed_discounted_subtotal` DECIMAL(12, 2) NULL,
    `tax_info_taxed_shipping` DECIMAL(12, 2) NULL,
    `b_company` VARCHAR(255) NULL,
    `s_company` VARCHAR(255) NULL,
    `vn_status` CHAR(2) NOT NULL DEFAULT 'NS',
    `referer_id` INTEGER NULL,
    `amazon_purchase_date` DATETIME(0) NULL,
    `track_sms` TINYINT NOT NULL DEFAULT 0,
    `billing_same_shipping` TINYINT NOT NULL DEFAULT 1,
    `is_new_checkout` TINYINT UNSIGNED NOT NULL DEFAULT 0,

    INDEX `amazon_fulfillment_channel`(`amazon_fulfillment_channel`, `orderid`, `date`),
    INDEX `cart_number`(`cart_number`),
    INDEX `fraud_status`(`fraud_status`),
    INDEX `idx_amazonorderid`(`amazonorderid`, `orderid`),
    INDEX `idx_email`(`email`),
    INDEX `idx_sessid`(`sessid`),
    INDEX `idx_sf_order_date`(`storefrontid`, `date`, `cb_status`),
    INDEX `login`(`login`),
    INDEX `login_last_opened_or_saved_time_last_opened_or_saved`(`login_last_opened_or_saved`, `time_last_opened_or_saved`),
    INDEX `order_date`(`date`),
    INDEX `order_type`(`order_type`),
    INDEX `storefrontid`(`storefrontid`),
    INDEX `xcart_orders_xcart_users_user_id_fk`(`user_id`),
    PRIMARY KEY (`orderid`, `date`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_orders_additional_tags` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `status_id` INTEGER NOT NULL DEFAULT 0,
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `fill_time` TIMESTAMP(0) NULL DEFAULT CURRENT_TIMESTAMP(0),

    INDEX `fill_time`(`fill_time`, `status_id`),
    INDEX `idxOrderId`(`orderid`),
    INDEX `idxStatusID`(`status_id`),
    INDEX `idxStatus_Order`(`status_id`, `orderid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_otrs_options` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `OTRS_passphrase` VARCHAR(100) NOT NULL DEFAULT '',
    `status_id` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_pages` (
    `pageid` INTEGER NOT NULL AUTO_INCREMENT,
    `filename` VARCHAR(255) NOT NULL DEFAULT '',
    `title` VARCHAR(255) NOT NULL DEFAULT '',
    `level` CHAR(1) NOT NULL DEFAULT 'E',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `active` CHAR(1) NOT NULL DEFAULT 'Y',
    `language` CHAR(2) NOT NULL DEFAULT '',
    `header_pos` VARCHAR(32) NOT NULL DEFAULT '',
    `sfids` VARCHAR(255) NOT NULL DEFAULT '',
    `no_index` TINYINT UNSIGNED NOT NULL DEFAULT 1,

    INDEX `filename`(`filename`),
    INDEX `header_pos`(`header_pos`),
    INDEX `language`(`language`),
    INDEX `orderby`(`level`, `orderby`, `title`, `active`, `language`),
    PRIMARY KEY (`pageid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_partner_adv_campaigns` (
    `campaignid` INTEGER NOT NULL AUTO_INCREMENT,
    `campaign` VARCHAR(128) NOT NULL DEFAULT '',
    `per_visit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `per_period` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `start_period` INTEGER NOT NULL DEFAULT 0,
    `end_period` INTEGER NOT NULL DEFAULT 0,
    `type` CHAR(1) NOT NULL DEFAULT '',
    `data` VARCHAR(255) NOT NULL DEFAULT '',

    INDEX `type`(`type`),
    PRIMARY KEY (`campaignid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_partner_adv_clicks` (
    `campaignid` INTEGER NOT NULL DEFAULT 0,
    `add_date` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`campaignid`, `add_date`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_partner_adv_orders` (
    `campaignid` INTEGER NOT NULL DEFAULT 0,
    `orderid` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`campaignid`, `orderid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_partner_banners` (
    `bannerid` INTEGER NOT NULL AUTO_INCREMENT,
    `banner` VARCHAR(128) NOT NULL DEFAULT '',
    `body` MEDIUMBLOB NOT NULL,
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `is_image` CHAR(1) NOT NULL DEFAULT 'Y',
    `is_name` CHAR(1) NOT NULL DEFAULT 'Y',
    `is_descr` CHAR(1) NOT NULL DEFAULT 'Y',
    `is_add` CHAR(1) NOT NULL DEFAULT 'Y',
    `banner_type` CHAR(1) NOT NULL DEFAULT 'T',
    `open_blank` CHAR(1) NOT NULL DEFAULT 'Y',
    `legend` TEXT NOT NULL,
    `alt` TEXT NOT NULL,
    `image_type` VARCHAR(32) NOT NULL DEFAULT 'image/jpeg',
    `direction` CHAR(1) NOT NULL DEFAULT 'D',
    `banner_x` INTEGER NOT NULL DEFAULT 0,
    `banner_y` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`bannerid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_partner_banners_elements` (
    `elementid` INTEGER NOT NULL AUTO_INCREMENT,
    `data` MEDIUMBLOB NOT NULL,
    `data_type` VARCHAR(64) NOT NULL DEFAULT 'image/jpeg',
    `data_x` INTEGER NOT NULL DEFAULT 0,
    `data_y` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`elementid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_partner_clicks` (
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `add_date` INTEGER NOT NULL DEFAULT 0,
    `class` CHAR(1) NOT NULL DEFAULT '',
    `bannerid` INTEGER NOT NULL DEFAULT 0,
    `productid` INTEGER NOT NULL DEFAULT 0,
    `referer` VARCHAR(255) NOT NULL DEFAULT '',
    `clickid` INTEGER NOT NULL AUTO_INCREMENT,

    INDEX `add_date`(`add_date`),
    INDEX `login`(`login`),
    PRIMARY KEY (`clickid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_partner_commissions` (
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `plan_id` INTEGER NOT NULL DEFAULT 0,

    INDEX `plan_id`(`plan_id`),
    PRIMARY KEY (`login`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_partner_payment` (
    `payment_id` INTEGER NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `commissions` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `paid` CHAR(1) NOT NULL DEFAULT 'N',
    `add_date` INTEGER NOT NULL DEFAULT 0,
    `affiliate` VARCHAR(32) NOT NULL DEFAULT '',

    INDEX `affiliate`(`affiliate`),
    INDEX `login`(`login`),
    INDEX `orderid`(`orderid`),
    PRIMARY KEY (`payment_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_partner_plans` (
    `plan_id` INTEGER NOT NULL AUTO_INCREMENT,
    `plan_title` VARCHAR(64) NOT NULL DEFAULT '',
    `status` CHAR(1) NOT NULL DEFAULT 'A',
    `min_paid` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,

    INDEX `status`(`status`),
    PRIMARY KEY (`plan_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_partner_plans_commissions` (
    `plan_id` INTEGER NOT NULL DEFAULT 0,
    `commission` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `commission_type` CHAR(1) NOT NULL DEFAULT '%',
    `item_id` INTEGER NOT NULL DEFAULT 0,
    `item_type` CHAR(1) NOT NULL DEFAULT 'A',

    PRIMARY KEY (`plan_id`, `item_id`, `item_type`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_partner_product_commissions` (
    `itemid` INTEGER NOT NULL DEFAULT 0,
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `product_commission` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `login` VARCHAR(32) NOT NULL DEFAULT '',

    PRIMARY KEY (`itemid`, `orderid`, `login`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_partner_tier_commissions` (
    `level` INTEGER NOT NULL DEFAULT 0,
    `commission` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,

    PRIMARY KEY (`level`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_partner_views` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `add_date` INTEGER NOT NULL DEFAULT 0,
    `class` CHAR(1) NOT NULL DEFAULT '',
    `bannerid` INTEGER NOT NULL DEFAULT 0,
    `productid` INTEGER NOT NULL DEFAULT 0,

    INDEX `login`(`login`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_payment_methods` (
    `paymentid` INTEGER NOT NULL AUTO_INCREMENT,
    `payment_method` VARCHAR(128) NOT NULL DEFAULT '',
    `payment_details` VARCHAR(255) NOT NULL DEFAULT '',
    `processor_id` INTEGER NULL,
    `frontend_processor_id` INTEGER NULL,
    `payment_template` VARCHAR(128) NOT NULL DEFAULT '',
    `payment_script` VARCHAR(128) NOT NULL DEFAULT '',
    `protocol` VARCHAR(6) NOT NULL DEFAULT 'http',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `active` CHAR(1) NOT NULL DEFAULT 'Y',
    `is_cod` CHAR(1) NOT NULL DEFAULT '',
    `af_check` CHAR(1) NOT NULL DEFAULT 'Y',
    `processor_file` VARCHAR(255) NOT NULL DEFAULT '',
    `surcharge` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `surcharge_type` CHAR(1) NOT NULL DEFAULT '$',
    `acc_proc` CHAR(1) NOT NULL DEFAULT 'N',
    `acc_percent` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `acc_per_trans` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `percent_ref` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `per_ref` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `vt` CHAR(1) NOT NULL DEFAULT '',
    `transaction_id_link` VARCHAR(255) NOT NULL DEFAULT '',
    `honor_period` INTEGER NOT NULL DEFAULT 0,
    `authorization_period` INTEGER NOT NULL DEFAULT 0,
    `maximum_re_authorization_multiplier` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `maximum_re_authorization_increase` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `how_process_payment_at_checkout` CHAR(2) NOT NULL DEFAULT '',
    `transaction_link_anchor` VARCHAR(255) NOT NULL DEFAULT '',
    `order_tag_preference` ENUM('MFN', 'AFN', '') NOT NULL DEFAULT '',
    `submit_hint` VARCHAR(255) NULL,

    INDEX `orderby`(`orderby`),
    INDEX `processor_file`(`processor_file`, `paymentid`),
    INDEX `protocol`(`protocol`),
    PRIMARY KEY (`paymentid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_payment_methods_storefronts` (
    `storefrontid` INTEGER NOT NULL,
    `paymentid` INTEGER NOT NULL,

    PRIMARY KEY (`storefrontid`, `paymentid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_payment_processor` (
    `processor_id` INTEGER NOT NULL AUTO_INCREMENT,
    `processor_name` VARCHAR(255) NOT NULL DEFAULT '',
    `transaction_link` VARCHAR(255) NOT NULL DEFAULT '',
    `param01` VARCHAR(255) NULL,
    `param02` VARCHAR(255) NULL,
    `test_mode` CHAR(1) NOT NULL DEFAULT 'N',

    PRIMARY KEY (`processor_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_pbx_options` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `extension` VARCHAR(32) NOT NULL DEFAULT '',
    `anveo_account` VARCHAR(32) NOT NULL DEFAULT '',
    `anveo_password` VARCHAR(32) NOT NULL DEFAULT '',

    UNIQUE INDEX `anveo_account`(`anveo_account`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_pc_category_terms` (
    `categoryid` INTEGER NOT NULL DEFAULT 0,
    `termid` BIGINT NOT NULL DEFAULT 0,
    `term_count` INTEGER UNSIGNED NOT NULL DEFAULT 0,

    INDEX `FK_xcart_pc_category_terms_xcart_pc_terms`(`termid`),
    PRIMARY KEY (`categoryid`, `termid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_pc_locks` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `productid` INTEGER NOT NULL DEFAULT 0,
    `lock_date` INTEGER NOT NULL DEFAULT 0,
    `login` VARCHAR(32) NOT NULL DEFAULT '',

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_pc_options` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `storefrontid` INTEGER NOT NULL DEFAULT 0,
    `maximum_number_of_autoclassify_product_per_turn` INTEGER NOT NULL DEFAULT 50,
    `minimum_number_of_autoclassify_product_per_turn` INTEGER NOT NULL DEFAULT 3,
    `stop_words` TEXT NOT NULL,
    `excluded_char_sequences` TEXT NOT NULL,
    `recalc_if_approval_rate` DECIMAL(12, 2) NOT NULL DEFAULT 60.00,
    `amount_of_products_for_autoclassify_queue` INTEGER NOT NULL DEFAULT 50,
    `classification_approval_rate` DECIMAL(12, 2) NOT NULL DEFAULT -1.00,
    `allow_skip_products` CHAR(1) NOT NULL DEFAULT 'Y',
    `allow_blank_google_product_category` CHAR(1) NOT NULL DEFAULT 'N',
    `disable_AC_products` CHAR(1) NOT NULL DEFAULT 'N',
    `last_mc_acc_products_count` INTEGER UNSIGNED NOT NULL DEFAULT 0,

    INDEX `storefrontid`(`storefrontid`, `disable_AC_products`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_pc_runs_log` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `run` INTEGER NOT NULL DEFAULT 0,
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `date_time_start` INTEGER NOT NULL DEFAULT 0,
    `date_time_end` INTEGER NOT NULL DEFAULT 0,
    `products_assigned` INTEGER NOT NULL DEFAULT 0,
    `products_incorrect_assigned` INTEGER NOT NULL DEFAULT 0,
    `products_skipped` INTEGER NOT NULL DEFAULT 0,
    `products_approved` INTEGER NOT NULL DEFAULT 0,
    `storefrontid` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_pc_terms` (
    `termid` BIGINT NOT NULL AUTO_INCREMENT,
    `term` VARCHAR(255) NOT NULL DEFAULT '',

    UNIQUE INDEX `term-sfid`(`term`),
    INDEX `idxTerm`(`term`),
    INDEX `idxTermId`(`termid`),
    PRIMARY KEY (`termid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_pmethod_memberships` (
    `paymentid` INTEGER NOT NULL DEFAULT 0,
    `membershipid` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`paymentid`, `membershipid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_po_pipeline` (
    `po_id` INTEGER NOT NULL AUTO_INCREMENT,
    `PO_number` VARCHAR(25) NOT NULL,
    `order_id` INTEGER NULL,
    `storefront_id` INTEGER NOT NULL,
    `status` ENUM('dropped', 'entered', 'uploaded') NOT NULL DEFAULT 'uploaded',
    `login` VARCHAR(25) NULL,
    `file_name` VARCHAR(255) NULL,
    `modify_date` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
    `original_po_file` VARCHAR(255) NULL,
    `received_by` ENUM('', 'fax', 'mail_to_us', 'mail_to_ca', 'email', 'website') NOT NULL,

    INDEX `PO_number`(`PO_number`),
    INDEX `status`(`status`),
    INDEX `xcart_po_pipeline_ibfk_1`(`order_id`),
    PRIMARY KEY (`po_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_pricing` (
    `priceid` INTEGER NOT NULL AUTO_INCREMENT,
    `productid` INTEGER NOT NULL DEFAULT 0,
    `quantity` INTEGER NOT NULL DEFAULT 0,
    `price` DECIMAL(12, 2) NULL,
    `variantid` INTEGER NOT NULL DEFAULT 0,
    `membershipid` MEDIUMINT NOT NULL DEFAULT 0,

    UNIQUE INDEX `idx_primary`(`productid`, `quantity`, `variantid`),
    PRIMARY KEY (`priceid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_pricing_quick_1_to_drop` (
    `priceid` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `productid` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,

    UNIQUE INDEX `idx_priceid`(`priceid`),
    INDEX `price`(`price`),
    PRIMARY KEY (`productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_bookmarks` (
    `productid` INTEGER NOT NULL DEFAULT 0,
    `add_date` INTEGER NOT NULL DEFAULT 0,
    `login` VARCHAR(32) NOT NULL DEFAULT '',

    UNIQUE INDEX `productid`(`productid`, `login`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_files` (
    `description` VARCHAR(255) NOT NULL,
    `filename` VARCHAR(512) NOT NULL,
    `orderby` INTEGER NOT NULL,
    `productid` INTEGER NOT NULL,
    `filesize` INTEGER NOT NULL,
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `date` INTEGER NOT NULL,
    `fileid` INTEGER NOT NULL AUTO_INCREMENT,

    INDEX `avail`(`avail`),
    INDEX `productid`(`productid`),
    INDEX `productid_avail`(`productid`, `avail`),
    PRIMARY KEY (`fileid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_htmlshot` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `htmlshot` LONGTEXT NOT NULL,
    `product_id` INTEGER NOT NULL,
    `order_id` INTEGER NOT NULL,
    `create_date` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    INDEX `order_id`(`order_id`, `product_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_images` (
    `image_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `path` VARCHAR(255) NOT NULL,
    `width` SMALLINT UNSIGNED NULL,
    `height` SMALLINT UNSIGNED NULL,
    `hash` VARCHAR(50) NOT NULL,
    `link` VARCHAR(50) NULL,

    UNIQUE INDEX `hash`(`hash`),
    PRIMARY KEY (`image_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_links` (
    `productid1` INTEGER NOT NULL DEFAULT 0,
    `productid2` INTEGER NOT NULL DEFAULT 0,
    `orderby` INTEGER NOT NULL DEFAULT 0,

    INDEX `orderby`(`orderby`),
    INDEX `productid1`(`productid1`),
    INDEX `productid2`(`productid2`),
    PRIMARY KEY (`productid1`, `productid2`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_memberships` (
    `productid` INTEGER NOT NULL DEFAULT 0,
    `membershipid` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`productid`, `membershipid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_option_variants` (
    `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_option_id` MEDIUMINT UNSIGNED NOT NULL,
    `variant_id` MEDIUMINT UNSIGNED NOT NULL,
    `modifier` DECIMAL(13, 3) NOT NULL DEFAULT 0.000,
    `modifier_type` ENUM('percent', 'value') NOT NULL DEFAULT 'value',
    `weight_modifier` DECIMAL(12, 3) NOT NULL DEFAULT 0.000,
    `weight_modifier_type` ENUM('percent', 'value') NOT NULL DEFAULT 'value',
    `active` BOOLEAN NOT NULL DEFAULT true,
    `position` SMALLINT UNSIGNED NOT NULL DEFAULT 10,

    INDEX `FK_xcart_product_option_variants_xcart_option_variants`(`variant_id`),
    INDEX `position`(`position`),
    INDEX `status`(`active`),
    UNIQUE INDEX `product_option_id_variant_id`(`product_option_id`, `variant_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_options` (
    `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` INTEGER NOT NULL,
    `option_id` MEDIUMINT UNSIGNED NOT NULL,
    `required` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `active` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `position` SMALLINT UNSIGNED NOT NULL DEFAULT 10,

    INDEX `FK_xcart_product_options_xcart_options`(`option_id`),
    INDEX `FK_xcart_product_options_xcart_products`(`product_id`),
    INDEX `position`(`position`),
    UNIQUE INDEX `product_id_option_id`(`product_id`, `option_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_options_ex` (
    `optionid` INTEGER NOT NULL DEFAULT 0,
    `exceptionid` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`optionid`, `exceptionid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_options_js` (
    `productid` INTEGER NOT NULL DEFAULT 0,
    `javascript_code` TEXT NULL,

    PRIMARY KEY (`productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_options_lng` (
    `code` CHAR(2) NOT NULL DEFAULT 'US',
    `optionid` INTEGER NOT NULL DEFAULT 0,
    `option_name` VARCHAR(255) NOT NULL DEFAULT '',

    PRIMARY KEY (`code`, `optionid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_question` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `productid` INTEGER NOT NULL,
    `status` CHAR(32) NOT NULL DEFAULT 'question_received_from_cust',
    `email` VARCHAR(128) NOT NULL DEFAULT '',
    `phone` VARCHAR(128) NOT NULL DEFAULT '',
    `phone_ext` VARCHAR(32) NOT NULL DEFAULT '',
    `question` TEXT NOT NULL,
    `answer` TEXT NULL,
    `name` VARCHAR(128) NOT NULL DEFAULT '',
    `company` VARCHAR(255) NOT NULL DEFAULT '',
    `address` VARCHAR(128) NOT NULL DEFAULT '',
    `address2` VARCHAR(128) NOT NULL DEFAULT '',
    `city` VARCHAR(64) NOT NULL DEFAULT '',
    `state` VARCHAR(32) NOT NULL DEFAULT '',
    `country` VARCHAR(2) NOT NULL DEFAULT '',
    `zipcode` VARCHAR(32) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `answered_on_page` CHAR(1) NOT NULL DEFAULT 'N',
    `question_published_on_page` CHAR(1) NOT NULL DEFAULT 'N',
    `otrs_ticket` VARCHAR(255) NOT NULL DEFAULT '',
    `order_by` INTEGER NOT NULL DEFAULT 0,
    `answered_date` INTEGER NOT NULL DEFAULT 0,
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `firstname` VARCHAR(64) NOT NULL DEFAULT '',
    `added_from_product_modify_page` CHAR(1) NOT NULL DEFAULT 'N',
    `new_otrs_email` CHAR(1) NOT NULL DEFAULT 'N',
    `publication_status` CHAR(1) NOT NULL DEFAULT 'U',

    INDEX `productid`(`productid`, `question_published_on_page`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_reviews` (
    `review_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `remote_ip` VARCHAR(15) NOT NULL DEFAULT '',
    `email` VARCHAR(128) NOT NULL DEFAULT '',
    `message` TEXT NOT NULL,
    `productid` INTEGER NOT NULL DEFAULT 0,

    INDEX `productid`(`productid`),
    INDEX `remote_ip`(`remote_ip`),
    PRIMARY KEY (`review_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_taxes` (
    `productid` INTEGER NOT NULL DEFAULT 0,
    `taxid` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`productid`, `taxid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_verification_history` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `productid` INTEGER NOT NULL,
    `verification_note` TEXT NOT NULL,
    `timestamp` INTEGER NOT NULL,
    `username` VARCHAR(35) NOT NULL,
    `oldstatusid` INTEGER NOT NULL,
    `newstatusid` INTEGER NOT NULL,

    INDEX `fk_xcart_verification_history_xcart_product_st_idx2`(`productid`),
    INDEX `fk_xcart_verification_history_xcart_product_verification_st_idx`(`oldstatusid`),
    INDEX `fk_xcart_verification_history_xcart_product_verification_st_idx1`(`newstatusid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_verification_statuses` (
    `statusid` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `orderby` INTEGER NOT NULL DEFAULT 10,

    PRIMARY KEY (`statusid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_videos` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` INTEGER UNSIGNED NOT NULL,
    `is_local` BOOLEAN NOT NULL DEFAULT false,
    `active` BOOLEAN NOT NULL DEFAULT true,
    `video` VARCHAR(255) NOT NULL DEFAULT '',
    `image` VARCHAR(255) NULL,
    `provider` VARCHAR(30) NULL,
    `name` VARCHAR(255) NULL,
    `description` TEXT NULL,

    INDEX `pid`(`product_id`, `id`, `video`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_product_votes` (
    `vote_id` INTEGER NOT NULL AUTO_INCREMENT,
    `remote_ip` VARCHAR(15) NOT NULL DEFAULT '',
    `vote_value` INTEGER NOT NULL DEFAULT 0,
    `productid` INTEGER NOT NULL DEFAULT 0,

    INDEX `productid`(`productid`),
    INDEX `remote_ip`(`remote_ip`),
    PRIMARY KEY (`vote_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_products` (
    `productid` INTEGER NOT NULL AUTO_INCREMENT,
    `productcode` VARCHAR(255) NOT NULL DEFAULT '',
    `ASIN` VARCHAR(15) NULL,
    `product` VARCHAR(600) NOT NULL DEFAULT '',
    `product_froogle` VARCHAR(150) NOT NULL DEFAULT '',
    `provider` VARCHAR(32) NOT NULL DEFAULT '',
    `original_provider` VARCHAR(32) NOT NULL DEFAULT '',
    `distribution` VARCHAR(255) NOT NULL DEFAULT '',
    `weight` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `list_price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `descr` TEXT NOT NULL,
    `fulldescr` MEDIUMTEXT NOT NULL,
    `seo_fulldescr` MEDIUMTEXT NOT NULL,
    `avail` INTEGER NOT NULL DEFAULT 0,
    `rating` MEDIUMINT NOT NULL DEFAULT 0,
    `forsale` CHAR(1) NOT NULL DEFAULT 'Y',
    `add_date` INTEGER NOT NULL DEFAULT 0,
    `mod_date` INTEGER NOT NULL DEFAULT 0,
    `views_stats` MEDIUMINT NOT NULL DEFAULT 0,
    `sales_stats` MEDIUMINT NOT NULL DEFAULT 0,
    `del_stats` MEDIUMINT NOT NULL DEFAULT 0,
    `shipping_freight` DECIMAL(12, 2) NOT NULL DEFAULT 0.01,
    `free_shipping` CHAR(1) NOT NULL DEFAULT 'N',
    `discount_avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `min_amount` MEDIUMINT NOT NULL DEFAULT 1,
    `dim_x` FLOAT NOT NULL DEFAULT 0,
    `dim_y` FLOAT NOT NULL DEFAULT 0,
    `dim_z` FLOAT NOT NULL DEFAULT 0,
    `low_avail_limit` MEDIUMINT NOT NULL DEFAULT 10,
    `free_tax` CHAR(1) NOT NULL DEFAULT 'N',
    `product_type` CHAR(1) NOT NULL DEFAULT 'N',
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `brandid` INTEGER NULL,
    `brand_normalized` BOOLEAN NOT NULL DEFAULT false,
    `sub_brand_id` INTEGER NULL,
    `return_time` INTEGER NOT NULL DEFAULT 0,
    `keywords` VARCHAR(255) NOT NULL DEFAULT '',
    `google_search_term` VARCHAR(255) NOT NULL DEFAULT '',
    `discount_slope` DECIMAL(12, 2) NOT NULL DEFAULT 0.60,
    `discount_table` VARCHAR(255) NOT NULL DEFAULT '2,3,4,6,8,12',
    `free_ship_zone` MEDIUMINT NOT NULL DEFAULT -1,
    `free_ship_text` VARCHAR(255) NOT NULL DEFAULT '',
    `upc` VARCHAR(14) NOT NULL DEFAULT '',
    `cost_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `source_sfid` SMALLINT NOT NULL,
    `map_price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `mult_order_quantity` CHAR(1) NOT NULL DEFAULT 'N',
    `new_map_price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `warning_code` MEDIUMINT NULL DEFAULT 0,
    `eta_date_mm_dd_yyyy` INTEGER UNSIGNED NULL,
    `lead_time_message` VARCHAR(255) NOT NULL DEFAULT '',
    `similar_cron_generated_flag` CHAR(1) NOT NULL DEFAULT 'Y',
    `similar_productids` VARCHAR(255) NOT NULL DEFAULT '',
    `similar_time` INTEGER NOT NULL DEFAULT 0,
    `product_price_multiplier` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `supplier_internal_product_id` MEDIUMINT NOT NULL DEFAULT 0,
    `generate_similar_products` CHAR(1) NOT NULL DEFAULT 'Y',
    `tmp_generated_file` VARCHAR(255) NOT NULL DEFAULT '',
    `update_search_index` CHAR(1) NOT NULL DEFAULT 'Y',
    `pc_classify_status` CHAR(3) NOT NULL DEFAULT 'NC',
    `pc_mc_operator` VARCHAR(32) NOT NULL DEFAULT '',
    `pc_acc_operator` VARCHAR(32) NOT NULL DEFAULT '',
    `r_avail` INTEGER NOT NULL DEFAULT 0,
    `supplier_internal_id` VARCHAR(512) NOT NULL DEFAULT '',
    `supplier_internal_id_last_parsed` INTEGER NOT NULL DEFAULT 0,
    `supplier_internal_id_last_parsed_update` INTEGER NOT NULL DEFAULT 0,
    `last_incremental_update` INTEGER NOT NULL DEFAULT 0,
    `pc_most_relevant_categories` VARCHAR(128) NOT NULL DEFAULT '',
    `pc_delta` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `supplier_internal_option` VARCHAR(64) NOT NULL DEFAULT '',
    `amazon_enabled` CHAR(1) NOT NULL DEFAULT 'N',
    `amazon_verified` CHAR(1) NOT NULL DEFAULT 'N',
    `amazon_fba` CHAR(1) NOT NULL DEFAULT 'N',
    `amazon_fba_avail` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `title_tag` VARCHAR(600) NULL DEFAULT '',
    `seo_product_name` VARCHAR(600) NULL DEFAULT '',
    `seo_meta_descr` VARCHAR(600) NULL DEFAULT '',
    `lock_forsale` CHAR(1) NOT NULL DEFAULT 'N',
    `lock_price` CHAR(1) NOT NULL DEFAULT 'N',
    `seo_h2` VARCHAR(600) NOT NULL DEFAULT '',
    `prevent_search_indexing_this_product_page` CHAR(1) NOT NULL DEFAULT 'N',
    `controlled_by_feed` VARCHAR(32) NOT NULL DEFAULT '',
    `eta_date_lock` CHAR(1) NOT NULL DEFAULT 'N',
    `clone_parent_productid` INTEGER NOT NULL,
    `dim_lock` CHAR(1) NOT NULL DEFAULT 'N',
    `shipping_dim_x` FLOAT NOT NULL DEFAULT 0,
    `shipping_dim_y` FLOAT NOT NULL DEFAULT 0,
    `shipping_dim_z` FLOAT NOT NULL DEFAULT 0,
    `shipping_dim_lock` CHAR(1) NOT NULL DEFAULT 'N',
    `shipping_weight` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `shipping_weight_lock` CHAR(1) NOT NULL DEFAULT 'N',
    `weight_lock` CHAR(1) NOT NULL DEFAULT 'N',
    `verification_statusid` INTEGER NOT NULL DEFAULT 0,
    `last_verify_date` INTEGER NULL DEFAULT 0,
    `retail_trust_enabled` ENUM('N', 'Y') NOT NULL DEFAULT 'N',
    `log_stock_history` ENUM('N', 'Y') NOT NULL DEFAULT 'N',
    `in_list_showed` BIGINT NULL DEFAULT 0,
    `splash_id` INTEGER NULL,
    `group_root` INTEGER NULL,
    `group_option` VARCHAR(255) NULL,
    `group_mask` VARCHAR(255) NULL,
    `group_order` TINYINT UNSIGNED NOT NULL DEFAULT 255,
    `shipping_calc_disabled` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `in_stock` TINYINT NULL,
    `hash_product` VARCHAR(50) NULL,
    `total_reviews` SMALLINT UNSIGNED NULL DEFAULT 0,

    UNIQUE INDEX `productcode`(`productcode`),
    INDEX `ASIN`(`ASIN`),
    INDEX `FK_xcart_products_xcart_manufacturers`(`manufacturerid`),
    INDEX `add_date`(`forsale`, `add_date`),
    INDEX `amazon_verified`(`amazon_verified`),
    INDEX `avail`(`avail`),
    INDEX `brand_forsale`(`brandid`, `forsale`),
    INDEX `clone_parent_productid`(`clone_parent_productid`),
    INDEX `cost_to_us`(`cost_to_us`),
    INDEX `fba`(`forsale`, `amazon_enabled`, `amazon_fba`, `amazon_fba_avail`),
    INDEX `forsale_manufacturerid`(`forsale`, `manufacturerid`),
    INDEX `group_root`(`group_root`),
    INDEX `idxPRODUCTID_FORSALE`(`forsale`, `productid`),
    INDEX `idx__FORSALE_GROOT_ID`(`forsale`, `group_root`, `productid`),
    INDEX `in_stock`(`in_stock`),
    INDEX `pc_classify_status`(`pc_classify_status`, `forsale`),
    INDEX `r_avail`(`r_avail`),
    INDEX `supplier_internal_product_id`(`supplier_internal_product_id`),
    PRIMARY KEY (`productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_products_amazon_rates` (
    `product_id` INTEGER NOT NULL,
    `shipping_id` INTEGER NOT NULL,
    `state_id` INTEGER NOT NULL,
    `rate` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `last_update` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    PRIMARY KEY (`product_id`, `shipping_id`, `state_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_products_amz_fields` (
    `productid` INTEGER NOT NULL DEFAULT 0,
    `amazon_product` VARCHAR(500) NOT NULL DEFAULT '',
    `amazon_bulletpoint1` VARCHAR(500) NOT NULL DEFAULT '',
    `amazon_bulletpoint2` VARCHAR(500) NOT NULL DEFAULT '',
    `amazon_bulletpoint3` VARCHAR(500) NOT NULL DEFAULT '',
    `amazon_bulletpoint4` VARCHAR(500) NOT NULL DEFAULT '',
    `amazon_bulletpoint5` VARCHAR(500) NOT NULL DEFAULT '',
    `amazon_searchterms1` VARCHAR(50) NOT NULL DEFAULT '',
    `amazon_searchterms2` VARCHAR(50) NOT NULL DEFAULT '',
    `amazon_searchterms3` VARCHAR(50) NOT NULL DEFAULT '',
    `amazon_searchterms4` VARCHAR(50) NOT NULL DEFAULT '',
    `amazon_searchterms5` VARCHAR(50) NOT NULL DEFAULT '',
    `amazon_product_type` VARCHAR(150) NOT NULL DEFAULT '',
    `amazon_category_item_type` VARCHAR(150) NOT NULL DEFAULT '',
    `asin` VARCHAR(50) NOT NULL DEFAULT '',
    `fnsku` VARCHAR(50) NOT NULL DEFAULT '',
    `longest_side` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `median_side` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `shortest_side` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `length_and_girth` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `unit_of_dimension` VARCHAR(50) NOT NULL DEFAULT '',
    `item_package_weight` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `unit_of_weight` VARCHAR(50) NOT NULL DEFAULT '',
    `product_size_tier` VARCHAR(50) NOT NULL DEFAULT '',
    `estimated_fee_total` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `estimated_referral_fee_per_unit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `estimated_variable_closing_fee` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `estimated_order_handling_fee_per_order` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `estimated_pick_pack_fee_per_unit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `estimated_weight_handling_fee_per_unit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `amazon_fee_preview_last_update_date` INTEGER NOT NULL DEFAULT 0,
    `amazon_fba_restricted` ENUM('Y', 'N') NOT NULL DEFAULT 'N',
    `amazon_fba_restricted_reason` TEXT NULL,
    `prevent_selling_on_amazon` ENUM('No', 'FBA', 'MFN') NOT NULL DEFAULT 'No',
    `expected_fulfillment_fee_per_unit` DECIMAL(12, 2) NULL,
    `estimated_future_order_handling_fee_per_order` DECIMAL(12, 2) NULL,
    `estimated_future_pick_pack_fee_per_unit` DECIMAL(12, 2) NULL,
    `estimated_future_weight_handling_fee_per_unit` DECIMAL(12, 2) NULL,
    `expected_future_fulfillment_fee_per_unit` DECIMAL(12, 2) NULL,
    `amazon_listing_sku_to_load` VARCHAR(50) NULL,
    `sleep_mp` TINYINT UNSIGNED NULL,
    `sleep_cp` TINYINT UNSIGNED NULL,

    PRIMARY KEY (`productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_products_categories` (
    `categoryid` INTEGER NOT NULL DEFAULT 0,
    `productid` INTEGER NOT NULL DEFAULT 0,
    `main` CHAR(1) NOT NULL DEFAULT 'N',
    `orderby` INTEGER NOT NULL DEFAULT 0,

    INDEX `categoryid`(`categoryid`, `main`),
    INDEX `main`(`main`),
    INDEX `orderby`(`categoryid`, `orderby`),
    INDEX `productid`(`productid`, `main`),
    INDEX `productid_categoryid`(`productid`, `categoryid`),
    PRIMARY KEY (`categoryid`, `productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_products_disabled_marketplaces` (
    `marketplace_id` INTEGER NOT NULL,
    `resource_id` INTEGER NOT NULL,
    `resource_type` ENUM('P', 'D', 'B') NOT NULL,
    `update_date` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    INDEX `resource_id`(`resource_id`, `resource_type`),
    PRIMARY KEY (`marketplace_id`, `resource_id`, `resource_type`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_products_external_marketplaces` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `marketplace_name` VARCHAR(100) NOT NULL,
    `processor_class` VARCHAR(30) NOT NULL,
    `active` ENUM('Y', 'N') NOT NULL,
    `mask` INTEGER UNSIGNED NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_products_group` (
    `group_index_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `given` TINYINT UNSIGNED NOT NULL DEFAULT 1,

    PRIMARY KEY (`group_index_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_products_hard_resell` (
    `product_id` INTEGER NOT NULL DEFAULT 0,
    `positive_count` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `negative_count` SMALLINT UNSIGNED NOT NULL DEFAULT 0,

    PRIMARY KEY (`product_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_products_images` (
    `product_id` INTEGER NOT NULL,
    `image_id` INTEGER NOT NULL,

    PRIMARY KEY (`product_id`, `image_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_products_lng` (
    `code` CHAR(2) NOT NULL DEFAULT '',
    `productid` INTEGER NOT NULL DEFAULT 0,
    `product` VARCHAR(255) NOT NULL DEFAULT '',
    `descr` TEXT NOT NULL,
    `fulldescr` TEXT NOT NULL,
    `keywords` VARCHAR(255) NOT NULL DEFAULT '',

    PRIMARY KEY (`code`, `productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_products_sf` (
    `productid` INTEGER NOT NULL,
    `sfid` INTEGER NOT NULL DEFAULT 0,

    UNIQUE INDEX `productid_sfid`(`sfid`, `productid`),
    PRIMARY KEY (`productid`, `sfid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_products_showed` (
    `productid` INTEGER NOT NULL,
    `in_list_showed` INTEGER UNSIGNED NULL DEFAULT 1,

    PRIMARY KEY (`productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_products_stock_history` (
    `productid` MEDIUMINT UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `quantity` INTEGER UNSIGNED NOT NULL DEFAULT 0,

    PRIMARY KEY (`productid`, `date`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_products_upc_changes` (
    `productid` INTEGER NOT NULL,
    `original_upc` VARCHAR(14) NOT NULL DEFAULT '',
    `corrected_upc` VARCHAR(14) NOT NULL DEFAULT '',
    `last_date` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    PRIMARY KEY (`productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_products_videos` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` INTEGER NOT NULL,
    `video_id` INTEGER UNSIGNED NOT NULL,

    INDEX `products_videos_xcart_products_productid_fk`(`product_id`),
    INDEX `xcart_products_videos_xcart_videos_video_id_fk`(`video_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_quick_flags` (
    `productid` INTEGER NOT NULL DEFAULT 0,
    `is_variants` CHAR(1) NOT NULL DEFAULT '',
    `is_product_options` CHAR(1) NOT NULL DEFAULT '',
    `is_taxes` CHAR(1) NOT NULL DEFAULT '',
    `image_path_T` VARCHAR(255) NULL,

    INDEX `idx_product_flags_options`(`is_variants`, `is_product_options`, `is_taxes`),
    PRIMARY KEY (`productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_quick_prices` (
    `productid` INTEGER NOT NULL DEFAULT 0,
    `priceid` INTEGER NOT NULL DEFAULT 0,
    `membershipid` INTEGER NOT NULL DEFAULT 0,
    `variantid` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`productid`, `variantid`, `priceid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_reconciliation_account` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(10) NOT NULL,
    `name` VARCHAR(50) NOT NULL,

    UNIQUE INDEX `code`(`code`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_reconciliation_manufacturers` (
    `reconciliation_id` INTEGER NOT NULL,
    `manufacturer_id` INTEGER NOT NULL,

    INDEX `manufacturer_id`(`manufacturer_id`),
    PRIMARY KEY (`reconciliation_id`, `manufacturer_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_reconciliation_orderid` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `reconciliation_id` INTEGER NOT NULL DEFAULT 0,
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `time_to_db` INTEGER NOT NULL DEFAULT 0,
    `orders_variant_DELETE_IN_MONTH` INTEGER NOT NULL DEFAULT 0,
    `ref_to_us_DELETE_IN_MONTH` CHAR(1) NOT NULL DEFAULT '',
    `ref_reconciliation_id` INTEGER NOT NULL DEFAULT 0,
    `invoice_number` INTEGER NOT NULL DEFAULT 0,
    `memo_number` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_reconciliation_search_keyphrases` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `search_keyphrase` VARCHAR(255) NOT NULL DEFAULT '',
    `code` VARCHAR(8) NOT NULL DEFAULT '',
    `expense_description` VARCHAR(255) NOT NULL DEFAULT '',

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_reconciliation_upload_info` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `date` INTEGER NOT NULL DEFAULT 0,
    `orig_file_name` VARCHAR(255) NOT NULL DEFAULT '',
    `local_file` VARCHAR(255) NOT NULL DEFAULT '',
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `min_date_in_file` INTEGER NOT NULL DEFAULT 0,
    `max_date_in_file` INTEGER NOT NULL DEFAULT 0,
    `count_lines` INTEGER NOT NULL DEFAULT 0,
    `count_added_rows` INTEGER NOT NULL DEFAULT 0,
    `checksum` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_reconciliations` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `date_csv` INTEGER NOT NULL DEFAULT 0,
    `file_upload_date` INTEGER NOT NULL DEFAULT 0,
    `description_csv` VARCHAR(255) NOT NULL DEFAULT '',
    `amount_csv` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `action` CHAR(1) NOT NULL DEFAULT '',
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `transaction_type` CHAR(1) NOT NULL DEFAULT '',
    `type` ENUM('SPEND', 'RECEIVE', 'SPEND-TRANSFER', 'RECEIVE-TRANSFER') NULL,
    `status` ENUM('AUTHORISED', 'DELETED') NULL,
    `bank_transaction_id` CHAR(36) NULL,
    `account_id` INTEGER NULL DEFAULT 1,

    UNIQUE INDEX `bank_transaction_id`(`bank_transaction_id`),
    INDEX `action`(`action`),
    INDEX `date_csv`(`date_csv`),
    INDEX `file_upload_date`(`file_upload_date`),
    INDEX `manufacturerid`(`manufacturerid`),
    INDEX `transaction_type`(`transaction_type`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_reconciliations_invoices` (
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `invoice_number` INTEGER NOT NULL DEFAULT 0,
    `part_of_total_transaction_in_amount_of` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `reconciliation_id` INTEGER NOT NULL DEFAULT 0,

    INDEX `manufacturerid`(`manufacturerid`),
    INDEX `orderid`(`orderid`),
    PRIMARY KEY (`orderid`, `manufacturerid`, `invoice_number`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_reconciliations_memos` (
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `memo_number` INTEGER NOT NULL DEFAULT 0,
    `ref_to_us_part_of_transaction` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `ref_reconciliation_id` INTEGER NOT NULL DEFAULT 0,

    INDEX `manufacturerid`(`manufacturerid`),
    INDEX `orderid`(`orderid`),
    PRIMARY KEY (`orderid`, `manufacturerid`, `memo_number`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_referers` (
    `referer_id` INTEGER NOT NULL AUTO_INCREMENT,
    `referer` TEXT NOT NULL,
    `visits` INTEGER NOT NULL DEFAULT 0,
    `last_visited` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    PRIMARY KEY (`referer_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_refund_groups` (
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `shippingid` INTEGER NOT NULL DEFAULT 0,
    `shipping` VARCHAR(255) NOT NULL DEFAULT '',
    `tracking` TEXT NOT NULL,
    `total_net` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `total_gst` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `total_pst` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `total_gross` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `shipping_net` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `shipping_gst` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `shipping_pst` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `shipping_gross` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting` TEXT NOT NULL,
    `ref_ship` DECIMAL(12, 2) NOT NULL,
    `extra_data` TEXT NOT NULL,
    `notify_status` CHAR(1) NOT NULL DEFAULT 'Q',
    `accounting_net_0` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gst_0` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_pst_0` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gross_0` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_filled_0` CHAR(1) NOT NULL DEFAULT '',
    `accounting_net_1_cost_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gst_1_cost_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_pst_1_cost_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gross_1_cost_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_filled_1_cost_to_us` CHAR(1) NOT NULL DEFAULT '',
    `accounting_net_2_shipping` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gst_2_shipping` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_pst_2_shipping` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gross_2_shipping` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_filled_2_shipping` CHAR(1) NOT NULL DEFAULT '',
    `accounting_net_3_ref_to_cust` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gst_3_ref_to_cust` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_pst_3_ref_to_cust` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gross_3_ref_to_cust` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_filled_3_ref_to_cust` CHAR(1) NOT NULL DEFAULT '',
    `accounting_net_4_ref_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gst_4_ref_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_pst_4_ref_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gross_4_ref_to_us` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_filled_4_ref_to_us` CHAR(1) NOT NULL DEFAULT '',
    `accounting_net_5_profit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gst_5_profit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_pst_5_profit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_gross_5_profit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `accounting_filled_5_profit` CHAR(1) NOT NULL DEFAULT '',
    `OLD_accounting` TEXT NOT NULL,
    `refund_reason` TEXT NOT NULL,

    PRIMARY KEY (`orderid`, `manufacturerid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_refunded_products` (
    `productid` INTEGER NOT NULL,
    `orderid` INTEGER NOT NULL,
    `manufacturerid` INTEGER NOT NULL,
    `provider` VARCHAR(32) NOT NULL DEFAULT '',
    `ref_price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `ref_qty` INTEGER NOT NULL,
    `extra_data` TEXT NOT NULL,
    `itemid` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`productid`, `orderid`, `itemid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_register_field_values` (
    `fieldid` INTEGER NOT NULL DEFAULT 0,
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `value` TEXT NOT NULL,

    PRIMARY KEY (`fieldid`, `login`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_register_fields` (
    `fieldid` INTEGER NOT NULL AUTO_INCREMENT,
    `field` VARCHAR(255) NOT NULL DEFAULT '',
    `type` CHAR(1) NOT NULL DEFAULT 'T',
    `variants` TEXT NOT NULL,
    `def` VARCHAR(255) NOT NULL DEFAULT '',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `section` CHAR(1) NOT NULL DEFAULT 'A',
    `avail` VARCHAR(4) NOT NULL DEFAULT '',
    `required` VARCHAR(4) NOT NULL DEFAULT '',

    INDEX `avail`(`avail`),
    INDEX `required`(`required`),
    PRIMARY KEY (`fieldid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_related_objects_collector` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `storefrontid` INTEGER NOT NULL DEFAULT 0,
    `collecting_period_backward_months` INTEGER NOT NULL DEFAULT 0,
    `add_to_cart` CHAR(1) NOT NULL DEFAULT 'N',
    `order_submit` CHAR(1) NOT NULL DEFAULT 'N',
    `search` CHAR(1) NOT NULL DEFAULT 'N',
    `checkout` CHAR(1) NOT NULL DEFAULT 'N',
    `mobile` CHAR(1) NOT NULL DEFAULT 'N',

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_replacements` (
    `repid` INTEGER NOT NULL AUTO_INCREMENT,
    `what` VARCHAR(255) NOT NULL,
    `by` VARCHAR(255) NOT NULL,

    PRIMARY KEY (`repid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_reports` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `enabled` BOOLEAN NOT NULL DEFAULT false,
    `form_data` TEXT NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_request_availability_options` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `date_mm_dd_yyyy` VARCHAR(32) NULL DEFAULT '',
    `active` CHAR(1) NOT NULL DEFAULT 'Y',

    INDEX `idx_date`(`date_mm_dd_yyyy`, `active`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_returns` (
    `returnid` INTEGER NOT NULL AUTO_INCREMENT,
    `itemid` INTEGER NOT NULL DEFAULT 0,
    `amount` INTEGER NOT NULL DEFAULT 0,
    `status` CHAR(1) NOT NULL DEFAULT 'R',
    `reason` INTEGER NOT NULL DEFAULT 0,
    `action` INTEGER NOT NULL DEFAULT 0,
    `comment` TEXT NOT NULL,
    `date` INTEGER NOT NULL DEFAULT 0,
    `credit` VARCHAR(16) NOT NULL DEFAULT '',
    `creator` CHAR(1) NOT NULL DEFAULT 'C',

    INDEX `itemid`(`itemid`),
    PRIMARY KEY (`returnid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_reviews_images` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `review_id` INTEGER UNSIGNED NOT NULL,
    `image_id` INTEGER UNSIGNED NOT NULL,

    INDEX `xcart_reviews_images_xcart_images_image_id_fk`(`image_id`),
    INDEX `xcart_reviews_images_xcart_product_reviews_review_id_fk`(`review_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_reviews_videos` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `review_id` INTEGER UNSIGNED NOT NULL,
    `video_id` INTEGER UNSIGNED NOT NULL,

    INDEX `xcart_reviews_videos_xcart_videos_video_id_fk`(`video_id`),
    UNIQUE INDEX `xcart_reviews_videos_review_id_video_id_uindex`(`review_id`, `video_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_rma_details` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `rma_id` INTEGER NOT NULL DEFAULT 0,
    `productid` INTEGER NOT NULL DEFAULT 0,
    `itemid` INTEGER NOT NULL DEFAULT 0,
    `productcode` VARCHAR(255) NOT NULL DEFAULT '',
    `product` VARCHAR(255) NOT NULL DEFAULT '',
    `amount` INTEGER NOT NULL DEFAULT 0,
    `would_like` CHAR(1) NOT NULL DEFAULT 'O',

    INDEX `rma_id`(`rma_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_rma_images` (
    `rma_id` INTEGER NULL,
    `image_id` INTEGER NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_rma_statuses` (
    `code` CHAR(1) NOT NULL DEFAULT '',
    `name` CHAR(255) NOT NULL DEFAULT '',
    `orderby` INTEGER UNSIGNED NOT NULL DEFAULT 0,

    INDEX `orderby`(`orderby`),
    PRIMARY KEY (`code`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_rma_would_like_variants` (
    `code` CHAR(1) NOT NULL DEFAULT '',
    `name` CHAR(255) NOT NULL DEFAULT '',
    `orderby` INTEGER UNSIGNED NOT NULL DEFAULT 0,

    INDEX `orderby`(`orderby`),
    PRIMARY KEY (`code`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_rmas` (
    `rma_id` INTEGER NOT NULL AUTO_INCREMENT,
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `zipcode` VARCHAR(32) NOT NULL DEFAULT '',
    `email` VARCHAR(128) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `explanation` VARCHAR(500) NOT NULL DEFAULT '',
    `status` CHAR(1) NOT NULL DEFAULT 'O',
    `rma_number` INTEGER NOT NULL DEFAULT 0,
    `order_email` VARCHAR(128) NOT NULL DEFAULT '',

    PRIMARY KEY (`rma_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_search_stats` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `search_phrase` VARCHAR(255) NOT NULL DEFAULT '',
    `customer_id` VARCHAR(32) NULL DEFAULT '',
    `date_time` INTEGER NOT NULL DEFAULT 0,
    `request_delay` SMALLINT UNSIGNED NULL DEFAULT 0,
    `hits` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
    `storefrontid` SMALLINT UNSIGNED NULL DEFAULT 0,

    INDEX `idx_customerid`(`customer_id`),
    INDEX `idx_datetime`(`date_time`),
    INDEX `idx_storefrontid`(`storefrontid`, `hits`),
    INDEX `search_phrase`(`search_phrase`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_secure_data` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `data` LONGTEXT NOT NULL,
    `orderby` INTEGER NOT NULL DEFAULT 10,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_secure_data_users` (
    `login` VARCHAR(32) NOT NULL,
    `secure_data_id` INTEGER NOT NULL,

    INDEX `xcart_secure_data_users_ibfk_1`(`secure_data_id`),
    PRIMARY KEY (`login`, `secure_data_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_seed_categories` (
    `scatid` INTEGER NOT NULL AUTO_INCREMENT,
    `catid` INTEGER NULL,
    `title` VARCHAR(255) NOT NULL DEFAULT '',
    `keyphrase` VARCHAR(255) NOT NULL DEFAULT '',
    `is_bold` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL,
    `avail` CHAR(1) NOT NULL DEFAULT 'Y',
    `sfid` INTEGER NOT NULL DEFAULT 0,

    INDEX `avail`(`avail`),
    INDEX `orderby`(`orderby`),
    PRIMARY KEY (`scatid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_seo_categories_keyphrases` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `categoryid` INTEGER NOT NULL DEFAULT 0,
    `keyphrase` VARCHAR(300) NOT NULL DEFAULT '',
    `used` VARCHAR(1) NOT NULL DEFAULT 'N',
    `sfid` INTEGER NOT NULL DEFAULT 0,

    INDEX `categoryid`(`categoryid`, `used`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_sessions_data` (
    `sessid` CHAR(32) NOT NULL DEFAULT '',
    `start` INTEGER NOT NULL DEFAULT 0,
    `expiry` INTEGER NOT NULL DEFAULT 0,
    `data` MEDIUMTEXT NOT NULL,
    `cart_number` INTEGER NOT NULL DEFAULT 0,
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `new_column` INTEGER NULL,
    `user_id` BIGINT UNSIGNED NULL,

    UNIQUE INDEX `id`(`id`),
    INDEX `cart_number`(`cart_number`),
    INDEX `e`(`expiry`),
    INDEX `s`(`start`),
    INDEX `xcart_sessions_data_xcart_users_user_id_fk`(`user_id`),
    PRIMARY KEY (`sessid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_setup_images` (
    `itype` CHAR(1) NOT NULL DEFAULT '',
    `location` CHAR(2) NOT NULL DEFAULT 'DB',
    `save_url` CHAR(1) NOT NULL DEFAULT '',
    `size_limit` INTEGER NOT NULL DEFAULT 0,
    `md5_check` VARCHAR(32) NOT NULL DEFAULT '',
    `default_image` VARCHAR(255) NOT NULL DEFAULT './default_image.gif',

    UNIQUE INDEX `itype`(`itype`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_shipping` (
    `shippingid` INTEGER NOT NULL AUTO_INCREMENT,
    `shipping` VARCHAR(128) NOT NULL DEFAULT '',
    `frontend_name` VARCHAR(255) NOT NULL DEFAULT '',
    `shipping_time` VARCHAR(128) NOT NULL DEFAULT '',
    `destination` CHAR(1) NOT NULL DEFAULT 'I',
    `code` VARCHAR(32) NOT NULL DEFAULT '',
    `subcode` VARCHAR(32) NOT NULL DEFAULT '',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `active` CHAR(1) NOT NULL DEFAULT 'Y',
    `intershipper_code` VARCHAR(32) NOT NULL DEFAULT '',
    `weight_min` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `weight_limit` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `service_code` INTEGER NOT NULL DEFAULT 0,
    `is_cod` CHAR(1) NOT NULL DEFAULT '',
    `is_new` CHAR(1) NOT NULL DEFAULT '',
    `vol_threshold` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `dim_factor` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `important` BOOLEAN NULL DEFAULT false,
    `days_min` TINYINT UNSIGNED NOT NULL DEFAULT 255,
    `days_max` TINYINT UNSIGNED NOT NULL DEFAULT 255,
    `is_free_shipping` TINYINT UNSIGNED NOT NULL DEFAULT 0,

    INDEX `code`(`code`),
    INDEX `orderby`(`orderby`),
    PRIMARY KEY (`shippingid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_shipping_cache` (
    `shipping_cache_id` INTEGER NOT NULL AUTO_INCREMENT,
    `shipping_location_id` INTEGER NOT NULL,
    `shipping_carrier` ENUM('Amazon', 'UPS', 'Flat', 'ANX', 'APOST', 'ARB', 'CPC', 'DHL', 'EWW', 'FDX', 'USPS', 'UPSFlat', 'Free') NOT NULL,
    `cache_date` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),

    INDEX `cache_date`(`cache_date`),
    INDEX `shipping_carrier`(`shipping_carrier`),
    PRIMARY KEY (`shipping_cache_id`, `shipping_location_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_shipping_cache_location` (
    `shipping_location_id` INTEGER NOT NULL AUTO_INCREMENT,
    `zip_from` VARCHAR(32) NULL,
    `zip_to` VARCHAR(32) NULL,
    `state_from` VARCHAR(32) NULL,
    `state_to` VARCHAR(32) NULL,
    `country_from` VARCHAR(32) NULL,
    `country_to` VARCHAR(32) NULL,

    UNIQUE INDEX `zip_from_zip_to_state_from_state_to_country_from_country_to`(`zip_from`, `zip_to`, `state_from`, `state_to`, `country_from`, `country_to`),
    PRIMARY KEY (`shipping_location_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_shipping_cache_products` (
    `shipping_cache_id` INTEGER NOT NULL,
    `product_id` INTEGER NOT NULL,
    `product_quantity` SMALLINT NOT NULL DEFAULT 0,

    INDEX `product_id`(`product_id`),
    PRIMARY KEY (`shipping_cache_id`, `product_id`, `product_quantity`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_shipping_cache_quotes` (
    `shipping_cache_id` INTEGER NOT NULL,
    `rate_id` INTEGER NOT NULL,
    `shipping_quote` DECIMAL(12, 2) NULL,
    `shipping_charge` DECIMAL(12, 2) NULL,
    `shipping_charge_before_map` DECIMAL(12, 2) NULL,

    INDEX `FK_xcart_shipping_cache_quotes_xcart_shipping_rates`(`rate_id`),
    PRIMARY KEY (`shipping_cache_id`, `rate_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_shipping_carrier` (
    `carrier_code` VARCHAR(32) NOT NULL,
    `priority` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `cache_lifetime` SMALLINT UNSIGNED NOT NULL,

    INDEX `priority`(`priority`),
    PRIMARY KEY (`carrier_code`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_shipping_options` (
    `carrier` VARCHAR(32) NOT NULL DEFAULT '',
    `param00` TEXT NOT NULL,
    `param01` VARCHAR(128) NOT NULL DEFAULT '',
    `param02` VARCHAR(128) NOT NULL DEFAULT '',
    `param03` VARCHAR(128) NOT NULL DEFAULT '',
    `param04` VARCHAR(128) NOT NULL DEFAULT '',
    `param05` VARCHAR(128) NOT NULL DEFAULT '',
    `param06` VARCHAR(128) NOT NULL DEFAULT '',
    `param07` VARCHAR(128) NOT NULL DEFAULT '',
    `param08` VARCHAR(128) NOT NULL DEFAULT '',
    `param09` VARCHAR(128) NOT NULL DEFAULT '',

    PRIMARY KEY (`carrier`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_shipping_product` (
    `product_id` INTEGER NOT NULL,
    `shipping_rate_id` INTEGER NOT NULL,
    `weight_ratio` DECIMAL(10, 2) NOT NULL DEFAULT 1.00,

    PRIMARY KEY (`product_id`, `shipping_rate_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_shipping_quote_log` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `quote_id` INTEGER NOT NULL DEFAULT 0,
    `datetime` INTEGER NOT NULL DEFAULT 0,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `session_id` VARCHAR(255) NOT NULL DEFAULT '',
    `customer_id` VARCHAR(255) NOT NULL DEFAULT '',
    `ups_ground_quote` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `approx_ground_quote` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `product_cost` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `reviewed` CHAR(1) NOT NULL DEFAULT 'N',
    `reviewed_by` VARCHAR(255) NOT NULL DEFAULT '',
    `reviewed_date` INTEGER NOT NULL DEFAULT 0,
    `s_address` TEXT NOT NULL,
    `ups_server_quote` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `ab_options` VARCHAR(50) NOT NULL DEFAULT '',
    `logging_point` VARCHAR(50) NOT NULL DEFAULT '',

    INDEX `datetime`(`datetime`),
    INDEX `datetime_quoteid`(`quote_id`, `datetime`),
    INDEX `logging_point`(`logging_point`),
    INDEX `manufacturerid`(`manufacturerid`),
    INDEX `quote_id`(`quote_id`),
    INDEX `reviewed`(`reviewed`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_shipping_quote_products_log` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `quote_id` INTEGER NOT NULL DEFAULT 0,
    `productid` INTEGER NOT NULL DEFAULT 0,
    `qty` INTEGER NOT NULL DEFAULT 0,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,

    INDEX `manufacturerid`(`manufacturerid`),
    INDEX `productid`(`productid`),
    INDEX `quote_id`(`quote_id`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_shipping_rates` (
    `rateid` INTEGER NOT NULL AUTO_INCREMENT,
    `shippingid` INTEGER NOT NULL DEFAULT 0,
    `zoneid` INTEGER NOT NULL DEFAULT 0,
    `maxamount` INTEGER NOT NULL DEFAULT 1000000,
    `minweight` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `maxweight` DECIMAL(12, 2) NOT NULL DEFAULT 1000000.00,
    `mintotal` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `maxtotal` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `rate` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `item_rate` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `weight_rate` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `rate_p` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `provider` CHAR(32) NOT NULL DEFAULT 'master',
    `type` CHAR(1) NOT NULL DEFAULT 'D',
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `cost_marcup` DECIMAL(12, 2) NOT NULL DEFAULT 1.00,
    `real_drop_ship_fee` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `min_shipping_charge` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `max_shipping_charge` DECIMAL(12, 2) NOT NULL DEFAULT 999999.99,

    INDEX `idx_zoneid_manuf`(`zoneid`, `manufacturerid`),
    INDEX `manufacturerid`(`manufacturerid`),
    INDEX `maxamount`(`maxamount`),
    INDEX `maxweight`(`maxweight`),
    INDEX `provider`(`provider`),
    INDEX `shippingid`(`shippingid`),
    INDEX `zoneid`(`zoneid`),
    PRIMARY KEY (`rateid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_sitemap_extra` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `url` VARCHAR(255) NOT NULL DEFAULT '',
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `active` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `storefront` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_states` (
    `stateid` INTEGER NOT NULL AUTO_INCREMENT,
    `state` VARCHAR(100) NOT NULL DEFAULT '',
    `code` VARCHAR(20) NOT NULL DEFAULT '',
    `country_code` CHAR(2) NOT NULL DEFAULT '',
    `base_state_zipcode` VARCHAR(32) NOT NULL DEFAULT '',
    `est_time_offset` INTEGER NOT NULL DEFAULT 0,
    `phone` VARCHAR(32) NOT NULL DEFAULT '',
    `timezone` VARCHAR(100) NOT NULL DEFAULT '',
    `population` INTEGER NULL,

    UNIQUE INDEX `code`(`country_code`, `code`),
    UNIQUE INDEX `state`(`state`, `country_code`),
    PRIMARY KEY (`stateid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_stats_adaptive` (
    `platform` VARCHAR(64) NOT NULL DEFAULT '',
    `browser` VARCHAR(10) NOT NULL DEFAULT '',
    `version` VARCHAR(16) NOT NULL DEFAULT '',
    `java` CHAR(1) NOT NULL DEFAULT 'Y',
    `js` CHAR(1) NOT NULL DEFAULT 'Y',
    `count` INTEGER NOT NULL DEFAULT 0,
    `cookie` CHAR(1) NOT NULL DEFAULT '',
    `screen_x` INTEGER NOT NULL DEFAULT 0,
    `screen_y` INTEGER NOT NULL DEFAULT 0,
    `last_date` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`platform`, `browser`, `java`, `js`, `version`, `cookie`, `screen_x`, `screen_y`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_stats_cart_funnel` (
    `transactionid` INTEGER NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `start_page` INTEGER NOT NULL DEFAULT 0,
    `step1` INTEGER NOT NULL DEFAULT 0,
    `step2` INTEGER NOT NULL DEFAULT 0,
    `step3` INTEGER NOT NULL DEFAULT 0,
    `final_page` INTEGER NOT NULL DEFAULT 0,
    `date` INTEGER NOT NULL DEFAULT 0,

    INDEX `date`(`date`),
    INDEX `final_page`(`final_page`),
    INDEX `start_page`(`start_page`),
    INDEX `step1`(`step1`),
    INDEX `step2`(`step2`),
    INDEX `step3`(`step3`),
    PRIMARY KEY (`transactionid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_stats_customers_products` (
    `productid` INTEGER NOT NULL DEFAULT 0,
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `counter` INTEGER NOT NULL DEFAULT 0,

    INDEX `counter`(`counter`),
    UNIQUE INDEX `opt-O`(`login`, `productid`),
    PRIMARY KEY (`productid`, `login`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_stats_pages` (
    `pageid` INTEGER NOT NULL AUTO_INCREMENT,
    `page` VARCHAR(255) NOT NULL DEFAULT '',

    INDEX `page`(`page`),
    PRIMARY KEY (`pageid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_stats_pages_paths` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `path` VARCHAR(255) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,

    INDEX `counter`(`date`),
    INDEX `path`(`path`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_stats_pages_views` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `pageid` INTEGER NOT NULL DEFAULT 0,
    `time_avg` INTEGER NOT NULL DEFAULT 0,
    `date` INTEGER NOT NULL DEFAULT 0,

    INDEX `date`(`date`),
    INDEX `pageid`(`pageid`),
    INDEX `time_avg`(`time_avg`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_stats_search` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `search` VARCHAR(255) NOT NULL DEFAULT '',
    `date` INTEGER NOT NULL DEFAULT 0,
    `storefrontid` INTEGER NOT NULL DEFAULT 0,

    INDEX `date`(`date`),
    INDEX `search`(`search`),
    INDEX `storefrontid`(`storefrontid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_stats_shop` (
    `stats_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `id` INTEGER NOT NULL DEFAULT 0,
    `action` CHAR(1) NOT NULL DEFAULT 'V',
    `date` INTEGER NOT NULL DEFAULT 0,

    INDEX `action`(`action`),
    INDEX `date`(`date`),
    INDEX `id`(`id`),
    PRIMARY KEY (`stats_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_stop_list` (
    `octet1` INTEGER NOT NULL DEFAULT 0,
    `octet2` INTEGER NOT NULL DEFAULT 0,
    `octet3` INTEGER NOT NULL DEFAULT 0,
    `octet4` INTEGER NOT NULL DEFAULT 0,
    `ip` VARCHAR(15) NOT NULL DEFAULT '',
    `reason` CHAR(1) NOT NULL DEFAULT 'M',
    `date` INTEGER NOT NULL DEFAULT 0,
    `ipid` INTEGER NOT NULL AUTO_INCREMENT,
    `ip_type` CHAR(1) NOT NULL DEFAULT 'B',

    INDEX `ip`(`ip`),
    UNIQUE INDEX `octet1`(`octet1`, `octet2`, `octet3`, `octet4`),
    PRIMARY KEY (`ipid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_storefront_links` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `storefront1` INTEGER NOT NULL,
    `storefront2` INTEGER NOT NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_storefronts` (
    `storefrontid` INTEGER NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(10) NOT NULL DEFAULT '',
    `domain` VARCHAR(64) NOT NULL DEFAULT '',
    `short_name` VARCHAR(64) NOT NULL DEFAULT '',
    `prefix` VARCHAR(8) NOT NULL DEFAULT '',
    `status` CHAR(1) NOT NULL DEFAULT 'D',
    `orderby` INTEGER NOT NULL,
    `currency_id` MEDIUMINT UNSIGNED NULL,
    `company_name` VARCHAR(255) NULL,
    `company_website` VARCHAR(255) NULL,
    `local_phone` VARCHAR(255) NULL,
    `fax_number` VARCHAR(255) NULL,
    `CDN_domain` VARCHAR(255) NULL,
    `lang_id` MEDIUMINT UNSIGNED NULL,
    `start_year` INTEGER NULL,
    `Enable_surf_stats` TINYINT NOT NULL DEFAULT 0,
    `country` CHAR(2) NULL,
    `cidev_header_code` VARCHAR(255) NULL,
    `shop_closed_method` VARCHAR(255) NULL,
    `customer_service_working_time` VARCHAR(255) NULL,
    `shop_closed` TINYINT NOT NULL DEFAULT 0,
    `cidev_top_header_code` VARCHAR(64) NULL,
    `opt_order_prefix` VARCHAR(8) NULL,
    `newsletter_email` VARCHAR(64) NULL,
    `search_all_website_show` TINYINT NOT NULL DEFAULT 0,
    `Enable_CDN` TINYINT NOT NULL DEFAULT 1,
    `Google_Trusted_Store_ID` VARCHAR(255) NULL,
    `flat_shipping_enabled` TINYINT NOT NULL DEFAULT 0,
    `file_edit_image_favicon` VARCHAR(512) NULL,
    `show_full_state_country` TINYINT NULL DEFAULT 0,
    `logo` VARCHAR(255) NULL,
    `logo_mobile` VARCHAR(255) NULL,
    `base_category_id` INTEGER NULL,

    INDEX `FK_xcart_storefronts_xcart_currencies`(`currency_id`),
    INDEX `domain`(`domain`, `short_name`),
    PRIMARY KEY (`storefrontid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_storefronts_config` (
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `storefrontid` INTEGER NOT NULL DEFAULT 0,
    `comment` VARCHAR(255) NOT NULL DEFAULT '',
    `value` MEDIUMTEXT NOT NULL,
    `category` VARCHAR(32) NOT NULL DEFAULT '',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `type` ENUM('numeric', 'text', 'textarea', 'checkbox', 'separator', 'selector', 'multiselector') NULL DEFAULT 'text',
    `defvalue` MEDIUMTEXT NOT NULL,
    `variants` MEDIUMTEXT NOT NULL,
    `validation` VARCHAR(255) NOT NULL DEFAULT '',

    INDEX `orderby`(`orderby`),
    INDEX `type`(`type`),
    PRIMARY KEY (`storefrontid`, `name`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_storefronts_external_marketplaces` (
    `marketplace_id` INTEGER NOT NULL AUTO_INCREMENT,
    `storefront_id` INTEGER NOT NULL,
    `inventory_batch_count` INTEGER NOT NULL,
    `products_batch_count` INTEGER NOT NULL,
    `P1` VARCHAR(200) NOT NULL,
    `P2` VARCHAR(200) NOT NULL,
    `P0` VARCHAR(255) NOT NULL,
    `ftp_domain` VARCHAR(255) NOT NULL DEFAULT '',
    `ftp_login` VARCHAR(255) NOT NULL DEFAULT '',
    `ftp_password` VARCHAR(255) NOT NULL DEFAULT '',
    `ftp_path` VARCHAR(255) NOT NULL DEFAULT '',
    `export_filename_suffix` VARCHAR(255) NOT NULL DEFAULT '',
    `update_expired_before` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `update_max_expired_products_per_day` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `countries` VARCHAR(191) NULL DEFAULT 'US',

    PRIMARY KEY (`marketplace_id`, `storefront_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_subscription_customers` (
    `last_payed_date` INTEGER NOT NULL DEFAULT 0,
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `productid` INTEGER NOT NULL DEFAULT 0,
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `last_payed_orderid` INTEGER NOT NULL DEFAULT 0,
    `subscriptionid` INTEGER NOT NULL AUTO_INCREMENT,
    `subscription_status` VARCHAR(50) NOT NULL DEFAULT 'Active',

    INDEX `last_payed_date`(`last_payed_date`),
    PRIMARY KEY (`subscriptionid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_subscriptions` (
    `productid` INTEGER NOT NULL DEFAULT 0,
    `pay_period_type` VARCHAR(64) NOT NULL DEFAULT 'Monthly',
    `price_period` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `oneday_price` DECIMAL(12, 6) NOT NULL DEFAULT 0.000000,
    `days_as_period` INTEGER NOT NULL DEFAULT 0,
    `pay_dates` TEXT NULL,

    PRIMARY KEY (`productid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_supplier_feeds` (
    `feed_id` INTEGER NOT NULL AUTO_INCREMENT,
    `feed_name` VARCHAR(255) NOT NULL DEFAULT '',
    `feed_type` CHAR(1) NOT NULL DEFAULT 'I',
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `storefront_id` INTEGER NOT NULL DEFAULT 0,
    `base_category_id` INTEGER NULL,
    `feed_file_name` VARCHAR(255) NOT NULL DEFAULT '',
    `last_update_time` INTEGER NOT NULL DEFAULT 0,
    `average_update_period` INTEGER NOT NULL DEFAULT 0,
    `last_update_items_count` INTEGER NOT NULL DEFAULT 0,
    `threshold` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `add_new_only` CHAR(1) NOT NULL DEFAULT '',
    `last_md5` VARCHAR(100) NOT NULL DEFAULT '',
    `enabled` CHAR(1) NOT NULL DEFAULT 'Y',
    `last_update_period` INTEGER NOT NULL DEFAULT 0,
    `last_update_late` DECIMAL(10, 1) NULL,
    `multiple_feed_destinations` CHAR(1) NOT NULL DEFAULT 'N',
    `disable_search_of_discontinued_items` CHAR(1) NOT NULL DEFAULT 'N',
    `last_feed_fields` MEDIUMTEXT NOT NULL,
    `native_full_description` CHAR(1) NOT NULL DEFAULT 'N',
    `new_cron` ENUM('Y', 'N') NOT NULL DEFAULT 'Y',
    `feed_source` ENUM('site', 'price') NULL DEFAULT 'site',
    `feed_source_date` DATETIME(0) NULL,
    `schedule` VARCHAR(50) NULL,
    `run_force` TINYINT NOT NULL DEFAULT 0,
    `process_time` MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
    `dont_update_fields` TEXT NULL,

    INDEX `FK_xcart_supplier_feeds_xcart_categories`(`storefront_id`, `base_category_id`),
    INDEX `manufacturerid`(`manufacturerid`),
    INDEX `process_time`(`process_time`),
    PRIMARY KEY (`feed_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_supplier_product_feeds` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `manufacturerid` INTEGER NOT NULL DEFAULT 0,
    `storefrontid` INTEGER NOT NULL DEFAULT 0,
    `enabled_feed` CHAR(1) NOT NULL DEFAULT 'N',
    `ftp_host` VARCHAR(255) NOT NULL DEFAULT '',
    `ftp_login` VARCHAR(255) NOT NULL DEFAULT '',
    `ftp_password` VARCHAR(255) NOT NULL DEFAULT '',
    `ftp_folder` VARCHAR(255) NOT NULL DEFAULT '',
    `feed_procedure_id` VARCHAR(255) NOT NULL DEFAULT '',
    `default_productid` INTEGER NOT NULL DEFAULT 0,
    `product_management_team_email` VARCHAR(255) NOT NULL DEFAULT '',
    `last_import_date` INTEGER NOT NULL DEFAULT 0,
    `last_imported_updated_products_count` VARCHAR(32) NOT NULL DEFAULT '',
    `comments` TEXT NULL,
    `is_launched` CHAR(1) NOT NULL DEFAULT 'N',
    `import_new_products` CHAR(1) NOT NULL DEFAULT 'N',
    `import_new_and_update_existing_products` CHAR(1) NOT NULL DEFAULT 'N',
    `updation_frequency` VARCHAR(10) NOT NULL DEFAULT '',
    `last_products_count_in_file` INTEGER NOT NULL DEFAULT 0,
    `default_parent_categoryid` INTEGER NOT NULL DEFAULT 0,

    INDEX `manufacturerid`(`manufacturerid`),
    INDEX `storefrontid`(`storefrontid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_tax_rate_memberships` (
    `rateid` INTEGER NOT NULL DEFAULT 0,
    `membershipid` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`rateid`, `membershipid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_tax_rates` (
    `rateid` INTEGER NOT NULL AUTO_INCREMENT,
    `taxid` INTEGER NOT NULL DEFAULT 0,
    `zoneid` INTEGER NOT NULL DEFAULT 0,
    `formula` VARCHAR(255) NOT NULL DEFAULT '',
    `rate_value` DECIMAL(12, 3) NOT NULL DEFAULT 0.000,
    `rate_type` CHAR(1) NOT NULL DEFAULT '',
    `provider` VARCHAR(32) NOT NULL DEFAULT '',

    INDEX `provider`(`provider`),
    INDEX `tax_rate`(`taxid`, `zoneid`),
    PRIMARY KEY (`rateid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_taxes` (
    `taxid` INTEGER NOT NULL AUTO_INCREMENT,
    `tax_name` VARCHAR(10) NOT NULL DEFAULT '',
    `formula` VARCHAR(255) NOT NULL DEFAULT '',
    `address_type` CHAR(1) NOT NULL DEFAULT 'S',
    `active` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `price_includes_tax` CHAR(1) NOT NULL DEFAULT 'N',
    `display_including_tax` CHAR(1) NOT NULL DEFAULT 'N',
    `display_info` CHAR(1) NOT NULL DEFAULT '',
    `regnumber` VARCHAR(255) NOT NULL DEFAULT '',
    `priority` INTEGER NOT NULL DEFAULT 0,
    `position` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `is_vat` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `apply_to` ENUM('PS', 'SH') NOT NULL,

    UNIQUE INDEX `tax_name`(`tax_name`),
    INDEX `active`(`active`),
    PRIMARY KEY (`taxid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_templates_categories` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL DEFAULT '',
    `pos` INTEGER UNSIGNED NOT NULL DEFAULT 0,
    `root` INTEGER UNSIGNED NULL,
    `level` INTEGER UNSIGNED NULL,
    `lft` INTEGER UNSIGNED NULL,
    `rgt` INTEGER UNSIGNED NULL,
    `parent_id` INTEGER UNSIGNED NULL,

    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_templates_for_communication` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `template_name` VARCHAR(255) NOT NULL DEFAULT '',
    `pos` INTEGER NOT NULL DEFAULT 0,
    `subject_line` VARCHAR(255) NOT NULL DEFAULT '',
    `send_to_email` VARCHAR(255) NOT NULL DEFAULT '',
    `message_body` MEDIUMTEXT NOT NULL,
    `department` VARCHAR(255) NOT NULL DEFAULT '',
    `active` ENUM('Y', 'N') NOT NULL DEFAULT 'N',
    `ca_status` CHAR(1) NOT NULL DEFAULT '',
    `status_id` INTEGER NOT NULL DEFAULT 0,
    `attach_pdf_invoice` CHAR(1) NOT NULL DEFAULT 'N',
    `category_id` INTEGER UNSIGNED NULL,

    INDEX `FK_xcart_templates_for_communication_xcart_templates_categories`(`category_id`),
    INDEX `idx_dept_pos`(`department`, `pos`, `active`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_temporary_data` (
    `id` VARCHAR(32) NOT NULL DEFAULT '',
    `data` TEXT NULL,
    `expire` INTEGER NULL,

    INDEX `expire`(`expire`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_titles` (
    `titleid` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(64) NOT NULL DEFAULT '',
    `active` CHAR(1) NOT NULL DEFAULT 'Y',
    `orderby` INTEGER NOT NULL DEFAULT 0,

    INDEX `ia`(`titleid`, `active`),
    INDEX `orderby`(`orderby`),
    PRIMARY KEY (`titleid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_tracking_links` (
    `linkid` INTEGER NOT NULL AUTO_INCREMENT,
    `shipping` VARCHAR(128) NOT NULL DEFAULT '',
    `OLD_link_DELETE_IN_MONTH` VARCHAR(255) NOT NULL DEFAULT '',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `carrier_id` INTEGER NOT NULL DEFAULT 0,

    PRIMARY KEY (`linkid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_tracking_links_carrier` (
    `carrier_id` INTEGER NOT NULL AUTO_INCREMENT,
    `carrier` VARCHAR(255) NOT NULL DEFAULT '',
    `link` VARCHAR(255) NOT NULL DEFAULT '',
    `orderby` INTEGER NOT NULL DEFAULT 0,
    `phone` VARCHAR(32) NOT NULL DEFAULT '',
    `aftership_code` VARCHAR(50) NULL,

    PRIMARY KEY (`carrier_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_transaction_logs` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `order_transaction_id` INTEGER NULL,
    `orderid` INTEGER NOT NULL DEFAULT 0,
    `paymentid` INTEGER NOT NULL DEFAULT 0,
    `transaction_id` VARCHAR(64) NOT NULL DEFAULT '',
    `transaction_status` VARCHAR(32) NOT NULL DEFAULT '',
    `transaction_currency` VARCHAR(3) NOT NULL DEFAULT 'USD',
    `transaction_total` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `date` INTEGER NOT NULL DEFAULT 0,
    `login` VARCHAR(50) NOT NULL DEFAULT '',
    `transaction_log` MEDIUMTEXT NOT NULL,

    INDEX `FK_xcart_transaction_logs_xcart_order_transactions`(`order_transaction_id`),
    INDEX `orderid`(`orderid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_user_filter_link` (
    `user_id` INTEGER NOT NULL,
    `filter_id` INTEGER NOT NULL,
    `position_row` INTEGER NULL,
    `position_column` INTEGER NULL,

    PRIMARY KEY (`user_id`, `filter_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_users` (
    `user_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(64) NOT NULL,
    `rate_us` TINYINT UNSIGNED NULL,
    `experience_comment` MEDIUMTEXT NULL,
    `email` VARCHAR(128) NULL,
    `password` VARCHAR(64) NULL,
    `phone` VARCHAR(32) NULL,
    `avatar_image` VARCHAR(128) NULL,
    `cart_number` INTEGER NULL,
    `location` VARCHAR(64) NULL,
    `public_name` VARCHAR(64) NULL,
    `phone_country_code` VARCHAR(2) NULL,
    `decisions_required_count` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `access_token` VARCHAR(16) NULL,
    `tsv_suppressed` INTEGER NULL DEFAULT 0,
    `stripe_customer_id` VARCHAR(32) NULL,
    `tsv_count` SMALLINT UNSIGNED NULL DEFAULT 0,
    `tsv_preferred_method` ENUM('authenticator_app', 'phone_number', 'na') NOT NULL DEFAULT 'na',
    `wrong_password_attempts` INTEGER NOT NULL DEFAULT 0,

    UNIQUE INDEX `xcart_users_email_uindex`(`email`),
    UNIQUE INDEX `xcart_users_phone_uindex`(`phone`),
    INDEX `xcart_users_xcart_user_images_id_fk`(`avatar_image`),
    PRIMARY KEY (`user_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_users_online` (
    `sessid` VARCHAR(40) NOT NULL DEFAULT '',
    `usertype` CHAR(1) NOT NULL DEFAULT '',
    `is_registered` CHAR(1) NOT NULL DEFAULT '',
    `expiry` INTEGER NOT NULL DEFAULT 0,

    INDEX `iu`(`is_registered`, `usertype`),
    INDEX `usertype`(`usertype`),
    PRIMARY KEY (`sessid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_variant_items` (
    `optionid` INTEGER NOT NULL DEFAULT 0,
    `variantid` INTEGER NOT NULL DEFAULT 0,

    INDEX `variantid`(`variantid`),
    PRIMARY KEY (`optionid`, `variantid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_variants` (
    `variantid` INTEGER NOT NULL AUTO_INCREMENT,
    `productid` INTEGER NOT NULL DEFAULT 0,
    `avail` INTEGER NOT NULL DEFAULT 0,
    `weight` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `productcode` VARCHAR(64) NOT NULL DEFAULT '',
    `def` CHAR(1) NOT NULL DEFAULT '',

    UNIQUE INDEX `productcode`(`productcode`),
    INDEX `avail`(`avail`),
    INDEX `productid`(`productid`),
    UNIQUE INDEX `opt-S`(`productid`, `productcode`, `variantid`, `avail`),
    PRIMARY KEY (`variantid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_videos` (
    `video_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NULL,
    `video` VARCHAR(255) NULL,
    `provider` VARCHAR(31) NULL,
    `image_1` VARCHAR(255) NULL,
    `image_2` VARCHAR(255) NULL,

    PRIMARY KEY (`video_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_vrs` (
    `vrs_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `site_id` INTEGER NOT NULL,
    `company` VARCHAR(255) NOT NULL DEFAULT '',
    `link_website` VARCHAR(255) NOT NULL DEFAULT '',
    `last_action` VARCHAR(255) NOT NULL DEFAULT '',
    `status` TINYINT UNSIGNED NULL,
    `date` DATE NULL,
    `email` VARCHAR(255) NOT NULL DEFAULT '',
    `telephone` VARCHAR(255) NOT NULL DEFAULT '',
    `login` VARCHAR(255) NOT NULL DEFAULT '',
    `password` VARCHAR(255) NOT NULL DEFAULT '',
    `comment` VARCHAR(255) NOT NULL DEFAULT '',
    `created_at` TIMESTAMP(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
    `user_id` INTEGER UNSIGNED NULL,

    INDEX `FK_xcart_vrs_xcart_customers`(`user_id`),
    INDEX `FK_xcart_vrs_xcart_storefronts`(`site_id`),
    PRIMARY KEY (`vrs_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_wishlist` (
    `wishlistid` INTEGER NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(32) NOT NULL DEFAULT '',
    `productid` INTEGER NOT NULL DEFAULT 0,
    `amount` INTEGER NOT NULL DEFAULT 0,
    `amount_purchased` INTEGER NOT NULL DEFAULT 0,
    `options` TEXT NOT NULL,
    `event_id` INTEGER NOT NULL DEFAULT 0,
    `object` TEXT NOT NULL,

    INDEX `event`(`event_id`),
    INDEX `login_product`(`login`, `productid`),
    PRIMARY KEY (`wishlistid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_xmlmap_extra` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `url` VARCHAR(255) NOT NULL DEFAULT '',
    `storefrontid` INTEGER NOT NULL DEFAULT 0,

    INDEX `storefrontid`(`storefrontid`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_xmlmap_lastmod` (
    `id` INTEGER NOT NULL,
    `type` CHAR(1) NOT NULL,
    `date` CHAR(15) NOT NULL,

    UNIQUE INDEX `it`(`id`, `type`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_zip` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `zip` VARCHAR(20) NOT NULL,
    `country` CHAR(2) NOT NULL,
    `city` VARCHAR(180) NOT NULL,
    `state` VARCHAR(20) NULL,
    `state_full` VARCHAR(100) NOT NULL,

    INDEX `FK_xcart_zip_xcart_states`(`country`, `state`),
    INDEX `city`(`city`),
    INDEX `zip_country`(`zip`, `country`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_zip_code_info` (
    `zip` VARCHAR(32) NOT NULL DEFAULT '',
    `type` VARCHAR(32) NOT NULL DEFAULT '',
    `primary_city` VARCHAR(255) NOT NULL DEFAULT '',
    `acceptable_cities` VARCHAR(255) NOT NULL DEFAULT '',
    `unacceptable_cities` MEDIUMTEXT NOT NULL,
    `state` VARCHAR(32) NOT NULL DEFAULT '',
    `county` VARCHAR(64) NOT NULL DEFAULT '',
    `timezone` VARCHAR(32) NOT NULL DEFAULT '',
    `area_codes` VARCHAR(32) NOT NULL DEFAULT '',
    `latitude` VARCHAR(32) NOT NULL DEFAULT '',
    `longitude` VARCHAR(32) NOT NULL DEFAULT '',
    `world_region` VARCHAR(32) NOT NULL DEFAULT '',
    `country` VARCHAR(64) NOT NULL DEFAULT '',
    `decommissioned` VARCHAR(64) NOT NULL DEFAULT '',
    `estimated_population` VARCHAR(64) NOT NULL DEFAULT '',

    INDEX `acceptable_cities`(`acceptable_cities`),
    INDEX `primary_city`(`primary_city`),
    PRIMARY KEY (`zip`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_zip_code_info_helper` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `n` INTEGER NOT NULL,

    INDEX `idx_n`(`n`),
    PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_zone_element` (
    `zoneid` INTEGER NOT NULL DEFAULT 0,
    `field` VARCHAR(36) NOT NULL DEFAULT '',
    `field_type` CHAR(1) NOT NULL DEFAULT '',

    INDEX `field`(`field_type`, `field`),
    PRIMARY KEY (`zoneid`, `field`, `field_type`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CreateTable
CREATE TABLE `xcart_zones` (
    `zoneid` INTEGER NOT NULL AUTO_INCREMENT,
    `zone_name` VARCHAR(255) NOT NULL DEFAULT '',
    `zone_cache` VARCHAR(255) NOT NULL DEFAULT '',
    `provider` VARCHAR(32) NOT NULL DEFAULT '',

    INDEX `zone_name`(`zone_name`, `provider`),
    PRIMARY KEY (`zoneid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- AddForeignKey
ALTER TABLE `account_addresses` ADD CONSTRAINT `FK_account_addresses_account_delivery_types` FOREIGN KEY (`delivery_type_id`) REFERENCES `account_delivery_types`(`delivery_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `account_addresses` ADD CONSTRAINT `FK_account_addresses_xcart_countries` FOREIGN KEY (`country`) REFERENCES `xcart_countries`(`code`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `account_addresses` ADD CONSTRAINT `FK_account_addresses_xcart_states` FOREIGN KEY (`state`) REFERENCES `xcart_states`(`stateid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `account_addresses` ADD CONSTRAINT `FK_account_addresses_xcart_users` FOREIGN KEY (`user_id`) REFERENCES `xcart_users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `account_credit_cards` ADD CONSTRAINT `FK_account_credit_cards_account_addresses` FOREIGN KEY (`address_id`) REFERENCES `account_addresses`(`address_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `account_credit_cards` ADD CONSTRAINT `FK_account_credit_cards_xcart_users` FOREIGN KEY (`user_id`) REFERENCES `xcart_users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `account_decisions` ADD CONSTRAINT `account_decision_xcart_orders_orderid_fk` FOREIGN KEY (`order_id`) REFERENCES `xcart_orders`(`orderid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `account_list_items` ADD CONSTRAINT `FK_account_list_items_account_product_lists` FOREIGN KEY (`product_list_id`) REFERENCES `account_product_lists`(`product_list_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `account_order_cancel_requests` ADD CONSTRAINT `FK_account_order_cancel_requests_xcart_orders` FOREIGN KEY (`order_id`) REFERENCES `xcart_orders`(`orderid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `account_order_problems` ADD CONSTRAINT `FK_account_order_problems_account_order_problems_statuses` FOREIGN KEY (`status_id`) REFERENCES `account_order_problems_statuses`(`status_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

-- AddForeignKey
ALTER TABLE `account_transactions` ADD CONSTRAINT `FK_account_transactions_account_credit_cards` FOREIGN KEY (`credit_card_id`) REFERENCES `account_credit_cards`(`credit_card_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `account_transactions` ADD CONSTRAINT `FK_account_transactions_xcart_orders` FOREIGN KEY (`order_id`) REFERENCES `xcart_orders`(`orderid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `account_transactions` ADD CONSTRAINT `FK_account_transactions_xcart_users` FOREIGN KEY (`user_id`) REFERENCES `xcart_users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `account_user_list` ADD CONSTRAINT `FK_account_user_list_account_product_lists` FOREIGN KEY (`product_list_id`) REFERENCES `account_product_lists`(`product_list_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `account_user_list` ADD CONSTRAINT `FK_account_user_list_xcart_users` FOREIGN KEY (`user_id`) REFERENCES `xcart_users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `amazon_inventory_queue` ADD CONSTRAINT `FK_amazon_inventory_queue_xcart_products` FOREIGN KEY (`product_id`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `amazon_offers_competitors` ADD CONSTRAINT `FK_amazon_offers_competitors_amazon_offers` FOREIGN KEY (`offer_id`) REFERENCES `amazon_offers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `amazon_offers_stock_by_date` ADD CONSTRAINT `FK_amazon_offers_stock_by_date_amazon_offers` FOREIGN KEY (`offer_id`) REFERENCES `amazon_offers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `amazon_price_queue` ADD CONSTRAINT `FK_amazon_price_queue_xcart_products` FOREIGN KEY (`product_id`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `amazon_reorder_batch_data` ADD CONSTRAINT `FK_amazon_reorder_batch_data_amazon_reorder_batch` FOREIGN KEY (`batch_id`) REFERENCES `amazon_reorder_batch`(`batch_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `amazon_reorder_batch_data` ADD CONSTRAINT `FK_amazon_reorder_batch_data_xcart_manufacturers` FOREIGN KEY (`manufacturerid`) REFERENCES `xcart_manufacturers`(`manufacturerid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `amazon_reorder_batch_data` ADD CONSTRAINT `FK_amazon_reorder_batch_data_xcart_products` FOREIGN KEY (`productid`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `anveo_calls` ADD CONSTRAINT `FK_anveo_calls_xcart_pbx_options` FOREIGN KEY (`anveo_account`) REFERENCES `xcart_pbx_options`(`anveo_account`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `anveo_calls_listens` ADD CONSTRAINT `FK_anveo_calls_listens_anveo_calls` FOREIGN KEY (`call_id`) REFERENCES `anveo_calls`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `collaborative_fitering` ADD CONSTRAINT `FK_collaborative_fitering_xcart_products` FOREIGN KEY (`product_id`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `collaborative_fitering` ADD CONSTRAINT `FK_collaborative_fitering_xcart_sessions_data` FOREIGN KEY (`session_id`) REFERENCES `xcart_sessions_data`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `core_static_notification` ADD CONSTRAINT `FK_core_static_notification_xcart_storefronts` FOREIGN KEY (`storefront_id`) REFERENCES `xcart_storefronts`(`storefrontid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `decision_licenses` ADD CONSTRAINT `decision_licenses_account_decisions_decision_id_fk` FOREIGN KEY (`decision_id`) REFERENCES `account_decisions`(`decision_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `decisions_customer_files` ADD CONSTRAINT `decisions_files_account_decisions_decision_id_fk` FOREIGN KEY (`decision_id`) REFERENCES `account_decisions`(`decision_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `decisions_customer_files` ADD CONSTRAINT `decisions_customer_files_customer_files_file_id_fk` FOREIGN KEY (`file_id`) REFERENCES `customer_files`(`file_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `faxes_fax` ADD CONSTRAINT `FK_faxes_fax_xcart_orders` FOREIGN KEY (`order_id`) REFERENCES `xcart_orders`(`orderid`) ON DELETE NO ACTION ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `forms_email_action` ADD CONSTRAINT `FK_forms_email_action_forms_email` FOREIGN KEY (`email_id`) REFERENCES `forms_email`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `forms_email_action` ADD CONSTRAINT `FK_forms_email_action_xcart_customers` FOREIGN KEY (`user_id`) REFERENCES `xcart_customers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `forms_email_attachment` ADD CONSTRAINT `FK_forms_email_attachment_forms_email` FOREIGN KEY (`email_id`) REFERENCES `forms_email`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `forms_email_body` ADD CONSTRAINT `FK_forms_email_body_forms_email` FOREIGN KEY (`email_id`) REFERENCES `forms_email`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `forms_email_favorite` ADD CONSTRAINT `forms_email_favorite_ibfk_1` FOREIGN KEY (`email_id`) REFERENCES `forms_email`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `forms_email_favorite` ADD CONSTRAINT `forms_email_favorite_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `xcart_customers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `forms_email_label` ADD CONSTRAINT `FK_forms_email_label_forms_email` FOREIGN KEY (`email_id`) REFERENCES `forms_email`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `forms_email_label` ADD CONSTRAINT `FK_forms_email_label_forms_label` FOREIGN KEY (`label_id`) REFERENCES `forms_label`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `forms_email_user` ADD CONSTRAINT `FK_forms_email_user_forms_email` FOREIGN KEY (`email_id`) REFERENCES `forms_email`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `forms_email_user` ADD CONSTRAINT `FK_forms_email_user_xcart_customers` FOREIGN KEY (`user_id`) REFERENCES `xcart_customers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `google_products` ADD CONSTRAINT `FK_google_products_xcart_products` FOREIGN KEY (`product_id`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `helpful_reviews` ADD CONSTRAINT `helpful_reviews_product_reviews_product_review_id_fk` FOREIGN KEY (`review_id`) REFERENCES `product_reviews`(`product_review_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `helpful_reviews` ADD CONSTRAINT `helpful_reviews_xcart_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `xcart_users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `product_reviews` ADD CONSTRAINT `product_reviews_xcart_products_productid_fk` FOREIGN KEY (`product_id`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `product_reviews` ADD CONSTRAINT `product_reviews_xcart_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `xcart_users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `review_ratings` ADD CONSTRAINT `review_ratings_product_reviews_product_review_id_fk` FOREIGN KEY (`review_id`) REFERENCES `product_reviews`(`product_review_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `review_ratings` ADD CONSTRAINT `review_ratings_ratings_rating_id_fk` FOREIGN KEY (`rating_id`) REFERENCES `ratings`(`rating_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `sites_bank_account` ADD CONSTRAINT `FK_sites_bank_account_sites_corporate` FOREIGN KEY (`corporate_id`) REFERENCES `sites_corporate`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `sites_payment_methods` ADD CONSTRAINT `FK_sites_payment_methods_payment_methods` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods`(`payment_method_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `sites_payment_methods` ADD CONSTRAINT `FK_sites_payment_methods_xcart_storefronts` FOREIGN KEY (`site_id`) REFERENCES `xcart_storefronts`(`storefrontid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `sites_share_holder` ADD CONSTRAINT `FK_sites_shareholder_sites_corporate` FOREIGN KEY (`corporate_id`) REFERENCES `sites_corporate`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `sites_tax_return` ADD CONSTRAINT `FK__sites_corporate` FOREIGN KEY (`corporate_id`) REFERENCES `sites_corporate`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `tmp_og` ADD CONSTRAINT `FK_tmp_og_tmp_og_status` FOREIGN KEY (`cb_status`) REFERENCES `tmp_og_status`(`code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `total_product_ratings` ADD CONSTRAINT `total_product_ratings_review_ratings_rating_id_fk` FOREIGN KEY (`rating_id`) REFERENCES `review_ratings`(`rating_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `total_product_ratings` ADD CONSTRAINT `total_product_ratings_xcart_products_productid_fk` FOREIGN KEY (`product_id`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_amazon_fulfillment_locations` ADD CONSTRAINT `FK_xcart_amazon_fulfillment_locations_xcart_countries` FOREIGN KEY (`country`) REFERENCES `xcart_countries`(`code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_amazon_fulfillment_locations` ADD CONSTRAINT `FK_xcart_amazon_fulfillment_locations_xcart_states` FOREIGN KEY (`state`) REFERENCES `xcart_states`(`stateid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_amazon_fulfillment_locations` ADD CONSTRAINT `FK_xcart_amazon_fulfillment_locations_xcart_zip` FOREIGN KEY (`zipcode`) REFERENCES `xcart_zip`(`zip`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_amazon_fulfillment_locations` ADD CONSTRAINT `FK_xcart_amazon_fulfillment_locations_xcart_zip_country` FOREIGN KEY (`zipcode`, `country`) REFERENCES `xcart_zip`(`zip`, `country`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_amazon_list_inbound_shipment_items` ADD CONSTRAINT `FK_xcart_amazon_list_inbound_items_list_inbound_shipments` FOREIGN KEY (`shipment_id`) REFERENCES `xcart_amazon_list_inbound_shipments`(`shipment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_amazon_list_inbound_shipments` ADD CONSTRAINT `FK_xcart_amazon_list_inbound_shipments_xcart_locations` FOREIGN KEY (`destination_fulfillment_center_id`) REFERENCES `xcart_amazon_fulfillment_locations`(`code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_amazon_list_inbound_shipments` ADD CONSTRAINT `FK_xcart_amazon_list_inbound_shipments_xcart_orders` FOREIGN KEY (`order_id`) REFERENCES `xcart_orders`(`orderid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_authenticators` ADD CONSTRAINT `xcart_authenticators_xcart_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `xcart_users`(`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_brands` ADD CONSTRAINT `FK_xcart_brands_xcart_brands` FOREIGN KEY (`parent_brand_id`) REFERENCES `xcart_brands`(`brandid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_cidev_gmc_quality_issues` ADD CONSTRAINT `xcart_cidev_gmc_quality_issues_ibfk_1` FOREIGN KEY (`issue_id`) REFERENCES `xcart_cidev_issues_processing_rules`(`issue_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_cidev_surf_meta` ADD CONSTRAINT `FK_surf_meta__customers__id` FOREIGN KEY (`user_id`) REFERENCES `xcart_customers`(`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_cidev_surf_meta` ADD CONSTRAINT `FK_xcart_cidev_surf_meta_xcart_sessions_data` FOREIGN KEY (`sessid`) REFERENCES `xcart_sessions_data`(`sessid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_cidev_surf_path` ADD CONSTRAINT `FK_xcart_cidev_surf_path_xcart_cidev_surf_meta` FOREIGN KEY (`meta_id`) REFERENCES `xcart_cidev_surf_meta`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_customers` ADD CONSTRAINT `FK_xcart_customers_xcart_customers` FOREIGN KEY (`parent_user_id`) REFERENCES `xcart_customers`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_dashboard_filters_statistic` ADD CONSTRAINT `FK_xcart_dashboard_filter_statistic_xcart_dashboard_filters` FOREIGN KEY (`filter_id`) REFERENCES `xcart_dashboard_filters`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_distributor_contact_utility` ADD CONSTRAINT `FK_xcart_distributor_contact_utility_xcart_distributor_contacts` FOREIGN KEY (`contact_id`) REFERENCES `xcart_distributor_contacts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_distributor_contact_utility` ADD CONSTRAINT `FK_xcart_distributor_contact_utility_xcart_distributor_utility` FOREIGN KEY (`utility_id`) REFERENCES `xcart_distributor_utility`(`utility_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_distributor_contacts` ADD CONSTRAINT `FK_xcart_distributor_contacts_xcart_manufacturers` FOREIGN KEY (`manufacturerid`) REFERENCES `xcart_manufacturers`(`manufacturerid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_distributor_tabs` ADD CONSTRAINT `FK_xcart_distributor_tabs_xcart_manufacturers` FOREIGN KEY (`distributor_id`) REFERENCES `xcart_manufacturers`(`manufacturerid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_distributor_taxes` ADD CONSTRAINT `FK_xcart_distributor_taxes_xcart_manufacturers` FOREIGN KEY (`distributor_id`) REFERENCES `xcart_manufacturers`(`manufacturerid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_distributor_taxes` ADD CONSTRAINT `FK_xcart_distributor_taxes_xcart_taxes` FOREIGN KEY (`tax_id`) REFERENCES `xcart_taxes`(`taxid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_external_verification_products_queue` ADD CONSTRAINT `FK_xcart_external_verification_feeds` FOREIGN KEY (`feed_id`) REFERENCES `xcart_external_verification_feeds`(`feed_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_external_verification_products_queue` ADD CONSTRAINT `xcart_external_verification_products_queue_ibfk_1` FOREIGN KEY (`productid`) REFERENCES `xcart_products`(`productid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_fingerprints` ADD CONSTRAINT `xcart_fingerprints_xcart_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `xcart_users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_help_menu_content` ADD CONSTRAINT `FK_xcart_help_menu_content_xcart_help_menu_items` FOREIGN KEY (`menu_id`) REFERENCES `xcart_help_menu_items`(`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_images_D` ADD CONSTRAINT `FK_xcart_images_D_xcart_products` FOREIGN KEY (`id`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_manufacturers` ADD CONSTRAINT `FK_xcart_manufacturers_xcart_currencies` FOREIGN KEY (`d_currency`) REFERENCES `xcart_currencies`(`currency_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_manufacturers` ADD CONSTRAINT `FK_xcart_manufacturers_xcart_templates_for_communication_2` FOREIGN KEY (`order_entry_template_id`) REFERENCES `xcart_templates_for_communication`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_manufacturers` ADD CONSTRAINT `FK_xcart_manufacturers_xcart_templates_for_communication_3` FOREIGN KEY (`order_submit_template_id`) REFERENCES `xcart_templates_for_communication`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_manufacturers` ADD CONSTRAINT `FK_xcart_manufacturers_xcart_templates_for_communication` FOREIGN KEY (`request_avail_template_id`) REFERENCES `xcart_templates_for_communication`(`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_manufacturers_carrier` ADD CONSTRAINT `FK_xcart_manufacturers_carrier_xcart_manufacturers` FOREIGN KEY (`manufacturer_id`) REFERENCES `xcart_manufacturers`(`manufacturerid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_manufacturers_carrier` ADD CONSTRAINT `FK_xcart_manufacturers_carrier_xcart_tracking_links_carrier` FOREIGN KEY (`carrier_id`) REFERENCES `xcart_tracking_links_carrier`(`carrier_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_manufacturers_site` ADD CONSTRAINT `FK_xcart_manufacturers_site_xcart_manufacturers` FOREIGN KEY (`manufacturer_id`) REFERENCES `xcart_manufacturers`(`manufacturerid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_manufacturers_site` ADD CONSTRAINT `FK_xcart_manufacturers_site_xcart_storefronts` FOREIGN KEY (`site_id`) REFERENCES `xcart_storefronts`(`storefrontid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_one_time_passwords` ADD CONSTRAINT `xcart_one_time_passwords_xcart_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `xcart_users`(`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_option_variants` ADD CONSTRAINT `FK_xcart_option_variants_xcart_options` FOREIGN KEY (`option_id`) REFERENCES `xcart_options`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_order_details` ADD CONSTRAINT `FK_xcart_order_details_xcart_order_groups` FOREIGN KEY (`order_group_id`) REFERENCES `xcart_order_groups`(`order_group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_order_details` ADD CONSTRAINT `FK_xcart_order_details_xcart_orders` FOREIGN KEY (`orderid`) REFERENCES `xcart_orders`(`orderid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_order_groups` ADD CONSTRAINT `FK3_order_groups_bd_status` FOREIGN KEY (`bd_status`) REFERENCES `xcart_order_statuses`(`code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_order_groups` ADD CONSTRAINT `FK1_order_groups_cb_status` FOREIGN KEY (`cb_status`) REFERENCES `xcart_order_statuses`(`code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_order_groups` ADD CONSTRAINT `FK2_order_groups_dc_status` FOREIGN KEY (`dc_status`) REFERENCES `xcart_order_statuses`(`code`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_order_groups` ADD CONSTRAINT `FK_xcart_order_groups_xcart_orders` FOREIGN KEY (`orderid`) REFERENCES `xcart_orders`(`orderid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_order_status_availability` ADD CONSTRAINT `FK_xcart_order_status_availability_xcart_order_statuses` FOREIGN KEY (`destination_status_id`) REFERENCES `xcart_order_statuses`(`status_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_order_status_availability` ADD CONSTRAINT `FK_xcart_order_status_availability_xcart_order_statuses_3` FOREIGN KEY (`source_status_id`) REFERENCES `xcart_order_statuses`(`status_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_order_statuses_history` ADD CONSTRAINT `adshf9h9h2934f` FOREIGN KEY (`group_id`) REFERENCES `xcart_order_groups`(`order_group_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_order_tracking` ADD CONSTRAINT `FK_xcart_order_tracking_xcart_order_groups` FOREIGN KEY (`order_group_id`) REFERENCES `xcart_order_groups`(`order_group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_order_tracking` ADD CONSTRAINT `FK_xcart_order_tracking_xcart_tracking_links` FOREIGN KEY (`linkid`) REFERENCES `xcart_tracking_links`(`linkid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_order_tracking` ADD CONSTRAINT `FK_xcart_order_tracking_xcart_tracking_links_carrier` FOREIGN KEY (`carrier_id`) REFERENCES `xcart_tracking_links_carrier`(`carrier_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_order_transactions` ADD CONSTRAINT `xcart_order_transactions_xcart_orders_orderid_fk` FOREIGN KEY (`orderid`) REFERENCES `xcart_orders`(`orderid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_orders` ADD CONSTRAINT `FK_xcart_orders_xcart_storefronts` FOREIGN KEY (`storefrontid`) REFERENCES `xcart_storefronts`(`storefrontid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_orders` ADD CONSTRAINT `xcart_orders_xcart_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `xcart_users`(`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_pc_category_terms` ADD CONSTRAINT `FK_xcart_pc_category_terms_xcart_pc_terms` FOREIGN KEY (`termid`) REFERENCES `xcart_pc_terms`(`termid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_po_pipeline` ADD CONSTRAINT `xcart_po_pipeline_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `xcart_orders`(`orderid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_pricing` ADD CONSTRAINT `FK_xcart_pricing_xcart_products` FOREIGN KEY (`productid`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_product_option_variants` ADD CONSTRAINT `FK_xcart_product_option_variants_xcart_option_variants` FOREIGN KEY (`variant_id`) REFERENCES `xcart_option_variants`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_product_option_variants` ADD CONSTRAINT `FK_xcart_product_option_variants_xcart_product_options` FOREIGN KEY (`product_option_id`) REFERENCES `xcart_product_options`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_product_options` ADD CONSTRAINT `FK_xcart_product_options_xcart_options` FOREIGN KEY (`option_id`) REFERENCES `xcart_options`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_product_options` ADD CONSTRAINT `FK_xcart_product_options_xcart_products` FOREIGN KEY (`product_id`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_product_question` ADD CONSTRAINT `FK_xcart_product_question_xcart_products` FOREIGN KEY (`productid`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_product_verification_history` ADD CONSTRAINT `fk_xcart_verification_history_xcart_product_verification_stat1` FOREIGN KEY (`newstatusid`) REFERENCES `xcart_product_verification_statuses`(`statusid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_product_verification_history` ADD CONSTRAINT `fk_xcart_verification_history_xcart_product_verification_stat` FOREIGN KEY (`oldstatusid`) REFERENCES `xcart_product_verification_statuses`(`statusid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_product_verification_history` ADD CONSTRAINT `fk_xcart_verification_history_xcart_products_stat2` FOREIGN KEY (`productid`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_products` ADD CONSTRAINT `FK_xcart_products_xcart_brands` FOREIGN KEY (`brandid`) REFERENCES `xcart_brands`(`brandid`) ON DELETE SET NULL ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_products` ADD CONSTRAINT `FK_xcart_products_xcart_manufacturers` FOREIGN KEY (`manufacturerid`) REFERENCES `xcart_manufacturers`(`manufacturerid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_products_categories` ADD CONSTRAINT `FK_xcart_products_categories_xcart_categories` FOREIGN KEY (`categoryid`) REFERENCES `xcart_categories`(`categoryid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_products_categories` ADD CONSTRAINT `FK_xcart_products_categories_xcart_products` FOREIGN KEY (`productid`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_products_disabled_marketplaces` ADD CONSTRAINT `fk_xcart_products_disabled_marketplaces_xcart_products_extern1` FOREIGN KEY (`marketplace_id`) REFERENCES `xcart_products_external_marketplaces`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_products_hard_resell` ADD CONSTRAINT `FK_xcart_products_hard_resell_xcart_products` FOREIGN KEY (`product_id`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_products_sf` ADD CONSTRAINT `FK_xcart_products_sf_xcart_products` FOREIGN KEY (`productid`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_products_sf` ADD CONSTRAINT `FK_xcart_products_sf_xcart_storefronts` FOREIGN KEY (`sfid`) REFERENCES `xcart_storefronts`(`storefrontid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_products_showed` ADD CONSTRAINT `product` FOREIGN KEY (`productid`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_products_videos` ADD CONSTRAINT `products_videos_xcart_products_productid_fk` FOREIGN KEY (`product_id`) REFERENCES `xcart_products`(`productid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_products_videos` ADD CONSTRAINT `xcart_products_videos_xcart_videos_video_id_fk` FOREIGN KEY (`video_id`) REFERENCES `xcart_videos`(`video_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_reconciliation_manufacturers` ADD CONSTRAINT `FK_xcart_reconciliation_manufacturers_xcart_manufacturers` FOREIGN KEY (`manufacturer_id`) REFERENCES `xcart_manufacturers`(`manufacturerid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_reconciliation_manufacturers` ADD CONSTRAINT `FK_xcart_reconciliation_manufacturers_xcart_reconciliations` FOREIGN KEY (`reconciliation_id`) REFERENCES `xcart_reconciliations`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_reviews_images` ADD CONSTRAINT `xcart_reviews_images_xcart_images_image_id_fk` FOREIGN KEY (`image_id`) REFERENCES `xcart_images`(`image_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_reviews_images` ADD CONSTRAINT `xcart_reviews_images_xcart_product_reviews_review_id_fk` FOREIGN KEY (`review_id`) REFERENCES `xcart_product_reviews`(`review_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_reviews_videos` ADD CONSTRAINT `xcart_reviews_videos_xcart_product_reviews_review_id_fk` FOREIGN KEY (`review_id`) REFERENCES `xcart_product_reviews`(`review_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_reviews_videos` ADD CONSTRAINT `xcart_reviews_videos_xcart_videos_video_id_fk` FOREIGN KEY (`video_id`) REFERENCES `xcart_videos`(`video_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_secure_data_users` ADD CONSTRAINT `xcart_secure_data_users_ibfk_1` FOREIGN KEY (`secure_data_id`) REFERENCES `xcart_secure_data`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_sessions_data` ADD CONSTRAINT `xcart_sessions_data_xcart_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `xcart_users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_shipping_cache_products` ADD CONSTRAINT `FK_xcart_shipping_cache_products_xcart_shipping_cache` FOREIGN KEY (`shipping_cache_id`) REFERENCES `xcart_shipping_cache`(`shipping_cache_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_shipping_cache_quotes` ADD CONSTRAINT `FK_xcart_shipping_cache_quotes_xcart_shipping_cache` FOREIGN KEY (`shipping_cache_id`) REFERENCES `xcart_shipping_cache`(`shipping_cache_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_shipping_cache_quotes` ADD CONSTRAINT `FK_xcart_shipping_cache_quotes_xcart_shipping_rates` FOREIGN KEY (`rate_id`) REFERENCES `xcart_shipping_rates`(`rateid`) ON DELETE CASCADE ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_storefronts` ADD CONSTRAINT `FK_xcart_storefronts_xcart_currencies` FOREIGN KEY (`currency_id`) REFERENCES `xcart_currencies`(`currency_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_storefronts_external_marketplaces` ADD CONSTRAINT `fk_xcart_storefronts_external_marketplaces_xcart_products_ext` FOREIGN KEY (`marketplace_id`) REFERENCES `xcart_products_external_marketplaces`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_supplier_feeds` ADD CONSTRAINT `FK_xcart_supplier_feeds_xcart_categories` FOREIGN KEY (`storefront_id`, `base_category_id`) REFERENCES `xcart_categories`(`storefrontid`, `categoryid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_templates_for_communication` ADD CONSTRAINT `FK_xcart_templates_for_communication_xcart_templates_categories` FOREIGN KEY (`category_id`) REFERENCES `xcart_templates_categories`(`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_transaction_logs` ADD CONSTRAINT `FK_xcart_transaction_logs_xcart_order_transactions` FOREIGN KEY (`order_transaction_id`) REFERENCES `xcart_order_transactions`(`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_vrs` ADD CONSTRAINT `FK_xcart_vrs_xcart_customers` FOREIGN KEY (`user_id`) REFERENCES `xcart_customers`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- AddForeignKey
ALTER TABLE `xcart_vrs` ADD CONSTRAINT `FK_xcart_vrs_xcart_storefronts` FOREIGN KEY (`site_id`) REFERENCES `xcart_storefronts`(`storefrontid`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AddForeignKey
ALTER TABLE `xcart_zip` ADD CONSTRAINT `FK_xcart_zip_xcart_states` FOREIGN KEY (`country`, `state`) REFERENCES `xcart_states`(`country_code`, `code`) ON DELETE CASCADE ON UPDATE CASCADE;
