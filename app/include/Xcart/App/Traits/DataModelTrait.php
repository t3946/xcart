<?php
namespace Xcart\App\Traits;

use Exception;
use Xcart\App\Orm\ModelInterface;
use Xcart\Data;

trait DataModelTrait
{
    /**
     * @var Data|null
     */
    private $dataModel = null;

    /**
     * @return string class of Data
     */
    public static function getDataModelClass()
    {
        return Data::className();
    }

    /**
     * @return Data
     */
    public function getDataModel()
    {
        /** @var ModelInterface $this */

        if (!$this->dataModel) {
            $class = static::getDataModelClass();
            $this->dataModel = new $class();
            $this->dataModel->setAttributes($this->getAttributes());

            $this->afterFetchDataModel($this->dataModel);
        }

        return $this->dataModel;
    }

    /**
     * @param Data $model
     */
    public function afterFetchDataModel($model) {}

    public function afterSetAttribute($name, $value)
    {
        parent::afterSetAttribute($name, $value);

        if ($this->dataModel) {
            $this->dataModel->setAttribute($name, $value);
        }
    }


    /**
     * @param $method
     * @param $args
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $args)
    {
        $manager = $method . 'Manager';
        if (method_exists($this, $manager)) {
            return call_user_func_array([$this, $manager], array_merge([$this], $args));
        }
        elseif (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $args);
        }
        elseif (method_exists($this->getDataModel(), $method)) {
            return call_user_func_array([$this->getDataModel(), $method], $args);
        }
        else {
            throw new Exception('Call unknown method ' . $method);
        }
    }
}