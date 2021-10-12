<?php


namespace Modules\User\Models;


use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Model;

/**
 * @property ?string $slug
 */
class RoleModel extends Model
{
    public const ROLE_FEED_QUALITY_SLUG = 'fqa';

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
        $path = $request->getPath();

        switch($this->slug) {
            case 'vrs':
            case 'vrv':
                $permission = strpos($path, 'manufacturers.php') !== false;
                $permission = $permission || strpos($path, '/admin/create/Admin/') !== false;
                $permission = $permission || strpos($path, '/admin/list/Distributor/') !== false;
                $permission = $permission || strpos($path, '/admin/forms/') !== false;
                $permission = $permission || strpos($path, '/admin/logout') !== false;
                $permission = $permission || strpos($path, '/admin/login') !== false;
                $permission = $permission || strpos($path, '/Distributor/VrsAdmin') !== false;
                return $permission || strpos($path, 'admin/distributor/') !== false;
            case 'fqa':
                $permission = strpos($path, '/admin/list/Distributor/DistributorAdmin') !== false;
                $permission = $permission || strpos($path, '/admin/logout') !== false;
                $permission = $permission || strpos($path, '/admin/login') !== false;
                return $permission || strpos($path, 'admin/distributor/') !== false;
        }
        return true;
    }
}