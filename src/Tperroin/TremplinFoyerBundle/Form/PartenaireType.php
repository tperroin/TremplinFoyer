<?php

namespace Tperroin\TremplinFoyerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PartenaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('site')
            ->add('contact')
            ->add('image')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tperroin\TremplinFoyerBundle\Entity\Partenaire'
        ));
    }

    public function getName()
    {
        return 'tperroin_tremplinfoyerbundle_partenairetype';
    }
}
