<?php
/**
 * Created by PhpStorm.
 * User: gael
 * Date: 06/02/19
 * Time: 17:04
 */

namespace App\Command;


use App\Services\RhService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportPeopleCommand extends ContainerAwareCommand
{

    private $em;

    public function __construct(?string $name = null, EntityManagerInterface $em)
    {
        parent::__construct($name);
        $this->em = $em;
    }

    public function configure()
    {
        $this
            ->setName('restau:import:people')
            ->setDescription("Importe le personnel du restau")
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var RhService $rhService */
        $rhService = $this->getApplication()->getKernel()->getContainer()->get('restau.service.sh');


        $listUsers = $rhService->getPeople();
        foreach ($listUsers as $user)
            $this->em->persist($user);
        $this->em->flush();

        if(sizeof($listUsers) != 0)
            $output->writeln('New People imported !');
        else
            $output->writeln('No people to import');

    }

}