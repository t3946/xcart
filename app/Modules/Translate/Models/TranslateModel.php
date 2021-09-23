<?php

namespace Modules\Translate\Models;

use Gettext\Translation;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class TranslateModel extends Model
{
    public static function getFields()
    {
        return [
            'lang_code' => CharField::class,
            'msgctxt' => CharField::class,
            'msgid' => CharField::class,
            'msgstr' => CharField::class,
            'id' => CharField::class,
        ];
    }

    public static function getPrimaryKeyName( $asArray = false )
    {
        return 'id';
    }

    public function asTranslation(): Translation
    {
        $attrs = $this->getAttributes();
        $translation = Translation::create( $attrs[ 'msgctxt' ], $attrs[ 'msgid' ] );
        $translation->setTranslation( $attrs[ 'msgstr' ] );

        return $translation;
    }

    public function __toString()
    {
        return (string)$this->getAttribute( 'msgctxt' );
    }
}
