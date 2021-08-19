<?php
namespace Modules\User\Models;

use Doctrine\DBAL\Types\Types;
use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\DataModelTrait;
use Xcart\Customer;

/**
 * @property mixed login
 * @method static Manager admins()
 */
class UserModel extends Model
{
    use DataModelTrait, AutoMetaTrait;

    public static function getDataModelClass(): string
    {
        return Customer::class;
    }

    public static function tableName()
    {
        return 'xcart_customers';
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
            'pbx_extension' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
            'login' => [
                'class' => CharField::class,
                'null' => false,
                'unique' => true,
            ],
            'show_events' => [
                'class' => IntField::class,
                'length' => 1,
                'default' => 0,
                'choices' => [
                    0 => 'Disable',
                    1 => 'Enable'
                ]
            ],
            'show_events_min_date' => [
                'class' => DateTimeField::class,
                'null' => true
            ],
            'usertype' => [
                'class' => CharField::class,
                'null' => false,
                'default' => 'C',
                'choices' => [
                    'A' => 'Admin',
                    'B' => 'B (Partners ?)',
                    'C' => 'Customer',
                    'P' => 'Operator',
                    'V' => 'Verificator',
                ]
            ],
            'role' => [
                'field' => 'membershipid',
                'class' => ForeignField::class,
                'modelClass' => RoleModel::class,
                'link' => ['membershipid' => 'membershipid']
            ],
            'distributors' => [
                'modelClass' => DistributorModel::class,
                'class' => HasManyField::class,
                'sqlType' => Types::STRING,
                'link' => ['login' => 'provider']
            ],
            'childs' => [
                'class' => HasManyField::class,
                'modelClass' => UserModel::class,
                'link' => ['id' => 'parent_user_id']
            ],
            'firstname' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ]
        ];
    }

    public function __toString(): string
    {
        return (string) $this->firstname;
    }

    public function getIsGuest()
    {
        return $this->isNewRecord || empty($this->login);
    }

    public function getIsStaff()
    {
        if (!$this->getIsGuest()) {
            return !in_array($this->usertype, ['C', 'B']) || !empty($this->membershipid);
        }

        return false;
    }

    public function getIsSuperuser()
    {
        if (!$this->getIsGuest()) {
            return ($this->usertype === 'A') && empty($this->membershipid);
        }

        return false;
    }

    public function getAdminUrl()
    {
        return "/admin/user_modify.php?user={$this->login}&usertype={$this->usertype}";
    }

    public function getShortSurname():? string
    {
        if ($name = explode(' ', $this->firstname)) {
            $length = count($name)-1;
            $last_name = (string) $name[$length];
            $name[$length] = $last_name[0];
            return implode(' ', $name);
        }
        return null;
    }

    public function hasRole($slug): bool
    {
        if ($role = $this->role) {
            return $role->slug === $slug;
        }
        return false;
    }

    public function hasRoles($roles): bool
    {
        if ($role = $this->role) {
            return $role->getObjects()->filter(['slug__in' => $roles])->count() > 0;
        }
        return false;
    }

    public static function adminsManager($instance = null): Manager
    {
        return static::objects($instance)
            ->exclude(['position__in' => ['VRS', 'programmer']])
            ->filter(['usertype' => 'A', 'status' => 'Y', 'activity' => 'Y'])
            ->order(['firstname']);
    }
}