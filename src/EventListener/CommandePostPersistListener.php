<?php
/**
 * Created by PhpStorm.
 * User: gael
 * Date: 11/02/19
 * Time: 16:17
 */

namespace App\EventListener;


use App\Entity\Commande;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CommandePostPersistListener
{

    public function postPersist(LifecycleEventArgs $args, \Swift_Mailer $mailer)
    {
        /** @var Commande $entity */
        $entity = $args->getObject();

        if(!$entity instanceof Commande) {
            return;
        }

        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipe@example.com')
            ->setBody('Nouvelle commande Créée ! Date de la commande : '.$entity->getDate()->format('dd/MM/yyyy'))
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    ['name' => $name]
                ),
                'text/plain'
            )
            */
        ;

        $mailer->send($message);
    }

}