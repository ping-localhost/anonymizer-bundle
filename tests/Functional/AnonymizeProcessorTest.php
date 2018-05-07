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
        $unchanged_object = new ExampleObject('PHPUnit', 'email@example.com');
        $changed_object   = new ExampleObject('PHPUnit', 'email@not-example.com');

        $this->anonymizer->anonymize($unchanged_object);
        self::assertEquals('PHPUnit', $unchanged_object->getUsername());
        self::assertEquals('email@example.com', $unchanged_object->getEmail());

        $this->anonymizer->anonymize($changed_object);
        self::assertNotEquals('PHPUnit', $changed_object->getUsername());
        self::assertNotEquals('email@example.com', $changed_object->getEmail());
    }
}
