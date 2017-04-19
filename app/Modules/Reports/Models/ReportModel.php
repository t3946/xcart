<?php
namespace Modules\Reports\Models;

use Xcart\App\Main\Xcart;
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
                'verboseName' => 'Report name',
            ],
            'form_data' => [
                'class' => JsonField::className(),
                'null' => false,
                'verboseName' => 'Report condition',
            ],
        ];
    }

    public function getUrl()
    {
        return Xcart::app()->router->url('reports:load', ['id' => $this->id]);
    }

    public function getAdminUrl()
    {
        if ($this->isNewRecord) {
            return Xcart::app()->router->url('reports:create_report');
        }
        else {
            return Xcart::app()->router->url('reports:update_report', ['id' => $this->id]);
        }
    }
}