<?php

namespace Cscr\SlimsApiBundle\Tests\Entity;

use Cscr\SlimsApiBundle\Entity\AbstractSampleInstanceTemplateAttribute;
use Cscr\SlimsApiBundle\Entity\SampleInstanceTemplateStoredAttribute;

class SampleInstanceTemplateStoredAttributeTest extends \PHPUnit_Framework_TestCase
{
    public function testSettingEmptyOptionsSetsTheOptionsToNull()
    {
        $attribute = new SampleInstanceTemplateStoredAttribute();
        $attribute->setOptions([]);
        $this->assertNull($attribute->getOptions());
    }

    public function testSettingNullOptionsSetsTheOptionsToNull()
    {
        $attribute = new SampleInstanceTemplateStoredAttribute();
        $attribute->setOptions(null);
        $this->assertNull($attribute->getOptions());
    }

    public function testSettingAnArrayOfOptions()
    {
        $attribute = new SampleInstanceTemplateStoredAttribute();
        $options = [
            'a' => 'a',
            'b' => 'b',
        ];
        $attribute->setOptions($options);
        $this->assertEquals($options, $attribute->getOptions());
    }

    public function testBlankOptionsAreIgnored()
    {
        $passedOptions = [
            'a' => 'a',
            'b' => 'b',
            '',
        ];
        $expectedOptions = [
            'a' => 'a',
            'b' => 'b',
        ];

        $attribute = new SampleInstanceTemplateStoredAttribute();
        $attribute->setOptions($passedOptions);
        $this->assertEquals($expectedOptions, $attribute->getOptions());
    }

    /**
     * @dataProvider onlyOptionTypeAllowsOptionsToBeSpecifiedProvider
     *
     * @param string $type
     * @param bool   $optionsAllowed
     */
    public function testOnlyOptionTypeAllowsOptionsToBeSpecified($type, $optionsAllowed)
    {
        $attribute = new SampleInstanceTemplateStoredAttribute();
        $attribute->setType($type);
        $this->assertEquals($optionsAllowed, $attribute->allowsOptionsToBeSpecified());
    }

    public function onlyOptionTypeAllowsOptionsToBeSpecifiedProvider()
    {
        $values = [];

        foreach (AbstractSampleInstanceTemplateAttribute::getValidChoices() as $type) {
            $allowed = (AbstractSampleInstanceTemplateAttribute::TYPE_OPTION === $type);
            $values[] = [$type, $allowed];
        }

        return $values;
    }
}
