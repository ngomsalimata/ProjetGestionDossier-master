<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DossierType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('degreImportance','choice',array(
                'choices'=>array(
                    'Modéré'=>'Modéré',
                    'Important'=>'Important',
                    'Urgent'=>'Urgent',
                )
            ))
            ->add('dateDebutTraitement', 'datetime')
            ->add('dateFinTraitementPrevu', 'datetime')
            ->add('dureeMaximumTraitement')
            ->add('etat','choice',array(
                'choices'=>array(
                    'En Cours'=>'En Cours',
                    'Suspendu'=>'Suspendu',
                    'Terminé'=>'Terminé',
                )
            ))
            ->add('dateDerniereModification', 'datetime')
            ->add('utilisateurDerniereModification')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Dossier'
        ));
    }
}
