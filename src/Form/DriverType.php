<?php

namespace App\Form;

use App\Entity\Driver;
use App\Entity\Store;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Repository\StoreRepository;


class DriverType extends AbstractType
{
    private $storeRepository;

    public function __construct(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder
            ->add('NIC')
            ->add('license_no')
            ->add('first_name')
            ->add('last_name')
            ->add('store', EntityType::class, [
                'class' => Store::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.deleted_at is NULL');
                },
                'choice_label' => 'name',
            ])
            ->add('submit', SubmitType::class)
        ;
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Driver::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'driver-token',
        ]);
    }

    public function getStores()
    {
        $stores = $this->storeRepository->getAllAsArray();
        $storeArray = [];
        foreach ($stores as $store) {
            $storeArray[$store['name']] = $store['id'];
        }
        return $storeArray;
    }
}
