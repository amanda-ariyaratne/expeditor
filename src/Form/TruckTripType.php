<?php

namespace App\Form;

use App\Entity\TruckTrip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use App\Entity\Truck;
use App\Entity\Driver;
use App\Entity\DriverAssistant;
use App\Entity\TruckRoute;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class TruckTripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',DateType::class)

            ->add('start_time',TimeType::class)
            
            
            
            ->add('truck', EntityType::class, [
                'class' => Truck::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.deleted_at is NULL');
                },
                'choice_label' => 'id',
                'choice_value' => 'id',
                'placeholder' => ''
            ])
            ->add('driver', EntityType::class, [
                'class' => Driver::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.deleted_at is NULL');
                },
                'choice_label' => 'id',
                'choice_value' => 'id',
                'placeholder' => ''
            ])
            ->add('driver_assistant', EntityType::class, [
                'class' => DriverAssistant::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.deleted_at is NULL');
                },
                'choice_label' => 'id',
                'choice_value' => 'id',
                'placeholder' => ''
            ])
            ->add('truck_route', EntityType::class, [
                'class' => TruckRoute::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.deleted_at is NULL');
                },
                'choice_label' => 'id',
                'choice_value' => 'id',
                'placeholder' => ''
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TruckTrip::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'truck_trip'
        ]);
    }
}
