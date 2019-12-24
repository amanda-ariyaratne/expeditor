<?php

namespace App\Form;

use App\Entity\Purchase;
use App\Entity\TruckRoute;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Validator\Constraints\Date;
use App\Form\AddressType;


class PurchaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('delivery_date' , DateType::class)
            ->add('address' , AddressType::class)
            ->add('truck_route', EntityType::class, [
                'class' => TruckRoute::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.deleted_at is NULL');
                },
                'choice_label' => 'name',
                'choice_value' => 'id',
                'placeholder' => ''
            ])
            ->add('purchase', SubmitType::class , [
                'attr' => [
                    'class' => 'btn btn-1'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class
        ]);
    }
}
