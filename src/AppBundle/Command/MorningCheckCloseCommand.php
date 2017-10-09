<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MorningCheckCloseCommand
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class MorningCheckCloseCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('morningcheck:close')
            ->setAliases(['mc:close'])
            ->setDescription('Close all open morning check')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $mc = $manager->getRepository("AppBundle:MorningCheck")->findBy(['closed' => false]);


        foreach ($mc as $morningCheck) {
            $morningCheck->setClosed(true)->setClosedBy("computer");
            $manager->persist($morningCheck);
        }

        $manager->flush();
    }

}
