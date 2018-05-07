<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Functional;

use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Classes\ExampleObject;
use PingLocalhost\AnonymizerBundle\Processor\AnonymizeProcessor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AnonymizeProcessorTest extends KernelTestCase
{
    /**
     * @var AnonymizeProcessor
     */
    private $anonymizer;

    protected function setUp()
    {
        static::bootKernel();

        $this->anonymizer = static::$kernel->getContainer()->get(AnonymizeProcessor::class);
    }

    public function testAnonymizer(): void
    {
        $unchanged_property = new ExampleObject('root', 'email@example.com');
        $unchanged_class    = new ExampleObject('PHPUnit', 'email@example.com');
        $changed_object     = new ExampleObject('PHPUnit', 'email@not-example.com');

        $this->anonymizer->anonymize($unchanged_property);
        self::assertEquals('root', $unchanged_property->getUsername());
        self::assertEquals('email@example.com', $unchanged_property->getEmail());

        $this->anonymizer->anonymize($unchanged_class);
        self::assertEquals('PHPUnit', $unchanged_class->getUsername());
        self::assertEquals('email@example.com', $unchanged_class->getEmail());

        $this->anonymizer->anonymize($changed_object);
        self::assertNotEquals('PHPUnit', $changed_object->getUsername());
        self::assertNotEquals('email@example.com', $changed_object->getEmail());
    }
}
