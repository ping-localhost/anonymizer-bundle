<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Exception;

class InvalidFakerException extends \RuntimeException
{
    public function __construct(string $generator, string $property, \Throwable $previous)
    {
        parent::__construct(
            sprintf('Invalid generator "%s" specified at: "%s".', $generator, $property),
            $previous->getCode(),
            $previous
        );
    }
}
