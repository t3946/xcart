<?php


namespace Modules\Forms\Models;


use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class EmailLabelModel extends Model
{
    public static function getFields()
    {
        return [
            'label' => [
                'field' => 'label_id',
                'class' => ForeignField::class,
                'modelClass' => LabelModel::class,
                'link' => ['label_id' => 'id'],
                'primary' => true,
            ],
            'email' => [
                'field' => 'email_id',
                'class' => ForeignField::class,
                'modelClass' => EmailModel::class,
                'link' => ['email_id' => 'id'],
                'primary' => true,
            ],
        ];
    }
}