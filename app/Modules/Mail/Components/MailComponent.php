<?php
namespace Modules\Mail\Components;

use Xcart\App\Helpers\SmartProperties;
use Xcart\Countries;

class MailComponent
{
    use SmartProperties;

    public $to = null;
    public $from = null;
    public $reply_to = null;
    public $attachments = [];

    public $body = null;
    public $subject = null;
    public $charset = 'utf-8';

    public $header = [];

    public $subject_template = 'mail/simple_email_subj.tpl';
    public $body_template = 'mail/simple_email_body.tpl';

    private $aReplaceRules = null;

    public function replaceSubject()
    {
        if (!empty($this->aReplaceRules)) {
            foreach ($this->aReplaceRules as $key => $sRule) {
                $this->subject = str_replace($key, $sRule, $this->subject);
            }
        }
    }

    private function eol2br($content)
    {
        return ($content == strip_tags($content)) ? nl2br($content) : $content;
    }

    public function setSubjectTemplate($template)
    {
        $this->subject_template = $template;
        return $this;
    }

    public function setBodyTemplate($template)
    {
        $this->body_template = $template;
        return $this;
    }

    public function addHeader($header)
    {
        $this->header[key($header)] = current($header);
    }

    public function addAttachment($sFilePath)
    {
        $this->attachments[] = $sFilePath;
        return $this;
    }

    public function replaceBody()
    {
        if (!empty($this->aReplaceRules)) {
            foreach ($this->aReplaceRules as $key => $sRule) {
                $this->body = str_replace($key, $sRule, $this->body);
            }
        }
        $this->body = $this->eol2br($this->body);
    }

    /**
     * @param $sReplaceKey
     * @param $sReplaceValue
     * @return $this
     */
    public function addReplaceRule($sReplaceKey, $sReplaceValue)
    {
        $this->aReplaceRules[$sReplaceKey] = $sReplaceValue;
        return $this;
    }

    public function prepareMail()
    {
        $this->replaceSubject();
        $this->replaceBody();
        return $this;
    }

    public function sendEmail()
    {
        $this->prepareMail();
        $this->send($this->subject_template, $this->body_template);
    }

    protected function send($subject_template, $body_template)
    {
        global $config, $mail_smarty;
        $lend = (X_DEF_OS_WINDOWS ? "\r\n" : "\n");
        $mail_message = $this->body;
        $mail_subject = $this->subject;
        $message_header = '';

        if (isset($mail_smarty)) {
            $mail_smarty->assign("body", $this->body);
            $mail_smarty->assign("subj", $this->subject);
            $mail_smarty->assign_by_ref("config", $config);
            $mail_subject = chop(func_display($subject_template, $mail_smarty, false));
            $mail_message = func_display($body_template, $mail_smarty, false);

            $shop_language = 'US'; //TODO remove this;
            if (!empty($shop_language)) {
                $oCountry = Countries::objects()->filter(['code' => $shop_language])->get();
                if ($oCountry) {
                    $this->charset = $oCountry->charset;
                }
            }

            $msgs = array(
                "header" => array(
                    "Content-Type" => "multipart/related;$lend\ttype=\"multipart/alternative\""
                ),
                "content" => array()
            );

            if (X_DEF_OS_WINDOWS) {
                $mail_message = preg_replace("/(?<!\r)\n/S", "\r\n", $mail_message);
            }

            $msgs['content'][] = array(
                "header" => array(
                    "Content-Type" => "multipart/alternative"
                ),
                "content" => array(
                    array(
                        "header" => array(
                            "Content-Type" => "text/plain;$lend\tcharset=\"$this->charset\"",
                            "Content-Transfer-Encoding" => "8bit"
                        ),
                        "content" => strip_tags($mail_message)
                    )
                )
            );

            if (file_exists($mail_smarty->template_dir . "/mail/html/" . basename($body_template))) {
                $mail_smarty->assign("mail_body_template", "mail/html/" . basename($body_template));
                $mail_message = func_display("mail/html/html_message_template.tpl", $mail_smarty, false);
                list($mail_message, $files) = func_attach_images($mail_message);

                $files_counter = count($files);

                if (!empty($this->attachments))
                    foreach ($this->attachments as $sFile) {
                        $files_counter++;
                        $data = "";

                        if (file_exists($sFile) && is_readable($sFile)) {
                            $fp = @fopen($sFile, "rb");
                            if ($fp) {
                                if (filesize($sFile) > 0) {
                                    $data = fread($fp, filesize($sFile));
                                }
                                fclose($fp);
                            }
                        } else {
                            continue;
                        }


                        $files[$files_counter]["name"] = basename($sFile);
                        $files[$files_counter]["type"] = mime_content_type($sFile);
                        $files[$files_counter]["data"] = $data;
                    }

                $msgs['content'][0]['content'][] = array(
                    "header" => array(
                        "Content-Type" => "text/html;$lend\tcharset=\"$this->charset\"",
                        "Content-Transfer-Encoding" => "8bit"
                    ),
                    "content" => $mail_message
                );

                if (!empty($files)) {
                    foreach ($files as $v) {
                        $msgs['content'][] = array(
                            "header" => array(
                                "Content-Type" => "$v[type];$lend\tname=\"$v[name]\"",
                                "Content-Transfer-Encoding" => "base64",
                                "Content-ID" => "<$v[name]>"
                            ),
                            "content" => chunk_split(base64_encode($v['data']))
                        );
                    }
                }
            }

            list($message_header, $mail_message) = func_parse_mail($msgs);
        }
        $headers = "From: " . $this->from . $lend . "X-Mailer: PHP/" . phpversion() . $lend . "MIME-Version: 1.0" . $lend . $message_header;
        if (trim($this->from) != "") {
            $mail_from = $this->from;
            if (!empty($this->reply_to)) {
                $mail_from = $this->reply_to;
            }
            $headers .= "Reply-to: " . $mail_from . $lend;
        }
        if (!empty($this->header)){
            foreach ($this->header as $key => $header) {
                $headers .= "{$key}: {$header}" . $lend;
            }
        }


        if (preg_match('/([^ @,;<>]+@[^ @,;<>]+)/S', $this->from, $m)) {
            return @mail($this->to, $mail_subject, $mail_message, $headers, "-f" . $m[1]);
        } else {
            return @mail($this->to, $mail_subject, $mail_message, $headers);
        }
    }

    public function setFrom($sFrom)
    {
        $this->from = preg_replace('![\x00-\x1f].*$!sm', '', $sFrom);
    }
}