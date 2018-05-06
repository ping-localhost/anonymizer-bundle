<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Metadata;

use Metadata\MethodMetadata;

class AnonymizedMethodMetadata extends MethodMetadata
{
    /**
     * @var array
     */
    private $arguments = [];

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }

    public function invoke($obj, array $args = [])
    {
        return parent::invoke($obj, $this->arguments);
    }
}
