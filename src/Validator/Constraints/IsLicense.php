<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsLicense extends Constraint
{
    public $message = 'Invalid License !';
}