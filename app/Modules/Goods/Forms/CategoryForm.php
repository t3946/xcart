<?php

namespace Modules\Goods\Forms;

use Modules\Editor\Fields\EditorField;
use Modules\Goods\Models\CategoryModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Fields\TextAreaField;
use Xcart\App\Form\ModelForm;

class CategoryForm extends ModelForm
{
    public array $exclude = ['products', 'site', 'pos'];

    public function getModel()
    {
        return new CategoryModel();
    }

    public function getName(): string
    {
        return 'Category';
    }

    public function getFieldsets()
    {
        return [
            'Base information' => [
                'icon_path',
                'picture_path',
                'category',
                'description',
                'is_bold',
                'avail',
                'supplemental_category',
                'pc_ready_to_classify',
                'pc_category_weight',
                'pc_z',
            ],
            'SEO Options' => [
                'prevent_index_products',
                'prevent_index_category_page',
                'title_tag',
                'SEO_category_name',
                'SEO_h2',
                'meta_keywords',
                'meta_descr',
                'google_product_category',
            ],
        ];
    }

    public function getFields()
    {
        return [
            'icon_path' => ImageField::class,
            'picture_path' => ImageField::class,
            'category' => [
                'class' => CharField::class,
                'label' => 'Category name',
                'required' => true,
            ],
            'description' => [
                'class' => EditorField::class,
                'html' => [
                    'class' => 'tinymce-field',
                ],
            ],
            'is_bold' => [
                'class' => Select2Field::class,
                'html' => [
                    'style' => 'width: 100px',
                ],
                'inline_editor' => true,
            ],
            'avail' => [
                'class' => Select2Field::class,
                'html' => [
                    'style' => 'width: 100px',
                ],
                'inline_editor' => true,
            ],
            'supplemental_category' => CheckboxField::class,
            'pc_category_weight' => [
                'class' => CharField::class,
                'html' => [
                    'style' => 'width: 150px',
                    'disabled' => 'disabled'
                ],
            ],
            'pc_z' => [
                'class' => CharField::class,
                'html' => [
                    'style' => 'width: 150px',
                    'disabled' => 'disabled'
                ],
            ],
            'SEO_h2' => [
                'class' => EditorField::class,
                'html' => [
                    'class' => 'tinymce-field',
                ],
            ],
            'meta_keywords' => TextAreaField::class,
            'meta_descr' => TextAreaField::class,
        ];
    }
}