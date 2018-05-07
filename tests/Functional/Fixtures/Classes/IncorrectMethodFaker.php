<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Functional\Fixtures\Classes;

use Faker\Generator;
use PingLocalhost\AnonymizerBundle\Mapping\Anonymize;
use PingLocalhost\AnonymizerBundle\Mapping\AnonymizeClass;

class IncorrectMethodFaker
{
    /**
     * @var string
     */
    private $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    /**
     * @Anonymize()
     */
    public function anonymize(string $username): void
    {
        $this->username = $username;
    }
}
