<?php
namespace Modules\Core\Models;


use Modules\Sites\Models\SiteModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Model;

class StaticNotificationModel extends Model
{

    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'active' => [
                'class' => BooleanField::class,
                'default' => false,
            ],

            'bg_color' => [
                'class' => CharField::class,
                'default' =>  '#58af42',
                'verboseName' => 'Background color',
            ],

            'text_color' => [
                'class' => CharField::class,
                'default' => '#ffffff',
                'verboseName' => 'Text color'
            ],

            'title' => [
                'class' => CharField::class,
                'null' => true,
            ],

            'description' => [
                'class' => TextField::class,
                'required' => true,
            ],

            'start_at' => [
                'class' => DateTimeField::class,
                'required' => false,
                'null' => true,
                'verboseName' => 'Start showing message date and time'
            ],

            'end_at' => [
                'class' => DateTimeField::class,
                'required' => false,
                'null' => true,
                'verboseName' => 'End showing message date and time'
            ],

            'site' => [
                'field' => 'storefront_id',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'null' => true
            ]
        ];
    }

}