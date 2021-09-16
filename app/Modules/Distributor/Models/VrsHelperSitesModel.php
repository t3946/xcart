<?php


namespace Modules\Distributor\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

/**
 * @property string $status
 * @property int $site_id
 */
class VrsHelperSitesModel extends Model
{
    public static function tableName()
    {
        return 'vrs_sites';
    }

    public static function getFields()
    {
        return [
            'site_id' => [
                'class' => AutoField::class,
            ],
            'domain' => [
                'class' => CharField::class,
                'field' => 'domain'
            ],
            'status' => [
                'class' => CharField::class,
                'field' => 'status',
                'default' => 'visited'
            ],
        ];
    }

}