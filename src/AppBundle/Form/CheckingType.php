<?php

/*
 * This file is part of the Kaazar Project
 *
 * (c) 2017 LiveXP <dev@livexp.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CheckingType
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class CheckingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('internalComment', TextareaType::class, [
                'label' => "Commentaire Interne (n'est pas envoyé au client)",
                'required' => false
            ])->add('comment', TextareaType::class, [
                'label' => "Commentaire",
                'required' => false
            ])->add('status', EntityType::class, [
                'label' => "Statut",
                "class" => "AppBundle\Entity\Status",
                'expanded' => true,
                'label_attr' => ['class' => 'radio-inline'],
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Checking']);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_checking';
    }


}
