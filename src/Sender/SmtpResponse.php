<?php
declare(strict_types=1);

namespace Rws\Mailer\Sender;

/**
 * @package Rws\Mailer\Sender
 */
class SmtpResponse
{
    private const SMTP_BASE64_RESPONSE_PATTERN = "/^[0-9]{3} [a-z0-9=]+$/i";
    private const SMTP_STANDARD_RESPONSE_PATTERN = "/^[0-9]{3} .+$/i";

    /**
     * @var string
     */
    private $message;
    /**
     * @var int|null
     */
    private $statusCode;

    /**
     * @param string $message
     * @param int|null $statusCode
     */
    public function __construct(string $message, ?int $statusCode = null)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    /**
     * @param string $response
     * @return SmtpResponse
     */
    public static function fromRaw(string $response): self
    {
        if (preg_match(static::SMTP_BASE64_RESPONSE_PATTERN, $response)) {
            list($code, $base64message) = explode(' ', $response);
            return new static(base64_decode($base64message), (int)$code);
        }

        if (preg_match(static::SMTP_STANDARD_RESPONSE_PATTERN, $response)) {
            return new static(substr($response, 3), (int)substr($response, 0, 3));
        }

        if (preg_match("/^[0-9]{3}-.+$/i", str_replace("\n", ' ', $response))) {
            list($code, $message) = explode(' ', $response);
            return new static(str_replace("\n", ' ', $response), (int)$code);
        }

        return new static($response);
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }
}