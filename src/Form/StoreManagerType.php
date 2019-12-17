<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\StoreManager;
use App\Entity\Store;
use App\Form\UserType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use App\Validator\Constraints\UniqueServiceId;


class StoreManagerType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $stores = $this->entityManager->createQueryBuilder()
                                    ->select('s')
                                    ->from(Store::class, 's')
                                    ->where('s.deleted_at is null')
                                    ->getQuery()
                                    ->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        $storeArray = [];
        foreach ($stores as $store) {
            $storeArray[$store['name']] = $store['id'];
        }
        $builder
            ->add('user', UserType::class, [
                'validation_groups' => ['edit']
            ])
            ->add('nic', TextType::class, [
                'constraints' => [
                    new NotNull([
                        'message' => 'NIC number is required',
                        'groups' => ['new', 'edit']
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 12,
                        'minMessage' => 'NIC number must be at least {{ limit }} characters long',
                        'maxMessage' => 'NIC number must be at least {{ limit }} characters long',
                        'groups' => ['new', 'edit']
                    ])
                ]
            ])
            ->add('service_no', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotNull([
                        'message' => 'Service ID is required',
                        'groups' => ['new', 'edit']
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 5,
                        'minMessage' => 'Invalid service ID. Length must be 5 and should start with "SM"',
                        'maxMessage' => 'Invalid service ID. Length must be 5 and should start with "SM"',
                        'groups' => ['new', 'edit']
                    ]),
                    new UniqueServiceId([
                        'groups' => ['new']
                    ])
                ]
            ])
            ->add('store_id', ChoiceType::class, [
                'choices'  => $storeArray,
                'mapped' => false
                ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StoreManager::class,
            'required' => false,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'store_manager',
            'validation_groups' => ['new', 'edit'],
        ]);
    }
}
