<?php

namespace Modules\Account\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * @property int user_id
 * @property string one_time_password
 * @property int attempts_number
 * @property string created
 */
class OneTimePasswordModel extends Model
{
    private const OUTDATED_LIMIT_S = 60 * 1;
    private const NEW_LIMIT_S = 60 * 2;
    private const ATTEMPTS_LIMIT_NUMBER = 3;

    public function beforeSave($owner, $isNew)
    {
        if ($isNew) {
            $this->one_time_password = mt_rand(100000, 999999);
        }
    }

    public static function tableName()
    {
        return 'xcart_one_time_passwords';
    }

    public static function getFields()
    {
        return [
            'one_time_password_id' => [
                'class' => AutoField::class,
            ],
            'user_id' => [
                'class' => IntField::class,
            ],
            'one_time_password' => [
                'class' => CharField::class,
            ],
            'attempts_number' => [
                'class' => IntField::class,
                'default' => self::ATTEMPTS_LIMIT_NUMBER,
            ],
            'created' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
            ],
        ];
    }

    private function getTimePassed(): int
    {
        return time() - strtotime($this->created);
    }

    public function isNew(): bool
    {
        return $this->getTimePassed() < self::NEW_LIMIT_S;
    }

    public function isOutdated(): bool
    {
        return $this->getTimePassed() > self::OUTDATED_LIMIT_S;
    }

    public function isLimitExhausted(): bool
    {
        return $this->attempts_number === 0;
    }

    public function decAttempts(): void
    {
        $this->attempts_number--;
        $this->save();
    }

    public function matchCode(string $one_time_password): bool
    {
        if ($this->isLimitExhausted() || $this->isOutdated()) {
            return false;
        }

        if ($this->one_time_password !== $one_time_password) {
            $this->decAttempts();
            return false;
        }

        return true;
    }

    public function toArray(): array
    {
        $created = $this->getAttribute('created');
        $start_life_time_s = strtotime($created);
        $now_time_s = time();
        $past_time_s = $now_time_s - $start_life_time_s;
        $left_is_new_time_s = max(self::NEW_LIMIT_S - $past_time_s, 0);

        return [
            'left_attempts' => $this->attempts_number,
            'created' => $this->getAttribute('created'),
            'left_is_new_time' => $left_is_new_time_s,
        ];
    }
}
