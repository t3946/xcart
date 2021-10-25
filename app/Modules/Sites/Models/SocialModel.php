<?php

namespace Modules\Sites\Models;

use Modules\Sites\Models\SiteModel;
use Modules\Sites\Models\TaxModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\ImageField;
use Xcart\App\Orm\Model;
use Xcart\App\Storage\FileNameHasher\MD5FileContentHasher;

/**
 * @property int social_id
 * @property string type
 * @property ImageField|null logo_path
 * @property string url
 */
class SocialModel extends Model
{

    public static function tableName(): string
    {
        return 'xcart_socials';
    }
    public function getLogoPath() : string
    {
        $type = strtolower($this->type);
        $path = $this->logo_path->getValue() ?? "logo/social_networks/$type.svg";
        return "https://i1.s3stores.com/$path";
    }
    public function __toString()
    {
        return $this->getIsNewRecord() ? 'social network' : $this->type;
    }

    public static function getFields(): array
    {
        return [
            'social_id' => AutoField::class,
            'type' => [
                'class' => CharField::class,
                'requires' => true,
                'verboseName' => 'Type social networks'
            ],
            'logo_path' => [
                'class' => ImageField::class,
                'adapterName' => 's3',
                'verboseName' => 'Logo file',
                'uploadTo' => "logo/social_networks",
                'nameHasher' => MD5FileContentHasher::class,
                'null' => true,
                'default' => null
            ],
            'url' => [
                'class' => CharField::class,
                'requires' => true,
                'verboseName' => 'Url'
            ],
        ];
    }
}