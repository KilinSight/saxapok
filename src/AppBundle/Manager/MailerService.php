<?php


namespace AppBundle\Manager;


class MailerService
{

    /**
     * @var string
     */
    private $mailerHost;
    /**
     * @var string
     */
    private $mailerUser;
    /**
     * @var string
     */
    private $mailerPassword;

    /**
     * MailerService constructor.
     * @param $mailerHost
     * @param $mailerUser
     * @param $mailerPassword
     */
    public function __construct(string $mailerHost, string $mailerUser, string $mailerPassword)
    {
        $this->mailerHost = $mailerHost;
        $this->mailerUser = $mailerUser;
        $this->mailerPassword = $mailerPassword;
    }

    /**
     *
     * @return \Swift_Mailer
     */
    public function getMailer()
    {
        $transport = (new \Swift_SmtpTransport($this->mailerHost, 465))
            ->setUsername($this->mailerUser)
            ->setPassword($this->mailerPassword)
            ->setEncryption('SSL');

        return new \Swift_Mailer($transport);
    }

    /**
     * @return string
     */
    public static function generateVerifyCode()
    {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < 40; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    /**
     *
     * @param string $to
     * @param string $text
     * @return void
     */
    public function sendMessageTo(string $to, string $text)
    {
        $mailer = $this->getMailer();
        $message = new \Swift_Message('Exception');
        $message->setFrom($this->mailerUser);
        $message->setTo($to);
        $message->setContentType('text/html');
        $message->setBody($text);
        $mailer->send($message);
    }
}