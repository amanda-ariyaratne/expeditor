<?php

namespace App\Form;

use App\Entity\Cart;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\NotNull;

use App\Form\CustomerType;
use App\Form\ProductType;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity' , TextType::class , [
                'required' => true,
                'empty_data' => '1',
                'constraints' => [
                    new NotNull([
                        'message' => 'Quantity is required'
                    ])
                ]
            ])
            // ->add('customer' , CustomerType::class , [
            //     'required' => true,
            //     'constraints' => [
            //         new NotNull([
            //             'message' => 'login before adding to the cart'
            //         ])
            //     ]
            // ])
            

            ->add('product', HiddenType::class)
            ->add('customer', HiddenType::class)
            ->add('AddToCart', SubmitType::class , [
                'attr' => [
                    'class' => 'add-to-cart btn btn-1'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cart::class,
        ]);
    }
}
