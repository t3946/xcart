<?php


namespace Modules\Account\Controllers\Api;


use Aws\Sns\SnsClient;
use Modules\Account\Controllers\AccountController;
use Modules\Core\Helpers\AdminHelper;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\TemplateLibraries\MenuLibrary as GoodsMenuLibrary;
use Modules\Main\Helpers\WorkingTimeHelper;
use Modules\Menu\TemplateLibraries\MenuLibrary;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Models\OrderModel;
use Modules\Payment\Models\ProcessorModel;
use Modules\Reviews\Controllers\Api\ReviewsApi;
use Modules\Reviews\Models\RatingsModel;
use Modules\Reviews\ReviewsModule;
use Modules\Sites\Helpers\StorageHelper;
use Modules\Sites\Models\PaymentMethodModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class AccountApi extends Controller
{
    public function cancelTransaction(){

        if (Xcart::app()->auth->getUser(true)->getIsGuest()) {
            $this->jsonResponse([], 401);
        }

        $data = $this->getRequest()->body;

        if ($data->has('orderid') === false) {
            $this->jsonResponse([], 400);
        }

        if (OrderModel::objects()->count(['orderid' => $data->orderid]) === 0) {
            $this->jsonResponse([], 400);
        }

        OrderHelper::cancelOrder($data->orderid);

        $this->jsonResponse();

    }

    public function getTerritory()
    {
        $this->jsonResponse(['countries' => $this->getCountries(), 'states' => $this->getStates()]);
    }

    public function getCountries()
    {
        $countries = [];
        foreach (CountryModel::objects()->all() as $key => $country) {
            $countries[$key]['value'] = $country->code;
            $countries[$key]['label'] = $country->name;
        }
        return $countries;
    }

    public function getStates()
    {
        $states = [];
        foreach (StateModel::objects()->all() as $key => $state) {
            $states[$key]['value'] = $state->stateid;
            $states[$key]['label'] = $state->state_name;
            $states[$key]['countryCode'] = $state->model_country_code;
            $states[$key]['country_id'] = $state->country_id;
        }
        return $states;
    }

    public function getSitePropertiesAction()
    {
        $site = Xcart::app()->getModule('Sites')->getSite();

        $this->jsonResponse([
            "code" => strtolower($site->code),
            "shortName" => $site->short_name,
            "workingDayTimeNow" => WorkingTimeHelper::workingDayTimeNow(),
        ]);
    }

    public function getInitialDataAction()
    {
        $site = Xcart::app()->getModule('Sites')->getSite();
        $config = $site->getConfig();
        $user = Xcart::app()->auth->getUser(true);

        if ($user->getIsGuest()) {
            $user = null;
        } else {
            $attributes = $user->toArray();
            $attributes['avatar_image'] = $user->avatar_image->getUrl();
            $user = $attributes;
        }

        $stripeSettings = ProcessorModel::objects()->asArray()->get(['processor_name' => 'Stripe']);

        $initial_data = [
            'user' => $user,
            'routes' => AdminHelper::getRoutesMap(),
            'mainMenu' => MenuLibrary::getData("main-menu"),
            'cart' => [
                'quantity' => Xcart::app()->cart->getQuantity(),
                'checkoutUrl' => OrderHelper::getCheckoutUrl(),
            ],
            'config' => [
                'cidev_top_header_code' => $config['cidev_top_header_code'],
                'cidev_header_code' => $config['cidev_header_code'],
                'companyName' => $config['company_name'],
                'stripePK' => $stripeSettings['param01'],
                'APP_LOCAL' => APP_LOCAL,
                'site' => [
                    'code' => strtolower($site->code),
                    'shortName' => $site->short_name,
                    'workingDayTimeNow' => WorkingTimeHelper::workingDayTimeNow(),
                    'account_enabled' => $site->account_enabled,
                    'logo' => (string)$site->logo,
                    'logo_mobile' => (string)$site->logo_mobile,
                    'file_edit_image_favicon' => (string)$site->file_edit_image_favicon,
                    'fax_number' => $site->fax_number,
                    'corporationName' => $site->corporation->name,
                ],
                'google_recaptchav2_site_key' => '6LenP30eAAAAAOUcOLvofYoaPMW6lMYTsov-RJ4p',
            ],
            'departmentsMenu' => [
                'desktop' => GoodsMenuLibrary::toArrayDesktop(),
                'mobile' => GoodsMenuLibrary::toArrayMobile(),
            ],
            'countries' => AccountController::getCountryPhoneCodes(),
            'payments' => [
                'cards' => [],
                'cardsLoading' => false,
                'methods' => $this->getPaymentMethods(),
            ],
        ];

        $this->jsonResponse($initial_data);
    }

    public function getInvoicePdf()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $orderid = $data['orderid'];
        $order = OrderModel::objects()->get(["orderid" => $orderid]);
        $hash = OrderHelper::getOrderHash([$order->orderid, $order->s_zipcode, $order->email]);
        $url = "/convert/pdf/?orderid={$orderid}&p={$hash}&mode=print";

        $this->jsonResponse($url);
    }

    public function sendSMS()
    {
        $data = json_decode(file_get_contents("php://input"));
        $params = [
            'credentials' => Xcart::app()->globals['aws']['sns']['credentials'],
            'region' => 'us-east-1',
            'version' => 'latest'
        ];
        $sns = new SnsClient($params);
        $args = [
            "MessageAttributes" => [
                'AWS.SNS.SMS.SenderID' => [
                    'DataType' => 'String',
                    'StringValue' => 'S3Stores'
                ],
                'AWS.SNS.SMS.SMSType' => ['DataType' => 'String', 'StringValue' => 'Transactional']
            ],
            "PhoneNumber" => $data->phone,
            "Message" => $data->message,
        ];

        $sns->publish($args);
        http_response_code(200);
    }

    public function getPaymentMethods() {
        $site = Xcart::app()->getModule('Sites')->getSite();
        $payment_methods = $site->payment_methods->asArray()->filter(['is_active' => 1])->order(['position'])->all();

        if (!$payment_methods) {
            $payment_methods = PaymentMethodModel::objects()->asArray()->select(["logo", "name"])->all(['is_active' => 1]);
        }

        return $payment_methods;
    }

    public function getPaymentMethodsAction()
    {
        $this->jsonResponse($this->getPaymentMethods());
    }

    // получить данные для клиентской части старого сайта
    public function getSiteDataAction() {
        AccountController::provideAccountData();
        $this->jsonResponse(StorageHelper::getStorage());
    }

    public function getProductInfo() {
        if (!$this->getRequest()->getIsAjax()) {
            $this->jsonResponse([], 404);
            return;
        }

        if (!$this->getRequest()->body->has('productId')) {
            $this->jsonResponse([], 400);
            return;
        }

        $product = ProductModel::objects()->get(['productid' => $this->getRequest()->body->productId]);

        if ($product === null) {
            $this->jsonResponse(['error' => 'product not found'], 404);
            return;
        }

        $ratings_models = RatingsModel::objects()->asArray()->all();
        $ratings = ['overall' => null, 'features' => []];

        foreach ($ratings_models as $i => $model) {
            if ($model['slug'] === 'overall') {
                $ratings['overall'] = $model;
            } else {
                $ratings['features'][] = $model;
            }
        }

        StorageHelper::push($ratings, 'ratings', 'ratings');

        $this->jsonResponse([
            'product_info' => [
                'product' => $product->getAttributes(),
                'image' => (string)$product->getMainImage(),
                'brand' => $product->brand,
                'distributor' => $product->distributor,
                'flags' => [
                    'isGroupRoot' => $product->isGroupRoot(),
                    'isOutOfStockFrontend' => $product->isOutOfStockFrontend(),
                    'isFreeShipping' => $product->isFreeShipping(),
                    'isFlatRate' => $product->isFlatRate(),
                    'isEarlyChildhoodResources' => $product->manufacturerid === 85,
                ],
            ],

            'reviews' => [
                'orders' => [
                    [
                        'previewValue' => 'Most recent',
                        'viewValue' => 'Most recent',
                        'value' => ReviewsApi::SORT_NEW,
                    ],
                    [
                        'previewValue' => 'Top reviews',
                        'viewValue' => 'Top reviews',
                        'value' => ReviewsApi::SORT_TOP,
                    ],
                    [
                        'previewValue' => 'With images',
                        'viewValue' => 'With images',
                        'value' => ReviewsApi::SORT_HAS_IMAGES,
                    ],
                    [
                        'previewValue' => 'With videos',
                        'viewValue' => 'With videos',
                        'value' => ReviewsApi::SORT_HAS_VIDEOS,
                    ],
                ],
                'limits' => [
                    'maxImageSizeMB' => ReviewsModule::MAX_IMAGE_SIZE_MB,
                    'maxVideoSizeMB' => ReviewsModule::MAX_VIDEOS_SIZE_MB,
                    'maxAttachments' => ReviewsModule::MAX_ATTACHMENTS_NUMBER,
                ]
            ]
        ]);
    }
}