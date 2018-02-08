<?php
namespace Modules\Core\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Model;

class StaticNotificationModel extends Model
{

    public static function getFields()
    {
        return [
            'id' => AutoField::className(),
            'active' => [
                'class' => BooleanField::className(),
                'default' => false,
            ],

            'bg_color' => [
                'class' => CharField::className(),
                'default' =>  '#58af42',
                'verboseName' => 'Background color',
            ],

            'text_color' => [
                'class' => CharField::className(),
                'default' => '#ffffff',
                'verboseName' => 'Text color'
            ],

            'title' => [
                'class' => CharField::className(),
                'null' => true,
            ],

            'description' => [
                'class' => TextField::className(),
                'required' => true,
            ],

            'start_at' => [
                'class' => DateTimeField::className(),
                'required' => false,
                'null' => true,
                'verboseName' => 'Start showing message date and time'
            ],

            'end_at' => [
                'class' => DateTimeField::className(),
                'required' => false,
                'null' => true,
                'verboseName' => 'End showing message date and time'
            ],
        ];
    }

}