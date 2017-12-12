<?php
namespace Modules\Core\Fields;

use Modules\Core\TemplateLibraries\AssetsLibrary;
use Xcart\App\Form\Fields\TextAreaField;

class AceField extends TextAreaField
{
    public $language = 'javascript';
//    public $theme = 'twilight';
    public $theme = 'gruvbox';

    public $inputTemplate = 'forms/field/ace/input.tpl';

    public function init()
    {
        parent::init();

        AssetsLibrary::addAsset(['type' => 'js', 'position' => 'head', 'key' => 'ace'],
                                '<script src="/static/backend/dist/raw/ace/src-min/ace.js"></script>');

        AssetsLibrary::addAsset(['type' => 'js', 'position' => 'head', 'key' => 'ace_mode'.$this->language],
                                '<script src="/static/backend/dist/raw/ace/src-min/mode-'.$this->language.'.js"></script>');

        AssetsLibrary::addAsset(['type' => 'js', 'position' => 'head', 'key' => 'ace_theme'.$this->theme],
                                '<script src="/static/backend/dist/raw/ace/src-min/theme-'.$this->theme.'.js"></script>');
    }
}