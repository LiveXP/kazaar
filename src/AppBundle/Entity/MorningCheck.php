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
 * MorningCheck
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 *
 * @ORM\Table(name="morning_check")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MorningCheckRepository")
 */
class MorningCheck
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
     * @ORM\Column(name="recipients", type="json_array")
     */
    private $recipients;

    /**
     * @var array $cc
     *
     * @ORM\Column(name="cc", type="json_array")
     */
    private $cc;

    /**
     * @var bool $closed
     *
     * @ORM\Column(name="closed", type="boolean")
     */
    private $closed = false;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="boolean")
     */
    private $email;

    /**
     * @var ArrayCollection $checkings
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Checking", mappedBy="morningCheck", cascade={"persist"})
     */
    private $checkings;

    /**
     * @var int $position
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->checkings = new ArrayCollection();
        $this->date = new \DateTime();
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
     * @return MorningCheck
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
     * @return MorningCheck
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
     * @return MorningCheck
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
     * Set closed
     *
     * @param boolean $closed
     *
     * @return MorningCheck
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;

        return $this;
    }

    /**
     * Get closed
     *
     * @return boolean
     */
    public function getClosed()
    {
        return $this->closed;
    }

    /**
     * Add checking
     *
     * @param Checking $checking
     *
     * @return MorningCheck
     */
    public function addChecking(Checking $checking)
    {
        $this->checkings[] = $checking;
        $checking->setMorningCheck($this);

        return $this;
    }

    /**
     * Remove checking
     *
     * @param Checking $checking
     */
    public function removeChecking(Checking $checking)
    {
        $this->checkings->removeElement($checking);
    }

    /**
     * Get checkings
     *
     * @return ArrayCollection|Checking[]
     */
    public function getCheckings()
    {
        return $this->checkings;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return MorningCheck
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set mailingName
     *
     * @param string $mailingName
     *
     * @return MorningCheck
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
     * Set email
     *
     * @param boolean $email
     *
     * @return MorningCheck
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
     * Set position
     *
     * @param integer $position
     *
     * @return MorningCheck
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

}
