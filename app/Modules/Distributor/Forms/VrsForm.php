<?php

namespace Modules\Distributor\Forms;

use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DateField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Fields\TextAreaField;
use Xcart\App\Form\ModelForm;
use \Modules\Distributor\Models\VrsModel;
use Xcart\App\Main\Xcart;

class VrsForm extends ModelForm
{
    public function getModel()
    {
        return new VrsModel();
    }
    public function getName()
    {
        return 'Edit VRS Team';
    }
    public function getFieldsets()
    {
        return [[
            'sf',
            'company',
            'link_website',
            'last_action',
            'status',
            'date',
            'email',
            'telephone',
            'login',
            'password',
            'user_added'
        ]];
    }

    public function getFields()
    {
        $dx = $this->getInstance();
        $user = $dx->user ?: Xcart::app()->user;
        return [
            'sf' => [
                'class' => Select2Field::class,
                'label' => 'SF',
                'html' => [
                    'style' => 'width: 300px',
                ],
                'inline_editor' => true,
            ],
            'status' => [
                'class' => Select2Field::class,
                'label' => 'Status',
                'html' => [
                    'style' => 'width: 300px',
                ],
                'inline_editor' => true,
            ],
            'date' => [
                'class' => DateField::class,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'user_added' => [
                'class' => CharField::class,
                'html' => [
                    'style' => 'border: none; width: 300px',
                    'readonly' => true,
                ],
                'label' => 'Added by',
                'value' => "$user->login ($user->firstname)",
            ],
            'link_website' => [
                'class' => CharField::class,
                'html' => [
                    'style' => 'width: 300px'
                ],
            ],
            'last_action' => [
                'class' => TextAreaField::class,
                'html' => [
                    'style' => 'width: 300px'
                ],
            ],
            'company' => [
                'class' => CharField::class,
                'html' => [
                    'style' => 'width: 300px'
                ],
            ],
            'email' => [
                'class' => CharField::class,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'telephone' => [
                'class' => CharField::class,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'login' => [
                'class' => CharField::class,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'password' => [
                'class' => CharField::class,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
        ];
    }

    public function isValid()
    {
        if ($this->getInstance()->getIsNewRecord()) {
            $this->getField('user')->setValue(Xcart::app()->user);
        }
        return parent::isValid();
    }
}