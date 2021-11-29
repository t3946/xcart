<?php
namespace Xcart\App\DataClasses;

class DataPriorityQueue extends DataQueue
{
    public function insert($data, $priority = 0)
    {
        $this->data[] = ['data' => $data, 'priority' => $priority];

        // TODO refactoring sort algorithm
        //usort is not stable sort algorithm
        /*usort($this->data, static function($a, $b){
            if ($a['priority'] === $b['priority']) {
                return 0;
            }
            return ($a['priority'] < $b['priority']) ? -1 : 1;
        });*/
    }

    public function getData($key)
    {
        $result = parent::getData($key);
        if ($result) {
            return $result['data'];
        }

        return null;
    }
}