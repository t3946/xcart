<?php

namespace Modules\Sites\Forms;

use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\ModelForm;

class SiteForm extends ModelForm
{
    public array $exclude = [
        'images',
        'link',
        'favicons',
        'link',
        'config',
        'link',
        'list_config',
        'link',
        'storefrontid',
        'prefix',
        'choices',
        'orderby',
        'static_page',
        'link',
        'marketplaces',
        'link',
        'corporates',
        'short_name',
    ];

    public function getModel()
    {
        return new SiteModel();
    }

    public function getName()
    {
        return 'Edit Site';
    }
}