<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\AppBundle\Utils;

use AppBundle\Entity\Checking;
use AppBundle\Entity\CheckingModel;
use AppBundle\Entity\MorningCheck;
use AppBundle\Entity\MorningCheckModel;
use tests\AppBundle\BaseTest;

/**
 * Class WorkerTest
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class WorkerTest extends BaseTest
{
    public function testInitMorningCheck()
    {
        $morningCheckModel = $this->getManager()->getRepository("AppBundle:MorningCheckModel")->findNext();

        //Duplicate Checking to have a last Checking
        $checkingModel = $morningCheckModel->getCheckingModels()->first();
        $checking = $this->getContainer()->get('app.worker')->duplicateChecking($checkingModel);
        $this->verifyCheckingData($checkingModel, $checking);

        $status = $this->getManager()->getRepository("AppBundle:Status")->findOneBy(['name' => "KO"]);
        $checking->setDate($checking->getDate()->sub(new \DateInterval("P1D")))->setStatus($status)
        ->setInternalComment("Test");

        $this->getManager()->persist($checking);
        $this->getManager()->flush();


        $morningCheck = $this->getContainer()->get('app.worker')->initMorningCheck($morningCheckModel);
        $this->assertInstanceOf(MorningCheck::class, $morningCheck);
        $this->verifyMorningCheckData($morningCheckModel, $morningCheck);

    }

    public function testPercentage()
    {
        $result = $this->getContainer()->get('app.worker')->percentage(10, 100);
        $this->assertEquals(10, $result);
    }

    public function testGenerateMailTitle()
    {
        $morningCheckModel = $this->getManager()->getRepository("AppBundle:MorningCheckModel")->findNext();
        $morningCheck = $this->getContainer()->get('app.worker')->initMorningCheck($morningCheckModel);

        $status = $this->getManager()->getRepository("AppBundle:Status")->findOneBy(['name' => "KO"]);
        foreach ($morningCheck->getCheckings() as $checking) {
            $checking->setStatus($status);
        }

        //Multiple KO
        $title = $this->getContainer()->get('app.worker')->generateMailTitle($morningCheck);
        $this->assertContains(sprintf("%s KO", count($morningCheck->getCheckings())), $title);

        $status = $this->getManager()->getRepository("AppBundle:Status")->findOneBy(['name' => "OK"]);
        foreach ($morningCheck->getCheckings() as $checking) {
            $checking->setStatus($status);
        }

        $title = $this->getContainer()->get('app.worker')->generateMailTitle($morningCheck);
        $this->assertContains("OK", $title);


        $status = $this->getManager()->getRepository("AppBundle:Status")->findOneBy(['name' => "KO"]);
        $morningCheck->getCheckings()->first()->setStatus($status);

        $title = $this->getContainer()->get('app.worker')->generateMailTitle($morningCheck);
        $this->assertContains("KO", $title);
    }

    /**
     * @param MorningCheckModel $morningCheckModel
     * @param MorningCheck $morningCheck
     */
    private function verifyMorningCheckData(MorningCheckModel $morningCheckModel, MorningCheck $morningCheck)
    {
        $this->assertEquals($morningCheckModel->getName(), $morningCheck->getName());
        $this->assertEquals($morningCheckModel->getCc(), $morningCheck->getCc());
        $this->assertEquals($morningCheckModel->getRecipients(), $morningCheck->getRecipients());
        $this->assertEquals($morningCheckModel->getEmail(), $morningCheck->getEmail());
        $this->assertEquals($morningCheckModel->getPosition(), $morningCheck->getPosition());
    }

    /**
     * @param CheckingModel $checkingModel
     * @param Checking $checking
     */
    private function verifyCheckingData(CheckingModel $checkingModel, Checking $checking)
    {
        $this->assertEquals($checkingModel->getName(), $checking->getName());
        $this->assertEquals($checkingModel->getDescription(), $checking->getDescription());
        $this->assertEquals($checkingModel->getCategory()->getName(), $checking->getCategory());
    }

}