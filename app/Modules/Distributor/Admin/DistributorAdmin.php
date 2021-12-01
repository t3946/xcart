<?php


namespace Modules\Distributor\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Distributor\Forms\DxFilterForm;
use Modules\Distributor\Models\DistributorModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Form;
use Xcart\App\Form\ModelForm;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\QuerySet;
use Xcart\App\QueryBuilder\Expression;
use Xcart\App\QueryBuilder\Q\QOr;

class DistributorAdmin extends Admin
{
    public ?string $order = '-created_at';

    public function getListColumns(): array
    {
        return [
            'manufacturer',
            'code',
            'sites',
            'products',
            'active_products',
            'feed',
            'feed_source',
            'provider',
            'created_at',
            'avail',
        ];
    }

    public function getAvailableListColumns()
    {
        return [
            'manufacturer' => [
                'title' => 'DX Company Name',
            ],
            'code' => [
                'title' => 'DX<br/>Prefix',
            ],
            'sites' => [
                'title' => 'Main SF',
            ],
            'products' => [
                'title' => 'All<br/>SKUs',
            ],
            'active_products' => [
                'title' => "Active<br/>SKUs",
            ],
            'feed' => [
                'title' => 'Feed',
            ],
            'feed_source' => [
                'title' => 'Feed<br/>source',
            ],
            'provider' => [
                'title' => 'Added by',
            ],
            'created_at' => [
                'title' => 'Added on',
            ],
            'avail' => [
                'title' => 'Active',
            ],
        ];
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'manufacturer' :
                return "<a href='{$item->getAdminUrl()}'>$item</a>";
            case 'sites' :
                return implode(
                    '',
                    array_map(
                        static fn(SiteModel $i) => "<div><a title='{$i->getName()}' target='_blank' href='{$i->getAbsoluteUrl()}'>$i->code</a></div>",
                        $item->$property->all()
                    )
                );
            case 'products' :
                return $item->products->filter([new QOr(['productid__isnt' => new Expression('group_root'), 'group_root__isnull' => true])])->count();
            case 'active_products' :
                return $item->products_active->filter([new QOr(['productid__isnt' => new Expression('group_root'), 'group_root__isnull' => true])])->count();
            case 'feed' :
                $i_count = $item->feed_I_E->count();
                $p_count = $item->feed_P_E->count();
                $value = '';
                if ($i_count) {
                    $value .= "I($i_count)";
                }
                if ($p_count) {
                    $value .= "P($p_count)";
                }
                return $value;
            case 'feed_source' :
                $value = '';
                foreach ($item->feeds as $feed) {
                    $field_source = $feed->getField('feed_source');
                    $feed_date = $feed->getField('feed_source_date')->getValue();
                    $date = $feed_date ? "({$feed_date->format('Y-m-d')})" : '';
                    $value .= "{$field_source->toText()}<br/> $date<br/>";
                }
                return $value;
            case 'created_at' :
                return $item->getField('created_at')->getValue()->format('Y-m-d');
            case 'provider' :
                $provider = $item->provider_model;
                return $provider ? $provider->getShortSurname() . "<br/> ({$item->provider})" : '';
        }
        return parent::getItemProperty($item, $property);
    }

    public function getForm(): ?ModelForm
    {
        return null;
    }

    public function getModel()
    {
        return new DistributorModel();
    }

    public static function getName()
    {
        return 'Distributors';
    }

    public function getListItemActions()
    {
        return [];
    }

    public function getFilterForm(): ?Form
    {
        return new DxFilterForm();
    }

    public function getSuggestionColumns()
    {
        return [
            'distributor' => [
                'class' => DistributorModel::class,
                'columns' => [
                    'manufacturer', 'code'
                ],
            ],
        ];
    }

    public function handleFilter($qs, $form)
    {
        if (($dx_field = $form->getField('manufacturer_code')) && $dx_value = trim($dx_field->getValue())) {
            $qs->filter(['manufacturer' => new QOr(['manufacturer__contains' => $dx_value, 'code' => $dx_value])]);
        }

        if (($letter = $form->getField('letter')) && $letter_value = trim($letter->getValue())) {
            $qs->filter(['manufacturer__startswith' => $letter_value]);
        }

        return parent::handleFilter($qs, $form);
    }

    public function getCreateUrl(): string
    {
        return Xcart::app()->router->url('admin:dx_add');
    }

}