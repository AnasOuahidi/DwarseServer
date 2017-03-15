<?php

namespace EmployeurBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeurType extends AbstractType {
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('libelle')->add('nom')->add('prenom')->add('adresse')->add('siret')->add('numTel')->add('civilite');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array('data_class' => 'EmployeurBundle\Entity\Employeur', 'csrf_protection' => false));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'employeurbundle_employeur';
    }
}
