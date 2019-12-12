<?php

namespace App\Form;

use App\Entity\Truck;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TruckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('insurance_no')
            ->add('registration_no')
            ->add('created_at')
            ->add('updated_at')
            ->add('deleted_at')
            ->add('store')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Truck::class,
        ]);
    }
}
