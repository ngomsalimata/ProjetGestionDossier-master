<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom')
            ->add('nom')
            ->add('datenaiss')
            ->add('lieunaiss')
            ->add('telephone')
            ->add('adresse')
            ->add('enabled')
            ->add('idgroup')
            ->add('email','email')
            ->add('plainPassword','password',array(
                'required'=>FALSE,
            ))
            ->add('username')
            ->add('entite','entity',array(
                'class'=>'AppBundle:Entite',
                'label'=>'Entite principale'
                ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }
}
