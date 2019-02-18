<?php
/**
 * Created by PhpStorm.
 * User: gael
 * Date: 06/02/19
 * Time: 17:18
 */

namespace App\Services;


use App\Entity\Commande;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class RhService
{
    /** @var LoggerInterface */
    private $logger;
    private $api_endpoint;
    private $em;

    /**
     * RhService constructor.
     * @param LoggerInterface $logger
     * @param $api_endpoint
     * @param $em EntityManager
     */
    public function __construct(LoggerInterface $logger, $api_endpoint, $em)
    {
        $this->logger = $logger;
        $this->api_endpoint = $api_endpoint;
        $this->em = $em;
    }

    public function getPeople()
    {
        $url= $this->api_endpoint."?method=people";

        $content = file_get_contents($url);
        $listePersonnel = json_decode($content);

        $users = array();

        foreach ($listePersonnel as $object) {

            if (!$this->em->getRepository(User::class)->findBy(array('username' => $object->id))) {
                $user = new User();

                $user->setUsername($object->id);
                $user->setFirstname($object->firstname);
                $user->setLastname($object->lastname);
                $user->setEmail($object->email);
                $user->setJobtitle($object->jobtitle);
                $user->setEnabled(true);
                $user->setCreatedAt(new \DateTime());

                array_push($users, $user);
            }
        }

        return $users;
    }

    public function getDayTeam(\DateTime $date)
    {
        if(!$date) $date = new \DateTime();
        $url= $this->api_endpoint."?method=planning&amp;date=".$date->format('Y-m-d');

        $content = file_get_contents($url);
        $listePersonnel = json_decode($content);

        $users = array();

        foreach ($listePersonnel->midi as $object) {

            if (!$this->em->getRepository(User::class)->findBy(array('username' => $object->id))) {
                $user = new User();

                $user->setUsername($object->id);
                $user->setFirstname($object->firstname);
                $user->setLastname($object->lastname);
                $user->setEmail($object->email);
                $user->setJobtitle($object->jobtitle);
                $user->setEnabled(true);
                $user->setCreatedAt(new \DateTime());

                array_push($users, $user);
            }
        }

        return $users;
    }

    public function setOrderPayed(Commande $order)
    {
        $url = $this->api_endpoint.
            "?method=order&order=".$order->getId().
            "&amount=".$order->getPrixTotal().
            "&server=".$order->getUtilisateur()->getUsername();

        return json_decode( file_get_contents($url) );
    }

}