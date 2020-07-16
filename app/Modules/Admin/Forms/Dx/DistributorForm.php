<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Form\ModelForm;

class DistributorForm extends ModelForm
{
    public $exclude = ['carriers', 'provider_model', 'site', 'country_model', 'state_model', 'disabled_marketplaces'];

    public $templates = [
        'default' => 'admin/distributor/form/_dx_form.tpl'
    ];
    public $fieldTemplate = 'admin/distributor/form/field.tpl';
    public $hintTemplate = 'admin/distributor/form/hint.tpl';

    public static $distributor_fields = [
        'General' => [
            1 => [
                'title' => 'General distributor information',
                'order_by' => '10',
                'distributor_section' => '1',
                'form' => DistributorGeneralForm::class
            ],
            15 => [
                'title' => 'Quick links',
                'order_by' => '11',
                'distributor_section' => '15',
                'form' => DistributorQuickLinksForm::class,
            ],
        ],
        'Communication with distributor' => [
            3 => [
                'title' => 'Distributor contacts',
                'order_by' => '30',
                'distributor_section' => '3',
                'form' => DistributorContactForm::class,
            ],
            50 => [
                'title' => 'Communication with distributor',
                'order_by' => '40',
                'distributor_section' => '50',
                'form' => DistributorContactForm::class,
            ],
        ],
        'Front-end settings' => [
            2 => [
                'title' => 'Front-end product page behavior',
                'order_by' => '20',
                'distributor_section' => '2',
                'form' => DistributorFrontEndMessagesForm::class,
            ],
        ],
        'Product and inventory management' => [
            5 => [
                'title' => 'Distributor pricing equations',
                'order_by' => '50',
                'distributor_section' => '5',
                'form' => DistributorPriceForm::class
            ],
            22 => [
                'title' => 'Product page locked fields',
                'order_by' => '105',
                'distributor_section' => '22'
            ],
            16 => [
                'title' => 'Product questions',
                'order_by' => '130',
                'distributor_section' => '16',
                'form' => DistributorProductQuestionsForm::class,
            ],
            31 => [
                'title' => 'Product verification settings',
                'order_by' => '180',
                'distributor_section' => '31',
                'form' => DistributorProductVerificationForm::class,
            ],
            17 => [
                'title' => 'Distributor feeds info',
                'order_by' => '140',
                'distributor_section' => '17',
                'form' => DistributorFeedInfoForm::class
            ],
            40 => [
                'title' => 'External marketplaces',
                'order_by' => '180',
                'distributor_section' => '40',
                'form' => DistributorExcludedMarketplacesForm::class,
            ],
            51 => [
                'title' => 'Questionable products',
                'order_by' => '180',
                'distributor_section' => '51',
                'form' => DistributorQuestionableProductsForm::class,
            ],
        ],
        'Submitting order to distributor' => [
            14 => [
                'title' => 'Requesting availability / shipping quote / cost to us',
                'order_by' => '75',
                'distributor_section' => '14',
                'form' => DistributorRequestAvailForm::class
            ],
            8 => [
                'title' => 'Order submission',
                'order_by' => '80',
                'distributor_section' => '8',
                'form' => DistributorOrderSubmissionForm::class
            ],
        ],
        'Shipping order to customer' => [
            6 => [
                'title' => 'Distributor ships from',
                'order_by' => '60',
                'distributor_section' => '6',
                'form' => DistributorShippesFromForm::class,
            ],
            7 => [
                'title' => 'Distributor shipping policy',
                'order_by' => '70',
                'distributor_section' => '7',
                'form' => DistributorShippingPolicyForm::class
            ],
            19 => [
                'title' => 'Shipping server markups',
                'order_by' => '73',
                'distributor_section' => '19'
            ],
            21 => [
                'title' => 'Flat rate shipping markups',
                'order_by' => '74',
                'distributor_section' => '21'
            ],
            12 => [
                'title' => 'Order tracking',
                'order_by' => '85',
                'distributor_section' => '12',
                'form' => DistributorOrderTrackingForm::class,
            ],
        ],

        'Product returns' => [
            10 => [
                'title' => 'Return policy',
                'order_by' => '100',
                'distributor_section' => '10'
            ],
        ],


        'Accounting' => [
            13 => [
                'title' => 'Distributor invoices',
                'order_by' => '110',
                'distributor_section' => '13',
                'form' => DistributorInvoiceForm::class
            ],
            11 => [
                'title' => 'Payment to distributor arrangement',
                'order_by' => '120',
                'distributor_section' => '11',
                'form' => DistributorPaymentToDxForm::class,
            ],
            9 => [
                'title' => 'Tax policy',
                'order_by' => '90',
                'distributor_section' => '9'
            ],
        ],

        'Bulk operations' => [
            30 => [
                'title' => 'Clone distributor to another storefront',
                'order_by' => '170',
                'distributor_section' => '30'
            ],
        ],

    ];

    public function getSections() {
        return self::$distributor_fields;
    }

    public static function getSection($section = null)
    {
        if ($section !== null) {
            foreach (self::$distributor_fields as $f) {
                foreach ($f as $k => $a) {
                    if ($k == $section) {
                        return $a;
                    }
                }
            }
        }

        return null;
    }

    public function getDx()
    {
        return $this->getInstance();
    }

    public function getModel()
    {
        return new DistributorModel();
    }
}