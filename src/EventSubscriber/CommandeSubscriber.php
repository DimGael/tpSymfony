<?php
/**
 * Created by PhpStorm.
 * User: gael
 * Date: 18/02/19
 * Time: 14:27
 */

namespace App\EventSubscriber;


use App\Entity\Commande;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class CommandeSubscriber implements EventSubscriber
{

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return [
            'order.payed',
            Events::prePersist,
            Events::postPersist,
            Events::preUpdate,
            Events::postUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Commande){
            $entity->setDate(new \DateTime());
            $entity->setStatus('prise');

            // TODO - mettre l'utilisateur connecté

            $entity->setPrixTotal($this->getPrixTotal($entity));
        }
    }

    public function postPersist()
    {
        dump('post persist');
        die;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Commande){

            $entity->setPrixTotal($this->getPrixTotal($entity));
        }
    }

    public function postUpdate()
    {
        dump('postUpdate');
        die;
    }

    private function getPrixTotal(Commande $commande)
    {
        $result = 0.0;

        foreach ($commande->getDishes() as $dish)
            $result += $dish->getPrice();

        return $result;
    }
}