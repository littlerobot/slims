<?php

namespace Cscr\SlimsApiBundle\Validator\Constraints;

use Cscr\SlimsApiBundle\Entity\SampleTypeAttribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OptionAllowedValidator extends ConstraintValidator
{
    /**
     * @param SampleTypeAttribute $object
     * @param Constraint $constraint
     */
    public function validate($object, Constraint $constraint)
    {
        if ($object->allowsOptionsToBeSpecified() && null === $object->getOptions()) {
            $this->context->buildViolation($constraint->optionMessage)
                ->addViolation();
            return;
        }

        if (!$object->allowsOptionsToBeSpecified() && null !== $object->getOptions()) {
            $this->context->buildViolation($constraint->nonOptionMessage)
                ->addViolation();
            return;
        }
    }
}
