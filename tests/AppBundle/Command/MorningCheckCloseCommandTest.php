<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\AppBundle\Command;

use AppBundle\Command\MorningCheckCloseCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use tests\AppBundle\BaseTest;

/**
 * Class MorningCheckCloseCommandTest
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class MorningCheckCloseCommandTest extends BaseTest
{
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $morningCheckModel = $this->getManager()->getRepository("AppBundle:MorningCheckModel")->find(1);
        $this->getContainer()->get('app.worker')->duplicateMorningCheck($morningCheckModel);

        $application = new Application($kernel);
        $application->add(new MorningCheckCloseCommand());

        $command = $application->find('morningcheck:close');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $morningChecks = $this->getManager()->getRepository("AppBundle:MorningCheck")->findBy(['closed' => false]);

        $this->assertEquals(0, count($morningChecks));
    }


}