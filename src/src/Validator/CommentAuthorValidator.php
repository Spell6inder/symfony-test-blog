<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CommentAuthorValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\CommentAuthor */

        if (null === $value || '' === $value) {
            return;
        }
        if(preg_match('/^\p{Lu}\w*\W*\p{Lu}\w*$/u', $value)){
            return;
        }
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
