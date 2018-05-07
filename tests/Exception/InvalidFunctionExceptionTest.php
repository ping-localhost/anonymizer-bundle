<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Exception;

use PHPUnit\Framework\TestCase;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Exception\InvalidFakerException
 */
class InvalidFunctionExceptionTest extends TestCase
{
    public function testConstructor(): void
    {
        $generator = 'Generator';
        $property  = 'Property';
        $previous  = new \InvalidArgumentException('Invalid!');

        self::assertSame(
            'Invalid generator "Generator" specified at: "Property".',
            (new InvalidFakerException($generator, $property, $previous))->getMessage()
        );
    }
}
