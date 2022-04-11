<?php

namespace Modules\Order\Models\Decisions;

use Modules\Forms\Models\TemplateModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\JsonField;
use Xcart\App\Orm\Model;

class DecisionTypeTemplateModel extends Model
{
    public static function tableName()
    {
        return 'decision_types_templates';
    }

    public static function getFields()
    {
        return [
            'decision_types_templates_id' => AutoField::class,
            'decision_type' => [
                'field' => 'decision_type_id',
                'class' => ForeignField::class,
                'modelClass' => DecisionTypeModel::class,
                'link' => ['decision_type_id' => 'decision_type_id']
            ],
            'action' => CharField::class,
            'template' => [
                'field' => 'xcart_templates_for_communication_id',
                'class' => ForeignField::class,
                'modelClass' => TemplateModel::class,
                'link' => ['xcart_templates_for_communication_id' => 'id']
            ],
            'template_data' => JsonField::class
        ];
    }
}