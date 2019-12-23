<?php
namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AddressType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $option){
        $builder   
            ->add('house_no', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'House number is required'
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 50,
                        'minMessage' => 'House number must be at least {{ limit }} characters long',
                        'maxMessage' => 'House number must be at most {{ limit }} characters long',
                    ])
                ]
            ])
            ->add('street', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'Street is required'
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 50,
                        'minMessage' => 'Street must be at least {{ limit }} characters long',
                        'maxMessage' => 'Street must be at most {{ limit }} characters long',
                    ])
                ]
            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'City is required'
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 50,
                        'minMessage' => 'City must be at least {{ limit }} characters long',
                        'maxMessage' => 'City must be at most {{ limit }} characters long',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' =>  Address::class,
            'allow_extra_fields' => true
        ]);
    }
}