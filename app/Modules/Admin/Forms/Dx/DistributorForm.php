<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Form\ModelForm;
use Xcart\App\Main\Xcart;

class DistributorForm extends ModelForm
{
    public array $exclude = ['carriers', 'provider_model', 'site', 'country_model', 'state_model', 'disabled_marketplaces', 'taxes'];

    public $templates = [
        'default' => 'admin/distributor/form/_dx_form.tpl'
    ];
    public $fieldTemplate = 'admin/distributor/form/field.tpl';
    public $hintTemplate = 'admin/distributor/form/hint.tpl';

    public static array $sections = [
        'General' => [
            1 => [
                'title' => 'General distributor information',
                'form' => DistributorGeneralForm::class,
                'required' => true,
            ],
        ],
        'Communication with distributor' => [
            3 => [
                'title' => 'Distributor contacts',
                'form' => DistributorContactsForm::class,
                'required' => true,
            ],
            50 => [
                'title' => 'Communication with distributor',
                'form' => DistributorContactsForm::class,
                'hidden' => true
            ],
        ],
        'Front-end settings' => [
            2 => [
                'title' => 'Front-end product page behavior',
                'form' => DistributorFrontEndMessagesForm::class,
                'required' => true,
            ],
        ],
        'Product and inventory management' => [
            5 => [
                'title' => 'Distributor pricing',
                'form' => DistributorPriceForm::class,
                'required' => true,
            ],
            15 => [
                'title' => 'Upload file pricing',
                'form' => DistributorPriceForm::class,
                'required' => true,
                'hidden' => true,
            ],
            22 => [
                'title' => 'Product page locked fields',
                'hidden' => true
            ],
            31 => [
                'title' => 'Product verification settings',
                'form' => DistributorProductVerificationForm::class,
                'hidden' => true
            ],
            17 => [
                'title' => 'Distributor feeds info',
                'form' => DistributorFeedInfoForm::class,
                'hidden' => true
            ],
            40 => [
                'title' => 'Forbidden API interactions',
                'form' => DistributorExcludedMarketplacesForm::class,
                'required' => true,
            ],
            51 => [
                'title' => 'Questionable products',
                'form' => DistributorQuestionableProductsForm::class,
                'required' => true,
            ],
        ],
        'Submitting order to distributor' => [
            14 => [
                'title' => 'Requesting availability / cost to us / shipping cost',
                'form' => DistributorRequestAvailForm::class,
                'required' => true,
            ],
            8 => [
                'title' => 'Order submission',
                'form' => DistributorOrderSubmissionForm::class,
                'required' => true,
            ],
        ],
        'Shipping order to customer' => [
            6 => [
                'title' => 'Distributor ships from',
                'form' => DistributorShippesFromForm::class,
                'required' => true,
            ],
            7 => [
                'title' => 'Distributor shipping policy',
                'form' => DistributorShippingPolicyForm::class,
                'required' => true,
            ],
            19 => [
                'title' => 'Shipping server markups',
                'hidden' => true
            ],
            21 => [
                'title' => 'Flat rate shipping markups',
                'hidden' => true
            ],
            12 => [
                'title' => 'Order tracking',
                'form' => DistributorOrderTrackingForm::class,
                'required' => true,
            ],
        ],
        'Product returns' => [
            10 => [
                'title' => 'Return policy',
                'form' => DistributorReturnPolicyForm::class,
                'required' => true,
            ],
        ],
        'Accounting' => [
            13 => [
                'title' => 'Distributor invoices',
                'form' => DistributorInvoiceForm::class,
                'required' => true,
            ],
            11 => [
                'title' => 'Payment to distributor arrangement',
                'form' => DistributorPaymentToDxForm::class,
                'required' => true,
            ],
            9 => [
                'title' => 'Taxes charged by distributor',
                'form' => DistributorTaxForm::class,
                'required' => true,
            ],
        ],
    ];

    public static function getSection($section = null)
    {
        if ($section !== null) {
            foreach (static::getSections() as $f) {
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

    public static function getSections(): array
    {
        $vrs = Xcart::app()->user->hasRoles(['vrs', 'vrv']);
        foreach (parent::getSections() as $key => $section) {
            $res[$key] = array_filter($section, static fn($s) => !(($vrs === true) && $s['hidden'] ?? false === true));
        }
        return $res ?? [];
    }

    /**
     * переделать ассоциативный массив секций в обычный, чтобы при переводе в json порядок не нарушался
     * @param callable|null $map позволяет модифицировать элементы массива
    */
    public static function getSectionsArray(?callable $map): array
    {
        $sections = self::getSections();
        $array = [];

        foreach ($sections as $name => $sub_sections_list) {
            $item = [
                'name' => $name,
                'sub_sections' => [],
            ];

            foreach ($sub_sections_list as $key => $sub_section) {
                $sub_section['key'] = $key;

                if ($map){
                    $map($sub_section);
                }

                $item['sub_sections'][] = $sub_section;
            }


            $array[] = $item;
        }

        return $array;
    }
}