<?php

namespace Cscr\SlimsApiBundle\Tests\ValueObject;

use Cscr\SlimsApiBundle\ValueObject\Colour;

class ColourTest extends \PHPUnit_Framework_TestCase
{
    public function testCreatingFromHexReturnsExpectedValue()
    {
        $hex = '#ff00ff';
        $colour = Colour::fromHex($hex);

        $this->assertEquals($hex, $colour->getAsHex());
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     */
    public function testCreatingFromHexWithInvalidInputThrowsException()
    {
        Colour::fromHex('FooBar');
    }

    public function testCreatingFromHexNormalisesString()
    {
        $colour = Colour::fromHex('#FF00FF');

        $this->assertEquals('#ff00ff', $colour->getAsHex());
    }

    public function testToString()
    {
        $hex = '#aabbcc';
        $colour = Colour::fromHex($hex);

        $this->assertEquals($hex, (string)$colour);
    }
}
