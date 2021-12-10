<?php


namespace Modules\Account\Controllers\Api;


use Modules\Account\Controllers\AccountController;
use Modules\Core\Helpers\AdminHelper;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Core\TemplateLibraries\StaticMessagesLibrary;
use Modules\Goods\TemplateLibraries\MenuLibrary as GoodsMenuLibrary;
use Modules\Main\Helpers\WorkingTimeHelper;
use Modules\Menu\TemplateLibraries\MenuLibrary;
use Modules\Order\Helpers\OrderHelper;
use Modules\Payment\Models\ProcessorModel;
use Modules\Sites\Helpers\StorageHelper;
use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class AccountApi extends FrontendController
{
    public function getTerritory()
    {
        $this->jsonResponse(['countries' => $this->getCountries(), 'states' => $this->getStates()]);
    }

    public function getCountries()
    {
        $countries = [];
        foreach (CountryModel::objects()->all() as $key => $country)
        {
            $countries[$key]['value'] = $country->code;
            $countries[$key]['viewValue'] = $country->name;
        }
        return $countries;
    }

    public function getStates()
    {
        $states = [];
        foreach (StateModel::objects()->all() as $key => $state)
        {
            $states[$key]['value'] = $state->stateid;
            $states[$key]['viewValue'] = $state->state_name;
            $states[$key]['countryCode'] = $state->model_country_code;
        }
        return$states;
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
        $user = $this->getUser();

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
            'site' => [
                'code' => strtolower($site->code),
                'shortName' => $site->short_name,
                'workingDayTimeNow' => WorkingTimeHelper::workingDayTimeNow(),
            ],
            'cart' => [
                'quantity' => Xcart::app()->cart->getQuantity(),
                'checkoutUrl' => OrderHelper::getCheckoutUrl(),
            ],
            'config' => [
                'cidev_top_header_code' => $config['cidev_top_header_code'],
                'cidev_header_code' => $config['cidev_header_code'],
                'companyName' => $config['company_name'],
                'stripePublicKey' => $stripeSettings['param01'],
            ],
            'templates' => [
                'renderStaticNotifications' => StaticMessagesLibrary::renderStaticMessages(),
            ],
            'departmentsMenu' => [
                'desktop' => GoodsMenuLibrary::toArrayDesktop(),
                'mobile' => GoodsMenuLibrary::toArrayMobile(),
            ],
            'params' => [
                'get' => Xcart::app()->request->get->all(),
            ],
            'APP_LOCAL' => APP_LOCAL,
            'countries' => AccountController::getCountryPhoneCodes(),
        ];

        $this->jsonResponse($initial_data);
    }
}