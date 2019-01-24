<?php
declare(strict_types=1);

namespace Rws\Mailer\Message;

/**
 * @package Rws\Mailer\Message
 */
class Sender
{
    /**
     * @var EmailAddress
     */
    private $from;
    /**
     * @var EmailAddress|null
     */
    private $replyTo;

    public function __construct(EmailAddress $from, ?EmailAddress $replyTo = null)
    {
        $this->from = $from;
        $this->replyTo = $replyTo;
    }

    public static function from(EmailAddress $from): self
    {
        return new static($from);
    }

    public function withReplyTo(EmailAddress $replyTo): self
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    /**
     * @return EmailAddress
     */
    public function getFrom(): EmailAddress
    {
        return $this->from;
    }

    /**
     * @return EmailAddress|null
     */
    public function getReplyTo(): ?EmailAddress
    {
        return $this->replyTo;
    }
}