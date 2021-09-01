<?php

namespace Xcart\App\Form\Fields;

use Closure;
use Xcart\App\Form\ModelForm;
use Xcart\App\Helpers\JavaScript;
use Xcart\App\Helpers\JavaScriptExpression;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Translate\Translate;

/**
 * Class Select2Field
 * @package Mindy\Form
 */
class Select2Field extends DropDownField
{
    public array $options = [];

    public int $pageSize = 30;

    public string $modelField = 'name';

    public string $placeholder = 'Click to select value';

    public bool $editable = false;
}
