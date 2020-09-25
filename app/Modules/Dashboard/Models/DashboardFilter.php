<?php
namespace Modules\Dashboard\Models;

use Mindy\QueryBuilder\Aggregation\Max;
use Modules\Dashboard\Stores\EmailSearchStore;
use Modules\Dashboard\Stores\OrderSearchStore;
use Modules\Forms\Models\EmailModel;
use Modules\Order\Models\OrderModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\JsonField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Model;

class DashboardFilter extends Model
{
    private $s_store = null;
    private $uf_link_model = null;

    public static function tableName()
    {
        return "xcart_dashboard_filters";
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
            'group' => [
                'field' => 'group_id',
                'class' => ForeignField::class,
                'modelClass' => GroupModel::class,
                'verboseName' => 'Filter group',
                'null' => true,
            ],
            'users' => [
                'class' => ManyToManyField::class,
                'modelClass' => UserModel::class,
                'through' => UserFiltersLinkModel::class,
                'verboseName' => 'In users dashboard',
            ],
            'enabled' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => 1,
            ],
            'bold' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => 0,
            ],
            'name' => [
                'class' => CharField::class,
                'null' => false,
                'verboseName' => 'Filter name',
            ],
            'position_row' => [
                'class' => IntField::class,
                'null' => false,
                'verboseName' => 'Row position',
            ],
            'position_column' => [
                'class' => IntField::class,
                'null' => false,
                'verboseName' => 'Column position',
            ],
            'tag' => [
                'class' => CharField::class,
                'length' => 5,
                'null' => true,
                'verboseName' => 'Tag symbol (obsolete)',
            ],
            'color' => [
                'class' => CharField::class,
                'null' => true,
                'verboseName' => 'Tag color',
            ],
            'manual_url' => [
                'class' => CharField::class,
                'null' => true,
                'verboseName' => 'Link to manual',
            ],
            'direct_url' => [
                'class' => CharField::class,
                'null' => true,
                'verboseName' => 'Direct link',
            ],
            'sorting' => [
                'class' => IntField::class,
                'length' => 2,
                'null' => true,
                'verboseName' => 'List sorting rule',
                'default' => 1,
                'choices' => [
                    11 => 'Reverse chronological order (Date DESC)',
                    10 => 'Chronological order (Date ASC)',
                    1 => 'Priority shipping, Events count, Date',
                ],
            ],
            'entity' => [
                'class' => CharField::class,
                'default' => OrderModel::class,
                'choices' => [
                    OrderModel::class => 'Order',
                    EmailModel::class => 'Email',
                ]
            ],
            'form_data' => [
                'class' => JsonField::class,
                'null' => false,
                'verboseName' => 'Filter condition',
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    public function getAdminUrl()
    {
        if ($this->isNewRecord) {
            return Xcart::app()->router->url('dashboard:create_filter');
        }

        return Xcart::app()->router->url('dashboard:update_filter', ['id' => $this->id]);
    }

    public function getAbsoluteUrl()
    {
        if (!$this->isNewRecord) {

            if ($this->direct_url) {
                return $this->direct_url;
            }

            return Xcart::app()->router->url('dashboard:filter', ['id' => $this->id]);
        }
    }

    public function getSearchStorage(array $form_data = [])
    {
        if (!$this->s_store) {
            $sData = $this->form_data;
            if (!empty($form_data)) {
                $sData = array_merge_recursive($sData, $form_data);
            }
            if ($this->entity === null || $this->entity === OrderModel::class) {
                $this->s_store = new OrderSearchStore($sData, empty($form_data) ? $this : null);
            }
            if ($this->entity === EmailModel::class) {
                $this->s_store = new EmailSearchStore($sData, empty($form_data) ? $this : null);
            }
        }

        return $this->s_store;
    }

    public function getCountEvents($user_id = null)
    {
        $result = 0;

        if (!$user_id && !Xcart::app()->user->getIsGuest())  {
            $user_id = Xcart::app()->user->id;
        }

        if ($user_id) {

        }

        return $result;
    }

    public function getMyPositions()
    {
        if (!$this->uf_link_model) {
            $this->uf_link_model = UserFiltersLinkModel::objects()->filter(['filter_id' => $this->id, 'user__login' => (string)Xcart::app()->user->login])->get();
        }

        if ($this->uf_link_model) {
            return [
                'position_row' => $this->uf_link_model->position_row ?: $this->position_row,
                'position_column' => $this->uf_link_model->position_column ?: $this->position_column,
            ];
        }

        return [
            'position_row' => $this->position_row,
            'position_column' => $this->position_column,
        ];
    }

    public function getMyPositionRow()
    {
        $positions = $this->getMyPositions();
        return $positions['position_row'];
    }

    public function getMyPositionColumn()
    {
        $positions = $this->getMyPositions();
        return $positions['position_column'];
    }

    public static function getMaxRowCol()
    {
        return self::objects()
                   ->select(['row' => new Max('position_row'), 'col' => new Max('position_column')])
                   ->asArray()
                   ->limit(1)
                   ->get();
    }

    public function getTextClassOwner() {
        $userQs = $this->users->filter(['status' => 'Y']);

        if ($userQs->count() > 0) {

            if ($currentUser = Xcart::app()->getUser()) {
                foreach ($userQs as $user) {
                    if ($user->id == $currentUser->id) {
                        return 'own';
                    }
                }
            }

            return 'other';

        }

        return 'false';
    }
}