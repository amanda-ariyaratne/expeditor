<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueServiceId extends Constraint
{
    public $message = 'This service id is already in use';
}