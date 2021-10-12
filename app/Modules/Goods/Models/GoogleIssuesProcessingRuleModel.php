<?php

namespace Modules\Goods\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class GoogleIssuesProcessingRuleModel extends Model
{
    public static function tableName()
    {
        return 'xcart_cidev_issues_processing_rules';
    }

    public static function getFields()
    {
        return [
            'issue_id' => AutoField::class,
            'issue_gmc_id' => CharField::class,
            'issue_processing' => [
                'class' => CharField::class,
                'choices' => [
                    'exclude' => 'Exclude',
                    'manual' => 'Manual',
                    'skip' => 'Skip'
                ],
                'default' => 'manual'
            ],
            'servability' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ],
            'issue_description' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null
            ]
        ];
    }
}