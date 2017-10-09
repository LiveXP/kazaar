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

use AppBundle\Entity\Category;
use AppBundle\Entity\CheckingModel;
use AppBundle\Entity\MorningCheckModel;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

/**
 * Class CheckingModelFixture
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class CheckingModelFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $datas = Yaml::parse(file_get_contents(__DIR__."/../Data/checking_model.yml"));
        foreach ($datas as $data) {
            $entity = new CheckingModel();
            $entity
                ->setName($data['name'])
                ->setCategory($this->getCategory($manager, $data['category']))
                ->setDescription($data['description'])
                ->setOccurrence("daily")
                ->setMorningCheckModel($this->getMorningCheckModel($manager, $data['morningCheckModel']));

            $manager->persist($entity);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param string $category the category name
     *
     * @return Category|null|object
     */
    public function getCategory(ObjectManager $manager, $category)
    {
        return $manager->getRepository("AppBundle:Category")->findOneBy(['name' => $category]);
    }

    /**
     * @param ObjectManager $manager
     * @param string $mcm the MorningCheckModel name
     *
     * @return MorningCheckModel|null|object
     */
    public function getMorningCheckModel(ObjectManager $manager, $mcm)
    {
        return $manager->getRepository("AppBundle:MorningCheckModel")->findOneBy(['name' => $mcm]);
    }



    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}
