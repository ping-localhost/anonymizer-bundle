<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Functional;

use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Entity\Entity;
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
        $unchanged_entity = new Entity('PHPUnit', 'email@example.com');
        $changed_entity = new Entity('PHPUnit', 'email@not-example.com');

        $this->anonymizer->anonymize($unchanged_entity);
        self::assertEquals('PHPUnit', $unchanged_entity->getUsername());
        self::assertEquals('email@example.com', $unchanged_entity->getEmail());

        $this->anonymizer->anonymize($changed_entity);
        self::assertNotEquals('PHPUnit', $changed_entity->getUsername());
        self::assertNotEquals('email@example.com', $changed_entity->getEmail());
    }
}
