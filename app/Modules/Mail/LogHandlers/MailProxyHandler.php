<?php
/**
 * Created by PhpStorm.
 * User: tsukasa
 * Date: 28.12.2017
 * Time: 21:14
 */

namespace Modules\Mail\LogHandlers;


use Xcart\App\Logger\Handler\ProxyHandler;

class MailProxyHandler extends ProxyHandler
{
    public $to;
    public $subject;

    public function getHandler()
    {
        $hndl = new MailHandler($this->getLevel());
        $hndl->subject = $this->subject;
        $hndl->to = $this->to;
        return $hndl;
    }
}