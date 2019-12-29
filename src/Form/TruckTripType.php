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


class TruckTripType extends AbstractType
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
            
            $formModifier = function (FormInterface $form,DateTimeInterface $date, DateTimeInterface $time = null,TruckRoute $truckRoute=null) 
            {   //dump($truckRoute);
                if ($truckRoute!=null){
                    $max_time=$truckRoute->getId();
                    $store_id=$truckRoute->getStore()->getId();
                }
                else{
                    $max_time=null;
                    $store_id=null;
                }
                //dump($max_time);
                
                $drivers =   $this->entityManager->getRepository(Driver::class)->getAll();
                //$drivers =   $this->entityManager->getRepository(Driver::class)->findD($time,$max_time,$date,$store_id);
                $form->add('driver', EntityType::class, [
                    'class' => Driver::class,
                    'choices'=>$drivers,
                    'choice_label' => 'id',
                    'choice_value' => 'id',
                    
                    'attr' => array('readonly' => false),
                    
                
                ])
                ->add('driver_assistant', EntityType::class, [
                    'class' => DriverAssistant::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                        ->where('s.deleted_at is NULL');
                    },
                    'choice_label' => 'id',
                    'choice_value' => 'id',
                    
                    'attr' => array('readonly' => false),
                    
                ]);
                

            };
            /*
            $builder->addEventListener(
                FormEvents::POST_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $data = $event->getData();
                    $form=$event->getForm();
                    dump($data->getTruckRoute());
                    $truckRoute=$data->getTruckRoute();
                    $form->get('truck_route')->setData($truckRoute);
                    //$formModifier($event->getForm(), $data->getStartTime());
                    $formModifier($event->getForm(), $data->getTruckRoute());

                }
            );
            */
            
            $builder->get('truck_route')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $form = $event->getForm();
                    $truckRoute = $event->getData();
                    $drivers =   $this->entityManager->getRepository(Driver::class)->getAll();
                    dump($form->getParent()->getData());
                    $form->getParent()->add('driver', EntityType::class, [
                        'class' => Driver::class,
                        'choices'=>$drivers,
                        'choice_label' => 'id',
                        'choice_value' => 'id',
                        
                        'attr' => array('readonly' => false),
                        
                    
                    ])
                    
                    ->add('driver_assistant', EntityType::class, [
                        'class' => DriverAssistant::class,
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('s')
                            ->where('s.deleted_at is NULL');
                        },
                        'choice_label' => 'id',
                        'choice_value' => 'id',
                        
                        'attr' => array('readonly' => false),
                        
                    ]);
                    
                    
                    
                    
                    

                }
            );
            
            /*
            $builder ->get('start_time')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier,$func) {
                    echo '<script>console.log("Welcome to GeeksforGeeks!"); </script>';
                    $startTime = $event->getForm()->getData();
                    dump($startTime);
                    
                    
                }
            );
            */
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
