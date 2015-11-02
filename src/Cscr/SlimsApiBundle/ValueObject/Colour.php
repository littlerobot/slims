<?php

namespace Cscr\SlimsApiBundle\ValueObject;

use Assert\Assertion;

class Colour
{
    /**
     * @var string
     */
    private $hex;

    /**
     * @param string $hex
     */
    private function __construct($hex)
    {
        $this->hex = strtolower($hex);
    }

    /**
     * @param string $hex
     *
     * @return Colour
     */
    public static function fromHex($hex)
    {
        Assertion::regex($hex, '/^#[a-f0-9]{6}$/i', 'Must be in full hex format. E.g. #ff00aa.');

        return new self($hex);
    }

    /**
     * @return string
     */
    public function getAsHex()
    {
        return $this->hex;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getAsHex();
    }
}
