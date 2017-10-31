<?php
/**
 * Created by PhpStorm.
 * User: maksim
 * Date: 30.10.17
 * Time: 20:12
 */

namespace Modules\Cart\Forms;

use Modules\Cart\Models\CouponRestrictionModel;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\ModelForm;
use Xcart\App\Helpers\JavaScript;

class DiscountRestrictionForm extends ModelForm
{
    protected static $class = 'Modules\Cart\Forms\DiscountRestrictionForm';

    public $exclude = ['data', 'class'];

    public static function getRestrictClass() { return; }

    public static function getFormName()
    {
        $class = self::getRestrictClass();
        $restrict = new $class;
        return $restrict->getName();
    }

    public function getModel()
    {
        return new CouponRestrictionModel();
    }

    public function getFields()
    {
        $model = $this->getInstance();
        $formClass = $this::className();

        if ($model->class) {
            /** @var \Modules\Cart\Interfaces\IDiscountRestriction $restrict */
            $restrict = new $model->class();
            $formClass = $restrict->getFormClass();
        }

        $result = [
            'coupon' => [
                'class' => DropDownField::className(),
                'html' => [
                    'disabled' => 'disabled',
                ],
            ],
            'form' => [
                'class' => DropDownField::className(),
                'value' => $formClass,
                'html' => [
                    'onChange' => JavaScript::encode('js: window.forms.loadForm(this.value)'),
                ],
                'choices' => function() {
                    $result = [self::$class => ''];
                    /** @var \Modules\Cart\Discounts\Restrictions\AbstractRestriction $m */
                    foreach ( $this->getFormClasses() as $class) {
                        $c = $class::getRestrictClass();
                        $m = new $c;

                        $result[$class] = $m->getName();
                    }

                    return  $result;
                },
            ],
        ];

        return $result;
    }

    public function getFormClasses()
    {
        $classes = [];
        $path = __DIR__;
        if (is_dir($path)) {
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename)
            {
                if ($filename->isDir()) continue;
                $name = $filename->getBasename('.php');
                $class = implode('\\', [__NAMESPACE__,  $name]);

                if (is_subclass_of($class, self::$class )) {
                    $classes[] = $class;
                }
            }
        }

        return $classes;
    }

    public function getRealFieldsList()
    {
        $model = $this->getInstance();
        return array_keys($model::getFields());
    }

    public function save()
    {
        $real_keys = $this->getRealFieldsList();

        $cleaned = [];
        foreach ($this->cleanedData as $key => $val)
        {
            if ($key == 'form') {
                $val = $val::getRestrictClass();
                $key = 'class';
            }

            if (!in_array($key, $real_keys))
            {
                $cleaned['data'][$key] = $val;
            }
            else {
                $cleaned[$key] = $val;
            }
        }

        $this->cleanedData = $cleaned;

        return parent::save();
    }

    protected function populateFromInstance(\Xcart\App\Orm\Model $model)
    {
        parent::populateFromInstance($model);

        $data = $model->data;

        foreach ($data as $key => $val)
        {
            if ($field = $this->getField($key)) {
                $this->getField($key)->setValue($val);
            }
        }
    }
}