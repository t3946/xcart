<?php


namespace Modules\Goods\Forms;


use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Goods\Admin\FeedAdmin;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\NumberField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;

class FeedForm extends ModelForm
{
    public array $exclude = [
        'last_feed_fields',
        'feed_source',
        'last_update_time',
        'feed_source_date',
        'process_time',
        'base_category'
    ];

    public function getModel()
    {
        return new SupplierFeedModel;
    }

    public function getFields()
    {
        $feed = $this->getInstance();

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
                'html' => [
                    'style' => 'width:300px;',
                ],
            ],
            'site' => [
                'class' => Select2Field::class,
                'choices' => static function () {
                    $res[-1] = '';
                    foreach (SiteModel::objects()->exclude(['code' => 'TA'])->order(['code']) as $site) {
                        $res[$site->storefrontid] = $site;
                    }
                    return $res ?? [];
                },
                'html' => [
                    'style' => 'width:300px;',
                ],
            ],
            'dont_update_fields' => [
                'class' => Select2Field::class,
                'choices' => [
                    'productcode' => 'SKU',
                    'product' => 'Product Name',
                    'fulldescr' => 'Description',
                    'supplier_images' => 'Images',
                    'cost_to_us' => 'Cost To Us',
                    'list_price' => 'List Price',
                    'min_amount' => 'Min Order Amount',
                    'brand_name' => 'Brand name',
                    'brand_normalized' => 'Brand normalized',
                    'r_avail' => 'Avail',
                ],
                'selected' => $feed->dont_update_fields,
                'html' => [
                    'style' => 'width:300px;',
                ],
                'multiple' => true,
            ],
            'feed_file_name' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:200px;']
            ],
            'threshold' => [
                'class' => CharField::class,
                'html' => ['style' => 'width:100px;']
            ],
        ];
    }

    public function getName()
    {
        return 'Feed';
    }

}