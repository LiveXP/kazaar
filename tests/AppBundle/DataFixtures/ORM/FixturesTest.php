<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\AppBundle\DataFixtures\ORM;


use AppBundle\Entity\CheckingModel;
use AppBundle\Entity\MorningCheckModel;
use AppBundle\Entity\Status;
use Symfony\Component\Yaml\Yaml;
use tests\AppBundle\BaseTest;

class FixturesTest extends BaseTest
{
    const BASE = __DIR__ . "/../../../../src/AppBundle/DataFixtures/Data/";
    public function testCheckingModelFixture()
    {
        $datas = Yaml::parse(file_get_contents(self::BASE."checking_model.yml"));
        foreach ($datas as $data) {
            $morningCheckModel = $this->getManager()->getRepository("AppBundle:MorningCheckModel")
                ->findOneBy(['name' => $data['morningCheckModel']]);

            $entity = $this->getManager()->getRepository("AppBundle:CheckingModel")
                ->findOneBy(['name' => $data['name'], 'morningCheckModel' => $morningCheckModel]);

            $this->assertInstanceOf(CheckingModel::class, $entity);
            $this->assertEquals($entity->getName(), $data['name']);
            $this->assertEquals($entity->getDescription(), $data['description']);
            $this->assertEquals($entity->getMorningCheckModel()->getName(), $data['morningCheckModel']);
        }
    }

    public function testMorningCheckModelFixture()
    {
        $datas = Yaml::parse(file_get_contents(self::BASE . "morning_check_model.yml"));
        foreach ($datas as $data) {
            $entity = $this->getManager()->getRepository("AppBundle:MorningCheckModel")->findOneBy(['name' => $data['name']]);

            $this->assertInstanceOf(MorningCheckModel::class, $entity);
            $this->assertEquals($entity->getName(), $data['name']);
            $this->assertEquals($entity->getMailingName(), $data['name']);
            $this->assertEquals($entity->getEmail(), $data['email']);
            $this->assertEquals($entity->getRecipients(), null === $data['recipients'] ? [] : $data['recipients']);
            $this->assertEquals($entity->getCc(), null === $data['cc'] ? [] : $data['cc']);
            $this->assertEquals($entity->getPosition(), $data['position']);
            $this->assertGreaterThan(0, $entity->getCheckingModels()->count());
        }
    }

    public function testStatusFixture()
    {
        $datas = Yaml::parse(file_get_contents(self::BASE . "status.yml"));
        foreach ($datas as $data) {
            $entity = $this->getManager()->getRepository("AppBundle:Status")->findOneBy(['name' => $data['name']]);
            $this->assertInstanceOf(Status::class, $entity);
            $this->assertEquals($entity->getName(), $data['name']);
            $this->assertEquals($entity->getColor(), $data['color']);
        }
    }

}