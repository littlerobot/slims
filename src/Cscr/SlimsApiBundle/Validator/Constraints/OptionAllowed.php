<?php

namespace Cscr\SlimsApiBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class OptionAllowed extends Constraint
{
    public $optionMessage = 'Options need to be specified for this attribute type';
    public $nonOptionMessage = 'Options cannot be specified for this attribute type';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
