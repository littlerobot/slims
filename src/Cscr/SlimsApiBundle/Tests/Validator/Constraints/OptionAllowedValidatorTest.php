<?php

namespace Cscr\SlimsApiBundle\Tests\Validator\Constraints;

use Cscr\SlimsApiBundle\Entity\SampleTypeTemplateAttribute;
use Cscr\SlimsApiBundle\Validator\Constraints\OptionAllowed;
use Cscr\SlimsApiBundle\Validator\Constraints\OptionAllowedValidator;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;
use Symfony\Component\Validator\Validation;

class OptionAllowedValidatorTest extends AbstractConstraintValidatorTest
{
    /**
     * @return string
     */
    protected function getApiVersion()
    {
        return Validation::API_VERSION_2_5;
    }

    /**
     * @return OptionAllowedValidator
     */
    protected function createValidator()
    {
        return new OptionAllowedValidator();
    }

    /**
     * @dataProvider getValidValues
     *
     * @param SampleTypeTemplateAttribute $object The object to validate.
     */
    public function testValidValues($object)
    {
        $this->validator->validate($object, new OptionAllowed());

        $this->assertNoViolation();
    }

    public function getValidValues()
    {
        return array(
            [
                (new SampleTypeTemplateAttribute())
                    ->setType(SampleTypeTemplateAttribute::TYPE_OPTION)
                    ->setOptions([1, 2]),
            ],
            [
                (new SampleTypeTemplateAttribute())
                    ->setType(SampleTypeTemplateAttribute::TYPE_BRIEF_TEXT),
            ],
        );
    }

    /**
     * @dataProvider getInvalidValues
     *
     * @param SampleTypeTemplateAttribute $object  The object to validate.
     * @param string                      $message The validation error message.
     */
    public function testInvalidValues($object, $message)
    {
        $this->validator->validate($object, new OptionAllowed());

        $this->buildViolation($message)
            ->assertRaised();
    }

    public function getInvalidValues()
    {
        return array(
            [
                (new SampleTypeTemplateAttribute())
                    ->setType(SampleTypeTemplateAttribute::TYPE_BRIEF_TEXT)
                    ->setOptions([1, 2]),
                'Options cannot be specified for this attribute type',
            ],
            [
                (new SampleTypeTemplateAttribute())
                    ->setType(SampleTypeTemplateAttribute::TYPE_OPTION),
                'Options need to be specified for this attribute type',
            ],
        );
    }
}
