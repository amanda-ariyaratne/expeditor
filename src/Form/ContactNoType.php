<?php

namespace App\Form;

use App\Entity\ContactNo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactNoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'Contact number is required'
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 12,
                        'minMessage' => 'Contact number must be at least {{ limit }} characters long',
                        'maxMessage' => 'Contact number must be at most {{ limit }} characters long',
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'allow_extra_fields' => true
        ]);
    }
}
