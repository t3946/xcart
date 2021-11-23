<?php

namespace Modules\Distributor\Helpers;


use League\Flysystem\FileNotFoundException;
use Modules\Core\Classes\GoogleDrive;
use Modules\Core\Models\CountryModel;
use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Exceptions\Exception;

class DistributorHelper
{
    /**
     * @param int $manufacturerid
     * @return CountryModel[]
     */
    public static function getShippingCountries($manufacturerid)
    {
        return CountryModel::objects()
            ->filter(['zone_element__shipping_rates__manufacturerid' => $manufacturerid])
            ->group(['code'])
            ->all();
    }

    /**
     * get Distributor emails by unity type
     * @param DistributorModel $dx
     * @param int $unity_type
     * @return array
     */
    public static function getDistributorEmails(DistributorModel $dx, int $unity_type): array
    {
        $to = $dx->contacts_model->filter([
            'utility__utility_id' => $unity_type
        ])->valuesList(['email'], true);

        $to = array_unique(array_map('trim', $to));

        return $to;
    }

    /**
     * @throws FileNotFoundException
     * @throws Exception
     */
    public static function getResourceGooglePriceFile(string $path): array
    {
        $google_drive = new GoogleDrive();
        $file = $google_drive->file_system->readStream($path);
        if (!is_resource($file)) {
            throw new Exception('Failed read file with google drive');
        }
        $info_file = $google_drive->file_system->getMetadata($path);
        return [$file, $info_file];
    }
}