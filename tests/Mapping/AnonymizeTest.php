<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Mapping;

use PHPUnit\Framework\TestCase;
use PingLocalhost\AnonymizerBundle\Exception\InvalidAnonymizeAnnotationException;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Mapping\Anonymize
 */
class AnonymizeTest extends TestCase
{
    public function testConstructorInvalidAnnotation(): void
    {
        $this->expectException(InvalidAnonymizeAnnotationException::class);
        $this->expectExceptionMessage('You can\'t set both the excluded array and the exclude annotation.');

        new Anonymize(['exclude' => 'something', 'excluded' => ['something']]);
    }

    public function testGetters(): void
    {
        $anonymize = new Anonymize([
            'faker'          => 'userName',
            'fakerArguments' => ['argument'],
            'unique'         => true,
            'exclude'        => 'something',
            'excluded'       => [],
        ]);

        self::assertSame('userName', $anonymize->getFaker());
        self::assertSame(['argument'], $anonymize->getArguments());
        self::assertSame('something', $anonymize->getExclude());
        self::assertEmpty($anonymize->getExcluded());
        self::assertTrue($anonymize->isUnique());
    }
}
