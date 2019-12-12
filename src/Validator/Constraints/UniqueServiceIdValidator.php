<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\StoreManager;
use App\Entity\ChainManager;

class UniqueServiceIdValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueServiceId) {
            throw new UnexpectedTypeException($constraint, UniqueServiceId::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

        $sm = $this->entityManager->createQueryBuilder()
                                ->select('count(u.service_no)')
                                ->from(StoreManager::class, 'u')
                                ->where('u.service_no = ?1')
                                ->setParameter(1, $value)
                                ->getQuery()
                                ->getSingleScalarResult();
        $cm = $this->entityManager->createQueryBuilder()
                                ->select('count(u.service_no)')
                                ->from(ChainManager::class, 'u')
                                ->where('u.service_no = ?1')
                                ->setParameter(1, $value)
                                ->getQuery()
                                ->getSingleScalarResult();
        if(($sm + $cm) > 0){
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }

    }
}