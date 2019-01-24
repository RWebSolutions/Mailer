<?php
declare(strict_types=1);

namespace Rws\Mailer\Sender;

use Rws\Mailer\Message\Message;
use Rws\Mailer\Provider;

/**
 * @package Rws\Sender
 */
class MockProvider implements Provider
{
    public function send(Message $message): void
    {
        // noop
    }
}