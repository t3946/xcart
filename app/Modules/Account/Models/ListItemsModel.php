<?php


namespace Modules\Account\Models;


use Modules\Goods\Models\ProductModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\TimestampField;
use Xcart\App\Orm\Model;

/**
 * Class ListItemsModel
 * @property int list_items_id
 * @property ProductModel product
 * @property ProductListsModel list
 * @property int product_id
 * @property string comment
 * @property int product_list_id
 * @property string product_type
 * @property int order_by
 * @property string priority
 * @property string needs
 * @property string has
 * @property ListIdeaModel idea
 * @package Modules\Account\Models
 */
class ListItemsModel extends Model
{
    public const TYPE_IDEA = 'idea';
    public const TYPE_PRODUCT = 'product';

    public static function tableName(): string
    {
        return 'account_list_items';
    }

    public static function getFields(): array
    {
        return [
            'list_items_id' => [
                'class' => AutoField::class,
            ],
            'product' => [
                'field' => 'product_id',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['product_id' => 'productid'],
            ],
            'idea' => [
                'field' => 'product_id',
                'class' => ForeignField::class,
                'modelClass' => ListIdeaModel::class,
                'link' => ['product_id' => 'product_id'],
            ],
            'list' => [
                'field' => 'product_list_id',
                'class' => ForeignField::class,
                'modelClass' => ProductListsModel::class,
                'link' => ['product_lists_id' => 'product_lists_id'],
            ],
            'order_by' => [
                'class' => IntField::class,
                'default' => 999999,
            ],
            'product_type' => [
                'class' => CharField::class,
            ],
            'comment' => [
                'class' => CharField::class,
            ],
            'priority' => [
                'class' => CharField::class,
            ],
            'needs' => [
                'class' => CharField::class,
            ],
            'has' => [
                'class' => CharField::class,
            ],
            'add_date' => [
                'class' => TimeStampField::class,
            ],
        ];
    }

    public function getFrontendData(): array
    {
        $base_data = [
            'comment' => $this->comment,
            'priority' => $this->priority,
            'has' => $this->has,
            'needs' => $this->needs,
            'orderBy' => $this->order_by,
            'productType' => $this->product_type,
            'productId' => (int)$this->product_id,
            'add_date'=> $this->add_date,
            'list_items_id' => (int)$this->pk
        ];
        switch ($this->product_type) {
            case self::TYPE_IDEA:
                $product_model = $this->idea;
                $base_data = array_merge($base_data, [
                    'product' => [
                        'productId' => $product_model->pk,
                        'name' => $product_model->name,
                    ]
                ]);
                break;
            case self::TYPE_PRODUCT:
                $product_model = $this->product;
                $base_data = array_merge($base_data, [
                    'product' => [
                        'product' => $product_model->product,
                        'code' => $product_model->productcode,
                        'productId' => (int)$product_model->pk,
                        'costToUs' => $product_model->cost_to_us,
                        'price' => $product_model->getPrice(),
                        'image' => (string)$product_model->getMainImage(),
                        'minAmount' => $product_model->min_amount,
                        'multOrderQuantity' => $product_model->mult_order_quantity,
                        'outOfStock' => $product_model->r_avail === 0,
                        'ratings' => $product_model->getRatings(),
                    ]
                ]);
                break;
        }
        return $base_data;
    }
}