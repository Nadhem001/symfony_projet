<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailerService
{   
    private $params;
    private $mailer;
    public function __construct(MailerInterface $mailer ,ParameterBagInterface $params){
        $this->mailer = $mailer;
        $this->params = $params;
    }

    public function sendEmail($email,$objet,$html)
    {
        $email = (new Email())
            ->from($this->params->get('mail_from'))
            ->to($email)
            ->subject($objet)
            ->html($html);

        $this->mailer->send($email);

       
    }

}



?>