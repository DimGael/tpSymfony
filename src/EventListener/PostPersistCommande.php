<?php
/**
 * Created by PhpStorm.
 * User: gael
 * Date: 18/02/19
 * Time: 20:46
 */

namespace App\EventListener;


use App\Entity\Commande;
use Doctrine\ORM\Event\LifecycleEventArgs;

class PostPersistCommande
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof Commande){
            /** @var Commande $commande */
            $commande = $args->getEntity();

            $message = (new \Swift_Message('Hello Email'))
                ->setFrom($commande->getUtilisateur()->getEmail())
                ->setTo('restaurant@gmail.com')
                ->setBody('Une nouvelle commande a été prise en salle, commande : '.$commande->getId())
            ;

            $this->mailer->send($message);

            $commande->getUtilisateur()->getEmail();
        }
    }

}