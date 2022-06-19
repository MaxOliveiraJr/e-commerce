<?php

namespace Hcode;

use Rain\Tpl;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer
{
    const USERNAME = "email@email.com";
    const PASSWORD = "senha";
    const NAME_FROM = "name_from";

    private $mail;
    public function __construct($toAddress, $toName, $subject, $tplname, $data = array())
    {

        $config = array(
            "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"] . "/views/email/",
            "cache_dir"     => $_SERVER["DOCUMENT_ROOT"] . "/views-cache/",
            "debug"         => false // set to false to improve the speed
        );

        Tpl::configure($config);
        $tpl = new Tpl;

        foreach ($data as $key => $value) {
            $tpl->assign($key, $value);
        }

        $html = utf8_decode($tpl->draw($tplname, true));

        $this->mail = new PHPMailer();
        $this->mail->isSMTP();
      
        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $this->mail->SMTPDebug = SMTP::DEBUG_OFF;
        // $this->mail->SMTPDebug = 3;
        $this->mail->Host = "smtp.gmail.com";
        // $this->mail->Debugoutput = "html";
        $this->mail->Port = 587;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = Mailer::USERNAME;
        $this->mail->Password = Mailer::PASSWORD;
        $this->mail->setFrom(Mailer::USERNAME, Mailer::NAME_FROM);
        $this->mail->addReplyTo(Mailer::USERNAME, Mailer::NAME_FROM);
        $this->mail->addAddress($toAddress, $toName);
        $this->mail->Subject = $subject;
        $this->mail->msgHTML($html);
        $this->mail->AltBody = "Teste";
    }

    public function send()
    {
        return $this->mail->send();
    }
}
