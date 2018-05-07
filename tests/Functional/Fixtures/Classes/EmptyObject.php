<?php
/**
 * @copyright 2018 ping-localhost
 */
declare(strict_types=1);

namespace PingLocalhost\AnonymizerBundle\Functional\Fixtures\Classes;

class EmptyObject
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $created_at;

    public function __construct(string $username, string $email)
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
}
