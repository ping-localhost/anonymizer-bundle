<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Functional\Fixtures\Classes;

use PingLocalhost\AnonymizerBundle\Mapping\Anonymize;

class IncorrectPropertyFaker
{
    /**
     * @var string
     *
     * @Anonymize(faker="this-will-error")
     */
    private $username;

    public function __construct(string $username)
    {
        $this->username   = $username;
    }
}
