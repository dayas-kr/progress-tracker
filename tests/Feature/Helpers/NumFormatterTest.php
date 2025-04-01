<?php

namespace Tests\Unit;

use App\Helpers\NumFormatter;
use PHPUnit\Framework\TestCase;

class NumFormatterTest extends TestCase
{
    public function testSingleDigit()
    {
        $this->assertEquals('1', NumFormatter::format(1));
        $this->assertEquals('12', NumFormatter::format(12));
        $this->assertEquals('123', NumFormatter::format(123));
    }

    public function testThousands()
    {
        $this->assertEquals('1.2k', NumFormatter::format(1234));
        $this->assertEquals('12k', NumFormatter::format(12345));
        $this->assertEquals('123k', NumFormatter::format(123456));
    }

    public function testMillions()
    {
        $this->assertEquals('1.2m', NumFormatter::format(1234567));
        $this->assertEquals('12m', NumFormatter::format(12345678));
        $this->assertEquals('123m', NumFormatter::format(123456789));
    }

    public function testBillions()
    {
        $this->assertEquals('1.2b', NumFormatter::format(1234567890));
        $this->assertEquals('12b', NumFormatter::format(12345678900));
        $this->assertEquals('123b', NumFormatter::format(123456789000));
    }
}
