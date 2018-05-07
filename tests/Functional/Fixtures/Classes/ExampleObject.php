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
     * @Anonymize(faker="userName", exclude="root")
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @Anonymize(faker="dateTime")
     */
    private $created_at;

    public function __construct(?string $username, ?string $email)
    {
        $this->username   = $username;
        $this->email      = $email;
        $this->created_at = new \DateTime('2018-01-01 00:00:00');
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * @Anonymize()
     */
    public function anonymize(Generator $generator): void
    {
        $this->email = $generator->email;
    }
}
