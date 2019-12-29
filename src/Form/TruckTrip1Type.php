<?php

namespace App\Form;

use App\Entity\TruckTrip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use App\Entity\Truck;
use App\Entity\Store;
use App\Entity\Driver;
use App\Entity\DriverAssistant;
use App\Entity\TruckRoute;
use DateTimeInterface;
use Symfony\Component\Form\FormInterface;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class TruckTrip1Type extends AbstractType
{
    
        private $entityManager;
    
        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->entityManager = $entityManager;
        }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            
            
            
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
            



            
            ->add('date',DateType::class)
            ->add('start_time',TimeType::class)
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
            
            
            
            
            ;
            
    }
           
    
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
             
            
        ]);
    }
}
