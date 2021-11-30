<?php


namespace Modules\Account\Controllers\Api;


use Modules\Core\Helpers\AdminHelper;
use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Modules\Main\Helpers\WorkingTimeHelper;
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

    public function getRoutesList() {
        $this->jsonResponse(AdminHelper::routesData());
    }
}