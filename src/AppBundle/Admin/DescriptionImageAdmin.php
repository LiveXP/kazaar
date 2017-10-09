<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Admin;

use AppBundle\Entity\DescriptionImage;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

/**
 * Class DescriptionImageAdmin
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class DescriptionImageAdmin extends AbstractAdmin
{
    /**
     * The directory where the Image are uploaded
     *
     * @var string $webdir
     */
    private $webdir;

    /**
     * DescriptionImageAdmin constructor.
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     * @param string $webdir
     */
    public function __construct($code, $class, $baseControllerName, $webdir)
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->webdir = $webdir;
    }

    /**
     * @param DescriptionImage $image
     */
    public function prePersist($image)
    {
        $this->manageFileUpload($image);
    }

    /**
     * @param DescriptionImage $image
     */
    private function manageFileUpload($image)
    {
        if ($image->getFile()) {
            $image->refreshUpdated();
        }
    }

    /**
     * @param DescriptionImage $image
     */
    public function preUpdate($image)
    {
        $this->manageFileUpload($image);
    }

    /**
     * Printed name of the current object
     *
     * @param mixed|DescriptionImage $object
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof DescriptionImage ? $object->getPath() : 'Image'; // shown in the breadcrumb on the create view
    }

    /**
     * Form fields
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var DescriptionImage $image */
        $image = $this->getSubject();
        $fileFieldOptions = ['label' => 'Image', 'required' => false];
        if ($image && ($path = $image->getPath())) {
            $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('request_stack')->getCurrentRequest()->getBasePath().$this->webdir.$path;
            $fileFieldOptions['sonata_help'] = "<img src=\"$fullPath\" class=\"admin-preview\" /> <br> <p> Chemin du fichier : $fullPath</p>";
        }
        $formMapper->add('file', 'file', $fileFieldOptions);
    }

    /**
     * Search fields
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('id')->add('path');
    }

    /**
     * List fields
     *
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('id', null, ['label' => 'ID'])
            ->addIdentifier('path', 'image', [
                'prefix' => $this->webdir,
                'width' => 200,
                'height' => null,
                'label' => "Image"
            ]);
    }
}