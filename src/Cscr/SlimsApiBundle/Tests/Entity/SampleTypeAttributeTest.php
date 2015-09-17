<?php

namespace Cscr\SlimsApiBundle\Tests\Entity;

use Cscr\SlimsApiBundle\Entity\SampleTypeAttribute;
use Cscr\SlimsApiBundle\Entity\SampleTypeTemplateAttribute;

class SampleTypeAttributeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Bit difficult to test this one. The date is transformed to Y-m-d and transformed back in the getter.
     */
    public function testDatesAreTransparentlyTransformed()
    {
        $date = '22/08/1982';
        $template = (new SampleTypeTemplateAttribute())
            ->setType(SampleTypeTemplateAttribute::TYPE_DATE);
        $attribute = (new SampleTypeAttribute())
            ->setTemplate($template)
            ->setValue($date);

        $this->assertEquals($date, $attribute->getValue());
    }
}
