<?php


namespace Modules\Account\Controllers\Api;


use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Xcart\App\Controller\FrontendController;

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
}