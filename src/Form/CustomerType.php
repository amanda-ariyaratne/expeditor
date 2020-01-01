<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use App\Form\UserType;
use App\Form\AddressType;
use App\Form\ContactNoType;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $validationGroup = $options['validation_group'];
        $builder
            ->add('user', UserType::class, [
                'constraints' => [
                    new Valid()
                ],
                'validation_group' => [ 'validation_group' => $validationGroup ]
            ])
            ->add('address' , AddressType::class)
            //->add('contactNo', ContactNoType::class)
            ->add('register', SubmitType::class , [
                'attr' => [
                    'class' => 'btn btn-1'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class
        ]);
        $resolver->setRequired('validation_group');
    }
}
