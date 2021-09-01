<?php

namespace Modules\Core\Components;


class Profiler
{
    private static $profiler;
    public $timers = array();
    public $points = array();

    public static function getInstance()
    {
        if (!self::$profiler) {
            self::$profiler = new Profiler();
        }
        return self::$profiler;
    }

    public function start($key)
    {
        if (!array_key_exists($key, $this->timers)) {
            $this->timers[$key] = array();
            $this->timers[$key]['time'] = 0;
        }
        $this->timers[$key]['start'] = microtime(true);
    }

    public function stop($key)
    {
        $this->timers[$key]['time'] += microtime(true) - $this->timers[$key]['start'];
    }

    public function addPoint($__k = null)
    {
        $this->points[] = array(
            'time' => microtime(true),
            'backtrace' => debug_backtrace(),
            'label' => $__k
        );
    }

    private function formatTime($time, $onlyMS = false)
    {
        if ($time > 0.1 && !$onlyMS) {
            return round($time, 2) . ' s';
        } else {
            return round($time * 1000) . ' ms';
        }
    }

    public function display($min = 0)
    {
        echo '<table class="minitimer_table" cellpadding="5">' . $this->displayTimers($min) . $this->displayPoints($min) . '</table>';
    }

    private function displayTimers($min = 0)
    {
        if (empty($this->timers)) {
            return false;
        }
        uasort($this->timers, function ($a, $b) {
            return $a['time'] < $b['time'];
        });
        $tableRow = '';
        foreach ($this->timers as $key => $timer) {
            if ($timer['time'] >= $min) {
                $tableRow .= '<tr><td>' . $key . '</td><td class="time">' . self::formatTime($timer['time']) . '</td></tr>';
            }
        }
        return $tableRow;
    }

    private function displayPoints($min = 0)
    {
        return false;

        if (empty($this->points)) {
            return false;
        }

        $first_point_time = 0;
        $isFirst = true;
        $tableRow = '';
        $last_point = array();

        foreach ($this->points as $point) {
            if ($isFirst) {
                $first_point_time = $point['time'];
                $isFirst = false;
            }
            else {
                $time = $point['time'] - $last_point['time'];
                $time_fallback = $point['time'] - $first_point_time;
                if ($time >= $min) {
                    $tableRow .= '<tr>
                        <td>
                            Between <small>' . $last_point['backtrace'][0]['file'] . '</small> line ' . $last_point['backtrace'][0]['line'] . '
                            and <small>' . $point['backtrace'][0]['file'] . '</small> line ' . $point['backtrace'][0]['line'] .' '.$point['label']. '
                        </td>
                        <td class="time">' . self::formatTime($time) . '</td>
                        <td class="point_time">' . self::formatTime($time_fallback, true) . '</td>
                    </tr>';
                }
            }
            $last_point = $point;
        }
        return $tableRow;
    }
}