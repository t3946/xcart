<?php
namespace Modules\Dashboard\Models;

use Modules\User\Models\UserModel;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class UserFiltersLinkModel extends Model
{
    public static function tableName()
    {
        return 'xcart_user_filter_link';
    }

    public static function getFields()
    {
        return [
            'user' => [
                'primary' => true,
                'field' => 'user_id',
                'class' => ForeignField::className(),
                'modelClass' => UserModel::className(),
                'verboseName' => 'User',
                'link' => ['user_id' => 'id'],
            ],
            'filter' => [
                'primary' => true,
                'field' => 'filter_id',
                'class' => ForeignField::className(),
                'modelClass' => DashboardFilter::className(),
                'verboseName' => 'Filter in dashboard',
                'link' => ['filter_id' => 'id']
            ],
            'position_row'    => [
                'class' => IntField::className(),
                'null'  => true,
            ],
            'position_column' => [
                'class' => IntField::className(),
                'null'  => true,
            ],
        ];
    }
}