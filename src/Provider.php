<?php
declare(strict_types=1);

namespace Rws\Mailer;

use RWS\Mailer\Message\Message;

/**
 * @package RWS\Mailer
 */
interface Provider
{
    public function send(Message $message): void;
}