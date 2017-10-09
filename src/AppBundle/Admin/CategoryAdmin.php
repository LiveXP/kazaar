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

use AppBundle\Entity\Category;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Class CategoryAdmin
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class CategoryAdmin extends AbstractAdmin
{
    /**
     * Printed name of the current object
     *
     * @param Category $object
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof Category ? $object->__toString() : 'Category';
    }
    /**
     * Search fields
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, ['label' => 'Nom'])
        ;
    }

    /**
     * List fields
     *
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name', null, ['label' => 'Nom'])
        ;
    }

    /**
     * Form fields
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, ['label' => 'Nom'])
        ;
    }

    /**
     * Show fields
     *
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name', null, ['label' => 'Nom'])
        ;
    }
}
