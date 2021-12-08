<?php


namespace Modules\Goods\Commands;

use PhpAmqpLib\Message\AMQPMessage;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class QueueFilesCommand extends Command
{

    public function handle($arguments = [])
    {
        Xcart::app()->queue->consume('files', [$this, 'consume']);
    }

    public function consume(AMQPMessage $message): void
    {
        $message->ack();
    }

}
