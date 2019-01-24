<?php
declare(strict_types=1);

namespace Rws\Mailer;

use RWS\Mailer\Message\Message;

/**
 * @package RWS\Mailer
 */
class Mailer
{
    /**
     * @var Provider
     */
    private $provider;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    public function send(Message $message): void
    {
        $this->provider->send($message);
    }
}