<?php

namespace Modules\Account\Models;

use Modules\User\Models\UserAccount\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\FileField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * Class UserListModel
 * @property ProductListsModel list_model
 * @property string role
 * @property string list_type
 * @property int user_id
 * @property UserModel user_model
 * @property string source
 * @package Modules\Account\Models
 */
class TSVRecoveryModel extends Model
{
    public static function tableName(): string
    {
        return 'tsv_recovery';
    }

    public static function getFields(): array
    {
        return [
            'tsv_recovery_id' => [
                'class' => AutoField::class,
            ],
            'user_id' => [
                'class' => IntField::class,
            ],
            'document_path' => [
                'class' => FileField::class,
                'required' => false,
                'null' => true,
                'adapterName' => 'www',
                'uploadTo' => 'user_files/%Y/%m/%d',
                'maxSize' => '10M',
            ],
            'created' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],
        ];
    }
}