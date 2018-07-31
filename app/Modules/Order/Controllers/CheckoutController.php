<?php

namespace Modules\Order\Controllers;

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QAndNot;
use Mobile_Detect;
use Modules\Cart\Components\CartItem;
use Modules\Cart\Helpers\StagesOfOrdering;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Core\Models\ZipCodeModel;
use Modules\Dashboard\Sqls\SearchSql;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Order\Forms\AccountsPayableForm;
use Modules\Order\Forms\BillingAddressForm;
use Modules\Order\Forms\BillingForm;
use Modules\Order\Forms\CheckoutReviewForm;
use Modules\Order\Forms\CustomerNotesForm;
use Modules\Order\Forms\PurchaseOrderDetailsForm;
use Modules\Order\Forms\PurchasingManagerForm;

use Modules\Order\Forms\ContactInfoForm;
use Modules\Order\Forms\ShippingAddressForm;

use Modules\Order\Forms\ShippingForm;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Helpers\OrderInvoiceHelper;
use Modules\Order\Helpers\PurchaseOrderHelper;
use Modules\Order\Models\LogModel;
use Modules\Order\Models\OrderDetailModel;
use Modules\Order\Models\OrderExtraModel;
use Modules\Order\Models\OrderGroupModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Modules\Order\Models\PurchaseOrderModel;
use Modules\Order\OrderModule;
use Modules\Order\Validation\ZipCodeValidator;
use Modules\Payment\Models\PaymentMethodModel;
use Modules\Shipping\Models\ShippingRateModel;
use Modules\Shipping\ShippingModule;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Application\Application;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Form\PrepareData;
use Xcart\App\Main\Xcart;
use Xcart\Connection;
use Xcart\Logs;
use Xcart\State;

class CheckoutController extends FrontendController
{

    public function beforeAction($action, $params): void
    {
        if ($action !== 'actionComplete' && !Xcart::app()->cart->isValid()) {
            $this->redirect('cart:list');
        }
    }

    protected function getOrder(): OrderModel
    {
        return OrderHelper::getCartOrder() ?? $this->redirect('checkout:shipping');
    }

    /**
     * Step 1
     *
     * @throws \Exception
     */
    public function actionShipping(): void
    {
        StagesOfOrdering::getInstance()->setStage(StagesOfOrdering::STAGE_SHIPPING_ADDRESS);
        /** @var OrderModel $order */

        /** @var Application $app */
        $app = Xcart::app();
        $cart = $app->cart;
        $shipping = null;
        $shippingForm = new ShippingForm();

        if ($app->request->getIsPost()) {
            $shippingForm->populate($app->request->post);

            if ($shippingForm->isValid()) {

                [$order, $is_created] = OrderModel::objects()->getOrCreate([
                    'cart_number' => $cart->getCartNumber(),
                ]);

                $order->setAttributes(array_merge($shippingForm->getAttributes(), ['cb_status' => OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP2]));

                if ($order->save()) {

                    if ($is_created) {
                        $app->event->trigger('order:created', ['model' => $order]);
                    }
                    $this->redirect('checkout:options');
                }
            }
        }

        $order = $order ?? OrderModel::objects()->get(['cart_number' => $cart->getCartNumber(), ]);

        if (!$app->request->getIsPost() && $order) {
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
        /** @var ShippingModule $ship_module */

        /** @var Application $app */
        $app = Xcart::app();
        //$user = $app->user;
        $site = $app->getModule('Sites')->getSite();
        $ship_module = $app->getModule('Shipping');
        $cart = $app->cart;
        $errors = [];
        $billingForm = new BillingForm();

        $order = $this->getOrder();

        $this->checkoutStepsValidate($order->cb_status, OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP2);

        if ($app->request->getIsPost()) {

            $data = $app->request->post->all();

            if ($cart_groups = $cart->getItemsGroupedBy()) {
                $rates = $app->request->post->get('shipping_rates');

                $order->subtotal = $order->shipping_cost = 0;

                $order->groups->delete([new QAndNot(['manufacturerid__in' => array_keys($cart_groups)])]);

                foreach ($cart_groups as $g => $cart_group)
                {
                    /** @var OrderGroupModel $group */
                    [$group, $is_created] = OrderGroupModel::objects()->getOrCreate(['manufacturerid' => $g, 'orderid' => $order->orderid]);

                    if (!$is_created) {
                        OrderDetailModel::objects()->delete(['order_group_id' => $group->order_group_id]);
                    }

                    $group->setAttributes(['shippingid' => null, 'shipping' => '']);

                    /** @var ShippingRateModel $rate */
                    if ($rates[$g] && ($rate = ShippingRateModel::objects()->get(['rateid' => $rates[$g]]))) {
                        $charge = 0;

                        /** @var ShippingRateModel[] $shipping_rates */
                        if (($shipping_rates = $ship_module::getShipping($g, $order, $cart_group)) && $shipping_rates[$rate->rateid]) {
                            $charge = $shipping_rates[$rate->rateid]->getShippingCharge();
                            $group->setAttributes([
                                'shippingid' => $shipping_rates[$rate->rateid]->shippingid,
                                'shipping' => $shipping_rates[$rate->rateid]->shipping->getFrontendName(),
                            ]);
                        }
                    }

                    $group->setAttributes([
                        'shipping_gross' => $charge,
                        'shipping_net' => $charge,
                        'total_gross' => $cart_group['subtotal'],
                        'total_net' => $cart_group['subtotal'],
                        'cb_status' => OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP3,
                    ]);

                    $order->subtotal += $group->total_gross;
                    $order->shipping_cost += $charge;

                    $group->total_gross += $charge;
                    $group->total_net += $charge;

                    $group->save();

                    /** @var CartItem $item */
                    foreach ($cart_group['items'] as $item)
                    {
                        /** @var ProductModel $product */
                        $product = $item->getObject();
                        $detail = new OrderDetailModel([
                            'orderid' => $group->orderid,
                            'productid' => $product->productid,
                            'order_group_id' => $group->order_group_id,
                            'price' => $product->getPrice($item->getQuantity()),
                            'amount' => $item->getQuantity(),
                            'productcode' => $product->productcode,
                            'product' => $product->getFrontendName(),
                            'provider' => $product->provider,
                            'original_provider' => $product->original_provider,
                            'item_cost_to_us' => $product->cost_to_us,
                            'product_options' => $item->data ?? null
                        ]);
                        $detail->save();
                    }
                }

                $order->setAttributes([
                    'total' => $order->subtotal + $order->shipping_cost,
                    'cb_status' => OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP3,
                ]);

            } else {
                    $order->groups->delete();
            }

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

            $order->firstname = $order->b_firstname;

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

        [$shipping_address, $billing_address] = $order->getAddressInfo();


        if (!$app->request->getIsPost() && !$app->request->post->get('billing_same') && $order->b_firstname) {
            $billingForm->setAttributes($order->getAttributes());
        }

        $this->display('checkout/options.tpl', [
            'order' => $order,
            'payment_methods' => $payment_methods,
            'errors' => $errors,
            'billingForm' => $billingForm,
            'shipping_address' => $shipping_address,
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
        $checkoutReviewForm = new CheckoutReviewForm();

        if ($app->request->getIsPost()) {

            $checkoutReviewForm->populate($app->request->post);
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

                if ($_FILES) {
                    $files = PrepareData::fixFiles($_FILES)['CheckoutReviewForm'] ?? $_FILES['CheckoutReviewForm'];
                }

                if (!empty($files['purchase_order_file']) && $files['purchase_order_file']['error'] === UPLOAD_ERR_OK) {
                    $original_file = $files['purchase_order_file']['name'];

                    /** @var SiteModel $site */
                    $site = Xcart::app()->getModule('Sites')->getSite();

                    $po_model = new PurchaseOrderModel([
                        'login' => Xcart::app()->user->login,
                        'PO_number' => $checkoutReviewForm->getField('po_number')->getValue(),
                        'storefront_id' => $site->storefrontid,
                        'received_by' => 'website'
                    ]);

                    try {
                        $ext = pathinfo($original_file)['extension'];
                        if (PurchaseOrderHelper::uploadPurchaseOrder($po_model, $files['purchase_order_file']['tmp_name'], $ext)) {
                            $po_model->setAttributes([
                                'status' => 'uploaded',
                                'order_id' => $order->orderid,
                                'file_name' => "{$po_model->PO_number}.{$ext}",
                                'original_po_file' => $original_file,
                            ]);
                            $order->orig_po = $site->getAbsoluteUrl() . sprintf('/files/purchase_orders/%s', $original_file);
                            $order->po_number = $po_model->PO_number;
                        }
                        $po_model->status = 'entered';
                        $po_model->save();
                        $message = sprintf('PO# %s has been successfully entered', "{$order->getOrderNumber()} ({$po_model->original_po_file})");
                    } catch (\Exception $ex) {
                        $message = $ex->getMessage();
                    } finally {
                        (new LogModel([
                            'resource_type' => 'purchase_orders',
                            'resource_id' => $po_model->po_id,
                            'type' => 'C',
                            'login' => $app->user->login,
                            'log' => $message
                        ]))->save();
                    }
                }


                $order->cb_status = OrderStatusModel::ORDER_STATUS_CHECKOUT_STEP4;
                $order->date = time();
                $order->save();

                $this->redirect('checkout:payment');
            }
        }

        [$shipping_address, $billing_address] = $order->getAddressInfo();

        if (!$app->request->getIsPost() && $order) {
            $purchase_manager = [
                'name_of_purchaser' => $order->firstname,
                'purchase_manager_phone' => $order->phone,
                'phone_ext' => $order->phone_ext,
                'purchase_manager_email' => $order->email,
                'purchase_manager_fax' => $order->fax,
            ];
            $checkoutReviewForm->setAttributes(array_merge($purchase_manager, $order->extra_model->purchase_order ?? []));
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
        $app = Xcart::app();
        $user = $app->user;

        if($order = OrderModel::objects()->get(['orderid' => $order_id])) {

            $hash = OrderHelper::getOrderHash([$order->orderid, $order->total, $order->email]);

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
            //Xcart::app()->flash->error(OrderModule::t('Cart changed: One or more items have changed!'));
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