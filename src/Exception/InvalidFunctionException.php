<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Exception;

class InvalidFunctionException extends \Exception
{
    public function __construct($function, $location, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Invalid function at %s, the function [%s] doesn\'t exist in the generator', $location, $function),
            null !== $previous ? $previous->getCode() : 2008,
            $previous
        );
    }
}
