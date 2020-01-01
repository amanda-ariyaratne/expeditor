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


class TruckTrip2Type extends AbstractType
{
    
        private $entityManager;
    
        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->entityManager = $entityManager;
        }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$drivers =   $this->entityManager->getRepository(Driver::class)->getAll();
        $builder
        
        

        ->add('date',DateType::class,[
            'disabled' => true,
            'attr' => array('readonly' => true)
        ])
        ->add('start_time',TimeType::class,[
            'disabled' => true,
            'attr' => array('readonly' => true)
        ])
        ->add('truck_route', EntityType::class, [
            'disabled' => true,
            'class' => TruckRoute::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('s')
                    ->where('s.deleted_at is NULL');
            },
            'choice_label' => 'id',
            'choice_value' => 'id',
            'attr' => array('readonly' => true),
            'placeholder' => ''
        ])
        ;
            
        
        $formModifier = function (FormInterface $form,DateTimeInterface $date=null, DateTimeInterface $time = null,TruckRoute $truckRoute=null) 
            {   //dump($truckRoute);
                if ($truckRoute!=null){
                    $max_time=$truckRoute-> getMaxTimeAllocation();
                    $store_id=$truckRoute->getStore()->getId();
                }
                else{
                    $max_time=null;
                    $store_id=null;
                }
                //dump($max_time);
                
                $drivers =   $this->entityManager->getRepository(Driver::class)->findD($time,$max_time,$date,$store_id);
                $driverAs =   $this->entityManager->getRepository(DriverAssistant::class)->findDA($time,$max_time,$date,$store_id);
                $trucks=$this->entityManager->getRepository(Truck::class)->getByStoreAndTime($time,$max_time,$date,$store_id);
                //$drivers =   $this->entityManager->getRepository(Driver::class)->findD($time,$max_time,$date,$store_id);
                $form
                ->add('truck', EntityType::class, [
                    'class' => Truck::class,
                    'choices'=>$trucks,
                    'choice_label' => 'id',
                    'choice_value' => 'id',
                    'placeholder' => '',
                    'attr' => array('readonly' => false)
                ])
                ->add('driver', EntityType::class, [
                    'class' => Driver::class,
                    'choices'=>$drivers,
                    'choice_label' => function ($driver) {
                        return $driver->getFirstName() . ' ' . $driver->getLastName();
                    },
                    'choice_value' => 'id',
                    
                    'attr' => array('readonly' => false),
                    
                
                ])
                
                ->add('driver_assistant', EntityType::class, [
                    'class' => DriverAssistant::class,
                    'choices'=>$driverAs,
                    'choice_label' => function ($driverAs) {
                        return $driverAs->getFirstName() . ' ' . $driverAs->getLastName();
                    },
                    'choice_value' => 'id',
                    
                    'attr' => array('readonly' => false),
                    
                ])
                ;
                

            };

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event)use ($formModifier)  {
                $form = $event->getForm();
                $data = $event->getData();
                if ($data!=null){
                //$drivers =   $this->entityManager->getRepository(Driver::class)->getAll();
                $truckRoute=$data->getTruckRoute();
                $date=$data->getDate();
                $start_time=$data->getStartTime();
                }
                else{
                    $truckRoute=null;
                $date=null;
                $start_time=null;
                }
                $formModifier($form,$date,$start_time,$truckRoute);
                
                
                
                
                
                
                

            }
        );
        } 
            
            
            
            
    
            
           
    
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TruckTrip::class
            
        ]);
    }
}
