<?php


namespace Modules\Distributor\Models;


use Modules\Forms\Models\EmailModel;
use Modules\User\Models\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class VrsHelperMessagesModel extends Model
{
    public static function tableName()
    {
        return 'vrs_helper_messages';
    }

    public static function getFields()
    {
        return [
            'message_id' => [
                'class' => AutoField::class,
            ],
            'user' => [
                'field' => 'user_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'primary' => true,
                'link' => ['user_id' => 'id'],
            ],
            'site' => [
                'field' => 'site_id',
                'class' => ForeignField::class,
                'modelClass' => VrsHelperSitesModel::class,
                'primary' => true,
                'link' => ['site_id' => 'site_id'],
            ],
            'date' => [
                'field' => 'date',
                'class' => DateTimeField::class,
                'autoNow' => true
            ],
            'message_text' => [
                'class' => CharField::class,
                'field' => 'message_text'
            ],
            'status' => [
                'class' => CharField::class,
                'field' => 'status'
            ],
        ];
    }

}