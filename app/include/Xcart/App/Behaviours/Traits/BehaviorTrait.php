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

trait BehaviorTrait
{

    /**
     * @var array
     */
    protected $_behaviors = [];

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
        $this->_behaviors[$name] = $behavior;

    }

    /**
     * @param $name
     */
    public function detachBehavior($name): void
    {
        unset($this->_behaviors[$name]);
    }

    /**
     * @param $name
     * @return FormViewBehavior
     */
    public function getBehavior($name): FormViewBehavior
    {
        if (!isset($this->_behaviors[$name])) {
            return null;
        }
        return $this->_behaviors[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasBehavior($name): bool
    {
        if (isset($this->_behaviors[$name])) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function hasAnyBehavior(): bool
    {
        return !empty($this->_behaviors);
    }

    /**
     * @return array
     */
    public function getAllBehaviors(): array
    {
        return $this->_behaviors;
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
            $this->_behaviors[$name] = Creator::createObject(array_merge([
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