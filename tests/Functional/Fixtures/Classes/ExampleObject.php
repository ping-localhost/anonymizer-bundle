<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Functional\Fixtures\Classes;

use Faker\Generator;
use PingLocalhost\AnonymizerBundle\Mapping\Anonymize;
use PingLocalhost\AnonymizerBundle\Mapping\AnonymizeClass;

/**
 * @AnonymizeClass(exclusions={"email": "/@example.com$/"})
 */
class ExampleObject
{
    /**
     * @var string
     *
     * @Anonymize(faker="userName")
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    public function __construct(string $username, string $email)
    {
        $this->username = $username;
        $this->email    = $email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @Anonymize()
     */
    public function anonymize(Generator $generator): void
    {
        $this->email = $generator->email;
    }
}