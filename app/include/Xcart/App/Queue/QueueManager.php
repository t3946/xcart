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

        $this->channel->queue_declare(
            $queue,    #queue name - Имя очереди может содержать до 255 байт UTF-8 символов
            false,        #passive - может использоваться для проверки того, инициирован ли обмен, без того, чтобы изменять состояние сервера
            true,        #durable - убедимся, что RabbitMQ никогда не потеряет очередь при падении - очередь переживёт перезагрузку брокера
            false,        #exclusive - используется только одним соединением, и очередь будет удалена при закрытии соединения
            false,        #autodelete - очередь удаляется, когда отписывается последний подписчик
            false,
            $requeue ? new AMQPTable(["x-dead-letter-exchange" =>"{$queue}_requeue"]) : []
        );

        if ($requeue) {
            $this->channel->queue_declare(
                "{$queue}_requeue",
                false,
                true,
                false,
                false,
                false,
                new AMQPTable(["x-dead-letter-exchange" => $queue, 'x-message-ttl=300000'])
            );
        }

        $msg = new AMQPMessage($message);

        $this->channel->basic_publish(
            $msg,
            '',
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