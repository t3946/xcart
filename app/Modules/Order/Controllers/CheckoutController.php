<?php

namespace Modules\Order\Controllers;

use Exception;
use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QAndNot;
use Modules\Cart\Components\CartItem;
use Modules\Cart\Helpers\StagesOfOrdering;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Core\Models\ZipCodeModel;
use Modules\Goods\Models\ProductModel;
use Modules\Order\Forms\BillingForm;
use Modules\Order\Forms\CheckoutForm;
use Modules\Order\Forms\CheckoutReviewForm;
use Modules\Order\Forms\CustomerNotesForm;
use Modules\Order\Forms\ShippingForm;
use Modules\Order\Helpers\CheckoutHelper;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\PurchaseOrderHelper;
use Modules\Order\Models\LogModel;
use Modules\Order\Models\OrderDetailModel;
use Modules\Order\Models\OrderExtraModel;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderGroupTaxModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\PurchaseOrderModel;
use Modules\Order\OrderModule;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Shipping\Models\ShippingRateModel;
use Modules\Shipping\ShippingModule;
use Modules\Sites\Helpers\TaxHelper;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Application\Application;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Form\PrepareData;
use Xcart\App\Main\Xcart;

class CheckoutController extends FrontendController
{
    public function actionCheckoutOnePage (): void {
        StagesOfOrdering::getInstance()->setStage(StagesOfOrdering::STAGE_SHIPPING_ADDRESS);
        /** @var OrderModel $order */
        /** @var SiteModel $site */
        /** @var Application $app */
        $app = Xcart::app();
        $site = $app->getModule('Sites')->getSite();
        $cart = $app->cart;

        if (!$cart->getCartNumber() || $cart->getIsEmpty()) {
            $this->redirect('cart:list');
        }

        $shipping = null;
        $checkout_form = new CheckoutForm();

        if ($site && $app->request->getIsPost()) {
            $checkout_form->populate($app->request->post);
            if (true || $checkout_form->isValid()) {

                $checkout_form->getInstance()->save();

                [$order] = OrderModel::objects()->getOrNew(['cart_number' => $cart->getCartNumber(),]);

                $order->setAttributes(array_merge($checkout_form->getAttributes(), [
                    'cb_status' => OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP4,
                    'currency' => $site->getCurrency()->currency_code ?? 'USD',
                    'date' => time()
                ]));

                if ($order->save()) {
                    $this->redirect('checkout:payment');
                }
            } else {
                $this->redirect('checkout:checkoutOnePage');
            }
        }

        $order = OrderHelper::getCartOrder();

        if (!$order) {
            [$order] = OrderModel::objects()->getOrCreate([
                'cart_number' => $cart->getCartNumber(),
                'paymentid' => PaymentMethodModel::PHONE_ORDER_PAYMENT_METHOD_ID,
            ]);
        }

        if ($order && !$app->request->getIsPost()) {
            $checkout_form->setAttributes(array_merge(
                $order->getAttributes(),
                $order->extra_model->purchase_order ?? [])
            );
        }

        $shipping_rates = OrderProcessController::getShippingRates( $order );

        CheckoutHelper::updateOrderShippingRates($order, $shipping_rates);

        CheckoutHelper::updateOrderGroupsFromCart($order, $cart);

        $order->save();

        $only_phone_order = count($shipping_rates) < $order->groups->count();

        $site = Xcart::app()->getModule('Sites')->getSite();

        $filter = ['active' => 'Y', 'site__through__storefrontid' => $site->storefrontid];

        if ($only_phone_order) {
            $filter['paymentid'] = PaymentMethodModel::PHONE_ORDER_PAYMENT_METHOD_ID;
        }

        $payment_methods = PaymentMethodModel::objects()
            ->filter($filter)
            ->order(['is_cod', 'orderby'])
            ->all();

        $this->display('checkout/shipping_one_page.tpl', [
            'order' => $order,
            'checkout_form' => $checkout_form,
            'shipping_rates' => $shipping_rates,
            'payment_methods' => $payment_methods
        ]);
    }

    public function beforeAction($action, $params): void
    {
        if ($action !== 'actionComplete' && !Xcart::app()->request->getIsAjax() && !Xcart::app()->cart->isValid()) {
            $this->redirect('cart:list');
        }
    }

    protected function getOrder(): OrderModel
    {
        if (!$order = OrderHelper::getCartOrder()) {
            $this->redirect('checkout:shipping');
        }
        return $order;
    }

    /**
     * Step 1
     *
     * @throws Exception
     */
    public function actionShipping(): void
    {
        StagesOfOrdering::getInstance()->setStage(StagesOfOrdering::STAGE_SHIPPING_ADDRESS);
        /** @var OrderModel $order */
        /** @var SiteModel $site */
        /** @var Application $app */
        $app = Xcart::app();
        $site = $app->getModule('Sites')->getSite();
        $cart = $app->cart;
        $shipping = null;
        $shippingForm = new ShippingForm();

        if ($site && $app->request->getIsPost()) {
            $shippingForm->populate($app->request->post);

            if ($shippingForm->isValid()) {

                [$order, $is_created] = OrderModel::objects()->getOrNew(['cart_number' => $cart->getCartNumber(),]);

                $order->subtotal = 0;

                $order->setAttributes(array_merge($shippingForm->getAttributes(), [
                    'cb_status' => OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP2,
                    'dc_status' => OrderStatusModel::ORDER_DC_STATUS_NOT_SHIPPED,
                    'bd_status' => OrderStatusModel::ORDER_BD_STATUS_UNPAID,
                    'currency'  => $site->getCurrency()->currency_code ?? 'USD'
                ]));

                if ($order->save()) {

                    if ($cart_groups = $cart->getItemsGroupedBy()) {
                        $order->groups->exclude(['manufacturerid__in' => array_keys($cart_groups)])->delete();

                        foreach ($cart_groups as $g => $cart_group)
                        {
                            /** @var OrderGroupModel $group */
                            [$group] = OrderGroupModel::objects()->getOrNew(['manufacturerid' => $g, 'orderid' => $order->orderid]);

                            $group->setAttributes([
                                'shippingid' => null,
                                'shipping' => '',
                                'cb_status' => $order->cb_status,
                                'dc_status' => $order->dc_status,
                                'bd_status' => $order->bd_status,
                                'total_gross' => $cart_group['subtotal'],
                                'total_net' => $cart_group['subtotal'],
                                'distributor_price_multiplier' => $group->manufacturer->supplier_products_price_multiplier,
                            ]);
                            $order->subtotal += $group->total_gross;
                            $order->total = $order->subtotal;
                            $order->shipping_cost = 0;

                            $group->save();

                            OrderDetailModel::objects()->delete(['order_group_id' => $group->order_group_id]);

                            /** @var CartItem $item */
                            foreach ($cart_group['items'] as $item)
                            {
                                /** @var ProductModel $product */
                                $product = $item->getObject();
                                $detail = new OrderDetailModel([
                                    'orderid' => $group->orderid,
                                    'productid' => $product->productid,
                                    'order_group_id' => $group->order_group_id,
                                    'price' => $product->getFrontendPrice($item->getQuantity()),
                                    'amount' => $item->getQuantity(),
                                    'productcode' => $product->productcode,
                                    'product' => $product->getFrontendName(),
                                    'provider' => $product->provider,
                                    'original_provider' => $product->original_provider,
                                    'item_cost_to_us' => $product->cost_to_us,
                                    'product_options' => $item->data ?? null,
                                ]);
                                $detail->save();
                            }
                        }

                    } else {
                        $order->groups->delete();
                    }

                    $order->save();

                    if ($is_created) {
                        $app->event->trigger('order:created', ['model' => $order]);
                    }

                    $this->redirect('checkout:options');
                }
            }
        }

        $order = $order ?? OrderModel::objects()->get(['cart_number' => $cart->getCartNumber(), ]);

        if ($order && !$app->request->getIsPost()) {
            $shippingForm->setAttributes($order->getAttributes());
        }

        if (!$cart->getCartNumber() || $cart->getIsEmpty()) {
            $this->redirect('cart:list');
        }

        $this->display('checkout/shipping.tpl', [
            'order' => $order,
            'shippingForm' => $shippingForm,
        ]);
    }

    /**
     * auto complete country code
     */
    public function actionAutoCompleteCountry(): void
    {
        if ($search = Xcart::app()->request->get->get('search')) {

            $filter = ['name__contains' => $search];

            if (array_key_exists(strtoupper($search), CountryModel::$codes)) {
                $filter = ['code' => CountryModel::$codes[strtoupper($search)]];
            }

            $countries = CountryModel::objects()->filter($filter)->limit(10)->order([new Expression("FIELD(code, 'US', 'CA') DESC, code")])->valuesList(['name', 'code'], false);
        }

        $this->jsonResponse($countries ?? []);
    }

    /**
     * auto complete zip code
     */
    public function actionAutoCompleteZipCode(): void
    {

        if ($country = Xcart::app()->request->get->get('country')) {
            $filter['country'] = $country;
        }

        if ($search = Xcart::app()->request->get->get('search')) {
            $zips = ZipCodeModel::objects()
                ->filter(array_merge(['zip__startswith' => $search],$filter ?? []))
                ->limit(10)
                ->valuesList(['zip', 'primary_city' => 'city', 'state', 'state_name' => 'state_model__state'], false);
        }

        $this->jsonResponse($zips ?? []);
    }

    /**
     * auto complete state action
     */
    public function actionAutoCompleteState(): void
    {
        if ($country = Xcart::app()->request->get->get('country')) {
            $filter['country_code'] = $country;
        }

        if ($search = Xcart::app()->request->get->get('search')) {
            $states = StateModel::objects()
                ->filter(array_merge(['state__contains' => $search],$filter ?? []))
                ->limit(10)
                ->order([new Expression("(CASE WHEN state LIKE '{$search}%' THEN 1 ELSE 2 END)"), 'state'])
                ->valuesList(['state', 'code'], false);
        }

        $this->jsonResponse($states ?? []);
    }

    /**
     * auto complete city action
     */
    public function actionAutoCompleteCity(): void
    {
        if ($country = Xcart::app()->request->get->get('country')) {
            $filter['country'] = $country;
        }
        if ($state = Xcart::app()->request->get->get('state')) {
            $filter['state'] = $state;
        }

        if ($search = Xcart::app()->request->get->get('search')) {
            $city = ZipCodeModel::objects()
                ->filter(array_merge(['city__contains' => $search],$filter ?? []))
                ->limit(10)
                ->order([new Expression("(CASE WHEN city LIKE '{$search}%' THEN 1 ELSE 2 END)"), 'city'])
                ->group(['city'])
                ->valuesList(['primary_city' => 'city'], true);
        }
        $this->jsonResponse($city ?? []);
    }

    /**
     * Step 2
     *
     * @throws \Xcart\App\Exceptions\UnknownMethodException
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public function actionOptions(): void
    {
        StagesOfOrdering::getInstance()->setStage(StagesOfOrdering::STAGE_SHIPPING_PAYMENT_OPTIONS);
        $errors = [];
        /** @var ShippingModule $ship_module */
        /** @var Application $app */
        $app = Xcart::app();
        $site = $app->getModule('Sites')->getSite();
        $ship_module = $app->getModule('Shipping');
        $cart = $app->cart;

        $billingForm = new BillingForm();

        $order = $this->getOrder();

        $this->checkoutStepsValidate($order->cb_status, OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP2);

        if ($cart_groups = $cart->getItemsGroupedBy()) {
            $rates = $app->request->post->get('shipping_rates');

            $order->subtotal = $order->shipping_cost = $order->tax = 0;

            $order->groups->delete([new QAndNot(['manufacturerid__in' => array_keys($cart_groups)])]);

            foreach ($cart_groups as $g => $cart_group) {
                $charge = 0;

                /** @var OrderGroupModel $group */
                if ($group = $order->groups->get(['manufacturerid' => $g])) {

                    /** @var ShippingRateModel[] $shipping_rates */
                    if ($shipping_rates = $ship_module::getShipping($g, $order, $cart_group)) {

                        $sh_rates[$g] = $shipping_rates;

                        /** @var ShippingRateModel $rate */
                        $rate = reset($shipping_rates);

                        if ($group->shippingid && $rate = array_filter($shipping_rates, static fn($a) => (int)$a->shippingid === (int)$group->shippingid)) {
                            $rate = reset($rate);
                        }

                        if ($rates[$g]) {
                            $rate = ShippingRateModel::objects()->get(['rateid' => $rates[$g]]);
                        }

                        $group->setAttributes(['shippingid' => null, 'shipping' => '']);

                        if ($sh_rate = $shipping_rates[$rate->rateid]) {
                            $charge = $sh_rate->getShippingCharge();
                            $group->setAttributes([
                                'shippingid' => $sh_rate->shippingid,
                                'shipping' => $sh_rate->shipping->getFrontendName(),
                                'shipping_quote' => $sh_rate->getShippingQuote()
                            ]);
                        }

                        $group->setAttributes([
                            'shipping_gross' => $charge,
                            'shipping_net' => $charge,
                            'total_gross' => $cart_group['subtotal'],
                            'total_net' => $cart_group['subtotal'],
                        ]);
                    }

                    $tax_value_total = 0;
                    if ($tax_rates = TaxHelper::getTaxRate($site, $order->s_country, $order->s_state)) {
                        foreach ($tax_rates as $tax_rate) {
                            $tax_value = TaxHelper::getTaxValue($tax_rate, $group->total_net, $group->shipping_net);
                            OrderGroupTaxModel::objects()->getOrCreate([
                                'order_group_id' => $group->order_group_id,
                                'tax_rate_id' => $tax_rate->rateid,
                                'value' => $tax_value
                            ]);
                            $tax_value_total += $tax_value;
                        }
                    }

                    $order->subtotal += $group->total_gross;
                    $order->shipping_cost += $charge;
                    $order->tax += $tax_value_total;
                    $group->total_tax = $tax_value_total;
                    $group->total_gross += $charge + $tax_value_total;
                    $group->total_net += $charge;

                    $group->save();
                }
            }

            $order->setAttributes([
                'total' => $order->subtotal + $order->shipping_cost + $order->tax,
            ]);

        } else {
            $order->groups->delete();
        }

        if ($app->request->getIsPost()) {

            $data = $app->request->post->all();

            if (!$app->request->post->has('payment_method')) {
                $this->redirect('checkout:options');
            }

            $order->setAttributes([
                'cb_status' => OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP3,
            ]);
            $order->groups->update(['cb_status' => OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP3]);

            if ($app->request->post->has('payment_method')) {
                if (($paymentid = $app->request->post->get('payment_method')) && $payment_method = PaymentMethodModel::objects()->get(['paymentid' => $paymentid])) {
                    /** @var PaymentMethodModel $payment_method */
                    $order->paymentid = $payment_method->paymentid;
                    $order->payment_method = $payment_method->payment_method;
                }
            }

            if ($app->request->post->get('billing_same')) {
                $order->setAttributes([
                    'b_address' => $order->s_address,
                    'b_firstname' => $order->s_firstname,
                    'b_company' => $order->s_company,
                    'b_city' => $order->s_city,
                    'b_state' => $order->s_state,
                    'b_country' => $order->s_country,
                    'b_zipcode' => $order->s_zipcode,
                ]);
            }
            elseif ($billingForm->populate($data)->isValid()) {
                $order->setAttributes($billingForm->getAttributes());
            } else {
                $errors = $billingForm->getErrors();
            }

            $order->firstname = $order->firstname ?: $order->b_firstname;

            $order->non_us_confirmation = false;
            if ($order->isCanadianShipping() && !($order->non_us_confirmation = $app->request->post->get('non_us_confirmation'))) {
                $app->flash->error(OrderModule::t('You must agree for custom duties'));
                $this->refresh();
            }

            if (!$errors) {
                $order->save();
                $this->redirect('checkout:review');
            }
        }

        $payment_methods = PaymentMethodModel::objects()
            ->filter(['active' => 'Y', 'site__through__storefrontid' => $site->storefrontid])
            ->order(['is_cod', 'orderby'])
            ->all();

        [$shipping_address] = $order->getAddressInfo();

        if (!$app->request->getIsPost()){
            if (!$app->request->post->get('billing_same') && $order->b_firstname) {
                $billingForm->setAttributes($order->getAttributes());
            }
            $order->save();
        }

        $this->display('checkout/options.tpl', [
            'order' => $order,
            'payment_methods' => $payment_methods,
            'errors' => $errors,
            'billingForm' => $billingForm,
            'shipping_address' => $shipping_address,
            'shipping_rates' => $sh_rates ?? []
        ]);

    }

    /**
     * Step 3
     *
     * @throws \Xcart\App\Exceptions\UnknownMethodException
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public function actionReview(): void
    {
        StagesOfOrdering::getInstance()->setStage(StagesOfOrdering::STAGE_ORDER_REVIEW);
        $order = $this->getOrder();
        $this->checkoutStepsValidate($order->cb_status, OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP3);

        if ($order->payment_method == 'Purchase Order') {
            $this->purchaseOrderReview($order);
        } else {
            $this->defaultReview($order);
        }
    }

    /**
     * Review purchase order
     * @param $order
     * @throws \Xcart\App\Exceptions\UnknownMethodException
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    private function purchaseOrderReview($order)
    {

        /** @var Application $app */
        $app = Xcart::app();
        $request = $app->request;
        $session = $request->session;
        $checkoutReviewForm = new CheckoutReviewForm();

        if ($request->getIsPost()) {

            $checkoutReviewForm->populate($request->post);
            $customerNote = $checkoutReviewForm->getField('customer_notes')->getValue();

            if (!empty($customerNote)) {
                $order->setAttributes([
                    'customer_notes' => trim($checkoutReviewForm->getField('customer_notes')->getValue()),
                ]);
            }

            if ($checkoutReviewForm->isValid()) {

                /** @var OrderModel $extra */
                [$extra] = OrderExtraModel::objects()->getOrNew(['order_id' => $order->orderid]);
                $extra->purchase_order = $checkoutReviewForm->getAttributes();
                $extra->save();

                if (($poId = $session->get('frontend_purchase_order_id')) &&
                    $poModel = PurchaseOrderModel::objects()->get(['po_id' => $poId]))
                {
                    $poModel->setAttributes(['order_id' => $order->orderid, 'status' => PurchaseOrderModel::PO_STATUS_ENTERED]);
                    $poModel->save();
                    $session->remove('frontend_purchase_order_id');
                } else {
                    if ($_FILES) {
                        $files = PrepareData::fixFiles($_FILES)['CheckoutReviewForm'] ?? $_FILES['CheckoutReviewForm'];
                    }

                    if (!empty($files['purchase_order_file']) && $files['purchase_order_file']['error'] === UPLOAD_ERR_OK) {
                        $original_file = $files['purchase_order_file']['name'];

                        /** @var SiteModel $site */
                        $site = $app->getModule('Sites')->getSite();

                        $poModel = $poModel ?? new PurchaseOrderModel([
                                'login' => $app->user->login,
                                'PO_number' => $checkoutReviewForm->getField('po_number')->getValue(),
                                'storefront_id' => $site->storefrontid,
                                'received_by' => 'website',
                                'order_id' => $order->orderid,
                            ]);

                        try {
                            $ext = pathinfo($original_file)['extension'];
                            if (PurchaseOrderHelper::uploadPurchaseOrder($poModel, $files['purchase_order_file']['tmp_name'], $ext)) {
                                $poModel->setAttributes([
                                    'status' => PurchaseOrderModel::PO_STATUS_UPLOADED,
                                    'file_name' => "{$poModel->PO_number}.{$ext}",
                                    'original_po_file' => $original_file,
                                ]);
                            }
                            $message = sprintf('PO# %s has been successfully entered', "{$order->getOrderNumber()} ({$poModel->original_po_file})");
                        } catch (Exception $ex) {
                            $message = $ex->getMessage();
                        } finally {
                            $order->orig_po = $site->getAbsoluteUrl() . PurchaseOrderHelper::getPurchaseOrderFileName($original_file);
                            $order->po_number = $poModel->PO_number;
                            $poModel->status = PurchaseOrderModel::PO_STATUS_ENTERED;
                            $poModel->save();
                            (new LogModel([
                                'resource_type' => 'purchase_orders',
                                'resource_id' => $poModel->po_id,
                                'type' => 'C',
                                'login' => $app->user->login,
                                'log' => $message
                            ]))->save();
                        }
                    }
                }

                $order->cb_status = OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP4;
                $order->date = time();
                $order->save();

                $this->redirect('checkout:payment');
            }
        }

        [$shipping_address, $billing_address] = $order->getAddressInfo();

        if (!$request->getIsPost() && $order) {
            $purchase_manager = [
                'name_of_purchaser' => $order->firstname,
                'purchase_manager_phone' => $order->phone,
                'phone_ext' => $order->phone_ext,
                'purchase_manager_email' => $order->email,
                'purchase_manager_fax' => $order->fax
            ];
            $account_payable = [
                'organization_name' => $order->b_company,
                'accounts_payable_full_name' => $order->b_firstname,
            ];

            if ($session->has('frontend_purchase_order_id')) {
                if ($poModel = PurchaseOrderModel::objects()->get(['po_id' => $session->get('frontend_purchase_order_id')])) {
                    $account_payable += [
                        'po_number' => $poModel->PO_number,
                        'purchase_order_file' => PurchaseOrderHelper::getPurchaseOrderFileName($poModel->file_name)
                    ];
                } else {
                    $session->remove('frontend_purchase_order_id');
                }
            }
            $checkoutReviewForm->setAttributes(array_merge($purchase_manager, $account_payable, $order->extra_model->purchase_order ?? []));
        }

        $this->display('checkout/review.tpl', [
            'order' => $order,
            'shipping_address' => $shipping_address,
            'billing_address' => $billing_address,
            'checkoutReviewForm' => $checkoutReviewForm,
            'showAllForm' => true
        ]);
    }

    /**
     * Review default order
     * @param $order
     */
    private function defaultReview($order)
    {

        /** @var Application $app */
        $app = Xcart::app();
        $customerNotesForm = new CustomerNotesForm();

        if ($app->request->getIsPost()) {

            $customerNotesForm->populate($app->request->post);
            $customerNote = $customerNotesForm->getField('customer_notes')->getValue();
            //dd($customerNote);

            if (!empty($customerNote)) {

                $order->setAttributes([
                    'customer_notes' => trim($customerNotesForm->getField('customer_notes')->getValue()),
                ]);
            }

            $order->cb_status = OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP4;
            $order->date = time();
            $order->save();

            $this->redirect('checkout:payment');
        }

        [$shippingAddress, $billingAddress] = $order->getAddressInfo();

        $customerNotesForm->setAttributes(['customer_notes' => $order->customer_notes]);

        $this->display('checkout/review.tpl', [
            'order' => $order,
            'shipping_address' => $shippingAddress,
            'billing_address' => $billingAddress,
            'checkoutReviewForm' => $customerNotesForm,
            'showAllForm' => false
        ]);
    }

    /**
     * Step 3.5
     * Redirect to payment processor
     */
    public function actionPayment(): void
    {
        StagesOfOrdering::getInstance()->setStage(StagesOfOrdering::STAGE_PAYMENT);
        $order = $this->getOrder();

        $this->checkoutStepsValidate($order->cb_status, OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP4);

        $this->redirect('payment:process', ['gateway' => strtolower($order->payment_method_model->frontend_processor->processor_name)]);

    }

    /**
     * Step 4
     *
     * @param int    $order_id
     * @param string $slug
     *
     * @throws \Xcart\App\Exceptions\HttpException
     */
    public function actionComplete(int $order_id, string $slug): void
    {
        /** @var OrderModel $order */
        if($order = OrderModel::objects()->get(['orderid' => $order_id])) {

            $hash = $order->getOrderHash();

            if ($slug !== $hash) {
                $this->error(404);
            }

            [$shipping, $billing] = $order->getAddressInfo();

            $this->display('checkout/complete.tpl', [
                'order' => $order,
                'shipping_info' => $shipping,
                'billing_info' => $billing,
                'hash' => $hash,
            ]);
        } else {
            $this->error(404);
        }
    }

    private function checkoutStepsValidate(string $order_status, $current_step = OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP1): void
    {
        if (!self::isStepValid($order_status, $current_step)) {
            $this->redirect(self::$steps[$order_status]['url'] ?? self::$steps[OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP1]['url']);
        }
    }

    private static $steps = [
        OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP1 => [
            'url' => 'checkout:shipping',
            'step' => 1,
        ],
        OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP2 => [
            'url' => 'checkout:options',
            'step' => 2,
        ],
        OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP3 => [
            'url' => 'checkout:review',
            'step' => 3,
        ],
        OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP4 => [
            'url' => 'checkout:payment',
            'step' => 4,
        ],
        OrderStatusModel::ORDER_STATUS_FAILED => [
            'url' => 'checkout:payment',
            'step' => 3,
        ],
    ];

    private static function isStepValid(string $order_status, string $current_step): bool
    {
        if (self::$steps[$order_status] && self::$steps[$current_step]) {
            return self::$steps[$order_status]['step'] >= self::$steps[$current_step]['step'];
        }
        return false;
    }
}