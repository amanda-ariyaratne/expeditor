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
use DateTimeInterface;
use Symfony\Component\Form\FormInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class TruckTripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',DateTimeType::class)

            ->add('start_time',DateTimeType::class);
            $formModifier = function (FormInterface $form, DateTimeInterface $time = null) {
                $truckRoutes = null === $time ? [] : $this->entityManager->getRepository(TruckRoute::class)->findD($time);
                $form->add('truck_route', EntityType::class, [
                    'class' => 'App\Entity\TruckRoute',
                    'placeholder' => '',
                    'choice_label' => 'name',
                    'choice_value' => 'id',
                    'choices' => $truckRoutes,
                ]);
            };
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $data = $event->getData();
                    $formModifier($event->getForm(), array_key_exists('start_time', $data) ? $data['start_time'] : null);
                }
            );
            $builder->get('start_time')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $time = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(), $time);
                }
            );
        }
    
            ->add('driver', EntityType::class, [
                        'class' => Driver::class,
                        'query_builder' => function (EntityRepository $er) {
                            return  $er->findD('start_time');
                                
                        },
                        'choice_label' => 'id',
                        'choice_value' => 'id',
                        'placeholder' => ''
                    ])
            ->add('driver_assistant', EntityType::class, [
                'class' => DriverAssistant::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->findDA(true);
                },
                'choice_label' => 'id',
                'choice_value' => 'id',
                'placeholder' => ''
            ])
            
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