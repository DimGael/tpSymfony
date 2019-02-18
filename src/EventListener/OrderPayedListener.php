<?php
/**
 * Created by PhpStorm.
 * User: gael
 * Date: 18/02/19
 * Time: 21:41
 */

namespace App\EventListener;


use App\Entity\Commande;
use App\Services\RhService;
use Doctrine\ORM\Event\LifecycleEventArgs;

class OrderPayedListener
{
    private $rhService;

    public function __construct()
    {
        //$this->rhService = $rhService;
    }

    public function orderPayed(LifecycleEventArgs $args)
    {
        dump('coucou');
        dump($args->getEntity());
        die;
    }
}