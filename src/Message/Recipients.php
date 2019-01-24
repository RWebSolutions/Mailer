<?php
declare(strict_types=1);

namespace Rws\Mailer\Message;

/**
 * @package Rws\Mailer\Message
 */
class Recipients
{
    /**
     * @var EmailAddress
     */
    private $to;
    /**
     * @var EmailAddress[]
     */
    private $cc = [];
    /**
     * @var EmailAddress[]
     */
    private $bcc = [];

    /**
     * @param EmailAddress $to
     * @param EmailAddress[] $cc
     * @param EmailAddress[] $bcc
     */
    private function __construct(EmailAddress $to, array $cc, array $bcc = [])
    {
        $this->to = $to;
        $this->cc = $cc;
        $this->bcc = $bcc;
    }

    public static function to(EmailAddress $to): self
    {
        return new static($to, [], []);
    }

    /**
     * @param EmailAddress ...$ccCollection
     * @return Recipients
     */
    public function withCc(EmailAddress ...$ccCollection): self
    {
        foreach ($ccCollection as $cc) {
            if (!$cc->inArray(...$this->cc)) {
                $this->cc[] = $cc;
            }
        }

        return $this;
    }

    /**
     * @param EmailAddress ...$bccCollection
     * @return Recipients
     */
    public function withBcc(EmailAddress ...$bccCollection): self
    {
        foreach ($bccCollection as $bcc) {
            if (!$bcc->inArray(...$this->bcc)) {
                $this->bcc[] = $bcc;
            }
        }

        return $this;
    }

    /**
     * @return EmailAddress
     */
    public function getTo(): EmailAddress
    {
        return $this->to;
    }

    /**
     * @return EmailAddress[]
     */
    public function getCc(): array
    {
        return $this->cc;
    }

    /**
     * @return EmailAddress[]
     */
    public function getBcc(): array
    {
        return $this->bcc;
    }
}