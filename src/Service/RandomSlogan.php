<?php
/**
 * Created by PhpStorm.
 * User: gael
 * Date: 20/11/18
 * Time: 16:18
 */

namespace App\Service;


use Psr\Log\LoggerInterface;

class RandomSlogan{

    private static $listSlogans = [
        "La vie change avec %s",
        "Avec %s c'est pour la vie",
        "Comtemplez la grandeur de %s",
        "%s, il faut le voir pour le croire",
        "%s et c'est parti",
        "Juste %s !"
    ];

    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger){
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger():LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param string $argument
     * @return string
     */
    public function getSlogan(string $argument):string{
        $slogan = self::$listSlogans[array_rand(self::$listSlogans)];
        return sprintf(
            $slogan,
            $argument
        );
    }

}