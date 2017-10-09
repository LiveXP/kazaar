<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Checking;
use AppBundle\Entity\DescriptionImage;
use AppBundle\Entity\Image;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * UploadListener for Image and DescriptionImage
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class UploadListener
{
    /**
     * Upload path for Image
     *
     * @var string $path
     */
    private $path;

    /**
     * Upload path for DescriptionImage
     *
     * @var string $descriptionPath
     */
    private $descriptionPath;

    /**
     * ProjectUploadListener constructor.
     *
     * @param string $path
     * @param string $descriptionPath
     */
    public function __construct($path, $descriptionPath)
    {
        $this->path = $path;
        $this->descriptionPath = $descriptionPath;
    }

    /**
     * Called at the postLoad Event
     * Used to fill the file parameter with a File object
     *
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Image) {
            $entity->setFile(new File($this->path . '/' . $entity->getPath()));
        }
        if ($entity instanceof DescriptionImage) {
            $entity->setFile(new File($this->descriptionPath . '/' . $entity->getPath()));
        }
    }

    /**
     * Called at the prePersist Event

     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    /**
     * Called at the preUpdate Event

     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    /**
     * Called from with the prePersist and preUpdate events
     *
     * @param $entity
     */
    public function uploadFile($entity)
    {
        /** @var UploadedFile $file */
        if ($entity instanceof DescriptionImage && $entity->getFile() instanceof UploadedFile) {
            $file = $entity->getFile();
            
            $fileName = substr(md5(uniqid()), 0, 5);
            $fileName = $fileName.'.'.$file->guessExtension();
            $file->move($this->descriptionPath, $fileName);
            $entity->setPath($fileName);
        }
    }


    /**
     * Create an image and upload it from a base64 string
     *
     * @param string $base64Image
     * @param string $output
     *
     * @return string
     */
    public function base64Image($base64Image, $output)
    {
        $splited = explode(',', substr($base64Image, 5), 2);
        $mime = $splited[0];
        $base64 = $splited[1];

        $mimeSplit = explode(';', $mime, 2);
        $mimeSplit = explode('/', $mimeSplit[0], 2);

        $extension = end($mimeSplit);
        $output_file = $output . '.' . $extension;
        $filepath = $this->buildPath($output_file);
        file_put_contents($this->path . "/" . $filepath, base64_decode($base64));

        return $filepath;
    }

    /**
     * Build and return the correct upload path (/year/month/day/...)
     *
     * @param string $filename
     *
     * @return string
     */
    public function buildPath($filename)
    {
        $path = (new \DateTime())->format("Y/m/d/");
        $fullpath = $this->path. "/" .$path;

        $fs = new Filesystem();
        if (!$fs->exists($fullpath)) {
            $fs->mkdir($fullpath);
        }

        return $path.$filename;
    }

    /**
     * Create and return an Image Entity from a base64 string
     *
     * @param string $base64Image
     * @param string $output
     * @param Checking $checking
     *
     * @return Image
     */
    public function initImage($base64Image, $output, Checking $checking)
    {
        $image = new Image();
        $image->setPath($this->base64Image($base64Image, $output))
            ->setChecking($checking)->setUpdated(new \DateTime());

        return $image;
    }

}