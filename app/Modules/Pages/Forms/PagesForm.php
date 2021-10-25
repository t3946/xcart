<?php

namespace Modules\Pages\Forms;

use Modules\Editor\Fields\EditorField;
use Modules\Meta\Forms\MetaInlineForm;
use Modules\Pages\Models\Page;
use Modules\Pages\PagesModule;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DateTimeField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Fields\TextAreaField;
use Xcart\App\Form\ModelForm;

/**
 * Class PagesForm
 * @package Modules\Pages
 */
class PagesForm extends ModelForm
{
    public function getFieldsets()
    {
        return [
            'Main information' => [
                'name', 'url', 'parent', 'is_index', 'no_index', 'is_published',
            ],
            'Content' => [
                'content_short', 'content',
            ],
            'Additional' => [
                'published_at', 'file', 'sites',
            ],
            'Display settings' => [
                'view', 'view_children', 'sorting', 'language',
            ],
        ];
    }

    public function getFields()
    {
        return [
            'is_index' => CheckboxField::class,
            'no_index' => CheckboxField::class,
            'is_published' => CheckboxField::class,
            'content_short' => [
                'class' => TextAreaField::class,
                'label' => 'Short content',
            ],
            'content' => [
                'class' => EditorField::class,
                'label' => 'Content',
                'html' => [
                    'class' => "tinymce-field",
                ],
            ],
            'view' => [
                'class' => DropDownField::class,
                'choices' => Page::getViews(),
                'label' => 'View',
            ],
            'view_children' => [
                'class' => DropDownField::class,
                'choices' => Page::getViews(),
                'label' => 'View children',
            ],
            'published_at' => [
                'class' => DateTimeField::class,
                'html' => [
                    'readonly' => 'readonly',
                ],
            ],
            'file' => ImageField::class,
//            'published_at' => DateTimeField::className()
            'sites' => [
                'class' => Select2Field::class,
                'multiple' => true,
                'choices' => function() {
                    $mass = [];
                    /** @var SiteModel $model */
                    foreach (SiteModel::objects()->all() as $model) {
                        if ($model->isWork()) {
                            $mass[ $model->storefrontid ] = (string) $model;
                        }
                    }
                    return $mass;
                },
                'empty' => 'All storefronts',
                'required' => true,
            ],
            'language' => [
                'class' => DropDownField::class,
                'required' => true
            ],
            'sorting' => [
              'class' => DropDownField::class
            ],
            'parent' => [
                'class' => DropDownField::class,
            ]
        ];
    }

    public function getInlines()
    {
        return [
            ['meta' => MetaInlineForm::class],
        ];
    }

    public function getModel()
    {
        return new Page;
    }
}
