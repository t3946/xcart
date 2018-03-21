<?php
namespace Modules\Core\Models;


use Cron\CronExpression;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Model;
use Xcart\App\Traits\SlugifyTrait;

/**
 * Class CronModel
 * @package Modules\Core\Models
 *
 * @property (int) $id
 * @property (bool) $active
 * @property (bool) $is_run
 * @property (bool) $run_force
 * @property (string) $name
 * @property (string) $command
 * @property (string) $schedule
 * @property (string) $log_file
 * @property \DateTime $run_start
 * @property \DateTime $run_end
 */
class CronModel extends Model
{
    use SlugifyTrait;

    public static function getFields()
    {
        return [
            'id' => AutoField::className(),
            'active' => [
                'class' => BooleanField::className(),
                'default' => false,
                'null' => false,
            ],
            'is_run' => [
                'class' => BooleanField::className(),
                'default' => false,
                'null' => false,
            ],
            'run_force' => [
                'class' => BooleanField::className(),
                'default' => false,
                'null' => false,
            ],
            'name' => [
                'class' => CharField::className(),
                'required' => true,
                'null' => false,
            ],
            'command' => [
                'class' => CharField::className(),
                'required' => true,
                'null' => false,
            ],
            'schedule' => [
                'class' => CharField::className(),
                'required' => true,
                'null' => false,
            ],
            'log_file' => [
                'class' => CharField::className(),
                'null' => false,
            ],
            'run_start' => [
                'class' => DateTimeField::className(),
                'null' => true,
            ],
            'run_end' => [
                'class' => DateTimeField::className(),
                'null' => true,
            ],

        ];
    }


    public function beforeSave($owner, $isNew)
    {
        if (empty($this->log_file)) {
            $this->log_file = $this->pk . '-' . $this->createSlug($this->name) . '.log';
        }
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getNextRunning()
    {
        if ($this->active) {
            return CronExpression::factory($this->run_force ? "* * * * *" : $this->schedule)->getNextRunDate('now');
        }

        return null;
    }

}