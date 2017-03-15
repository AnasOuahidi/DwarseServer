<?php

namespace EmployeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeType extends AbstractType {
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('nom')->add('prenom')->add('dateNaissance')->add('numTel')->add('password')->add('civilite');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array('data_class' => 'EmployeBundle\Entity\Employe', 'csrf_protection' => false));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'employebundle_employe';
    }


}
