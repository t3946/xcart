<?php


namespace Modules\User\Models;


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

    public function canRequest($request)
    {
        if ($this->membership === 'Vendor Relations Specialist') {
            return strpos(\Xcart\App\Main\Xcart::app()->request->getPath(), 'manufacturers.php') !== false;
        }
        return true;
    }
}