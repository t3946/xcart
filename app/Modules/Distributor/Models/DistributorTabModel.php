<?php


namespace Modules\Distributor\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class DistributorTabModel extends Model
{
    public static function tableName()
    {
        return 'xcart_distributor_tabs';
    }

    public static function getFields()
    {
        return [
            'tab_id' => AutoField::class,
            'distributor' => [
                'fields' => 'distributor_id',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['distributor_id' => 'manufacturerid']
            ],
            'name' => CharField::class,
            'content' => CharField::class,
        ];
    }

    public function __toString()
    {
        return (string) ($this->name ?? 'Tab');
    }
}