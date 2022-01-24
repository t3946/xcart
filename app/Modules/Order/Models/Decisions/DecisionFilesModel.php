<?php

namespace Modules\Order\Models\Decisions;

use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\FileField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class DecisionFilesModel extends Model
{
    public const UPLOAD_PATH = "user_files";
    public const UPLOAD_MAX_SIZE = "";

    public static function tableName()
    {
        return 'decision_files';
    }

    public static function getFields()
    {
        return [
            'file_id' => [
                'class' => IntField::class,
            ],

            'decision_id' => [
                'class' => IntField::class,
            ],

            'path' => [
                'class' => FileField::class,
                'required' => false,
                'null' => true,
                'adapterName' => 'www',
                'uploadTo' => rtrim(self::UPLOAD_PATH, '/') . '/%Y/%m/%d',
                'maxSize' => self::UPLOAD_MAX_SIZE . 'M',
            ],

            'title' => [
                'class' => CharField::class,
            ],

            'created' => [
                'class' => DateTimeField::class,
            ],
        ];
    }

    /**
     * save image and link
     */
    public function saveImage(int $linked_entity_id, array $image_attributes)
    {
        $image = new ImagesModel($image_attributes);
        $image->save();

        $attributes = [
            'review_id' => $linked_entity_id,
            'image_id' => (int)$image->pk,
        ];

        $this->setAttributes($attributes);
        $this->save();
    }
}
