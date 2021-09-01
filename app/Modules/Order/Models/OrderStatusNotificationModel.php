<?php

namespace Modules\Order\Models;


use Modules\Forms\Helpers\SnippetHelper;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\RenderTrait;

class OrderStatusNotificationModel extends Model
{
    use AutoMetaTrait;
    use RenderTrait;

    public static function tableName()
    {
        return 'xcart_order_status_notifications';
    }

    public static function getFields()
    {
        return [
            'code' => [
                'class' => CharField::class,
                'null' => false,
                'primary' => true,
            ],

        ];
    }

    public function render($name, $params)
    {
        return SnippetHelper::render($this->{$name}, $params);
    }
}