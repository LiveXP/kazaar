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
use AppBundle\Entity\MorningCheckModel;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

/**
 * Class MorningCheckModelFixture
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class MorningCheckModelFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $datas = Yaml::parse(file_get_contents(__DIR__."/../Data/morning_check_model.yml"));
        foreach ($datas as $data) {
            $entity = new MorningCheckModel();
            $entity
                ->setName($data['name'])
                ->setMailingName($data['name'])
                ->setRecipients($data['recipients'])
                ->setCc($data['cc'])
                ->setPosition($data['position'])
                ->setEmail($data['email'])
                ->setCategories($this->getCategories($manager, $data['categories']));
            
            $manager->persist($entity);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param array $categories an array of category name
     *
     * @return Category[]
     */
    public function getCategories(ObjectManager $manager, array $categories)
    {
        $cats = [];
        foreach ($categories as $category) {
            if (null === $cat = $manager->getRepository("AppBundle:Category")->findOneBy(['name' => $category])) {
                $cat = new Category();
                $cat->setName($category);
            }

            $cats[] = $cat;
        }

        return $cats;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
