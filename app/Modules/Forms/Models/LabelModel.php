<?php


namespace Modules\Forms\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

/**
 * @property string|null type
 * @property int id
 * @property string label_id
 * @property string name
 * @property string background_color
 * @property string color
 */
class LabelModel extends Model
{
    public const LABEL_TYPE_SYSTEM = 'system';
    public const LABEL_TYPE_USER = 'user';

    public static function getFields()
    {
        return [
            'id' => [
                'class' => AutoField::class,
            ],
            'label_id' => [
                'class' => CharField::class,
                'unique' => true,
                'verboseName' => "LabelId",
                'required' => true,
            ],
            'name' => [
                'class' => CharField::class,
                'verboseName' => "Name",
                'required' => true,
            ],
            'background_color' => [
                'class' => CharField::class,
                'verboseName' => "Background Color",
            ],
            'color' => [
                'class' => CharField::class,
                'verboseName' => "Color",
            ],
            'type' => [
                'class' => CharField::class,
                'verboseName' => "Type",
                'choices' => [
                    'system' => 'System',
                    'user' => 'User',
                ],
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}