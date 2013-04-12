<?php

namespace Tperroin\TremplinFoyerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('teaser')
            ->add('image')
            ->add('contenu')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tperroin\TremplinFoyerBundle\Entity\Groupe'
        ));
    }

    public function getName()
    {
        return 'tperroin_tremplinfoyerbundle_groupetype';
    }
}
