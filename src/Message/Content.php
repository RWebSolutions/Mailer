<?php
declare(strict_types=1);

namespace Rws\Mailer\Message;

/**
 * @package RWS\Mailer\Sender
 */
class Content
{
    /**
     * @var string|null
     */
    private $plain;
    /**
     * @var string|null
     */
    private $html;

    private function __construct(?string $plain = null, ?string $html = null)
    {
        $this->plain = $plain;
        $this->html = $html;
    }

    public static function plain(string $content): self
    {
        // @todo validate plain text content
        return new static($content);
    }

    public static function html(string $content, ?string $alternativePlainText = null): self
    {
        // @todo validate html content
        return new static($alternativePlainText ?? strip_tags($content), $content);
    }

    /**
     * @return string|null
     */
    public function getPlain(): ?string
    {
        return $this->plain;
    }

    /**
     * @return string|null
     */
    public function getHtml(): ?string
    {
        return $this->html;
    }
}