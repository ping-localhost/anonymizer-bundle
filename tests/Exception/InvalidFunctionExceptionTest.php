<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Exception;

use PHPUnit\Framework\TestCase;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Exception\InvalidFunctionException
 */
class InvalidFunctionExceptionTest extends TestCase
{
    public function testConstructor(): void
    {
        $function = 'Function';
        $location = 'Location';
        $previous = new \InvalidArgumentException('Invalid!');

        self::assertSame(
            sprintf('Invalid function at %s, the function [%s] doesn\'t exist in the generator', $location, $function),
            (new InvalidFunctionException($function, $location, $previous))->getMessage()
        );
    }
}
