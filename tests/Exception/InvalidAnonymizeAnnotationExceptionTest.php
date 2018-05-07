<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Exception;

use PHPUnit\Framework\TestCase;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Exception\InvalidAnonymizeAnnotationException
 */
class InvalidAnonymizeAnnotationExceptionTest extends TestCase
{
    public function testConstructor(): void
    {
        self::assertSame('Invalid!', (new InvalidAnonymizeAnnotationException('Invalid!'))->getMessage());
    }
}
