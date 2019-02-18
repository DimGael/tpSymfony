<?php
/**
 * Created by PhpStorm.
 * User: gael
 * Date: 11/02/19
 * Time: 17:03
 */

namespace App\EventListener;


use App\Entity\Commande;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class OrderPayedEvent extends Event
{
    const NAME = 'order.payed';

    protected $commande;

    public function __construct(Commande $commande)
    {
        $this->commande = $commande;
    }

    public function onOrderPayed()
    {
        // La commande a été payée !
        return $this->commande;
    }

}