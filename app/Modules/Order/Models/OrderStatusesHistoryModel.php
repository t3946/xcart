<?php

namespace Modules\Order\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;


class OrderStatusesHistoryModel extends Model
{
  public static function tableName()
  {
      return 'xcart_order_statuses_history';
  }

  public static function getFields()
  {
      return [
          'id' => AutoField::class,
          'group' => [
              'field' => 'group_id',
              'class' => ForeignField::class,
              'modelClass' => OrderGroupModel::class,
              'link' => [
                'group_id' => 'order_group_id'
              ]
          ],
          'status' => [
              'class' => CharField::class,
          ],
          'old_status' => [
              'class' => CharField::class,
          ],
          'updated' => [
              'class' => DateTimeField::class,
          ],
      ];
  }
}