<?php
namespace Modules\Dashboard\Models;

use Modules\User\Models\UserModel;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class UserFiltersThroughModel extends Model
{
    public static function tableName()
    {
        return 'xcart_user_filter_link';
    }

    public static function getFields()
    {
        return [
            'user' => [
                'class' => ForeignField::className(),
                'modelClass' => UserModel::className(),
                'verboseName' => 'User',
                'link' => ['id', 'user_id']
            ],
            'filter' => [
                'class' => ForeignField::className(),
                'modelClass' => DashboardFilter::className(),
                'verboseName' => 'Filter in dashboard',
                'link' => ['filter_id', 'id']
            ],
        ];
    }
}