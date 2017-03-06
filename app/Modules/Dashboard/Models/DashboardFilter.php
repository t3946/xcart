<?php
namespace Modules\Dashboard\Models;

use Mindy\QueryBuilder\Aggregation\Max;
use Modules\Dashboard\Stores\OrderSearchStore;
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
//        return "{{dashboard_filters}}";
        return "xcart_dashboard_filters";
    }

    public static function getFields()
    {
        return [
            'id'              => [
                'class' => AutoField::className(),
            ],
            'group_id'           => [
                'class'       => ForeignField::className(),
                'modelClass'  => GroupModel::className(),
                'verboseName' => 'Group',
                'link'        => ['id', 'group_id'],
                'null'        => true,
            ],
            'users'           => [
                'class'       => ManyToManyField::className(),
                'modelClass'  => UserModel::className(),
                'through'     => UserFiltersLinkModel::className(),
                'link'        => ['filter_id', 'user_id'],
                'verboseName' => 'In users dashboard',
            ],
            'enabled'         => [
                'class'   => BooleanField::className(),
                'null'    => false,
                'default' => 1,
            ],
            'bold'            => [
                'class'   => BooleanField::className(),
                'null'    => false,
                'default' => 0,
            ],
            'name'            => [
                'class'       => CharField::className(),
                'null'        => false,
                'verboseName' => 'Filter name',
            ],
            'position_row'    => [
                'class' => IntField::className(),
                'null'  => false,
            ],
            'position_column' => [
                'class' => IntField::className(),
                'null'  => false,
            ],
            'tag'             => [
                'class'  => CharField::className(),
                'length' => 5,
                'null'   => true,
            ],
            'color'           => [
                'class'       => CharField::className(),
                'null'        => true,
                'verboseName' => 'Tag color',
            ],
            'direct_url'      => [
                'class'       => CharField::className(),
                'null'        => true,
                'verboseName' => 'Direct link',
            ],
            'form_data'       => [
                'class'       => JsonField::className(),
                'null'        => false,
                'verboseName' => 'Filter condition',
            ],
        ];
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getAdminUrl()
    {
        if ($this->isNewRecord) {
            return Xcart::app()->router->url('dashboard:create_filter');
        }
        else {
            return Xcart::app()->router->url('dashboard:update_filter', ['id' => $this->id]);
        }
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

    public function getSearchStorage()
    {
        if (!$this->s_store) {
            $this->s_store = new OrderSearchStore($this->form_data, $this->id);
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
            $this->uf_link_model = UserFiltersLinkModel::objects()->filter(['filter_id' => $this->id, 'user_id__login' => (string)Xcart::app()->user->login])->get();
        }

        return [
            'position_row' => ($this->uf_link_model && $this->uf_link_model->position_row) ? $this->uf_link_model->position_row : $this->position_row,
            'position_column' => ($this->uf_link_model && $this->uf_link_model->position_column) ? $this->uf_link_model->position_column : $this->position_column,
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

}