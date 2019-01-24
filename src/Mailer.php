<?php
declare(strict_types=1);

namespace Rws\Mailer;

use Rws\Mailer\Message\Message;

/**
 * @package Rws\Mailer
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