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

/**
 * Category
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */

class Category
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
     * @var MorningCheckModel $morningCheckModel
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MorningCheckModel", inversedBy="categories", cascade={"persist"})
     * @ORM\JoinColumn(name="morningCheckModel", referencedColumnName="id")
     */
    private $morningCheckModel;


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
     * @return Category
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
     * Set morningCheckModel
     *
     * @param MorningCheckModel $morningCheckModel
     *
     * @return Category
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
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
    }
}
