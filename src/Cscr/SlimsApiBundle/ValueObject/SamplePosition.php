<?php

namespace Cscr\SlimsApiBundle\ValueObject;

use Assert\Assertion;

class SamplePosition
{
    /**
     * @var int
     */
    private $row;

    /**
     * @var int
     */
    private $column;

    /**
     * @param int $row    Must be between 0 and 99.
     * @param int $column Must be between 0 and 99.
     */
    private function __construct($row, $column)
    {
        Assertion::integerish($row);
        Assertion::integerish($column);
        Assertion::range($row, 0, 99);
        Assertion::range($column, 0, 99);

        $this->row = (int) $row;
        $this->column = (int) $column;
    }

    /**
     * @param string $coordinates In the form row:column. E.g. 0:1.
     *
     * @return SamplePosition
     */
    public static function fromCoordinates($coordinates)
    {
        Assertion::regex($coordinates, '/^[0-9]+:[0-9]+/');

        list($row, $column) = explode(':', $coordinates);

        return new self($row, $column);
    }

    /**
     * @param int $row
     * @param int $column
     *
     * @return SamplePosition
     */
    public static function fromRowAndColumn($row, $column)
    {
        return new self($row, $column);
    }

    /**
     * @return int
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @return int
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getAsCoordinates();
    }

    /**
     * @return string In the form row:column. E.g. 0:1.
     */
    public function getAsCoordinates()
    {
        return sprintf('%d:%d', $this->getRow(), $this->getColumn());
    }
}
