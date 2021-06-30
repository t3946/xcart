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
            $path = $request->getPath();
            $permission = strpos($path, 'manufacturers.php') !== false;
            $permission = $permission || strpos($path, '/admin/create/Admin/') !== false;
            $permission = $permission || strpos($path, '/admin/logout') !== false;
            $permission = $permission || strpos($path, '/admin/login') !== false;
            return $permission || strpos($path, 'admin/distributor/') !== false;
        }
        return true;
    }
}