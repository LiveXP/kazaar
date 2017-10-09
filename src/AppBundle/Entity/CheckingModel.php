<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CheckingModel
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 *
 * @ORM\Table(name="checking_model")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CheckingModelRepository")
 */
class CheckingModel
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var MorningCheckModel $morningCheckModel
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MorningCheckModel", inversedBy="checkingModels")
     * @ORM\JoinColumn(name="morningCheckModel", referencedColumnName="id")
     */
    private $morningCheckModel;

    /**
     * @var Category $category
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinColumn(name="category", referencedColumnName="id")
     */
    private $category;

    /**
     * @var ArrayCollection $descriptionImages
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DescriptionImage", mappedBy="checkingModel", cascade={"all"})
     * @ORM\JoinTable(name="descriptionImages")
     */
    private $descriptionImages;

    /**
     * @var int $position
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position = 1;

    /**
     * @var string $occurrence
     *
     * @ORM\Column(name="occurrence", type="string", length=255)
     */
    private $occurrence = "daily";

    /**
     * @var int $enabled
     *
     * @ORM\Column(name="enabled", type="boolean", options={"default" : 1})
     */
    private $enabled = true;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->descriptionImages = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return CheckingModel
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CheckingModel
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set morningCheckModel
     *
     * @param MorningCheckModel $morningCheckModel
     *
     * @return CheckingModel
     */
    public function setMorningCheckModel(MorningCheckModel $morningCheckModel = null)
    {
        $this->morningCheckModel = $morningCheckModel;

        return $this;
    }

    /**
     * Get morningCheckModel
     *
     * @return MorningCheckModel
     */
    public function getMorningCheckModel()
    {
        return $this->morningCheckModel;
    }

    /**
     * Set category
     *
     * @param Category $category
     *
     * @return CheckingModel
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * Add descriptionImage
     *
     * @param DescriptionImage $descriptionImage
     *
     * @return CheckingModel
     */
    public function addDescriptionImage(DescriptionImage $descriptionImage)
    {
        $descriptionImage->setCheckingModel($this);
        $this->descriptionImages[] = $descriptionImage;

        return $this;
    }

    /**
     * Remove descriptionImage
     *
     * @param DescriptionImage $descriptionImage
     */
    public function removeDescriptionImage(DescriptionImage $descriptionImage)
    {
        $descriptionImage->setCheckingModel(null);
        $this->descriptionImages->removeElement($descriptionImage);
    }

    /**
     * Get descriptionImages
     *
     * @return ArrayCollection|DescriptionImage[]
     */
    public function getDescriptionImages()
    {
        return $this->descriptionImages;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return CheckingModel
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set occurrence
     *
     * @param string $occurrence
     *
     * @return CheckingModel
     */
    public function setOccurrence($occurrence)
    {
        $this->occurrence = $occurrence;

        return $this;
    }

    /**
     * Get occurrence
     *
     * @return string
     */
    public function getOccurrence()
    {
        return $this->occurrence;
    }

    /**
     * Check the occurrence and the current date to see if the CheckingModel should be duplicated
     * The occurrences are defined in the admin : (daily, weekly, monthly)
     * Also check if the CheckingModel is enabled
     *
     * @return bool
     */
    public function isEnabledToDuplicate()
    {
        $occ = $this->occurrence;
        $weekly = (new \DateTime("monday this week"))->format('Y-m-d');
        $monthly = (new \DateTime("first monday of this month"))->format('Y-m-d');
        $date = (new \DateTime())->format('Y-m-d');

        $isToday = $occ === "daily" || empty($occ);
        $isWeeklyToday = $occ === "weekly" && $date === $weekly;
        $isMonthlyToday = $occ === "monthly" && $date === $monthly;

        if (($isToday || $isWeeklyToday || $isMonthlyToday) && $this->enabled) {
            return true;
        }

        return false;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return CheckingModel
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
}
