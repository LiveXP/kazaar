<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Utils;

use AppBundle\Entity\MorningCheck;

/**
 * Class Mailer
 * This class is a shortcut to use the Mailer (Swift_Mailer) by including twig to render the mail template
 * and contains the email(s) to send
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class Mailer
{
    /** @var \Swift_Mailer $mailer */
    protected $mailer;
    /** @var \Twig_Environment $twig */
    private $twig;

    /**
     * The current user email
     *
     * @var string $mailSender
     */
    private $mailSender;

    /**
     * The worker service used to treat some MorningCheck informations
     *
     * @var Worker $worker
     */
    private $worker;

    /**
     * Mailer constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     * @param Worker $worker
     * @param string $sender
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, Worker $worker, $sender)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->mailSender = $sender;
        $this->worker = $worker;
    }

    /**
     * @return \Swift_Mailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * @param $view
     * @param array $parameters
     *
     * @return string
     */
    public function renderView($view, array $parameters = [])
    {
        return $this->twig->render($view, $parameters);
    }

    /**
     * Return the mail_sender paramter
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->mailSender;
    }

    /**
     * @param MorningCheck $morningCheck
     */
    public function sendAndCloseMorningCheck(MorningCheck $morningCheck)
    {
        $morningCheck->setClosed(true);
        $this->worker->getManager()->persist($morningCheck);
        $this->worker->getManager()->flush();

        $signature = realpath($this->worker->getBasepath() . '/web/assets/img/email/livexp_signature.jpg');
        $message = new \Swift_Message($this->worker->generateMailTitle($morningCheck));
        $checkings = $this->worker->groupCheckingByCategory($morningCheck);

        $images = [];
        foreach ($morningCheck->getCheckings() as $checking) {
            foreach ($checking->getImages() as $image) {
                $images[$checking->getId()][] = $message->embed(\Swift_Image::fromPath($image->getFile()->getRealPath()));
            }
        }

        $renderedView = $this->renderView('AppBundle:Emails:morning_check.html.twig', [
            'images' => $images,
            'morningCheck' => $morningCheck,
            'checkings' => $checkings,
            'signature' => $message->embed(\Swift_Image::fromPath($signature))
        ]);

        $message
            ->setFrom($this->getFrom())
            ->setTo($morningCheck->getRecipients())
            ->setCc($morningCheck->getCc())
            ->setBody($renderedView, 'text/html');

        $this->getMailer()->send($message);
    }


}