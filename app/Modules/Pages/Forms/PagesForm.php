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
            'is_index' => CheckboxField::className(),
            'no_index' => CheckboxField::className(),
            'is_published' => CheckboxField::className(),
            'content_short' => [
                'class' => TextAreaField::className(),
                'label' => 'Short content',
            ],
            'content' => [
                'class' => EditorField::className(),
                'label' => 'Content',
                'html' => [
                    'class' => "tinymce-field",
                ],
            ],
            'view' => [
                'class' => DropDownField::className(),
                'choices' => Page::getViews(),
                'label' => 'View',
            ],
            'view_children' => [
                'class' => DropDownField::className(),
                'choices' => Page::getViews(),
                'label' => 'View children',
            ],
            'published_at' => [
                'class' => DateTimeField::className(),
                'html' => [
                    'readonly' => 'readonly',
                ],
            ],
            'file' => ImageField::className(),
//            'published_at' => DateTimeField::className()
            'sites' => [
                'class' => Select2Field::class,
                'html' => [
                    'class' => 'select2-field',
                ],
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
            ]
        ];
    }

    public function getInlines()
    {
        return [
            ['meta' => MetaInlineForm::className()],
        ];
    }

    public function getModel()
    {
        return new Page;
    }
}
