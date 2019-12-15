<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;
use App\Validator\Constraints\UniqueUserEmail;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'First Name is required',
                        'groups' => ['new', 'edit']
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'First Name must be at least {{ limit }} characters long',
                        'maxMessage' => 'First Name must be at most {{ limit }} characters long',
                        'groups' => ['new', 'edit']
                    ])
                ]
            ])
            ->add('last_name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull([
                        'message' => 'Last Name is required',
                        'groups' => ['new', 'edit']
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Last Name Name must be at least {{ limit }} characters long',
                        'maxMessage' => 'Last Name Name must be at most {{ limit }} characters long',
                        'groups' => ['new', 'edit']
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull([
                        'message' => 'Email is required',
                        'groups' => ['new', 'edit']
                    ]),
                    new Email([
                        'message' => 'Invalid Email Address',
                        'groups' => ['new', 'edit']
                    ]),
                    new Length([
                        'max' => 180,
                        'maxMessage' => 'Email must be at most {{ limit }} characters long',
                        'groups' => ['new', 'edit']
                    ]),
                    new UniqueUserEmail([
                        'groups' => ['new']
                    ])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],
                'constraints' => [
                    new NotNull([
                        'message' => 'Password is required',
                        'groups' => ['new']
                    ]),
                ]
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true,
            'validation_groups' => ['new', 'edit'],
        ]);
    }
}