<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends AbstractType {

    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class) {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('prenom')
                ->add('nom')
                ->add('roles')
                ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
                ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
                ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => ' ', 'attr' => array('class' => 'form-control', 'placeholder' => 'Mot de passe')),
                    'second_options' => array('label' => ' ', 'attr' => array('class' => 'form-control', 'placeholder' => 'Repeter mot de passe')),
                    'invalid_message' => 'Désolé, les mots de passe ne correspondent pas !',
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'registration',
        ));
    }

    public function getName() {
        return 'fos_user_registration';
    }

}
