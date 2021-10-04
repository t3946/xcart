<?php

namespace Modules\Goods\Models;

use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class GoogleProductQualityIssueModel extends Model
{
    public static function tableName()
    {
        return 'xcart_cidev_gmc_quality_issues';
    }

    public static function getFields()
    {
        return [
            'product' => [
                'field' => 'productid',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['productid' => 'productid'],
                'primary' => true
            ],
            'issue' => [
                'field' => 'issue_id',
                'class' => ForeignField::class,
                'modelClass' => GoogleIssuesProcessingRuleModel::class,
                'link' => ['issue_id' => 'issue_id'],
                'primary' => true
            ],
            'name' => CharField::class,
            'fixed' => [
                'class' => BooleanCharField::class,
                'default' => false
            ]
        ];
    }
}