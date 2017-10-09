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
 * Checking
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 *
 * @ORM\Table(name="checking")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CheckingRepository")
 */
class Checking
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
     * @var string $internalComment
     *
     * @ORM\Column(name="internal_comment", type="text", nullable=true)
     */
    private $internalComment;

    /**
     * @var string $comment
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var string $category
     *
     * @ORM\Column(name="category", type="text")
     */
    private $category;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var MorningCheck $morningCheck
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MorningCheck", inversedBy="checkings")
     * @ORM\JoinColumn(name="morningCheck", referencedColumnName="id")
     */
    private $morningCheck;

    /**
     * @var Status $status
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Status")
     * @ORM\JoinColumn(name="status", referencedColumnName="id", nullable=true)
     */
    private $status;

    /**
     * @var ArrayCollection $images
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Image", mappedBy="checking", cascade={"all"})
     * @ORM\JoinTable(name="images")
     */
    private $images;

    /**
     * @var int $position
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->images = new ArrayCollection();
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
     * @return Checking
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
     * @return Checking
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
     * @return string
     */
    public function getInternalComment()
    {
        return $this->internalComment;
    }

    /**
     * @param string $internalComment
     * @return Checking
     */
    public function setInternalComment($internalComment)
    {
        $this->internalComment = $internalComment;

        return $this;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Checking
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Checking
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
     * Set morningCheck
     *
     * @param MorningCheck $morningCheck
     *
     * @return Checking
     */
    public function setMorningCheck(MorningCheck $morningCheck = null)
    {
        $this->morningCheck = $morningCheck;

        return $this;
    }

    /**
     * Get morningCheck
     *
     * @return MorningCheck
     */
    public function getMorningCheck()
    {
        return $this->morningCheck;
    }

    /**
     * Set status
     *
     * @param Status $status
     *
     * @return Checking
     */
    public function setStatus(Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Checking
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add image
     *
     * @param Image $image
     *
     * @return Checking
     */
    public function addImage(Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param Image $image
     */
    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return ArrayCollection|Image[]
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Checking
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
