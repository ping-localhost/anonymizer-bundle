<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Faker;

use PHPUnit\Framework\TestCase;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Faker\Generator
 * @covers \PingLocalhost\AnonymizerBundle\Faker\ProviderInterface
 */
class GeneratorTest extends TestCase
{
    public function testConstructor(): void
    {
        $generator = new Generator('nl_NL', [$this->createProvider()]);

        self::assertCount(21, $generator->getProviders());
        self::assertSame('method', $generator->method());
    }

    private function createProvider(): ProviderInterface
    {
        return new class implements ProviderInterface
        {
            public function method(): string
            {
                return 'method';
            }
        };
    }
}
