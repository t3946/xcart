<?php

namespace Modules\Core\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

/**
 * @property int stateid
 * @property string state
 * @property string code
 * @property string country_code
 * @property string base_state_zipcode
 * @property string timezone
 */
class StateModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_states';
    }

    public static function getFields()
    {
        return [
            'stateid' => [
                'class' => AutoField::class,
            ],
            'timezone' => [
                'class' => CharField::class
            ],
            'state_name' => [
                'field'=> 'state',
                'class' => CharField::class
            ],
            'code' => [
                'class' => CharField::class
            ],
            'model_country_code' => [
                'field' => 'country_code',
                'class' => CharField::class
            ],
            'country_model' => [
                'field' => 'country_id',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'link' => ['country_id' => 'country_id']
            ]
        ];
    }

    public function __toString(): string
    {
        return $this->state;
    }
    public static function getState(string $country_code, string $code_state): ?string
    {
        $state_model = static::objects()->get(['country_code' => $country_code, 'code' => $code_state]);
        return $state_model->state ?? null;
    }
}