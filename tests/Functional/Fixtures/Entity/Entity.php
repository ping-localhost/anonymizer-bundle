<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Functional\Fixtures\Entity;

use PingLocalhost\AnonymizerBundle\Mapping\Anonymize;

class Entity
{
    /**
     * @Anonymize(faker="userName")
     */
    private $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
