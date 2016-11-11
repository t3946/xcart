<?php

namespace Xcart;

class Mail extends Data
{
    private $sTo = null;
    private $sFrom = null;
    private $sReplyTo = null;
    private $aAttachments = null;

    private $aReplaceRules = null;

    public function __construct($aParams = [])
    {
        parent::__construct($aParams);
    }

    public function getEmailBody()
    {
        return $this->getField('email_body');
    }

    public function getSubject()
    {
        return $this->getField('customer_subject');
    }

    public function replaceSubject()
    {

    }

    public function setBody($str)
    {
        $this->setField('email_body', $str);
        return $this;
    }

    public function addAttachment($sFilePath)
    {
        $this->aAttachments[] = $sFilePath;
        return $this;
    }

    public function setSubject($str)
    {
        $this->setField('customer_subject', $str);
        return $this;
    }

    public function replaceBody()
    {
        if (!empty($this->aReplaceRules)) {
            foreach ($this->aReplaceRules as $key => $sRule) {
                $this->setBody(str_replace($key, $sRule, $this->getEmailBody()));
            }
        }
        $this->setBody(func_eol2br($this->getEmailBody()));
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

        $this->send("mail/simple_email_subj.tpl", "mail/simple_email_body.tpl");
    }

    protected function send($subject_template, $body_template)
    {
        x_load('mail');
        global $config, $mail_smarty, $shop_language;

        $mail_smarty->assign("body", $this->getEmailBody());
        $mail_smarty->assign("subj", $this->getSubject());

        $lend = (X_DEF_OS_WINDOWS?"\r\n":"\n");
        $mail_smarty->assign_by_ref ("config", $config);

        $lng_code = $shop_language;

        $charset = Countries::model(['code' => $lng_code])->getField('charset');

        $mail_subject = chop(func_display($subject_template,$mail_smarty,false));

        $msgs = array(
            "header" => array (
                "Content-Type" => "multipart/related;$lend\ttype=\"multipart/alternative\""
            ),
            "content" => array()
        );
        $mail_message = func_display($body_template,$mail_smarty,false);

        if (X_DEF_OS_WINDOWS) {
            $mail_message = preg_replace("/(?<!\r)\n/S", "\r\n", $mail_message);
        }

        $msgs['content'][] = array (
            "header" => array (
                "Content-Type" => "multipart/alternative"
            ),
            "content" => array (
                array (
                    "header" => array (
                        "Content-Type" => "text/plain;$lend\tcharset=\"$charset\"",
                        "Content-Transfer-Encoding" => "8bit"
                    ),
                    "content" => strip_tags($mail_message)
                )
            )
        );

        if (file_exists($mail_smarty->template_dir."/mail/html/".basename($body_template))) {
            $mail_smarty->assign("mail_body_template", "mail/html/" . basename($body_template));
            $mail_message = func_display("mail/html/html_message_template.tpl", $mail_smarty, false);
            list($mail_message, $files) = func_attach_images($mail_message);

            $files_counter = count($files);

            if (!empty($this->aAttachments))
            foreach ($this->aAttachments as $sFile) {
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

            $msgs['content'][0]['content'][] = array (
                "header" => array (
                    "Content-Type" => "text/html;$lend\tcharset=\"$charset\"",
                    "Content-Transfer-Encoding" => "8bit"
                ),
                "content" => $mail_message
            );

            if (!empty($files)) {
                foreach ($files as $v) {
                    $msgs['content'][] = array (
                        "header" => array (
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
        $headers = "From: ".$this->getFrom().$lend."X-Mailer: PHP/".phpversion().$lend."MIME-Version: 1.0".$lend.$message_header;
        if (trim($this->getFrom()) != ""){
            $mail_from = $this->getFrom();
            if (!empty($this->sReplyTo)){
                $mail_from = $this->getReplyTo();
            }
            $headers .= "Reply-to: ".$mail_from.$lend;
        }

        if (preg_match('/([^ @,;<>]+@[^ @,;<>]+)/S', $this->getFrom(), $m)) {
            return @mail($this->getTo(),$mail_subject,$mail_message,$headers, "-f".$m[1]);
        } else {
            return @mail($this->getTo(),$mail_subject,$mail_message,$headers);
        }
    }

    public function getTo()
    {
        return $this->sTo;
    }

    public function setTo($sTo)
    {
        $this->sTo = $sTo;
        return $this;
    }

    public function getReplyTo()
    {
        return $this->sReplyTo;
    }

    public function getFrom()
    {
        return $this->sFrom;
    }

    public function setFrom($sFrom)
    {
        $this->sFrom = preg_replace('![\x00-\x1f].*$!sm', '', $sFrom);
        return $this;
    }
}