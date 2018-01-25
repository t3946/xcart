<?php

namespace Modules\Mail\Components;

use Modules\Sites\SitesModule;
use Swift_Mailer;
use Swift_MailTransport;
use Swift_Message;
use Swift_SendmailTransport;
use Swift_SmtpTransport;
use Xcart\App\Cli\Cli;
use Xcart\App\Main\Xcart;
use Xcart\App\Template\Renderer;

class Mailer
{
    use Renderer;

    // Swift_SendmailTransport
    const MODE_SENDMAIL = 'sendmail';

    // Swift_MailTransport
//    const MODE_MAIL = 'mail';

    // Swift_SmtpTransport
    const MODE_SMTP = 'smtp';

    public $mode = 'sendmail';

    public $config = [];

    /**
     * @var string in string replacement {domain} to current domain
     */
    public $defaultFrom;

    /**
     * Example: "http://example.com", "https://10.12.231.43:8000"
     * @var string
     */
    public $hostInfo;

    protected $_transport;

    protected $_mailer;

    protected $_message;

    public function getTransport()
    {
        if (!$this->_transport) {
            $this->_transport = $this->initTransport();
        }
        return $this->_transport;
    }

    protected function initTransport()
    {
        $config = $this->config;
        if ($this->mode == self::MODE_SENDMAIL) {
            $command = isset($config['command']) ? $config['command'] : '/usr/sbin/sendmail -bs';
            $transport = new Swift_SendmailTransport($command);
            return $transport;
        }
        elseif ($this->mode == self::MODE_SMTP) {
            $security = isset($config['security']) ? $config['security'] : null;
            $transport = new Swift_SmtpTransport($config['host'], $config['port'], $security);
            $transport->setUsername($config['username']);
            $transport->setPassword($config['password']);
            return $transport;
        }
//        elseif ($this->mode == self::MODE_MAIL) {
//            $extraParams = isset($config['extraParams']) ? $config['extraParams'] : '-f%s';
//            return new Swift_MailTransport($extraParams);
//        }
        return null;
    }


    public function getMailer()
    {
        if (!$this->_mailer) {
            $this->_mailer = new Swift_Mailer($this->getTransport());
        }
        return $this->_mailer;
    }

    public function raw($to, $subject, $body, $additional = [], $attachments = [])
    {
        $this->compose();
        $message = $this->getSwiftMessage();

        $message->setTo($to);
        $message->setSubject($subject);
        $message->setBody($body, 'text/html');

        if (isset($additional['from'])) {
            $message->setFrom($additional['from']);
            $message->setSender($additional['from']);
        }
        elseif ($this->defaultFrom) {
            $default = strtr($this->defaultFrom, ['{domain}' => $this->getDomain()]);
            $message->setFrom($default);
            $message->setSender($default);
        }

        return $this->getMailer()->send($message);
    }

    public function compose()
    {
        $this->createMessage();

        return $this;
    }

    public function createMessage()
    {
        $this->_message = new Swift_Message();

        return $this;
    }

    /**
     * @return Swift_Message
     */
    public function getSwiftMessage()
    {
        return $this->_message;
    }

    public function template($to, $subject, $template, $data = [], $additional = [], $attachments = [])
    {
        $data = array_merge([
            'hostInfo' => $this->getHostInfo(),
            'domain' => $this->getDomain(),
        ], $data);

        $body = self::renderTemplate($template, $data);

        return $this->raw($to, $subject, $body, $additional, $attachments);
    }

    protected function getDomain()
    {
        $domain = false;

        /** @var SitesModule $module */
        if ($module = Xcart::app()->getModule('Sites')) {
            $domain = $module->getSite()->getBaseDomain();
        }

        if (!$domain) {
            $domain = Xcart::app()->request->getHost();
            if ( strpos($domain, ':') !== false ){
                $domain = substr($domain, 0, strpos($domain, ':'));
            }
        }

        return $domain;
    }
    
    public function getHostInfo()
    {
        if (!$this->hostInfo && !Cli::isCli()) {
            $this->hostInfo = Xcart::app()->request->getHostInfo();
        }

        return $this->hostInfo;
    }
}