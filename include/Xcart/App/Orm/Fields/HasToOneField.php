<?php

namespace Xcart\App\Orm\Fields;

use Xcart\App\Orm\Manager;

class HasToOneField extends HasManyField
{

    /**
     * @return \Xcart\App\Orm\ManagerInterface
     * @throws \Exception
     */
    public function getManager()
    {
        $where = [];
        if ($this->link) {
            foreach ($this->link as $from => $to) {
                $where[$to] = $this->getModel()->getAttribute($from);
            }
        }
        $manager = new Manager($this->getRelatedModel(), $this->getModel()->getConnection());
        $manager->filter(array_merge($where, $this->extra));

        if ($this->getModel()->getIsNewRecord()) {
            $manager->distinct();
        }

        return $manager;
    }

    public function getValue()
    {
        return $this->getManager()->limit(1)->get();
    }
}