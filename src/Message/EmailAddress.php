<?php
declare(strict_types=1);

namespace Rws\Mailer\Message;

/**
 * @package Rws\Mailer\Message
 */
class EmailAddress
{
    /**
     * @var string
     */
    private $email;
    /**
     * @var string|null
     */
    private $name;

    public function __construct(string $email, ?string $name = null)
    {
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    public function equals(EmailAddress $recipient): bool
    {
        return $recipient->getEmail() === $this->email;
    }

    public function inArray(EmailAddress ...$recipients): bool
    {
        foreach ($recipients as $recipient) {
            if ($recipient->getEmail() === $this->email) {
                return true;
            }
        }

        return false;
    }

    public function asLabel(): string
    {
        if ($this->name === null) {
            return $this->email;
        }

        return $this->name . ' <' . $this->email . '>';
    }

    public function __toString(): string
    {
        return $this->email;
    }
}