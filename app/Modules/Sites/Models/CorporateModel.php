<?php


namespace Modules\Sites\Models;


use Modules\Core\Models\CountryModel;
use Modules\Core\Models\StateModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class CorporateModel extends Model
{
    public static function getFields(): array
    {
        return [
            'id' => AutoField::class,
            'name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'country_model' => [
                'field' => 'country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'null' => true,
                'default' => null
            ],
            'state_model' => [
                'field' => 'state',
                'class' => ForeignField::class,
                'modelClass' => StateModel::class,
                'null' => true,
                'default' => null
            ],
            'registration_number' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'incorporation_date' => [
                'class' => DateField::class,
                'null' => true,
                'default' => null
            ],
        ];
    }

    public function getAdminUrl()
    {
        return '';
    }
}