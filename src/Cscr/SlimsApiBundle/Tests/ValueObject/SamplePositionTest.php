<?php

namespace Cscr\SlimsApiBundle\Tests\ValueObject;

use Cscr\SlimsApiBundle\ValueObject\SamplePosition;

class SamplePositionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Assert\InvalidArgumentException
     */
    public function testThrowsExceptionWithInvalidCoordinates()
    {
        SamplePosition::fromCoordinates('FooBar');
    }

    public function testCreateFromRowAndColumn()
    {
        $row = 10;
        $column = 20;

        $position = SamplePosition::fromRowAndColumn($row, $column);

        $this->assertEquals($row, $position->getRow());
        $this->assertEquals($column, $position->getColumn());
    }

    /**
     * @param string $description
     * @param string $coordinates
     * @param bool   $throwsException
     *
     * @dataProvider rowAndColumnBoundsDataProvider
     */
    public function testRowAndColumnBounds($description, $coordinates, $throwsException)
    {
        if ($throwsException) {
            $exception = '\Assert\InvalidArgumentException';
            $this->setExpectedException($exception);
        }

        $position = SamplePosition::fromCoordinates($coordinates);
        $this->assertNotNull($position->getRow(), $description);
        $this->assertNotNull($position->getColumn(), $description);
    }

    public function rowAndColumnBoundsDataProvider()
    {
        // Description, coordinates, throws exception?
        return [
            ['Row too large', '100:10', true],
            ['Column too large', '10:100', true],
            ['Row and column in bounds', '10:10', false],
            ['Row too small', '-1:10', true],
            ['Column too small', '10:-1', true],
        ];
    }

    public function testToStringRepresentation()
    {
        $position = SamplePosition::fromRowAndColumn(1, 2);

        $this->assertEquals('1:2', (string)$position);
    }

    public function testGetCoordinates()
    {
        $position = SamplePosition::fromRowAndColumn(2, 3);

        $this->assertEquals('2:3', $position->getAsCoordinates());
    }
}
