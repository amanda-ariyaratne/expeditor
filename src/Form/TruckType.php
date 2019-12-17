<?php

namespace App\Form;

use App\Entity\Truck;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use App\Entity\Store;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Length;

class TruckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('insurance_no', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'Insurance number is required',
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 12,
                        'minMessage' => 'Insurance number must be at least {{ limit }} characters long',
                        'maxMessage' => 'Insurance number must be at least {{ limit }} characters long'
                    ])
                ]
            ])
            ->add('registration_no', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'Registration number is required',
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 12,
                        'minMessage' => 'Registration number must be at least {{ limit }} characters long',
                        'maxMessage' => 'Registration number must be at least {{ limit }} characters long'
                    ])
                ]
            ])
            ->add('store', EntityType::class, [
                'class' => Store::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.deleted_at is NULL');
                },
                'choice_label' => 'name',
                'choice_value' => 'id',
                'placeholder' => ''
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Truck::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'truck'
        ]);
    }
}
