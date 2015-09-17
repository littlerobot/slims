<?php

namespace Cscr\SlimsApiBundle\Tests\Entity;

use Cscr\SlimsApiBundle\Entity\SampleInstanceAttribute;
use Cscr\SlimsApiBundle\Entity\SampleInstanceTemplateStoredAttribute;

class SampleInstanceAttributeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Bit difficult to test this one. The date is transformed to Y-m-d and transformed back in the getter.
     */
    public function testDatesAreTransparentlyTransformed()
    {
        $date = '22/08/1982';
        $template = (new SampleInstanceTemplateStoredAttribute())
            ->setType(SampleInstanceTemplateStoredAttribute::TYPE_DATE);
        $attribute = (new SampleInstanceAttribute())
            ->setTemplate($template)
            ->setValue($date);

        $this->assertEquals($date, $attribute->getValue());
    }
}
