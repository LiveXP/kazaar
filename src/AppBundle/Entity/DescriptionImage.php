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

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DescriptionImage
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 *
 * @ORM\Table(name="description_image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DescriptionImageRepository")
 */
class DescriptionImage
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
     * @var CheckingModel $checkingModel
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CheckingModel", inversedBy="descriptionImages")
     * @ORM\JoinColumn(name="checking_model", referencedColumnName="id")
     */
    private $checkingModel;

    /**
     * @var string $path
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var File $file
     *
     * @Assert\File(
     *     maxSize = "10M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/jpg"},
     *     maxSizeMessage = "La taille de fichier maximim autorisée est de 10MO.",
     *     mimeTypesMessage = "Seulement les images sont autorisées."
     * )
     */
    private $file;

    /**
     * @var \DateTime $updated
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

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
     * Set path
     *
     * @param string $path
     *
     * @return DescriptionImage
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return DescriptionImage
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set checking
     *
     * @param CheckingModel $checkingModel
     *
     * @return DescriptionImage
     */
    public function setCheckingModel(CheckingModel $checkingModel = null)
    {
        $this->checkingModel = $checkingModel;

        return $this;
    }

    /**
     * Get checking
     *
     * @return CheckingModel
     */
    public function getCheckingModel()
    {
        return $this->checkingModel;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }
    /**
     * @param File $file
     * @return DescriptionImage
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }


    /**
     * Updates the hash value to force the preUpdate and postUpdate events to fire
     */
    public function refreshUpdated()
    {
        $this->setUpdated(new \DateTime());
    }

}
