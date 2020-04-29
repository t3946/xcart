<?php


namespace Modules\Admin\Controllers;


use Modules\Admin\AdminModule;
use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Main\Xcart;

class DistributorController extends BackendController
{

    public function index($mid, $section)
    {
        $distributor_sections = [
            [
                'title' => 'General distributor information',
                'order_by' => '10',
                'distributor_section' => '1'
            ],
            [
                'title' => 'Tax policy',
                'order_by' => '90',
                'distributor_section' => '9'
            ],
            [
                'title' => 'Quick links',
                'order_by' => '11',
                'distributor_section' => '15'
            ],
            [
                'title' => 'Return policy',
                'order_by' => '100',
                'distributor_section' => '10'
            ],
            [
                'title' => 'Front-end messages',
                'order_by' => '20',
                'distributor_section' => '2'
            ],
            [
                'title' => 'Product page locked fields',
                'order_by' => '105',
                'distributor_section' => '22'
            ],
            [
                'title' => 'Distributor contacts',
                'order_by' => '30',
                'distributor_section' => '3'
            ],
            [
                'title' => 'Distributor invoices',
                'order_by' => '110',
                'distributor_section' => '13'
            ],
            [
                'title' => 'Distributor pricing equations',
                'order_by' => '50',
                'distributor_section' => '5'
            ],
            [
                'title' => 'Payment to distributor arrangement',
                'order_by' => '120',
                'distributor_section' => '11'
            ],
            [
                'title' => 'Distributor ships from',
                'order_by' => '60',
                'distributor_section' => '6'
            ],
            [
                'title' => 'Product questions',
                'order_by' => '130',
                'distributor_section' => '16'
            ],
            [
                'title' => 'Distributor shipping policy',
                'order_by' => '70',
                'distributor_section' => '7'
            ],
            [
                'title' => 'Distributor feeds info',
                'order_by' => '140',
                'distributor_section' => '17'
            ],
            [
                'title' => 'UPS shipping markups',
                'order_by' => '73',
                'distributor_section' => '19'
            ],
            [
                'title' => 'SF product page behavior',
                'order_by' => '160',
                'distributor_section' => '20'
            ],
            [
                'title' => 'Flat rate shipping markups',
                'order_by' => '74',
                'distributor_section' => '21'
            ],
            [
                'title' => 'Clone distributor to another storefront',
                'order_by' => '170',
                'distributor_section' => '30'
            ],
            [
                'title' => 'Requesting availability / shipping quote / cost to us',
                'order_by' => '75',
                'distributor_section' => '14'
            ],
            [
                'title' => 'External marketplaces',
                'order_by' => '180',
                'distributor_section' => '40'
            ],
            [
                'title' => 'Order submission',
                'order_by' => '80',
                'distributor_section' => '8'
            ],
            [
                'title' => 'Product verification settings',
                'order_by' => '180',
                'distributor_section' => '31'
            ],
            [
                'title' => 'Order tracking',
                'order_by' => '85',
                'distributor_section' => '12'
            ],
        ];

        Xcart::app()->breadcrumbs->add($pageTitle = AdminModule::t('Distributors'));

        $dx = DistributorModel::objects()->get(['manufacturerid' => $mid]);
        $section = $section ?? 1;

        echo $this->renderInSmarty("admin/distributor/dx_{$section}.tpl", [
            'page_title' => $pageTitle,
            'distributorModel' => $dx,
            'section' => $section,
            'count_rows_in_cell' => ceil(count($distributor_sections) / 2),
            'distributor_sections' => $distributor_sections,
        ]);
    }
}