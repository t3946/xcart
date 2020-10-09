<?php


namespace Modules\User\Models;


use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

class RoleModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_memberships';
    }

    public static function getFields()
    {
        return [
            'membershipid' => [
                'class' => AutoField::class,
            ],
        ];
    }

    public function canRequest($request): bool
    {
        if ($this->slug === 'vrs' || $this->slug === 'vrv') {
            $permission = strpos(Xcart::app()->request->getPath(), 'manufacturers.php') !== false;
            $permission = $permission || strpos(Xcart::app()->request->getPath(), 'admin/distributor/') !== false;
            return $permission;
        }
        return true;
    }
}