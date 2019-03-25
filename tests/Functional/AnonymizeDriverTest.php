<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Functional;

use PingLocalhost\AnonymizerBundle\Driver\AnonymizeDriver;
use PingLocalhost\AnonymizerBundle\Exception\InvalidFakerException;
use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Classes\ExampleObject;
use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Classes\IncorrectMethodFaker;
use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Classes\IncorrectPropertyFaker;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Driver\AnonymizeDriver
 */
class AnonymizeDriverTest extends KernelTestCase
{
    /**
     * @var AnonymizeDriver
     */
    private $anonymizer;

    protected function setUp(): void
    {
        static::bootKernel();

        $this->anonymizer = static::$kernel->getContainer()->get(AnonymizeDriver::class);
    }

    public function testIncorrectPropertyFaker(): void
    {
        $this->expectException(InvalidFakerException::class);
        $this->expectExceptionMessage('Invalid generator "this-will-error" specified at:');

        $this->anonymizer->loadMetadataForClass(new \ReflectionClass(new IncorrectPropertyFaker('username')));
    }

    public function testIncorrectMethodFaker(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Didn\'t know how to inject class "string" for method "anonymize".');

        $this->anonymizer->loadMetadataForClass(new \ReflectionClass(new IncorrectMethodFaker('username')));
    }

    public function testDriver(): void
    {
        $object = new ExampleObject('username', 'email@example.com');

        $metadata = $this->anonymizer->loadMetadataForClass(new \ReflectionClass($object));

        self::assertTrue($metadata->isCouldExclude());
        self::assertSame(['email' => '/@example.com$/'], $metadata->getMatchers());

        $method_metadata = $metadata->getMethodMetadata();
        self::assertArrayHasKey('anonymize', $method_metadata);

        $property_metadata = $metadata->getPropertyMetadata();
        self::assertArrayHasKey('username', $property_metadata);
        self::assertArrayHasKey('created_at', $property_metadata);
    }
}
