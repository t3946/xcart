<?php


namespace Modules\Admin\Controllers;


use Modules\Admin\AdminModule;
use Modules\Admin\Forms\Dx\DistributorFrontEndMessagesForm;
use Modules\Admin\Forms\Dx\DistributorGeneralForm;
use Modules\Admin\Forms\Dx\DistributorPaymentToDxForm;
use Modules\Admin\Forms\Dx\DistributorPriceForm;
use Modules\Admin\Forms\Dx\DistributorQuickLinksForm;
use Modules\Admin\Forms\Dx\DistributorShippesFromForm;
use Modules\Admin\Forms\Dx\DistributorShippingPolicyForm;
use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Main\Xcart;

class DistributorController extends BackendController
{

    public function index($mid, $section)
    {
        $distributor_sections = [
            1 => [
                'title' => 'General distributor information',
                'order_by' => '10',
                'distributor_section' => '1',
                'form' => DistributorGeneralForm::class
            ],
            9 => [
                'title' => 'Tax policy',
                'order_by' => '90',
                'distributor_section' => '9'
            ],
            15 => [
                'title' => 'Quick links',
                'order_by' => '11',
                'distributor_section' => '15',
                'form' => DistributorQuickLinksForm::class,
            ],
            10 => [
                'title' => 'Return policy',
                'order_by' => '100',
                'distributor_section' => '10'
            ],
            2 => [
                'title' => 'Front-end messages',
                'order_by' => '20',
                'distributor_section' => '2',
                'form' => DistributorFrontEndMessagesForm::class,
            ],
            22 => [
                'title' => 'Product page locked fields',
                'order_by' => '105',
                'distributor_section' => '22'
            ],
            3 => [
                'title' => 'Distributor contacts',
                'order_by' => '30',
                'distributor_section' => '3'
            ],
            13 => [
                'title' => 'Distributor invoices',
                'order_by' => '110',
                'distributor_section' => '13'
            ],
            5 => [
                'title' => 'Distributor pricing equations',
                'order_by' => '50',
                'distributor_section' => '5',
                'form' => DistributorPriceForm::class
            ],
            11 => [
                'title' => 'Payment to distributor arrangement',
                'order_by' => '120',
                'distributor_section' => '11',
                'form' => DistributorPaymentToDxForm::class,
            ],
            6 => [
                'title' => 'Distributor ships from',
                'order_by' => '60',
                'distributor_section' => '6',
                'form' => DistributorShippesFromForm::class,
            ],
            16 => [
                'title' => 'Product questions',
                'order_by' => '130',
                'distributor_section' => '16'
            ],
            7 => [
                'title' => 'Distributor shipping policy',
                'order_by' => '70',
                'distributor_section' => '7',
                'form' => DistributorShippingPolicyForm::class
            ],
            17 => [
                'title' => 'Distributor feeds info',
                'order_by' => '140',
                'distributor_section' => '17'
            ],
            19 => [
                'title' => 'UPS shipping markups',
                'order_by' => '73',
                'distributor_section' => '19'
            ],
            20 => [
                'title' => 'SF product page behavior',
                'order_by' => '160',
                'distributor_section' => '20'
            ],
            21 => [
                'title' => 'Flat rate shipping markups',
                'order_by' => '74',
                'distributor_section' => '21'
            ],
            30 => [
                'title' => 'Clone distributor to another storefront',
                'order_by' => '170',
                'distributor_section' => '30'
            ],
            14 => [
                'title' => 'Requesting availability / shipping quote / cost to us',
                'order_by' => '75',
                'distributor_section' => '14'
            ],
            40 => [
                'title' => 'External marketplaces',
                'order_by' => '180',
                'distributor_section' => '40'
            ],
            8 => [
                'title' => 'Order submission',
                'order_by' => '80',
                'distributor_section' => '8'
            ],
            31 => [
                'title' => 'Product verification settings',
                'order_by' => '180',
                'distributor_section' => '31'
            ],
            12 => [
                'title' => 'Order tracking',
                'order_by' => '85',
                'distributor_section' => '12'
            ],
        ];

        Xcart::app()->breadcrumbs->add($pageTitle = AdminModule::t('Distributors'));

        $dx = DistributorModel::objects()->get(['manufacturerid' => $mid]);
        $section = $section ?? 1;

        $form = new $distributor_sections[$section]['form'];
        $form->setInstance($dx);

        $form->setAttributes(array_merge($dx->getAttributes(), [
            'd_sites' => $dx->sites,
            'distributor_carrier' => $dx->carriers
        ]));

        echo $this->renderInSmarty("admin/distributor/dx_{$section}.tpl", [
            'page_title' => $pageTitle,
            'distributorModel' => $dx,
            'form' => $form,
            'section' => $section,
            'count_rows_in_cell' => ceil(count($distributor_sections) / 2),
            'distributor_sections' => $distributor_sections,
        ]);
    }
}