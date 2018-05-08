<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle;

use PHPUnit\Framework\TestCase;
use PingLocalhost\AnonymizerBundle\Faker\ProviderInterface;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AnonymizerBundleTest extends TestCase
{
    /**
     * @var AnonymizerBundle
     */
    private $bundle;

    protected function setUp(): void
    {
        $this->bundle = new AnonymizerBundle();
    }

    public function testBuild(): void
    {
        $container  = $this->prophesize(ContainerBuilder::class);
        $definition = $this->prophesize(ChildDefinition::class);

        $container->registerForAutoconfiguration(ProviderInterface::class)->willReturn($definition);
        $definition->addTag('pinglocalhost.anonymizerbundle.provider')->shouldBeCalled();

        $this->bundle->build($container->reveal());
    }
}
