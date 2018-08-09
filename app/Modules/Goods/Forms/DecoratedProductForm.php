<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 01.08.2018
 * Time: 12:03
 */

namespace Modules\Goods\Forms;


use Modules\Core\Forms\ProductForm;

abstract class DecoratedProductForm extends ProductForm
{


    public $variants = [];
    public $type = '';
    public $title = '';
    /**
     * @var array
     */
    protected $extendFields = [];

    /**
     * DecoratedProductForm constructor.
     * @param array $config
     * @param null $formToExtend
     */
    public function __construct(array $config = [], $formToExtend = null)
    {
        $this->beforeConstruct($formToExtend);
        parent::__construct($config);
    }

    /**
     * @param null $formToExtend
     */
    protected function beforeConstruct($formToExtend = null): void
    {
        if (!empty($formToExtend)) {
            $this->extendFields = $formToExtend->getFields();
        }
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        $fields = $this->fields();
        return array_merge($this->extendFields, $fields);
    }

    /**
     * @return array
     */
    abstract protected function fields(): array;

    protected function createTitle(){
        return $this->title . ':';
    }

    /**
     * @return string
     */
    protected function createRequiredMessage(): string
    {
        return 'Please choose ' . lcfirst($this->title);
    }
}