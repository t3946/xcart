<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 01.08.2018
 * Time: 12:03
 */

namespace Modules\Goods\Forms;


use Modules\Core\Forms\ProductForm;

abstract class DecoratedForm extends ProductForm
{

    protected $extendFields = [];

    public function __construct(array $config = [], $formToExtend = null)
    {
        $this->beforeConstruct($formToExtend);
        parent::__construct($config);
    }

    protected function beforeConstruct($formToExtend = null): void
    {
        if (!empty($formToExtend)) {
            $this->extendFields = $formToExtend->getFields();
        }
    }


    public function getFields(): array
    {
        $fields = $this->fields();
        return array_merge($this->extendFields, $fields);
    }

    abstract protected function fields(): array;
}