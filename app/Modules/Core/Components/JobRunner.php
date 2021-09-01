<?php
namespace Modules\Core\Components;


use Jobby\Exception;
use Jobby\Helper;
use Jobby\Jobby;
use Jobby\ScheduleChecker;

class JobRunner extends Jobby
{
    public function run()
    {
        $isUnix = ($this->helper->getPlatform() === Helper::UNIX);

        if ($isUnix && !extension_loaded('posix')) {
            throw new Exception('posix extension is required');
        }

        $scheduleChecker = new ScheduleChecker();
        foreach ($this->jobs as $job => $jobConfig) {
            [$job, $config] = $jobConfig;
            if (!$scheduleChecker->isDue($config['schedule'])) {
                continue;
            }
            if ($isUnix) {
                $this->runUnix($job, $config);
            } else {
                $this->runWindows($job, $config);
            }

            if (isset($config['after']) && is_callable($config['after'])) {

                call_user_func_array($config['after'], ['config' => $config]);
            }
        }
    }

    protected function runUnix($job, array $config)
    {
        $command = $this->getExecutableCommand($job, $config);
        $binary = $this->getPhpBinary();

        $output = $config['debug'] ? 'debug.log' : '/dev/null';
        exec("$binary $command 1> $output 2>&1", $pid);

    }
}