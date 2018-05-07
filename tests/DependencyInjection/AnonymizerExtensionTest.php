<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\DependencyInjection;

use PHPUnit\Framework\TestCase;

/**
 * @covers \PingLocalhost\AnonymizerBundle\DependencyInjection\AnonymizerExtension
 */
class AnonymizerExtensionTest extends TestCase
{
    /**
     * @var AnonymizerExtension
     */
    private $anonymizer_extension;

    protected function setUp(): void
    {
        $this->anonymizer_extension = new AnonymizerExtension();
    }

    public function testLoad(): void
    {
    }
}
