<?php

namespace App\Form;

use App\Entity\DriverAssistant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DriverAssistantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('NIC')
            ->add('first_name')
            ->add('last_name')
            ->add('created_at')
            ->add('updated_at')
            ->add('deleted_at')
            ->add('store')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DriverAssistant::class,
        ]);
    }
}
