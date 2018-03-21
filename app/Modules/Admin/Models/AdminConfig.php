<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @company HashStudio
 * @site http://hashstudio.ru
 * @date 02/11/16 16:49
 */

namespace Modules\Admin\Models;



use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Model;

class AdminConfig extends Model
{
    public static function tableName()
    {
        return 'admin_config';
    }

    public static function getFields()
    {
        return [
            'module' => [
                'class' => CharField::className(),
                'verboseName' => 'Module'
            ],
            'admin' => [
                'class' => CharField::className(),
                'verboseName' => 'Admin'
            ],
            'user' => [
                'class' => ForeignField::className(),
                'verboseName' => 'User',
                'modelClass' => UserModel::className(),
                'link' => ['user_id' => 'id']
            ],
            // Comma-separated columns
            'columns' => [
                'class' => TextField::className(),
                'verboseName' => 'Columns',
                'null' => true
            ],
            'page_size' => [
                'class' => IntField::class,
                'verboseName' => 'Items in list',
                'null' => true,
            ],
        ];
    }

    public function getColumnsList()
    {
        $columns = $this->columns;
        return explode(',', $columns);
    }

    public function setColumnsList($columns)
    {
        $this->columns = implode(',', $columns);
        $this->save();
    }

    public static function fetch($module, $admin)
    {
        [$model, $isNew] = self::objects()->getOrCreate([
            'module' => $module,
            'admin' => $admin,
            'user_id' => Xcart::app()->user->id
        ]);

        return $model;
    }
}