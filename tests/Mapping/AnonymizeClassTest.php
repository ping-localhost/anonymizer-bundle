<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Mapping;

use PHPUnit\Framework\TestCase;
use PingLocalhost\AnonymizerBundle\Exception\InvalidAnonymizeAnnotationException;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Mapping\AnonymizeClass
 */
class AnonymizeClassTest extends TestCase
{
    public function testConstructorBothSet(): void
    {
        $this->expectException(InvalidAnonymizeAnnotationException::class);
        $this->expectExceptionMessage('Can\'t set both the inclusions and the exclusions');

        new AnonymizeClass(['exclusions' => ['username'], 'inclusions' => ['username']]);
    }

    public function testGetExclusions(): void
    {
        self::assertSame(['username'], (new AnonymizeClass(['exclusions' => ['username']]))->getExclusions());
    }

    public function testGetInclusions(): void
    {
        self::assertSame(['username'], (new AnonymizeClass(['inclusions' => ['username']]))->getInclusions());
    }
}
