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
 * MorningCheckModel
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 *
 * @ORM\Table(name="morning_check_model")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MorningCheckModelRepository")
 */
class MorningCheckModel
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
     * @var string $mailingName
     *
     * @ORM\Column(name="mailing_name", type="string", length=255, nullable=true)
     */
    private $mailingName;

    /**
     * @var array $recipients
     *
     * @ORM\Column(name="recipients", type="json_array", nullable=true)
     */
    private $recipients;

    /**
     * @var array $cc
     *
     * @ORM\Column(name="cc", type="json_array", nullable=true)
     */
    private $cc;

    /**
     * @var int $position
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="boolean")
     */
    private $email;

    /**
     * @var ArrayCollection $checkingModels
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CheckingModel", mappedBy="morningCheckModel", cascade={"persist", "remove"})
     */
    private $checkingModels;

    /**
     * @var ArrayCollection $categories
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Category", mappedBy="morningCheckModel", cascade={"persist", "remove"})
     */
    private $categories;

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
        $this->checkingModels = new ArrayCollection();
        $this->categories = new ArrayCollection();
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
     * @return MorningCheckModel
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
     * Set recipients
     *
     * @param array $recipients
     *
     * @return MorningCheckModel
     */
    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;

        return $this;
    }

    /**
     * Get recipients
     *
     * @return array
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * Set cc
     *
     * @param array $cc
     *
     * @return MorningCheckModel
     */
    public function setCc($cc)
    {
        $this->cc = $cc;

        return $this;
    }

    /**
     * Get cc
     *
     * @return array
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return MorningCheckModel
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
     * Set email
     *
     * @param boolean $email
     *
     * @return MorningCheckModel
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return boolean
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Add checkModel
     *
     * @param CheckingModel $checkModel
     *
     * @return MorningCheckModel
     */
    public function addCheckModel(CheckingModel $checkModel)
    {
        $this->checkingModels[] = $checkModel;

        return $this;
    }

    /**
     * Remove checkModel
     *
     * @param CheckingModel $checkModel
     */
    public function removeCheckModel(CheckingModel $checkModel)
    {
        $this->checkingModels->removeElement($checkModel);
    }

    /**
     * @return ArrayCollection|CheckingModel[]
     */
    public function getCheckingModels()
    {
        return $this->checkingModels;
    }

    /**
     * Add category
     *
     * @param Category $category
     *
     * @return MorningCheckModel
     */
    public function addCategory(Category $category)
    {
        $category->setMorningCheckModel($this);
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $category->setMorningCheckModel(null);
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return ArrayCollection|Category[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param ArrayCollection|array $categories
     */
    public function setCategories($categories)
    {
        foreach ($categories as $category) {
            $category->setMorningCheckModel($this);
        }

        $this->categories = is_array($categories) ? new ArrayCollection($categories) : $categories;
    }

    /**
     * Add checkingModel
     *
     * @param CheckingModel $checkingModel
     *
     * @return MorningCheckModel
     */
    public function addCheckingModel(CheckingModel $checkingModel)
    {
        $this->checkingModels[] = $checkingModel;

        return $this;
    }

    /**
     * Remove checkingModel
     *
     * @param CheckingModel $checkingModel
     */
    public function removeCheckingModel(CheckingModel $checkingModel)
    {
        $this->checkingModels->removeElement($checkingModel);
    }

    /**
     * Set mailingName
     *
     * @param string $mailingName
     *
     * @return MorningCheckModel
     */
    public function setMailingName($mailingName)
    {
        $this->mailingName = $mailingName;

        return $this;
    }

    /**
     * Get mailingName
     *
     * @return string
     */
    public function getMailingName()
    {
        return $this->mailingName;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return MorningCheckModel
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
