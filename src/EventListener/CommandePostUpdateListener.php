<?php
/**
 * Created by PhpStorm.
 * User: gael
 * Date: 11/02/19
 * Time: 16:51
 */

namespace App\EventListener;


use App\Entity\Commande;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CommandePostUpdateListener
{

    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function postUpdate(LifecycleEventArgs $args, \Swift_Mailer $mailer)
    {
        /** @var Commande $entity */
        $entity = $args->getObject();

        if(!$entity instanceof Commande) {
            return;
        }

        switch ($entity->getStatus()){
            case 'preparee':
                $message = (new \Swift_Message('Hello Email'))
                    ->setFrom('send@example.com')
                    ->setTo('recipe@example.com')
                    ->setBody('Maître hotel ! La commande pour la '.$entity->getTableRestaurant()->getName().' est prête à être servie !')
                ;

                $mailer->send($message);
                break;

            case 'servie':
                $message = (new \Swift_Message('Hello Email'))
                    ->setFrom('send@example.com')
                    ->setTo('recipe@example.com')
                    ->setBody('La commande peut être encaissée !')
                ;

                $mailer->send($message);
                break;

            case 'payee':
                $message = (new \Swift_Message('Hello Email'))
                    ->setFrom('send@example.com')
                    ->setTo('recipe@example.com')
                    ->setBody('La commande a été payée !')
                ;

                $mailer->send($message);

                $listener = new OrderPayedEvent($entity);
                $this->eventDispatcher->addListener('order.payed', array($listener, 'onOrderPayed'));
                $this->eventDispatcher->addListener('order.payed', array(new CommandePayeeListener(), 'onOrderPayed'));

                break;

            default:
        }

    }

}