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

use AppBundle\Entity\MorningCheckModel;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

/**
 * Class MorningCheckModelAdmin
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class MorningCheckModelAdmin extends AbstractAdmin
{
    /**
     * Printed name of the current object
     *
     * @param MorningCheckModel $object
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof MorningCheckModel ? $object->__toString() : 'MorningCheckModel';
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
            ->add('mailingName', null, ['label' => 'Titre du mail'])
            ->add('position', null, ['label' => 'Position'])
            ->add('email', null, ['label' => 'Email'])
            ->add('enabled', null, ['label' => 'Activé'])
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
            ->add('mailingName', null, ['label' => 'Titre du mail'])
            ->add('position', null, ['label' => 'Position'])
            ->add('email', null, ['label' => 'Email'])
            ->add('enabled', null, ['label' => 'Activé'])
            ->add('_action', null, ['actions' => ['show' => null, 'edit' => null, 'delete' => null]])
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
            ->with('Contenu', ['class' => 'col-md-7'])
            ->add('name', null, ['label' => 'Nom'])
            ->add('mailingName', null, [
                'label' => 'Titre du mail',
                'help' => 'Le titre sera au format suivant : NB KO - Morning Check TITRE_EVENTUEL du DATEDUJOUR)'])
            ->add('categories', 'sonata_type_collection', [
                'by_reference' => false,
                'label' => 'Catégories',
                'help' => 'Entrer au minimum une catégorie'
            ],
                ['edit' => 'inline', 'inline' => 'table'])
            ->add('position', null, ['label' => 'Position'])
            ->add('email', null, ['label' => 'Email'])
            ->add('enabled', null, ['label' => 'Activé'])
            ->end()

            ->with('Mailing', ['class' => 'col-md-5'])
            ->add('recipients', CollectionType::class, [
                'label' => 'Destinataires',
                'entry_type' => EmailType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])->add('cc', CollectionType::class, [
                'label' => 'Destinataires Secondaires',
                'entry_type' => EmailType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false
            ])->end();
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
            ->add('mailingName', null, ['label' => 'Titre du mail'])
            ->add('recipients', 'array', ['label' => 'Destinataires'])
            ->add('cc', 'array', ['label' => 'Destinataire(s) Secondaires'])
            ->add('categories', 'sonata_type_collection')
            ->add('position', null, ['label' => 'Position'])
            ->add('email', null, ['label' => 'Email'])
            ->add('enabled', null, ['label' => 'Activé'])
        ;
    }

    /**
     * Routes available for this Admin
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('reloadCategories', 'reload_categories');
    }

}
