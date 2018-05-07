<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Exception;

class InvalidAnonymizeAnnotationException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
