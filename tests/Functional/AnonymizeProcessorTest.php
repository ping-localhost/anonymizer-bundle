<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Functional;

use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Classes\EmptyObject;
use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Classes\ExampleObject;
use PingLocalhost\AnonymizerBundle\Processor\AnonymizeProcessor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @covers \PingLocalhost\AnonymizerBundle\Processor\AnonymizeProcessor
 */
class AnonymizeProcessorTest extends KernelTestCase
{
    /**
     * @var AnonymizeProcessor
     */
    private $anonymizer;

    protected function setUp(): void
    {
        static::bootKernel();

        $this->anonymizer = static::$kernel->getContainer()->get(AnonymizeProcessor::class);
    }

    public function testNoAnnotations(): void
    {
        $object = new EmptyObject('username', 'email@example.com');

        $this->anonymizer->anonymize($object);
        self::assertEquals('username', $object->getUsername());
        self::assertEquals('email@example.com', $object->getEmail());
    }

    public function testExcludedUsername(): void
    {
        $object = new ExampleObject('root', 'email@example.com');

        $this->anonymizer->anonymize($object);
        self::assertEquals('root', $object->getUsername());
        self::assertEquals('email@example.com', $object->getEmail());
    }

    public function testExcludedEmailOnClass(): void
    {
        $object = new ExampleObject('PHPUnit', 'email@example.com');

        $this->anonymizer->anonymize($object);
        self::assertEquals('PHPUnit', $object->getUsername());
        self::assertEquals('email@example.com', $object->getEmail());
    }

    public function testAnonymize(): void
    {
        $object = new ExampleObject('PHPUnit', 'email@not-example.com');

        $this->anonymizer->anonymize($object);
        self::assertNotEquals('PHPUnit', $object->getUsername());
        self::assertNotEquals('email@example.com', $object->getEmail());
    }
}
