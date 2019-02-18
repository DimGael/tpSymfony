<?php
/**
 * Created by PhpStorm.
 * User: gael
 * Date: 18/02/19
 * Time: 21:08
 */

namespace App\EventListener;


use App\Entity\Commande;
use App\Event\CommandePayeeEvent;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcher;

class PostUpdateCommande
{
    private $mailer;
    private $dispatcher;

    public function __construct(\Swift_Mailer $mailer, EventDispatcher $dispatcher)
    {
        $this->mailer = $mailer;
        $this->dispatcher = $dispatcher;
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof Commande){
            /** @var Commande $commande */
            $commande = $args->getEntity();

            $mailer = null;

            switch ($commande->getStatus()){
                case 'preparee':
                    $message = (new \Swift_Message('Commande Préparée'))
                        ->setFrom('restaurant@gmail.com')
                        ->setTo($commande->getUtilisateur()->getEmail())
                        ->setBody('La commande est prête à être servie, commande : '.$commande->getId())
                    ;
                    break;

                case 'servie':
                    $message = (new \Swift_Message('Commande servie'))
                        ->setFrom('restaurant@gmail.com')
                        ->setTo('accueil.restaurant@example.com')
                        ->setBody('La commande a été servie, commande : '.$commande->getId())
                    ;
                    break;

                case 'payee':
                    $message = (new \Swift_Message('Commande payée'))
                        ->setFrom('restaurant@gmail.com')
                        ->setTo($commande->getUtilisateur()->getEmail())
                        ->setBody('Votre commande a été payée, commande : '.$commande->getId())
                    ;

                    $this->dispatcher = new EventDispatcher();

                    $event = new CommandePayeeEvent($commande);

                    $this->dispatcher->dispatch(CommandePayeeEvent::NAME, $event);
                    break;
            }

            if ($mailer)
                $this->mailer->send($message);
        }
    }
}