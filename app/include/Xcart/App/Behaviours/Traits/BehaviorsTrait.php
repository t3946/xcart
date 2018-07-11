<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 09.07.2018
 * Time: 13:32
 */

namespace Xcart\App\Behaviours\Traits;


use Xcart\App\Behaviours\Interfaces\IBehavior;
use Xcart\App\Form\FormView\FormViewBehavior;
use Xcart\App\Helpers\Creator;

trait BehaviorsTrait
{

    /**
     * @var array
     */
    protected $_behaviours = [];

    /**
     * @param $name
     * @param $behavior
     */
    public function attachBehavior($name, $behavior): void
    {
        if (!($behavior instanceof IBehavior) || isset($_behaviorsList[$behavior->name])) {
            return;
        }

        $behavior->setOwner($this);
        $this->_behaviours[$name] = $behavior;

    }

    /**
     * @param $name
     */
    public function detachBehavior($name): void
    {
        unset($this->_behaviours[$name]);
    }

    /**
     * @param $name
     * @return FormViewBehavior
     */
    public function getBehavior($name):FormViewBehavior
    {
        if(!isset($this->_behaviours[$name])){
            return null;
        }
        return $this->_behaviours[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasBehavior($name):bool
    {
        if(isset($this->_behaviours[$name])){
            return true;
        }
        return false;
    }

    /**
     *
     */
    protected function applyDefaultBehaviors(): void
    {

        if (empty($this->behaviours())) {
            return;
        }

        foreach ($this->behaviours() as $name => $behaviorItem) {
            $this->_behaviours[$name] = Creator::createObject(array_merge([
                'name' => $name,
                'owner' => $this
            ], $behaviorItem));
        }
    }

    public function  __call($method, $parameters)
    {
        var_dump('call');
        var_dump($method);
        foreach ($this->_behaviours as $name => $behaviour){
            if(method_exists($behaviour, $method))
            {
                return call_user_func_array([$behaviour, $method], $parameters);
            }
        }
    }

    public static function  __callStatic($method, $parameters){
        var_dump('callStatic');
        var_dump($method);
        //exit;
    }

    /**
     * @return array
     */
    protected function behaviours()
    {
        return [];
    }


}