<?php
namespace Xcart\App\Orm\Fields;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class JsonField extends TextField
{

    public function convertToPHPValueSQL($value, AbstractPlatform $platform)
    {
        if (is_string($value)) {
            return json_decode($value, true);
        }

        return parent::convertToPHPValueSQL($value, $platform);
    }
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (is_string($value)) {
            return json_decode($value, true);
        }

        return parent::convertToPHPValue($value, $platform);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!is_string($value)) {
            $value = json_encode($value);
        }
        return parent::convertToDatabaseValue($value, $platform);
    }

}