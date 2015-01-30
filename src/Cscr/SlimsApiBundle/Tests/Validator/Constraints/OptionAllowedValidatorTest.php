<?php
namespace Cscr\SlimsApiBundle\Tests\Validator\Constraints;

use Cscr\SlimsApiBundle\Entity\SampleTypeAttribute;
use Cscr\SlimsApiBundle\Validator\Constraints\OptionAllowed;
use Cscr\SlimsApiBundle\Validator\Constraints\OptionAllowedValidator;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;
use Symfony\Component\Validator\Validation;

class OptionAllowedValidatorTest extends AbstractConstraintValidatorTest
{
    protected function getApiVersion()
    {
        return Validation::API_VERSION_2_5;
    }

    protected function createValidator()
    {
        return new OptionAllowedValidator();
    }

    /**
     * @dataProvider getValidValues
     * @param SampleTypeAttribute $object The object to validate.
     */
    public function testValidValues($object)
    {
        $this->validator->validate($object, new OptionAllowed());

        $this->assertNoViolation();
    }

    public function getValidValues()
    {
        return array(
            [(new SampleTypeAttribute())->setType(SampleTypeAttribute::TYPE_OPTION)->setOptions([1, 2])],
            [(new SampleTypeAttribute())->setType(SampleTypeAttribute::TYPE_BRIEF_TEXT)],
        );
    }

    /**
     * @dataProvider getInvalidValues
     * @param SampleTypeAttribute $object The object to validate.
     * @param string $message The validation error message.
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
                (new SampleTypeAttribute())->setType(SampleTypeAttribute::TYPE_BRIEF_TEXT)->setOptions([1, 2]),
                'Options cannot be specified for this attribute type',
            ],
            [
                (new SampleTypeAttribute())->setType(SampleTypeAttribute::TYPE_OPTION),
                'Options need to be specified for this attribute type',
            ],
        );
    }
}
