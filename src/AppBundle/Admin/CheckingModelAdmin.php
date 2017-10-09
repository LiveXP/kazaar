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

use AppBundle\Entity\CheckingModel;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager;

/**
 * Class CheckingModelAdmin
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 *
 * @method ModelManager getModelManager()
 */
class CheckingModelAdmin extends AbstractAdmin
{
    /**
     * The directory where the DescriptionImage are uploaded
     *
     * @var string $webdir
     */
    private $webdir;

    /**
     * DescriptionImageAdmin constructor.
     *
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
     * Printed name of the current object
     *
     * @param mixed|CheckingModel $object
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof CheckingModel ? $object->__toString() : parent::toString($object);
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
            ->add('description', null, ['label' => 'Description'])
            ->add('morningCheckModel', null, ['sortable' => 'morningCheckModel.name'])
            ->add('occurrence', null, ['label' => 'Occurrence'])
            ->add('position', null, ['label' => 'Position'])
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
            ->add('morningCheckModel', null, ['sortable' => 'morningCheckModel.name'])
            ->add('description', null, ['label' => 'Description'])
            ->add('position', null, ['label' => 'Position'])
            ->add('occurrence', null, ['label' => 'Occurrence'])
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
        $em = $this->getModelManager()->getEntityManager('AppBundle:MorningCheckModel');

        //Only MorningCheckModel with at least 1 Category will be displayed
        $query = $em->createQueryBuilder()
            ->select('q')
            ->from('AppBundle:MorningCheckModel', 'q')
            ->leftJoin("q.categories", "categories")
            ->where('categories.morningCheckModel = q.id')
            ->getQuery();

        $formMapper
            ->with("Contenu", ['class' => "col-md-7"])
            ->add('name', null, ['label' => 'Nom'])
            ->add('description', null, ['label' => 'Description'])
            ->add('position', null, ['label' => 'Position'])
            ->add('occurrence', 'choice', [
                'label' => 'Occurrence',
                'choices' => [
                    "Journalier" => 'daily',
                    "Hebdomadaire (tous les lundis)" => 'weekly',
                    "Mensuel (tous les premiers lundis du mois)" => 'montly'
                ]
            ])->add('morningCheckModel', 'sonata_type_model', [
                'label' => "MorningCheckModel",
                'query' => $query,
                'btn_add' => false,
                'btn_delete' => false,
                'help' => "Seulement les MorningCheckModel avec au moins 1 Category seront affichés"
            ])->add('category', 'sonata_type_model', [
                'label' => "Catégories",
                'btn_add' => false,
                'btn_delete' => false
            ])->add('enabled', null, ['label' => 'Activé'])
            ->end()

            ->with("Images", ['class' => "col-md-5"])
            ->add('descriptionImages','sonata_type_collection',
                [
                    'by_reference' => false,
                    'label' => false,
                    "help" => sprintf("Le chemin à entrer dans le markdown est : '%s'", $this->webdir)
                ],
                ['edit' => 'inline', 'inline' => 'table']
            )->end();

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
            ->add('description', null, ['label' => 'Description'])
            ->add('category', null, ['label' => 'Catégorie'])
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
