<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUserEmail extends Constraint
{
    protected $validationGroup;

    public function __construct($options)
    {
        if($options['validation_group'])
        {
            $this->validationGroup = $options['validation_group'];
        }
        else
        {
            throw new MissingOptionException("...");
        }
    }

    public function getValidationGroup()
    {
        return $this->validationGroup;
    }
    public $message = 'This email is already in use';
}