<?php
declare(strict_types=1);

namespace Rws\Mailer;

use Rws\Mailer\Message\Message;

/**
 * @package Rws\Mailer
 */
interface Provider
{
    public function send(Message $message): void;
}