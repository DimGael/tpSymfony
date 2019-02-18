<?php
/**
 * Created by PhpStorm.
 * User: gael
 * Date: 11/02/19
 * Time: 16:17
 */

namespace App\EventListener;


use App\Entity\Commande;
use App\Entity\Dish;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\ORMException;

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
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var Commande $entity */
        $entity = $args->getObject();

        if(!$entity instanceof Commande) {
            return;
        }

        $entityManager = $args->getEntityManager();

        $entity->setDate(new \DateTime());
        $entity->setStatus('prise');

        $entity = $this->calculPrixTotal($entity);

        try {
            $entityManager->persist($entity);
            $entityManager->flush();
        } catch (ORMException $e) {
            echo $e->getMessage();
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        /** @var Commande $entity */
        $entity = $args->getObject();

        if(!$entity instanceof Commande) {
            return;
        }

        $entity = $this->calculPrixTotal($entity);

        $entityManager = $args->getEntityManager();

        try {
            $entityManager->persist($entity);
            $entityManager->flush();
        } catch (ORMException $e) {
            echo $e->getMessage();
        }
    }


    public function calculPrixTotal(Commande $commande)
    {

        $prixTotal = 0.0;
        /** @var Dish $dish */
        foreach($commande->getDishes() as $dish)
            $prixTotal += $dish->getPrice();
        $commande->setPrixTotal($prixTotal);

        return $commande;
    }

}