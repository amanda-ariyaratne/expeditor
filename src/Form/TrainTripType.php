<?php

namespace App\Form;

use App\Entity\TrainTrip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainTripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('allowed_capacity')
            ->add('start_time')
            ->add('created_at')
            ->add('updated_at')
            ->add('deleted_at')
            ->add('store')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrainTrip::class,
        ]);
    }
}
