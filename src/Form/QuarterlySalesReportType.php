<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;

class QuarterlySalesReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        // $builder->add('expirationDate', 'date', array(
        //     'label' => 'Expiration date',
        //     'widget' => 'choice',
        //     'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
        //     'format' => 'dd-MM-yyyy',
        //     'input' => 'string',
        //     'data' => date('Y-m-d'),
        //     'years' => range(date('Y'), date('Y') + 10),
        //    ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
