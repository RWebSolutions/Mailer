<?php
declare(strict_types=1);

namespace Rws\Mailer\Provider;

/**
 * @package Rws\Mailer\Provider
 */
class SmtpCommand
{
    private const HELLO = 'EHLO';
    private const START_TLS = 'STARTTLS';
    private const AUTH_LOGIN = 'AUTH LOGIN';
    private const MAIL_FROM = 'MAIL FROM:';
    private const MAIL_TO = 'RCPT TO:';
    private const DATA = 'DATA';
    private const RESET = 'RSET';
    private const QUIT = 'QUIT';

    /**
     * @var string|null
     */
    private $command;
    /**
     * @var string|null
     */
    private $value;

    /**
     * @param string|null $command
     * @param string|null $value
     */
    private function __construct(?string $command = null, ?string $value = null)
    {
        $this->command = $command;
        $this->value = $value;
    }

    /**
     * @param string $hostname
     * @return SmtpCommand
     */
    public static function hello(string $hostname): self
    {
        return new static(static::HELLO, $hostname);
    }

    /**
     * @return SmtpCommand
     */
    public static function startTls(): self
    {
        return new static(static::START_TLS);
    }

    /**
     * @return SmtpCommand
     */
    public static function authenticate(): self
    {
        return new static(static::AUTH_LOGIN);
    }

    /**
     * @param string $credentials username|password
     * @return SmtpCommand
     */
    public static function credentials(string $credentials): self
    {
        return new static(null, base64_encode($credentials));
    }

    /**
     * @param string $emailAddress
     * @return SmtpCommand
     */
    public static function mailFrom(string $emailAddress): self
    {
        return new static(static::MAIL_FROM, '<' . $emailAddress . '>');
    }

    /**
     * @param string $emailAddress
     * @return SmtpCommand
     */
    public static function mailTo(string $emailAddress): self
    {
        return new static(static::MAIL_TO, '<' . $emailAddress . '>');
    }

    /**
     * @param string|null $data
     * @return SmtpCommand
     */
    public static function data(?string $data = null): self
    {
        if (null === $data) {
            return new static(static::DATA);
        }

        return new static(null, $data);
    }

    /**
     * @return SmtpCommand
     */
    public static function reset(): self
    {
        return new static(static::RESET);
    }

    /**
     * @return SmtpCommand
     */
    public static function quit(): self
    {
        return new static(static::QUIT);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return implode(' ', array_filter([$this->command, $this->value]));
    }
}