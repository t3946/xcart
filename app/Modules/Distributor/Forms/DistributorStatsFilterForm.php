<?php
namespace Modules\Distributor\Forms;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Form;

class DistributorStatsFilterForm extends Form
{
    public function getFields(): array
    {
        return [
            'manufacturer_code' => [
                'class' => CharField::class,
                'label' => 'Dx name',
                'html' => [
                    'style' => 'width: 300px',
                ],
            ],
            'stats_period' => [
                'class' => Select2Field::class,
                'label' => 'Period stats',
                'choices' => [
                    '',
                    '7' => '7 Days',
                    '14' => '14 Days',
                    '30' => '30 Days'
                ],
                'html' => [
                    'style' => 'width: 300px',
                ],
            ],
            'avail' => [
                'class' => Select2Field::class,
                'label' => 'Active',
                'choices' => [
                    '' => 'All',
                    'Y' => 'Y',
                    'N' => 'N',
                ],
                'html' => [
                    'style' => 'width: 300px',
                ],
            ],
            'sites' => [
                'class' => Select2Field::class,
                'label' => 'Main SF',
                'html' => [
                    'style' => 'width: 300px',
                ],
                'multiple' => true,
                'choices' => function () {
                    foreach (SiteModel::objects()->order(['code']) as $site) {
                        $sites[$site->pk] = (string)$site;
                    }
                    return $sites ?? [];
                }
            ],
        ];
    }
}