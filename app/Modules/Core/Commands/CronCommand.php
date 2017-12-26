<?php

namespace Modules\Core\Commands;


use Jobby\Jobby;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Core\Components\JobRunner;
use Modules\Core\Models\CronModel;
use Xcart\App\Commands\Command;
use Xcart\App\Helpers\Paths;

class CronCommand extends Command
{

    public function handle($arguments = [])
    {
        $cron_logs_path = Paths::get('root.log.cron');

        if (!is_dir($cron_logs_path)) {
            mkdir($cron_logs_path, 0775, true);
        }

        $this->checkCronFiles();
        $this->configureJobs();
    }

    private function checkCronFiles()
    {
        $crons_path = Paths::get('root.cron');

        if ($files = glob($crons_path . '/*.php')) {
            foreach ($files as $file)
            {
                $fname = basename($file);
                $name = basename(current(explode('.', $file)));
                $bname =  '/' . $name . '.php';

                if (!CronModel::objects()->filter(['command__contains' => $bname])->count()) {

                    $model = new CronModel();
                    $model->schedule  = '* * * * *';
                    $model->command  = "php ./cron/" . $fname;
                    $model->name = $name;
                    $model->log_file = $name .'.log';
                    $model->save();

                }
            }
        }
    }

    private function configureJobs()
    {
        $cron_logs_path = Paths::get('root.log.cron');
        $jobby = new Jobby();

        if ($models = CronModel::objects()->filter([new QOr(['active' => true, 'run_force' => true])])->all())
        {
            /** @var CronModel $model */
            foreach ($models as $model) {
                $jobby->add($model->name. "__runner", [
                    'schedule' => $model->run_force ? '* * * * *' : $model->schedule,
                    'command'  => 'php ./app/console.php Core Cron run '. $model->pk,
                    'output'   => $cron_logs_path . '/' . $model->log_file,
                ]);
            }
        }


        $jobby->run();
    }

    public function run($args = [])
    {
        if (!empty($args)) {

            $cron_logs_path = Paths::get('root.log.cron');
            $pk = $args[0];

            /** @var CronModel $model */
            if ($model = CronModel::objects()->filter([new QOr(['active' => true, 'run_force' => true]), 'id' => $pk])->get())
            {
                $job = new JobRunner();
                $job->add($model->name, [
                    'model_pk' => $model->pk,
                    'schedule' => '* * * * *',
                    'command'  => $model->command,
                    'output'   => $cron_logs_path . '/' . $model->log_file,
                    'after' => [$this, 'runAfter']
                ]);

                $model->run_end = null;
                $model->run_start = new \DateTime();
                $model->is_run = true;
                $model->run_force = false;
                $model->save();

                $job->run();
            }
        }
    }

    public function runAfter($config = [])
    {
        if (isset($config['model_pk'])) {
            /** @var CronModel $model */
            $model = CronModel::objects()->get(['pk' => $config['model_pk']]);

            $model->run_end = new \DateTime();
            $model->is_run = false;
            $model->save();
        }
    }


}