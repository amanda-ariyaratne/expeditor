<?php

namespace App\Form;

use App\Entity\DriverAssistant;
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

class DriverAssistantType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nic', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'NIC number is required',
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 12,
                        'minMessage' => 'NIC number must be at least {{ limit }} characters long',
                        'maxMessage' => 'NIC number must be at least {{ limit }} characters long'
                    ])
                ]
            ])
            ->add('first_name', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'First Name is required'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'First Name must be at least {{ limit }} characters long',
                        'maxMessage' => 'First Name must be at most {{ limit }} characters long'
                    ])
                ]
            ])
            ->add('last_name', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'First Name is required'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'First Name must be at least {{ limit }} characters long',
                        'maxMessage' => 'First Name must be at most {{ limit }} characters long'
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
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DriverAssistant::class,
            'required' => false,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'driver_assistant'
        ]);
    }
}
