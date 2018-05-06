<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Functional\Fixtures;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    public function __construct(string $environment, bool $debug)
    {
        AnnotationRegistry::registerLoader('class_exists');

        parent::__construct($environment, $debug);
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \PingLocalhost\AnonymizerBundle\AnonymizerBundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return \dirname(__DIR__) . '/var/cache';
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return \dirname(__DIR__) . '/var/logs';
    }
}
