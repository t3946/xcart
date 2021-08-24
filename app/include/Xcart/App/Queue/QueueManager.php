<?php

namespace Xcart\App\Queue;


use ErrorException;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPConnectionClosedException;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class QueueManager
{
    public string $host;
    public string $port;
    public string $user;
    public string $password;
    private ?AMQPStreamConnection $connection = null;
    private ?AMQPChannel $channel = null;

    private array $init_queues = [];

    private function connect(): void
    {
        if ($this->connection === null) {
            $this->connection = new AMQPStreamConnection(
                $this->host,    #host - имя хоста, на котором запущен сервер RabbitMQ
                $this->port,    #port - номер порта сервиса, по умолчанию - 5672
                $this->user,    #user - имя пользователя для соединения с сервером
                $this->password #password
            );
            $this->channel = $this->connection->channel();
            $this->channel->basic_qos( null, 50, null );
        }
    }

    private function reconnect(): void
    {
        try {
            if ($this->connection !== null) {
                $this->connection->close();
            }
        } catch (ErrorException $e) {
        }
        $this->connection = null;
        $this->connect();
    }

    private function init_queue(string $queue, $requeue = false): void
    {
        if (!isset($this->init_queues[$queue])) {
            $this->channel->exchange_declare($queue,
                                             'direct',
                                             false,
                                             true,
                                             false,
                                             false
            );

            $this->channel->queue_declare(
                $queue,    #queue name - Имя очереди может содержать до 255 байт UTF-8 символов
                false,        #passive - может использоваться для проверки того, инициирован ли обмен, без того, чтобы изменять состояние сервера
                true,        #durable - убедимся, что RabbitMQ никогда не потеряет очередь при падении - очередь переживёт перезагрузку брокера
                false,        #exclusive - используется только одним соединением, и очередь будет удалена при закрытии соединения
                false,        #autodelete - очередь удаляется, когда отписывается последний подписчик
                false,
                $requeue ? new AMQPTable(["x-dead-letter-exchange" => "{$queue}_requeue"]) : []
            );

            $this->channel->queue_bind($queue, $queue);

            if ($requeue) {
                $this->channel->exchange_declare(
                    "{$queue}_requeue",
                    'direct',
                    false,
                    true,
                    false,
                    false
                );

                $this->channel->queue_declare(
                    "{$queue}_requeue",
                    false,
                    true,
                    false,
                    false,
                    false,
                    new AMQPTable(["x-dead-letter-exchange" => $queue, 'x-message-ttl' => 60000])
                );
                $this->channel->queue_bind("{$queue}_requeue", "{$queue}_requeue");
            }

            $this->init_queues[$queue] = true;
        }
    }

    /**
     * Отправляет сообщение в очередь
     *
     * @param string $queue
     * @param string $message
     * @param bool $requeue
     */
    public function send(string $queue, string $message, bool $requeue = false): void
    {
        $this->connect();

        $this->init_queue($queue, $requeue);

        $msg = new AMQPMessage($message);

        $this->channel->basic_publish(
            $msg,
            $queue
        );

    }

    public function get(string $queue): ?AMQPMessage
    {
        $this->connect();
        return $this->channel->basic_get($queue);
    }

    public function consume($queue, $callback, $tag = ''): void
    {
        $this->connect();

        $this->channel->basic_consume($queue, $tag, false, false, false, false, $callback);

        while ($this->channel->is_consuming()) {
            try {
                $this->channel->wait();
            } catch (AMQPConnectionClosedException $e) {
                echo "{$e->getMessage()}\n";
                $this->reconnect();
                $this->channel->basic_consume($queue, $tag, false, false, false, false, $callback);
            }
        }
    }
}