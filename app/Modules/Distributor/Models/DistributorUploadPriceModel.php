<?php

namespace Modules\Distributor\Models;

use Modules\User\Models\UserModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * @property int upload_id
 * @property DistributorModel manufacturer
 * @property int manufacturer_id
 * @property int count_rows
 * @property UserModel user
 * @property int user_id
 * @property int date
 * @property string file_path
 * @property string status
 * @property string file_name
 */
class DistributorUploadPriceModel extends Model
{
    public const UPLOAD_STATUS_SUCCESS = 'Success';
    public const UPLOAD_STATUS_ERROR = 'Error';
    public const UPLOAD_STATUS_IN_PROCESS = 'In process';

    public static function tableName(): string
    {
        return 'xcart_manufacturers_upload_prices';
    }

    public static function getFields(): array
    {
        return [
            'upload_id' => AutoField::class,
            'manufacturer' => [
                'field' => 'manufacturer_id',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturer_id' => 'manufacturerid'],
            ],
            'date' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],
            'count_rows' => IntField::class,
            'user' => [
                'field' => 'user_id',
                'class' => ForeignField::class,
                'modelClass' => UserModel::class,
                'link' => ['user_id' => 'id'],
            ],
            'file_path' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true
            ],
            'status' => [
                'class' => CharField::class,
                'default' => self::UPLOAD_STATUS_IN_PROCESS,
                'null' => true
            ],
            'file_name' => [
                'class' => CharField::class,
                'default' => null,
                'null' => true
            ]
        ];
    }
}