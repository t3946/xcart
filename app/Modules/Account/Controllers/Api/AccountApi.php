<?php


namespace Modules\Account\Controllers\Api;


use Aws\Sns\SnsClient;
use Modules\Account\Controllers\AccountController;
use Modules\Core\Helpers\AdminHelper;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Core\TemplateLibraries\StaticMessagesLibrary;
use Modules\Goods\TemplateLibraries\MenuLibrary as GoodsMenuLibrary;
use Modules\Main\Helpers\WorkingTimeHelper;
use Modules\Menu\TemplateLibraries\MenuLibrary;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Models\OrderModel;
use Modules\Payment\Models\ProcessorModel;
use Modules\Sites\Helpers\StorageHelper;
use Modules\Sites\Models\PaymentMethodModel;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class AccountApi extends Controller
{
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
            $user = $user->toArray();
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
                ],
                'google_recaptchav2_site_key' => '6LenP30eAAAAAOUcOLvofYoaPMW6lMYTsov-RJ4p',
            ],
            'departmentsMenu' => [
                'desktop' => GoodsMenuLibrary::toArrayDesktop(),
                'mobile' => GoodsMenuLibrary::toArrayMobile(),
            ],
            'countries' => AccountController::getCountryPhoneCodes(),
        ];

        $this->jsonResponse($initial_data);
    }

    public function getInvoicePdf()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $orderid = $data['orderid'];
        $order = OrderModel::objects()->get(["orderid" => $orderid]);
        $hash = OrderHelper::getOrderHash([$order->orderid, $order->s_zipcode, $order->email]);
        $url = "/convert/pdf?orderid={$orderid}&p={$hash}&mode=print";

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

    public function getPaymentMethods()
    {
        $site = Xcart::app()->getModule('Sites')->getSite();
        $payment_methods = $site->payment_methods->filter(['is_active' => 1])->order(['position'])->all();

        if (!$payment_methods) {
            $payment_methods = PaymentMethodModel::objects()->asArray()->select(["logo", "name"])->all(['is_active' => 1]);
        }

        $this->jsonResponse($payment_methods);
    }
}