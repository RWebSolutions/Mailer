<?php
declare(strict_types=1);

namespace Rws\Mailer\Sender;

use http\Exception\RuntimeException;
use Rws\Mailer\Message\Message;
use Rws\Mailer\Provider;

/**
 * @package Rws\Mailer\Sender
 */
class PHPMailProvider implements Provider
{
    public function __construct()
    {
        if (!function_exists('mail')) {
            throw new RuntimeException('Function mail() is not enabled.');
        }
    }

    public function send(Message $message): void
    {

    }
}