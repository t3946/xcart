<?php


namespace Modules\Goods\Forms;


use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Goods\Admin\FeedAdmin;
use Modules\Goods\Models\CategoryModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\NumberField;
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
        if ($feed = $this->getInstance()) {
            $choices[$feed->base_category_id] = (string)$feed->base_category;
        }
        return [
            'feed_name' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:300px;']
            ],
            'distributor' => [
                'class' => Select2Field::class,
                'choices' => static function () {
                    $res[] = '';
                    foreach (DistributorModel::objects()->order(['code']) as $dx) {
                        $res[$dx->manufacturerid] = "[{$dx->code}] {$dx}";
                    }
                    return $res ?? [];
                },
                'html' => ['style' => 'width:300px;']
            ],
            'site' => [
                'class' => Select2Field::class,
                'choices' => static function () {
                    $res[-1] = '';
                    foreach (SiteModel::objects()->order(['code']) as $site) {
                        $res[$site->storefrontid] = $site;
                    }
                    return $res ?? [];
                },
                'html' => ['style' => 'width:300px;']
            ],
            'base_category' => [
                'class' => Select2Field::class,
                'ajaxUrl' => (new FeedAdmin)->getSuggestionUrl('category'),
                'choices' => $choices ?? [],
                //'selected' => $choices ?? [],
                'html' => ['style' => 'width:300px;']
            ],
            'feed_file_name' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:200px;']
            ],
            'threshold' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:100px;']
            ],
            'schedule' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:200px;']
            ]
        ];
    }

    public function getName()
    {
        return 'Feed';
    }

}