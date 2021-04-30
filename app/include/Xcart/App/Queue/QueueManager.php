<?php

namespace Xcart\App\Queue;


class QueueManager
{
    public string $host;
    public string $port;
    public string $user;
    public string $password;

    /**
     * Отправляет сообщение в очередь
     *
     * @param string $queue
     * @param string $message
     */
    public function send(string $queue, string $message): void
    {
        /**
         * Создаёт совединение с RabbitAMQP
         */
        /*$connection = new AMQPConnection([
            $this->host,    #host - имя хоста, на котором запущен сервер RabbitMQ
            $this->port,        #port - номер порта сервиса, по умолчанию - 5672
            $this->user,        #user - имя пользователя для соединения с сервером
            $this->password        #password
        ]);

        /*
        $channel = $connection->channel();

        $channel->queue_declare(
            $queue,	#queue name - Имя очереди может содержать до 255 байт UTF-8 символов
            false,      	#passive - может использоваться для проверки того, инициирован ли обмен, без того, чтобы изменять состояние сервера
            false,      	#durable - убедимся, что RabbitMQ никогда не потеряет очередь при падении - очередь переживёт перезагрузку брокера
            false,      	#exclusive - используется только одним соединением, и очередь будет удалена при закрытии соединения
            false       	#autodelete - очередь удаляется, когда отписывается последний подписчик
        );

        $msg = new AMQPMessage($message);

        $channel->basic_publish(
            $message,       	#message
            '',         	#exchange
            $queue 	#routing key
        );

        $channel->close();
        $connection->close();
*/
    }

}