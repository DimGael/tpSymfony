<?php
/**
 * Created by PhpStorm.
 * User: gael
 * Date: 11/02/19
 * Time: 17:14
 */

namespace App\EventListener;


use App\Entity\Commande;
use App\Services\RhService;

class CommandePayeeListener
{

    /** @var RhService */
    private $rhService;

    public function setRhService(RhService $service)
    {
        $this->rhService = $service;
    }

    /**
     * @param $order Commande
     */
    public function onOrderPayed($order)
    {
        dump($this->rhService->setOrderPayed($order));
    }

}