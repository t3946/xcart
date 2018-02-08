<?php

namespace Xcart\App\Logger\Handler;

use Monolog\Handler\SwiftMailerHandler as MonoSwiftMailerHandler;
use Xcart\App\Main\Xcart;

/**
 * Class SwiftMailerHandler
 * @package Xcart\App\Logger
 */
class SwiftMailerHandler extends ProxyHandler
{
    public $subject;
    public $to;

    public function getHandler()
    {
        $mail = Xcart::app()->mail;

        $mailer = $mail->getMailer();
        $message = $mail->createMessage()->getSwiftMessage();


        return new MonoSwiftMailerHandler($mailer, $message, $this->getLevel(), $this->bubble);
    }
}
