<?php
/**
 * Created by PhpStorm.
 * User: anna
 * Date: 09.07.2018
 * Time: 13:32
 */

namespace Xcart\App\Behaviours\Traits;


use Xcart\App\Behaviours\Interfaces\IBehavior;
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
    public function attachBehaviour($name, $behavior): void
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
    public function detachBehaviour($name): void
    {
        unset($this->_behaviours[$name]);
    }

    /**
     *
     */
    protected function applyDefaultBehaviours(): void
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

    /**
     * @return array
     */
    protected function behaviours()
    {
        return [];
    }


}