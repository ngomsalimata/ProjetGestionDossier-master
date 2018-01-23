<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TraitementDossierType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('etat','choice',array(
                'choices'=>array(
                    'En Cours'=>'En Cours',
                    'Suspendu'=>'Suspendu',
                    'Terminé'=>'Terminé',
                    
                )
            ))
            ->add('dateDebut', 'datetime')
            ->add('dateFin', 'datetime')
            ->add('remarques')
            ->add('user')
            ->add('dossier')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\TraitementDossier'
        ));
    }
}
