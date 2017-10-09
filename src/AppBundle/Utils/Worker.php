<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Checking;
use AppBundle\Entity\CheckingModel;
use AppBundle\Entity\MorningCheck;
use AppBundle\Entity\MorningCheckModel;
use AppBundle\Entity\Status;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class Worker
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class Worker
{
    /** @var ObjectManager $manager */
    private $manager;

    /**
     * The project path (kernel.project_dir)
     *
     * @var string $basepath
     */
    private $basepath;

    /**
     * Worker constructor.
     *
     * @param ObjectManager $manager
     * @param string $basepath
     */
    public function __construct(ObjectManager $manager, $basepath)
    {
        $this->manager = $manager;
        $this->basepath = $basepath;
    }

    /**
     * Initialize the Daily Morning Check by creating a MorningCheck and its Checkings
     *
     * @param MorningCheckModel $morningCheckModel
     *
     * @return MorningCheck
     */
    public function initMorningCheck(MorningCheckModel $morningCheckModel)
    {
        $morningCheck = $this->duplicateMorningCheck($morningCheckModel);
        foreach ($morningCheckModel->getCheckingModels() as $checkingModel) {
            if ($checkingModel->isEnabledToDuplicate()) {
                $morningCheck->addChecking($this->duplicateChecking($checkingModel));
                $this->getManager()->persist($morningCheck);
            }
        }

        $this->getManager()->flush();

        return $morningCheck;
    }

    /**
     * Create a MorningCheck from a MorningCheckModel
     *
     * @param MorningCheckModel $morningCheckModel
     *
     * @return MorningCheck
     */
    public function duplicateMorningCheck(MorningCheckModel $morningCheckModel)
    {
        $morningCheck = new MorningCheck();
        $morningCheck->setName($morningCheckModel->getName())->setCc($morningCheckModel->getCc())
            ->setRecipients($morningCheckModel->getRecipients())->setEmail($morningCheckModel->getEmail())
            ->setPosition($morningCheckModel->getPosition())->setMailingName($morningCheckModel->getMailingName())
        ;

        $this->getManager()->persist($morningCheck);
        $this->getManager()->flush();

        return $morningCheck;
    }

    /**
     * Create a Checking from a CheckingModel

     * @param CheckingModel $checkingModel
     *
     * @return Checking
     */
    public function duplicateChecking(CheckingModel $checkingModel)
    {
        $checking = new Checking();
        $lastChecking = $this->getManager()->getRepository("AppBundle:Checking")
            ->findLast($checkingModel->getName(), $checkingModel->getCategory()->getName());
        $checking
            ->setName($checkingModel->getName())
            ->setDescription($checkingModel->getDescription())
            ->setCategory($checkingModel->getCategory()->getName())
            ->setPosition($checkingModel->getPosition())
        ;

        if (null !== $lastChecking) {
            $checking->setInternalComment($lastChecking->getInternalComment());
        }

        $this->getManager()->persist($checking);
        $this->getManager()->flush();

        return $checking;
    }

    /**
     * Return an array of the MorningCheck Checkings grouped by category
     *
     * @param MorningCheck $morningCheck
     *
     * @return array
     */
    public function groupCheckingByCategory(MorningCheck $morningCheck)
    {
        $final = [];
        $checkings = $this->getManager()->getRepository("AppBundle:Checking")
            ->findBy(['morningCheck' => $morningCheck], ['position' => "ASC"]);

        foreach ($checkings as $checking) {
            $final[$checking->getCategory()][] = $checking;
        }

        return $final;
    }

    /**
     * @return ObjectManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param $done
     * @param $total
     *
     * @return float
     */
    public function percentage($done, $total)
    {
        return round(($done / $total) * 100);
    }

    /**
     * Generate a formated string for the mail title with the $date parameter of the current date if null
     *
     * @param MorningCheck $morningCheck
     * @param \DateTime|null $date
     *
     * @return string
     */
    public function generateMailTitle(MorningCheck $morningCheck, \DateTime $date = null)
    {
        $title = "%ko% - Morning Check %client%du %date%";
        $client = null === $morningCheck->getMailingName() ? "" : $morningCheck->getMailingName()." ";
        $date = null === $date ? new \DateTime(): $date;

        $ko = 0;
        foreach ($morningCheck->getCheckings() as $checking) {
            if ($checking->getStatus() instanceof Status) {
                $ko+= $checking->getStatus()->getName() === "KO" ? 1 : 0;
            }
        }

        if ($ko == 0) {
            $title = strtr($title, ['%ko%' => "OK"]);
        }elseif ($ko == 1) {
            $title = strtr($title, ['%ko%' => "KO"]);
        }else {
            $title = strtr($title, ['%ko%' => sprintf("%s KO", $ko)]);
        }

        return strtr($title, ['%client%' => $client, "%date%" => $date->format("d/m/Y")]);
    }

    /**
     * @return string
     */
    public function getBasepath()
    {
        return $this->basepath;
    }
}