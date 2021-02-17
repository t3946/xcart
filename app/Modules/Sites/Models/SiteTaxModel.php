<?php

namespace Modules\Sites\Models;

use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class SiteTaxModel extends Model
{
    public static function tableName(): string
    {
        return 'sites_taxes';
    }

    public static function getFields(): array
    {
        return [
            'site' => [
                'field' => 'site_id',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => [
                    'site_id' => 'storefrontid'
                ],
            ],
            'tax' => [
                'field' => 'tax_id',
                'class' => ForeignField::class,
                'modelClass' => TaxModel::class,
                'link' => [
                    'tax_id' => 'taxid'
                ]
            ]
        ];
    }
}
