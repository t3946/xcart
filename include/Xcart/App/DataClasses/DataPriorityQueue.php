<?php
namespace Xcart\App\DataClasses;

class DataPriorityQueue extends DataQueue
{
    public function insert($data, $priority = 0)
    {
        $this->data[] = ['data' => $data, 'priority' => $priority];

        usort($this->data, function($a, $b){
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });
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