<?php

namespace Pentarim\SyliusAffiliateBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateRangeValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     *
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        if (null === $value->getStartsAt() || null === $value->getEndsAt()) {
            return;
        }

        if ($value->getStartsAt()->getTimestamp() > $value->getEndsAt()->getTimestamp()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('endsAt')
                ->addViolation();
        }
    }
}
