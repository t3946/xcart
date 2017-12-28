<?php
namespace Modules\Mail\LogHandlers;

use \Monolog\Handler\MailHandler as MonologMailHandler;
use Xcart\App\Main\Xcart;


class MailHandler extends MonologMailHandler
{
    public $to;
    public $subject;

    protected function send($content, array $records)
    {
        $msg = '';

        if (Xcart::app()->getIsWebMode()) {

            $login = '';
            $session = Xcart::app()->request->session;

            if ($session) {
                $login = $session->get('admin_login') ?: $session->get('admin_login');
            }

            $msg .= $this->format('Site', $_SERVER["HTTP_HOST"]. $_SERVER['REQUEST_URI']);
            $msg .= $this->format('Remote IP', $_SERVER['REMOTE_ADDR']);
            $msg .= $this->format('Logged as', $login);

            $msg .= "\n";
        }


        foreach ($records as $record)
        {
            $msg .= $this->format('Date Time', $record['datetime']->format('Y-m-d H:i:s'));
            $msg .= $this->format('Chanel', $record['channel']);
            $msg .= $this->format('Level name', $record['level_name'] . " | " . $record['level']);

            if (!empty($record['message'])) {
                $msg .= $this->format('Message', $record['message']);
            }

            if (!empty($record['context'])) {
                $msg .= "\n";
                $msg .= sprintf("  %1$20s\n","-- Context --");

                foreach ($record['context'] as $code => $value) {
                    $msg .= $this->format($code, $value);
                }
            }

            $msg .= (count($records)>1) ? sprintf("\n\n%1$20s\n\n","--- next ---"): null;
        }

        $message = $this->toHtmlString($msg);

        Xcart::app()->mail->template(
            $this->to,
            $this->subject,
            'mail/log_template.tpl',
            ['message' => $message]
        );
    }

    protected function format($title, $value)
    {
        $post_val = !strstr($value, "\n")?'':"\n-";
        return sprintf("**%1$15s**: %2\$s \n", strtoupper($title), $value . $post_val);
    }

    protected function toHtmlString($string)
    {
        $string = preg_replace("/\*\*(.*)\*\*(.+)/","<b>$1</b>$2", $string);
        $string = str_replace(' ', '&nbsp;', $string);
        return $string;
    }

}