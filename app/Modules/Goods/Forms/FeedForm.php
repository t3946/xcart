<?php


namespace Modules\Goods\Forms;


use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;

class FeedForm extends ModelForm
{
    public $exclude = [
        'last_feed_fields',
        'feed_source',
        'last_update_time',
        'feed_source_date',
    ];

    public function getModel()
    {
        return new SupplierFeedModel;
    }

    public function getFields()
    {
        return [
            'distributor' => [
                'class' => Select2Field::class,
                'choices' => static function() {
                    foreach (DistributorModel::objects()->order(['code']) as $dx) {
                        $res[$dx->manufacturerid] = "[{$dx->code}] {$dx}";
                    }
                    return $res ?? [];
                }
            ],
            'site' => [
                'class' => Select2Field::class,
                'choices' => static function() {
                    foreach (SiteModel::objects()->order(['code']) as $site) {
                        $res[$site->storefrontid] = $site;
                    }
                    return $res ?? [];
                }
            ]
        ];
    }

}