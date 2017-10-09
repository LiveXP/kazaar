<?php

/*
 * This file is part of the MorningCheck Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Status;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

/**
 * Class StatusFixture
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class StatusFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $datas = Yaml::parse(file_get_contents(__DIR__."/../Data/status.yml"));
        foreach ($datas as $data) {
            $status = new Status();
            $status->setName($data['name'])->setColor($data['color']);
            $manager->persist($status);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
