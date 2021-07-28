<?php
namespace Modules\Goods\Models;

use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BlobField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\FileField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;
use Xcart\Product;

/**
 * @property mixed image_path
 */
class ImageModel extends Model
{
    public static function tableName()
    {
        return 'xcart_images';
    }

    public static function getFields()
    {
        return [
            'imageid' => [
                'class' => AutoField::class,
            ],
            'id' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
            ],
            'image' => [
                'class' => BlobField::class,
                'null' => false,
                'default' => '',
            ],
            'image_path' => [
                'class' => FileField::class,
                'adapterName' => 'www',
                'null' => false,
                'default' => '',
            ],
            'image_type' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'image_x' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
            ],
            'image_y' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
            ],
            'image_size' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
            ],
            'filename' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'date' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
            ],
            'alt' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],
            'avail' => [
                'class' => CharField::class,
                'null' => false,
                'default' => 'Y',
            ],
            'orderby' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 0,
            ],
            'md5' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
            ],
        ];
    }

    public function getURL($width = null)
    {
        return ltrim($this->image_path, '.');
    }

    public function getCdnURL($width = null): string
    {
        $site = Xcart::app()->getModule('Sites')->getSite();
        $pref = $site->Enable_CDN ? 'cdn.': 'www.';
        $domain = $site->getBaseDomain();
        return '//' .$pref . $domain . $this->getURL($width);
    }

    public function __toString()
    {
        return $this->getCdnURL();
    }
}