<?php
namespace Modules\Dashboard\Models;

use Mindy\QueryBuilder\Aggregation\Max;
use Modules\Dashboard\Stores\OrderSearchStore;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\JsonField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Model;

class DashboardFilter extends Model
{
    private $s_store;

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
                'class'       => TextField::className(),
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
                'min'   => 1,
            ],
            'tag'             => [
                'class'  => TextField::className(),
                'length' => 5,
                'null'   => true,
            ],
            'color'           => [
                'class'       => TextField::className(),
                'null'        => true,
                'verboseName' => 'Tag color',
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
            return Xcart::app()->router->url('dashboard:create');
        }
        else {
            return Xcart::app()->router->url('dashboard:update', ['id' => $this->id]);
        }
    }

    public function getAbsoluteUrl()
    {
        if (!$this->isNewRecord) {
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

    public static function getMaxRowCol()
    {
        return self::objects()
                   ->select(['row' => new Max('position_row'), 'col' => new Max('position_column')])
                   ->asArray()
                   ->limit(1)
                   ->get();
    }

}