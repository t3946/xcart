<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 24.07.2018
 * Time: 16:09
 */

namespace Modules\Order\Forms;

use Xcart\App\Form\Fields\TextField;
use Modules\Order\OrderModule;


use Modules\Core\Forms\FrontendForm;

class CustomerNotesForm extends FrontendForm
{
    public function getFields(){
        return [
            'customer_notes' => [
                'class' => TextField::class,
                'label' => OrderModule::t('Customer notes'),
                'className' => 'wide_footer',
                'html' => [
                    'placeholder' => 'Put order related instructions here',
                ],
            ],
        ];
    }

    public function getFieldsets()
    {
        return [
            'notes' => ['customer_notes']
        ];
    }
}