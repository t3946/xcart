<?php

namespace Modules\Order\Components;

use Modules\Order\Models\OrderLogModel;
use Xcart\App\Main\Xcart;

class OrderLogger
{
    private array $messages = [];

    public function __construct()
    {
        Xcart::app()->event->on('app:end', [$this, 'save']);
    }

    /**
     * Add order log message
     * @param int $order_id
     * @param string $type
     * @param string $message
     * @param null $login
     */
    public function add(int $order_id, string $type, string $message, $login = null): void
    {
        if ($login === null) {
            $login = Xcart::app()->user->login;
        }
        $this->messages[$order_id][$login][$type][] = $message;
    }

    /**
     * Save order log massages in one record
     */
    public function save(): void
    {
        foreach($this->messages as $order_id => $user_messages) {
            foreach ($user_messages as $login => $messages) {
                foreach (array_keys($messages) as $type) {
                    OrderLogModel::objects()->create([
                        'orderid' => $order_id,
                        'type' => $type,
                        'date' => time(),
                        'login' => $login,
                        'log' => nl2br(implode("\n", $messages[$type]))
                    ]);
                }
            }
        }
    }
}