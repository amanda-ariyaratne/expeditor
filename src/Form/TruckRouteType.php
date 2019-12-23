<?php

namespace App\Form;

use App\Entity\TruckRoute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TruckRouteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('delivery_charge')
            ->add('created_at')
            ->add('updated_at')
            ->add('deleted_at')
            ->add('store')
            ->add('road')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TruckRoute::class,
        ]);
    }
}
