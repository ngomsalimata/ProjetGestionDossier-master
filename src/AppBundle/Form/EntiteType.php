<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntiteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('sigle')
            ->add('adresse')
            ->add('numeroTelephone1')
            ->add('numeroTelephone2')
            ->add('email')
            ->add('entite','entity',array(
                'class'=>'AppBundle:Entite',
                'empty_value'=>'Selectionner l\'entité parente',
                'required'=>FALSE,
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Entite'
        ));
    }
}
