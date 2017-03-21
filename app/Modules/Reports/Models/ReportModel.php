<?php
namespace Modules\Reports\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\JsonField;
use Xcart\App\Orm\Model;

class ReportModel extends Model
{
    public static function tableName()
    {
        return "xcart_reports";
    }

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::className(),
            ],
            'enabled' => [
                'class' => BooleanField::className(),
                'null' => false,
                'default' => 1,
            ],
            'name' => [
                'class' => CharField::className(),
                'null' => false,
                'verboseName' => 'Filter name',
            ],
            'form_data' => [
                'class' => JsonField::className(),
                'null' => false,
                'verboseName' => 'Filter condition',
            ],
        ];
    }
}