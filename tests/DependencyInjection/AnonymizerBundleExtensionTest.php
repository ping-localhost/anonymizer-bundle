<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\DependencyInjection;

use PHPUnit\Framework\TestCase;
use PingLocalhost\AnonymizerBundle\Processor\AnonymizeProcessor;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \PingLocalhost\AnonymizerBundle\DependencyInjection\AnonymizerExtension
 */
class AnonymizerBundleExtensionTest extends TestCase
{
    /**
     * @var AnonymizerExtension
     */
    private $extension;

    protected function setUp(): void
    {
        $this->extension = new AnonymizerExtension();
    }

    public function testLoad(): void
    {
        $container_builder = new ContainerBuilder();
        $this->extension->load([], $container_builder);

        self::assertNotNull($container_builder->get(
            AnonymizeProcessor::class,
            ContainerBuilder::NULL_ON_INVALID_REFERENCE
        ));
    }
}
