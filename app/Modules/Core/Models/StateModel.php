<?php

namespace Modules\Core\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

/**
 * @property int stateid
 * @property string state
 * @property string code
 * @property string country_code
 * @property string base_state_zipcode
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
                'class' => AutoField::className(),
            ],
        ];
    }

    public function __toString(): string
    {
        return (string) $this->state;
    }
}