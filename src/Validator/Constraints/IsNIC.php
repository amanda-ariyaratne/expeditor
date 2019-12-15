<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsNIC extends Constraint
{
    public $message = 'Invalid NIC !';
}