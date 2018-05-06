<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Functional;

use PingLocalhost\AnonymizerBundle\Functional\Fixtures\Entity\Entity;
use PingLocalhost\AnonymizerBundle\Processor\AnonymizeProcessor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EnumTranslationTest extends KernelTestCase
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
        $entity = new Entity('PHPUnit');
        $this->anonymizer->anonymize($entity);
        self::assertNotEquals('PHPUnit', $entity->getUsername());
    }
}
