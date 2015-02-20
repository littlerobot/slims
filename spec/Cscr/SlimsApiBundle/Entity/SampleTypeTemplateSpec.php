<?php

namespace spec\Cscr\SlimsApiBundle\Entity;

use Cscr\SlimsApiBundle\Entity\SampleTypeTemplateAttribute;
use PhpSpec\ObjectBehavior;

class SampleTypeTemplateSpec extends ObjectBehavior
{
    function it_has_a_name()
    {
        $name = 'Template name';
        $this->setName($name);
        $this->getName()->shouldReturn($name);
    }

    function it_has_one_or_more_sample_type_template_attributes(
        SampleTypeTemplateAttribute $attribute1,
        SampleTypeTemplateAttribute $attribute2
    ) {
        $this->addAttribute($attribute1)
              ->addAttribute($attribute2);
        $this->getAttributes()->shouldHaveCount(2);
    }

    function it_should_not_add_the_same_sample_type_template_attribute_more_than_once(
        SampleTypeTemplateAttribute $attribute
    ) {
        $this->addAttribute($attribute)
              ->addAttribute($attribute);
        $this->getAttributes()->shouldHaveCount(1);
    }
}
