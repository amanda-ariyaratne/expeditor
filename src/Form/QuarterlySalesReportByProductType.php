<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Store;
use App\Entity\TruckRoute;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class QuarterlySalesReportByProductType extends AbstractType
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year', ChoiceType::class, [
                'choices'  => [
                    '2020' => 2020,
                    '2019' => 2019,
                    '2018' => 2018,
                ],
                'placeholder' => 'year'
            ])
            // ->add('store', EntityType::class, [
            //     'class' => Store::class,
            //     'query_builder' => function (EntityRepository $er) {
            //         return $er->createQueryBuilder('s')
            //             ->where('s.deleted_at is NULL');
            //     },
            //     'choice_label' => 'name',
            //     'choice_value' => 'id',
            //     'placeholder' => 'store'
            // ])
            ->add('submit', SubmitType::class)
        ;

        // $formModifier = function (FormInterface $form, Store $store = null) {

        //     $truckRoutes = null === $store ? [] : $this->entityManager->getRepository(TruckRoute::class)->getByStore($store);

        //     $form->add('truck_route', EntityType::class, [
        //         'class' => 'App\Entity\TruckRoute',
        //         'placeholder' => 'route',
        //         'choice_label' => 'name',
        //         'choice_value' => 'id',
        //         'choices' => $truckRoutes,
        //     ]);
        // };

        // $builder->addEventListener(
        //     FormEvents::PRE_SET_DATA,
        //     function (FormEvent $event) use ($formModifier) {

        //         $data = $event->getData();

        //         $formModifier($event->getForm(), array_key_exists('store', $data) ? $data['store'] : null);
        //     }
        // );

        // $builder->get('store')->addEventListener(
        //     FormEvents::POST_SUBMIT,
        //     function (FormEvent $event) use ($formModifier) {
        //         $store = $event->getForm()->getData();
        //         $formModifier($event->getForm()->getParent(), $store);
        //     }
        // );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entityManager' => null,
            'required' => false
        ]);
    }
}
