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
            PagesModule::t('Main information') => [
                'name', 'url', 'parent', 'is_index', 'no_index', 'is_published'
            ],
            PagesModule::t('Content') => [
                'content_short', 'content'
            ],
            PagesModule::t('Additional') => [
                'published_at', 'file', 'sites'
            ],
            PagesModule::t('Display settings') => [
                'view', 'view_children', 'sorting'
            ]
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
                'label' => PagesModule::t('Short content')
            ],
            'content' => [
                'class' => EditorField::className(),
                'label' => PagesModule::t('Content')
            ],
            'view' => [
                'class' => DropDownField::className(),
                'choices' => Page::getViews(),
                'label' => PagesModule::t('View')
            ],
            'view_children' => [
                'class' => DropDownField::className(),
                'choices' => Page::getViews(),
//                'hint' => PagesModule::t('View for children pages'),
                'label' => PagesModule::t('View children')
            ],
            'published_at' => [
                'class' => DateTimeField::className(),
                'html' => [
                    'readonly' => 'readonly',
                ]
            ],
            'file' => ImageField::className(),
//            'published_at' => DateTimeField::className()
            'sites' => [
                'class' => DropDownField::className(),
                'multiple' => true,
                'choices' => function() {
                    return ['' => ''] + array_filter(SiteModel::objects()->all(),
                        function($model){
                            /** @var SiteModel $model */
                            return $model->isWork();
                        });
                },
            ],
        ];
    }

    public function getInlines()
    {
        return [
            ['meta' => MetaInlineForm::className()]
        ];
    }

    public function getModel()
    {
        return new Page;
    }
}
