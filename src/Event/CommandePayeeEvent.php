<?php
/**
 * Created by PhpStorm.
 * User: gael
 * Date: 18/02/19
 * Time: 21:28
 */

namespace App\Event;


use App\Entity\Commande;
use Symfony\Component\EventDispatcher\Event;

class CommandePayeeEvent extends Event
{
    const NAME = 'order.payed';

    protected $commande;

    public function __construct(Commande $commande)
    {
        $this->commande = $commande;
        dump('creation de la commande');
        die;
    }

    public function getOrder():Commande
    {
        return $this->commande;
    }
}